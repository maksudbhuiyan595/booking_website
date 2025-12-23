<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentFailedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Square\SquareClient;
use Square\Environment;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        $request->validate([
            'square_nonce'   => 'required|string',
            'amount_charged' => 'required|numeric|min:1',
            'passenger_name' => 'required|string',
            'passenger_email'=> 'required|email',
        ]);

        $nonce         = $request->square_nonce;
        $amountCharged = (float) $request->amount_charged;

        try {
            // 1. Square Client Setup
            $client = new SquareClient(
                accessToken: env('SQUARE_ACCESS_TOKEN'),
                environment: env('SQUARE_ENVIRONMENT') === 'production'
                    ? Environment::PRODUCTION
                    : Environment::SANDBOX
            );

            $money = new Money();
            $money->setAmount((int) round($amountCharged * 100));
            $money->setCurrency('USD');

            $paymentRequest = new CreatePaymentRequest(
                sourceId: $nonce,
                idempotencyKey: (string) Str::uuid(),
                amountMoney: $money
            );
            $paymentRequest->setNote('Booking for: ' . $request->passenger_name);

            // 2. Execute Payment
            $paymentsApi = $client->getPaymentsApi();
            $response    = $paymentsApi->createPayment($paymentRequest);

            if (!$response->isSuccess()) {
                $errors = $response->getErrors();
                $msg = $errors[0]->getDetail() ?? 'Square payment failed';
                throw new \Exception($msg);
            }

            $payment       = $response->getResult()->getPayment();
            $transactionId = $payment->getId();

            // ---------------------------------------------------------
            // 3. GENERATE SEQUENTIAL BOOKING NO (BLAT-0001)
            // ---------------------------------------------------------
            $lastBooking = Booking::orderBy('id', 'desc')->first();

            if ($lastBooking && preg_match('/BLAT-(\d+)/', $lastBooking->booking_no, $matches)) {
                // If last booking exists, increment number
                $newNumber = intval($matches[1]) + 1;
            } else {
                // If no booking exists, start from 1
                $newNumber = 1;
            }

            // Format: BLAT-0001, BLAT-0002 ...
            $bookingNo = 'BLAT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            // ---------------------------------------------------------


            // 4. Save Booking
            $totalFare = isset($request->fare['total']) ? (float) $request->fare['total'] : 0;

            $booking = new Booking();
            $booking->booking_no = $bookingNo; // New Sequential ID

            // Passenger
            $booking->passenger_name  = $request->passenger_name;
            $booking->passenger_email = $request->passenger_email;
            $booking->passenger_phone = $request->phone_number;

            // Trip
            $booking->trip_type       = $request->trip_type;
            $booking->pickup_address  = $request->pickup_formatted ?? $request->fromAddress;
            $booking->dropoff_address = $request->dropoff_formatted ?? $request->to_address;
            $booking->pickup_date     = $request->date;
            $booking->pickup_time     = $request->time;
            $booking->distance        = $request->distance_miles ?? 0;
            $booking->vehicle_type    = $request->vehicle_display_name;

            // Extra
            $booking->airline_name    = $request->airline_name;
            $booking->flight_number   = $request->flight_number;
            $booking->luggage_count   = $request->luggage ?? 0;
            $booking->passenger_count = ((int) $request->adults) + ((int) $request->seats_dummy);

            // Payment
            $booking->total_fare     = $totalFare;
            $booking->paid_amount    = $amountCharged;
            $booking->due_amount     = max(0, $totalFare - $amountCharged);
            $booking->payment_method = 'square';
            $booking->transaction_id = $transactionId;

            // Status
            $booking->payment_status = ($booking->due_amount <= 0.01) ? 'paid' : 'partial';
            $booking->status         = 'confirmed';

            $booking->save();

            // Send Success Email
            try {
                Mail::to(env('MAIL_FROM_ADDRESS'))->send(new BookingConfirmationMail($booking));
            } catch (\Exception $emailEx) {
                Log::error('Success Email Failed: ' . $emailEx->getMessage());
            }

            return redirect()->route('home', ['id' => $booking->booking_no])
                ->with('notify', ['type' => 'success', 'message' => 'Booking confirmed successfully!']);

        } catch (\Throwable $e) {

            // ---------------------------------------------------------
            // 5. SEND FAILURE EMAIL WITH USER DETAILS
            // ---------------------------------------------------------
            try {
                $failureDetails = [
                    'name'          => $request->passenger_name,
                    'email'         => $request->passenger_email, // Added
                    'phone'         => $request->phone_number,    // Added
                    'error_message' => $e->getMessage(),
                    'date'          => now()->toDateTimeString()
                ];

                // Send to User
                Mail::to(env('MAIL_FROM_ADDRESS'))->send(new PaymentFailedMail($failureDetails));

            } catch (\Exception $emailEx) {
                Log::error('Failure Email Failed: ' . $emailEx->getMessage());
            }

            return back()->with('notify', [
                'type'    => 'error',
                'message' => 'Payment failed: ' . $e->getMessage()
            ]);
        }
    }
}
