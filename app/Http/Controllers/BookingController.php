<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentFailedMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        // 1. Validation
        $request->validate([
            'stripe_token'   => 'required|string',
            'amount_charged' => 'required|numeric|min:1',
            'passenger_name' => 'required|string',
            'passenger_email'=> 'required|email',
        ]);

        $token         = $request->stripe_token;
        $amountCharged = (float) $request->amount_charged;

        try {
            // ---------------------------------------------------
            // 2. Stripe Payment Processing
            // ---------------------------------------------------
            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => (int) round($amountCharged * 100), // USD Cents
                'currency' => 'usd',
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => ['token' => $token],
                ],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'description' => 'Booking: ' . $request->passenger_name,
                'receipt_email' => $request->passenger_email,
                'return_url' => route('home'),
                'metadata' => [
                    'phone' => $request->phone_number,
                    'type' => $request->trip_type
                ]
            ]);

            // কার্ডের তথ্য সংগ্রহ
            $cardBrand = null;
            $cardLast4 = null;
            if (isset($paymentIntent->charges->data[0])) {
                $charge = $paymentIntent->charges->data[0];
                $cardBrand = $charge->payment_method_details->card->brand ?? null;
                $cardLast4 = $charge->payment_method_details->card->last4 ?? null;
            }

            $transactionId = $paymentIntent->id;

            // ---------------------------------------------------
            // 3. Generate Booking No
            // ---------------------------------------------------
            $lastBooking = Booking::orderBy('id', 'desc')->first();
            $newNumber = ($lastBooking && preg_match('/BLAT-(\d+)/', $lastBooking->booking_no, $matches))
                ? intval($matches[1]) + 1
                : 1;
            $bookingNo = 'BLAT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // ---------------------------------------------------
            // 4. Save Data to Database
            // ---------------------------------------------------
            $fare = $request->fare ?? [];

            $booking = new Booking();
            $booking->booking_no = $bookingNo;

            // Passenger Info
            $booking->passenger_name     = $request->passenger_name;
            $booking->passenger_email    = $request->passenger_email;
            $booking->passenger_phone    = $request->phone_number;
            $booking->phone_country_code = $request->phone_country_code;
            $booking->alternate_phone    = $request->alternate_phone;
            $booking->mailing_address    = $request->mailing_address;
            $booking->special_needs      = $request->special_needs;

            // Trip Info
            $booking->trip_type       = $request->trip_type;
            $booking->pickup_date     = Carbon::parse($request->date)->format('Y-m-d');
            $booking->pickup_time     = $request->time;
            $booking->pickup_address  = $request->pickup ?? $request->fromAddress;
            $booking->dropoff_address = $request->dropoff ?? $request->to_address;
            $booking->distance_miles  = $request->distance_miles ?? 0;

            // Flight & Vehicle
            $booking->airline_name    = $request->airline_name;
            $booking->flight_number   = $request->flight_number;
            $booking->vehicle_id      = $request->vehicle_id;
            $booking->vehicle_type    = $fare['name'] ?? 'Unknown';
            $booking->vehicles_used   = $request->vehicles_used ?? 1;

            // Counts
            $booking->adults           = $request->adults ?? 0;
            $booking->children         = $request->children ?? 0;
            $booking->total_passengers = ((int)$request->adults + (int)$request->seats_dummy);
            $booking->luggage          = $request->luggage ?? 0;

            // Extras
            $booking->booster_seat_count = $request->booster_seat ?? 0;
            $booking->infant_seat_count  = $request->infant_seat ?? 0;
            $booking->front_seat_count   = $request->front_seat ?? 0;
            $booking->stopover_count     = $request->stopover ?? 0;

            // Billing
            $booking->card_holder_name = $request->card_holder_name;
            $booking->billing_phone    = $request->billing_phone;
            $booking->billing_address  = $request->billing_address;
            $booking->billing_city     = $request->billing_city;
            $booking->billing_state    = $request->billing_state;
            $booking->billing_zip      = $request->billing_zip;

            // Fare Breakdown
            $booking->estimated_fare    = $fare['estimatedFare'] ?? 0;
            $booking->gratuity          = $fare['gratuity'] ?? 0;
            $booking->pickup_tax        = $fare['pickup_tax'] ?? 0;
            $booking->dropoff_tax       = $fare['dropoff_tax'] ?? 0;
            $booking->parking_fee       = $fare['parking_fee'] ?? 0;
            $booking->toll_fee          = $fare['toll_fee'] ?? 0;
            $booking->surcharge_fee     = $fare['surcharge_fee'] ?? 0;
            $booking->extra_luggage_fee = $fare['extra_luggage_fee'] ?? 0;
            $booking->extras_total      = $request->extras_total ?? 0;
            $booking->child_seat_fee    = $fare['child_seat_fee'] ?? 0;
            $booking->booster_seat_fee  = $fare['booster_seat_fee'] ?? 0;
            $booking->front_seat_fee    = $fare['front_seat_fee'] ?? 0;
            $booking->stopover_fee      = $fare['stopover_fee'] ?? 0;

            // Payment Totals
            $booking->total_fare  = isset($fare['total']) ? (float) $fare['total'] : 0;
            $booking->paid_amount = $amountCharged;
            $booking->due_amount  = max(0, $booking->total_fare - $amountCharged);

            // Payment Meta
            $booking->payment_method = 'stripe';
            $booking->transaction_id = $transactionId;
            $booking->payment_status = ($booking->due_amount <= 0.01) ? 'paid' : 'partial';
            $booking->status         = 'confirmed';
            $booking->card_brand     = $cardBrand;
            $booking->card_last_four = $cardLast4;

            // ✅ JSON Arrays (মডেলে casts থাকার কারণে এটি অটোমেটিক কাজ করবে)
            $booking->surcharge_details = $request->surcharge_details ?? [];
            $booking->extra_charge_details = $request->extra_charge_details ?? [];

            $booking->save();

            // 5. Send Email
            try {
                Mail::to(config('mail.from.address'))->send(new BookingConfirmationMail($booking));
                Mail::to($booking->passenger_email)->send(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                Log::error('Mail Error: ' . $e->getMessage());
            }

            return redirect()->route('home')
                ->with('notify', ['type' => 'success', 'message' => 'Booking Confirmed Successfully!']);

        } catch (\Throwable $e) {
            Log::error('Stripe Error: ' . $e->getMessage());

            try {
                $failData = [
                    'name' => $request->passenger_name,
                    'email' => $request->passenger_email,
                    'phone' => $request->phone_number,
                    'error_message' => $e->getMessage(),
                    'date' => now()->toDateTimeString()
                ];
                Mail::to(config('mail.from.address'))->send(new PaymentFailedMail($failData));
            } catch (\Exception $ex) {}

            return back()->with('notify', [
                'type' => 'error',
                'message' => 'Payment Failed, Please try again.'
            ])->withInput();
        }
    }

}
