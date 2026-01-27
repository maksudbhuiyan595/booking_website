<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentFailedMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        // ======================================================
        // STEP 1: INPUT VALIDATION
        // ======================================================
        $request->validate([
            'stripe_token'   => 'required|string',
            'amount_charged' => 'required|numeric|min:1',
            'passenger_name' => 'required|string',
            'passenger_email'=> 'required|email',
        ]);
        $token         = $request->stripe_token;
        $amountCharged = (float) $request->amount_charged;
        $booking       = null;

        // ======================================================
        // STEP 2: SAVE DATA FIRST (DATABASE TRANSACTION)
        // ======================================================
        DB::beginTransaction();
        try {
            
            $lastBooking = Booking::lockForUpdate()->orderBy('id', 'desc')->first();
            $lastNumber = 0;
            if ($lastBooking && preg_match('/BLAT-(\d+)/', $lastBooking->booking_no, $matches)) {
                $lastNumber = (int) $matches[1];
            }
            $bookingNo = 'BLAT-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            $booking = new Booking();
            $booking->booking_no = $bookingNo;

            // --- Passenger Info ---
            $booking->passenger_name     = $request->passenger_name;
            $booking->passenger_email    = $request->passenger_email;
            $booking->passenger_phone    = $request->phone_number;
            $booking->phone_country_code = $request->phone_country_code;
            $booking->alternate_phone    = $request->alternate_phone;
            $booking->mailing_address    = $request->mailing_address;
            $booking->special_needs      = $request->special_needs;

            // --- Trip Info ---
            $booking->trip_type       = $request->trip_type;
            $booking->pickup_date     = Carbon::parse($request->date)->format('Y-m-d');
            $booking->pickup_time     = $request->time;
            $booking->pickup_address  = $request->pickup ?? $request->fromAddress;
            $booking->dropoff_address = $request->dropoff ?? $request->to_address;
            $booking->distance_miles  = $request->distance_miles ?? 0;

            // --- Flight & Vehicle ---
            $fare = $request->fare ?? [];
            $booking->airline_name    = $request->airline_name;
            $booking->flight_number   = $request->flight_number;
            $booking->vehicle_id      = $request->vehicle_id;
            $booking->vehicle_type    = $fare['name'] ?? 'Unknown';
            $booking->vehicles_used   = $request->vehicles_used ?? 1;

            // --- Counts ---
            $booking->adults           = $request->adults ?? 0;
            $booking->children         = $request->child_seat ?? 0;
            $booking->total_passengers = $request->reqPassengers;
            $booking->luggage          = $request->luggage ?? 0;

            // --- Extras ---
            $booking->booster_seat_count = $request->booster_seat ?? 0;
            $booking->infant_seat_count  = $request->infant_seat ?? 0;
            $booking->front_seat_count   = $request->front_seat ?? 0;
            $booking->stopover_count     = $request->stopover ?? 0;
            $booking->pet_count          = $request->pets ?? 0;

            // --- Billing ---
            $booking->card_holder_name = $request->card_holder_name;
            $booking->billing_phone    = $request->billing_phone;
            $booking->billing_address  = $request->billing_address;
            $booking->billing_city     = $request->billing_city;
            $booking->billing_state    = $request->billing_state;
            $booking->billing_zip      = $request->billing_zip;

            // --- Fees ---
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
            $booking->surcharge_details = $request->surcharge_details ?? [];
            $booking->extra_charge_details = $request->extra_charge_details ?? [];

            // --- Payment Initials ---
            $booking->total_fare     = isset($fare['total']) ? (float) $fare['total'] : 0;
            $booking->paid_amount    = 0;
            $booking->due_amount     = $booking->total_fare;
            $booking->payment_method = 'stripe';
            $booking->status         = 'pending';
            $booking->payment_status = 'unpaid';

            $booking->save();
            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Database Save Error: ' . $e->getMessage());

            return back()->with('notify', [
                'type' => 'error',
                'message' => 'System Error: Could not initiate booking. Please try again. (No money was charged)'
            ])->withInput();
        }
        // ======================================================
        // STEP 3: STRIPE PAYMENT PROCESSING
        // ======================================================
        $paymentIntent = null;
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) round($amountCharged * 100),
                'currency' => 'usd',
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => ['token' => $token],
                ],
                'confirmation_method' => 'manual',
                'capture_method' => 'manual',
                'confirm' => true,

                // --- FIX: Redirect Error Solution ---
                'return_url' => route('home'),

                'description' => 'Booking: ' . $booking->booking_no,
                'receipt_email' => $request->passenger_email,
                'metadata' => [
                    'booking_id' => $booking->id,
                    'booking_no' => $booking->booking_no,
                    'phone' => $request->phone_number
                ]
            ]);
            $cardBrand = null;
            $cardLast4 = null;
            if (isset($paymentIntent->charges->data[0])) {
                $charge = $paymentIntent->charges->data[0];
                $cardBrand = $charge->payment_method_details->card->brand ?? null;
                $cardLast4 = $charge->payment_method_details->card->last4 ?? null;
            }

            // ==================================================
            // STEP 4: SUCCESS - CAPTURE & UPDATE
            // ==================================================
            $paymentIntent->capture();
            $booking->transaction_id = $paymentIntent->id;
            $booking->paid_amount    = $amountCharged;
            $booking->due_amount     = max(0, $booking->total_fare - $amountCharged);
            $booking->payment_status = ($booking->due_amount <= 0.01) ? 'paid' : 'partial';
            $booking->status         = 'confirmed';
            $booking->card_brand     = $cardBrand;
            $booking->card_last_four = $cardLast4;
            $booking->save();
            try {
                Mail::to(config('mail.from.address'))->send(new BookingConfirmationMail($booking));
                Mail::to($booking->passenger_email)->send(new BookingConfirmationMail($booking));
            } catch (\Exception $e) {
                Log::error('Mail Error: ' . $e->getMessage());
            }

            return redirect()->route('home', [
                'payment' => 'success',
                'booking' => $booking->booking_no
            ])->with('notify', [
                'type' => 'success',
                'message' => 'Payment successful! Booking confirmed.'
            ]);

        } catch (\Throwable $e) {
            // ==================================================
            // STEP 5: FAILURE & SAFETY NET (REFUND LOGIC)
            // ==================================================
            Log::error('Stripe/System Error: ' . $e->getMessage());

            if($booking) {
                $booking->status = 'failed';
                $booking->payment_status = 'failed';
                $booking->save();
            }
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
                'message' => 'Payment Failed: '. 'If charged, it will be refunded automatically.'
            ])->withInput();
        }
    }
}
