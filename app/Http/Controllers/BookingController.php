<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// --- SQUARE IMPORTS (MUST BE HERE) ---
use Square\SquareClient;
use Square\Environment;
use Square\Models\Money;
use Square\Models\CreatePaymentRequest;
use Square\Exceptions\ApiException;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        // ১. ইনপুট ভ্যালিডেশন
        $nonce = $request->input('square_nonce');
        $amountCharged = (float) $request->input('amount_charged');

        if (empty($nonce)) {
            return back()->with('error', 'Payment token is invalid. Please try again.');
        }

        try {
            // ২. Square Client সেটআপ
            // আপনার এরর মেসেজ অনুযায়ী আমরা এখানে Array [] ব্যবহার করব না।
            // সরাসরি ভেরিয়েবল পাস করব। এটি ১০০% কাজ করবে যদি আপনার SDK পুরনো স্টাইল ফলো করে।

            $accessToken = env('SQUARE_ACCESS_TOKEN');
            // Environment::SANDBOX বা Environment::PRODUCTION ব্যবহার করতে হলে
            // উপরে 'use Square\Environment;' থাকতে হবে।
            $env = env('SQUARE_ENVIRONMENT') === 'production' ? Environment::PRODUCTION : Environment::SANDBOX;

            // ফিক্স: সরাসরি পাস করা হচ্ছে
            $client = new SquareClient($accessToken, $env);

            // ৩. Money অবজেক্ট তৈরি
            $amountMoney = new Money();
            // Square Cents হিসেবে এমাউন্ট নেয়
            $amountMoney->setAmount((int) round($amountCharged * 100));
            $amountMoney->setCurrency('USD');

            // ৪. পেমেন্ট রিকোয়েস্ট তৈরি
            $paymentRequest = new CreatePaymentRequest(
                $nonce,
                uniqid(),
                $amountMoney
            );

            // নোট যুক্ত করা
            $paymentRequest->setNote('Booking Ref: ' . $request->passenger_name);

            // ৫. পেমেন্ট এক্সিকিউট করা
            $paymentsApi = $client->getPaymentsApi();
            $response = $paymentsApi->createPayment($paymentRequest);

            if ($response->isSuccess()) {
                $paymentResult = $response->getResult()->getPayment();
                $transactionId = $paymentResult->getId();

                // ৬. ডাটাবেজে বুকিং সেভ করা
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
                // টেবিলে কলাম থাকলে নিচের লাইনগুলো আনকমেন্ট করুন
                // $booking->luggage_count = $request->luggage ?? 0;
                // $booking->passenger_count = (int)$request->adults + (int)$request->seats_dummy;

                // Payment Info
                $totalFare = isset($request->fare['total']) ? (float)$request->fare['total'] : 0;

                $booking->total_fare = $totalFare;
                $booking->paid_amount = $amountCharged;
                $booking->due_amount = max(0, $totalFare - $amountCharged);
                $booking->payment_method = 'square'; // Payment method fix

                // Status Logic
                $booking->payment_status = ($booking->due_amount <= 0.01) ? 'paid' : 'partial';
                $booking->transaction_id = $transactionId;
                $booking->status = 'confirmed';

                $booking->save();

                return redirect()->route('booking.success', ['id' => $booking->booking_no])
                                 ->with('success', 'Booking Confirmed Successfully!');

            } else {
                // Square API Error
                $errors = $response->getErrors();
                $msg = isset($errors[0]) ? $errors[0]->getDetail() : 'Payment processing failed.';
                return back()->with('error', 'Payment Failed: ' . $msg);
            }

        } catch (ApiException $e) {
            return back()->with('error', 'Square API Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }
}
