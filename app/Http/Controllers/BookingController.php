<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentFailedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Square\SquareClient;
use Square\Types\Money;
use Square\Payments\Requests\CreatePaymentRequest;
use Square\Environment;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        // 1. Validation
        $request->validate([
            'square_nonce'   => 'required|string',
            'amount_charged' => 'required|numeric|min:1',
            'passenger_name' => 'required|string',
            'passenger_email'=> 'required|email',
        ]);

        $nonce         = $request->square_nonce;
        $amountCharged = (float) $request->amount_charged;

        try {
       $environment = config('services.square.environment') === 'production' ? 'production' : 'sandbox';

            $client = new SquareClient([
                'accessToken' => config('services.square.access_token'),
                'environment' => $environment,
            ]);

            $money = new Money();
            $money->setCurrency('USD');
            $money->setAmount((int) round($amountCharged * 100));
            $paymentRequest = new CreatePaymentRequest([
            'sourceId' => 'cnon:card-nonce-ok',
            'idempotencyKey' => uniqid(),
            'amountMoney' => $money,
            'note' => 'Taxi Booking Payment',
            ]);
            $paymentRequest->setNote('Booking for: ' . $request->passenger_name);

            // 3. Execute Payment
            $response  = $client->payments->create($paymentRequest);

             $payment       = $response->getPayment();
            $transactionId = $payment->getId();

            // =========================================================
            // 4. GENERATE BOOKING NO (BLAT-XXXX)
            // =========================================================
            $lastBooking = Booking::orderBy('id', 'desc')->first();

            if ($lastBooking && preg_match('/BLAT-(\d+)/', $lastBooking->booking_no, $matches)) {
                $newNumber = intval($matches[1]) + 1;
            } else {
                $newNumber = 1;
            }
            $bookingNo = 'BLAT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // =========================================================
            // 5. Save Booking Data
            // =========================================================
            $totalFare = isset($request->fare['total']) ? (float) $request->fare['total'] : 0;

            $booking = new Booking();
            $booking->booking_no = $bookingNo;

            // Passenger Info
            $booking->passenger_name  = $request->passenger_name;
            $booking->passenger_email = $request->passenger_email;
            $booking->passenger_phone = $request->phone_number;

            // Trip Info
            $booking->trip_type       = $request->trip_type;
            $booking->pickup_address  = $request->pickup_formatted ?? $request->fromAddress;
            $booking->dropoff_address = $request->dropoff_formatted ?? $request->to_address;
            $booking->pickup_date     = $request->date;
            $booking->pickup_time     = $request->time;
            $booking->distance        = $request->distance_miles ?? 0;
            $booking->vehicle_type    = $request->vehicle_display_name;

            // Extra Info
            $booking->airline_name    = $request->airline_name;
            $booking->flight_number   = $request->flight_number;
            $booking->luggage_count   = $request->luggage ?? 0;
            $booking->passenger_count = ((int) $request->adults) + ((int) $request->seats_dummy);

            // Payment Info
            $booking->total_fare   = $totalFare;
            $booking->paid_amount  = $amountCharged;
            $booking->due_amount   = max(0, $totalFare - $amountCharged);
            $booking->payment_method = 'square';
            $booking->transaction_id = $transactionId;

            // Status
            $booking->payment_status = ($booking->due_amount <= 0.01) ? 'paid' : 'partial';
            $booking->status         = 'confirmed';

            $booking->save();

            // Send Success Email
            try {
                Mail::to(config('mail.from.address'))->send(new BookingConfirmationMail($booking));
            } catch (\Exception $emailEx) {
                Log::error('Success Email Failed: ' . $emailEx->getMessage());
            }

            return redirect()->route('home', ['id' => $booking->booking_no])
                ->with('notify', ['type' => 'success', 'message' => 'Booking confirmed successfully!']);

        } catch (\Throwable $e) {
            // =========================================================
            // 6. Handle Failure
            // =========================================================

            // ফেইল করলে লগ রাখা ভালো
            Log::error('Payment Error: ' . $e->getMessage());

            try {
                $failureDetails = [
                    'name'          => $request->passenger_name,
                    'email'         => $request->passenger_email,
                    'phone'         => $request->phone_number,
                    'error_message' => $e->getMessage(),
                    'date'          => now()->toDateTimeString()
                ];
                Mail::to(config('mail.from.address'))->send(new PaymentFailedMail($failureDetails));
            } catch (\Exception $emailEx) {
                Log::error('Failure Email Failed: ' . $emailEx->getMessage());
            }

            return back()->with('notify', [
                'type'    => 'error',
                'message' => 'Payment failed: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
