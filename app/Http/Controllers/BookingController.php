<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Square\SquareClient;
use Square\Environment;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        // ---------------- BASIC VALIDATION ----------------
        $request->validate([
            'square_nonce'   => 'required|string',
            'amount_charged' => 'required|numeric|min:1',
            'passenger_name' => 'required|string',
            'passenger_email'=> 'required|email',
        ]);

        $nonce         = $request->square_nonce;
        $amountCharged = (float) $request->amount_charged;

        try {

            // ---------------- SQUARE CLIENT (NEW SDK) ----------------
            $client = new SquareClient(
                accessToken: env('SQUARE_ACCESS_TOKEN'),
                environment: env('SQUARE_ENVIRONMENT') === 'production'
                    ? Environment::PRODUCTION
                    : Environment::SANDBOX
            );

            // ---------------- PAYMENT MONEY ----------------
            $money = new Money();
            $money->setAmount((int) round($amountCharged * 100)); // cents
            $money->setCurrency('USD');

            // ---------------- PAYMENT REQUEST ----------------
            $paymentRequest = new CreatePaymentRequest(
                sourceId: $nonce,
                idempotencyKey: (string) Str::uuid(),
                amountMoney: $money
            );

            $paymentRequest->setNote('Booking for: ' . $request->passenger_name);

            // ---------------- EXECUTE PAYMENT ----------------
            $paymentsApi = $client->getPaymentsApi();
            $response    = $paymentsApi->createPayment($paymentRequest);

            if (!$response->isSuccess()) {
                $errors = $response->getErrors();
                $msg = $errors[0]->getDetail() ?? 'Square payment failed';
                throw new \Exception($msg);
            }

            // ---------------- PAYMENT RESULT ----------------
            $payment       = $response->getResult()->getPayment();
            $transactionId = $payment->getId();

            // ---------------- BOOKING SAVE ----------------
            $totalFare = isset($request->fare['total'])
                ? (float) $request->fare['total']
                : 0;

            $booking = new Booking();
            $booking->booking_no = 'BK-' . strtoupper(Str::random(8));

            // Passenger
            $booking->passenger_name  = $request->passenger_name;
            $booking->passenger_email = $request->passenger_email;
            $booking->passenger_phone = $request->phone_number;

            // Trip
            $booking->trip_type        = $request->trip_type;
            $booking->pickup_address   = $request->pickup_formatted ?? $request->fromAddress;
            $booking->dropoff_address  = $request->dropoff_formatted ?? $request->to_address;
            $booking->pickup_date      = $request->date;
            $booking->pickup_time      = $request->time;
            $booking->distance         = $request->distance_miles ?? 0;
            $booking->vehicle_type     = $request->vehicle_display_name;

            // Extra
            $booking->airline_name     = $request->airline_name;
            $booking->flight_number    = $request->flight_number;
            $booking->luggage_count    = $request->luggage ?? 0;
            $booking->passenger_count  = ((int) $request->adults) + ((int) $request->seats_dummy);

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

            // ---------------- SUCCESS ----------------
            return redirect()->route('home', ['id' => $booking->booking_no])
                ->with('notify', [
                    'type'    => 'success',
                    'message' => 'Booking confirmed successfully!'
                ]);

        } catch (\Throwable $e) {

            // ---------------- ERROR ----------------
            return back()->with('notify', [
                'type'    => 'error',
                'message' => 'Payment failed: ' . $e->getMessage()
            ]);
        }
    }
}
