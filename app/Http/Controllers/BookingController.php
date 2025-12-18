<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Square\SquareClient;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {

        $nonce = $request->input('square_nonce');
        $amountCharged = (float) $request->input('amount_charged');

        if (empty($nonce)) {
            return back()->with('error', 'Payment token is invalid. Please try again.');
        }

        try {
           $client = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => env('SQUARE_ENVIRONMENT', 'sandbox')
        ]);
            // ৩. Money অবজেক্ট তৈরি
            $amountMoney = new Money();
            $amountMoney->setAmount((int) round($amountCharged * 100)); // cents
            $amountMoney->setCurrency('USD');

            // ৪. Create Payment Request
            $paymentRequest = new CreatePaymentRequest(
                $nonce,           // Payment token from frontend
                uniqid(),         // Idempotency key
                $amountMoney
            );
            $paymentRequest->setNote('Booking for: ' . $request->passenger_name);

            // ৫. Payment execute করা
            $paymentsApi = $client->getPaymentsApi();
            $response = $paymentsApi->createPayment($paymentRequest);

            if ($response->isSuccess()) {
                $paymentResult = $response->getResult()->getPayment();
                $transactionId = $paymentResult->getId();

                // ৬. Booking ডাটাবেজে সেভ করা
                $booking = new Booking();
                $booking->booking_no = 'BK-' . strtoupper(Str::random(8));

                // Passenger Info
                $booking->passenger_name = $request->passenger_name;
                $booking->passenger_email = $request->passenger_email;
                $booking->passenger_phone = $request->phone_number;

                // Trip Info
                $booking->trip_type = $request->trip_type;
                $booking->pickup_address = $request->pickup_formatted ?? $request->fromAddress;
                $booking->dropoff_address = $request->dropoff_formatted ?? $request->to_address;
                $booking->pickup_date = $request->date;
                $booking->pickup_time = $request->time;
                $booking->distance = $request->distance_miles ?? 0;
                $booking->vehicle_type = $request->vehicle_display_name;

                // Extra Info
                $booking->airline_name = $request->airline_name;
                $booking->flight_number = $request->flight_number;
                $booking->luggage_count = $request->luggage ?? 0;
                $booking->passenger_count = (int)$request->adults + (int)$request->seats_dummy;

                // Payment Info
                $totalFare = isset($request->fare['total']) ? (float)$request->fare['total'] : 0;
                $booking->total_fare = $totalFare;
                $booking->paid_amount = $amountCharged;
                $booking->due_amount = max(0, $totalFare - $amountCharged);
                $booking->payment_method = $request->payment_method ?? 'square';

                // Status Logic
                $booking->payment_status = ($booking->due_amount <= 0.01) ? 'paid' : 'partial';
                $booking->transaction_id = $transactionId;
                $booking->status = 'confirmed';

                $booking->save();

                return redirect()->route('booking.success', ['id' => $booking->booking_no])
                                 ->with('success', 'Booking Confirmed!');
            } else {
                // Square API Error
                $errors = $response->getErrors();
                $msg = isset($errors[0]) ? $errors[0]->getDetail() : 'Unknown error from Square';
                return back()->with('error', 'Payment Failed: ' . $msg);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }
}
