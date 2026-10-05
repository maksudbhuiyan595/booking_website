<?php

namespace App\Http\Controllers;

use App\Mail\AdminBookingConfirmationMail;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentFailedMail;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class BookingController extends Controller
{
    public function confirmBooking(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'stripe_token' => [
                'required',
                'string',
            ],

            'payment_method' => [
                'required',
                'in:cash,deposit,card',
            ],

            'passenger_name' => [
                'required',
                'string',
                'max:255',
            ],

            'passenger_email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone_number' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $paymentMethod = $validated['payment_method'];

        /*
        |--------------------------------------------------------------------------
        | 2. Create Booking
        |--------------------------------------------------------------------------
        */

        try {
            $booking = DB::transaction(function () use ($request, $paymentMethod) {

                $booking = new Booking();

                /*
                |--------------------------------------------------------------------------
                | Generate Booking Number
                |--------------------------------------------------------------------------
                */

                $lastNumber = Booking::where('booking_no', 'like', 'BLAT-%')
                    ->selectRaw("
                        MAX(
                            CAST(
                                SUBSTRING(booking_no, 6)
                                AS UNSIGNED
                            )
                        ) as max_number
                    ")
                    ->value('max_number');

                $nextNumber = ((int) ($lastNumber ?? 0)) + 1;

                $booking->booking_no =
                    'BLAT-' . str_pad(
                        $nextNumber,
                        4,
                        '0',
                        STR_PAD_LEFT
                    );

                /*
                |--------------------------------------------------------------------------
                | Passenger
                |--------------------------------------------------------------------------
                */

                $booking->passenger_name = $request->passenger_name;
                $booking->passenger_email = $request->passenger_email;
                $booking->passenger_phone = $request->phone_number;
                $booking->phone_country_code = $request->phone_country_code;
                $booking->alternate_phone = $request->alternate_phone;
                $booking->mailing_address = $request->mailing_address;
                $booking->special_needs = $request->special_needs;

                /*
                |--------------------------------------------------------------------------
                | Trip
                |--------------------------------------------------------------------------
                */

                $booking->trip_type = $request->trip_type;
                $booking->pickup_date = $request->date;
                $booking->pickup_time = $request->time;

                $booking->pickup_address =
                    $request->pickup ?? $request->fromAddress;

                $booking->dropoff_address =
                    $request->dropoff ?? $request->to_address;

                $booking->distance_miles =
                    (float) ($request->distance_miles ?? 0);

                /*
                |--------------------------------------------------------------------------
                | Flight
                |--------------------------------------------------------------------------
                */

                $booking->airline_name = $request->airline_name;
                $booking->flight_number = $request->flight_number;

                /*
                |--------------------------------------------------------------------------
                | Vehicle
                |--------------------------------------------------------------------------
                */

                $booking->vehicle_id = $request->vehicle_id;
                $booking->vehicle_type = $request->vehicle_type;
                $booking->vehicles_used =
                    (int) ($request->vehicles_used ?? 1);

                /*
                |--------------------------------------------------------------------------
                | Passengers
                |--------------------------------------------------------------------------
                */

                $booking->adults =
                    (int) ($request->adults ?? 0);

                $booking->children =
                    (int) ($request->children ?? 0);

                $booking->total_passengers =
                    (int) (
                        $request->total_passengers
                        ?? $request->reqPassengers
                        ?? 0
                    );

                $booking->luggage =
                    (int) ($request->luggage ?? 0);

                /*
                |--------------------------------------------------------------------------
                | Extras
                |--------------------------------------------------------------------------
                */

                $booking->booster_seat_count =
                    (int) ($request->booster_seat ?? 0);

                $booking->infant_seat_count =
                    (int) ($request->infant_seat ?? 0);

                $booking->front_seat_count =
                    (int) ($request->front_seat ?? 0);

                $booking->stopover_count =
                    (int) ($request->stopover ?? 0);

                $booking->pet_count =
                    (int) ($request->pets ?? 0);

                /*
                |--------------------------------------------------------------------------
                | Billing
                |--------------------------------------------------------------------------
                */

                $booking->card_holder_name =
                    $request->card_holder_name;

                $booking->billing_phone =
                    $request->billing_phone;

                $booking->billing_address =
                    $request->billing_address;

                $booking->billing_city =
                    $request->billing_city;

                $booking->billing_state =
                    $request->billing_state;

                $booking->billing_zip =
                    $request->billing_zip;

                /*
                |--------------------------------------------------------------------------
                | Fare
                |--------------------------------------------------------------------------
                */

                $fare = $request->fare ?? [];

                $booking->estimated_fare =
                    (float) (
                        $fare['estimatedFare']
                        ?? $fare['estimated_fare']
                        ?? 0
                    );

                $booking->gratuity =
                    (float) ($fare['gratuity'] ?? 0);

                $booking->pickup_tax =
                    (float) ($fare['pickup_tax'] ?? 0);

                $booking->dropoff_tax =
                    (float) ($fare['dropoff_tax'] ?? 0);

                $booking->parking_fee =
                    (float) ($fare['parking_fee'] ?? 0);

                $booking->toll_fee =
                    (float) ($fare['toll_fee'] ?? 0);

                $booking->surcharge_fee =
                    (float) ($fare['surcharge_fee'] ?? 0);

                $booking->extra_luggage_fee =
                    (float) ($fare['extra_luggage_fee'] ?? 0);

                $booking->child_seat_fee =
                    (float) ($fare['child_seat_fee'] ?? 0);

                $booking->booster_seat_fee =
                    (float) ($fare['booster_seat_fee'] ?? 0);

                $booking->front_seat_fee =
                    (float) ($fare['front_seat_fee'] ?? 0);

                $booking->stopover_fee =
                    (float) ($fare['stopover_fee'] ?? 0);

                $booking->extras_total =
                    (float) (
                        $fare['extras_total']
                        ?? $request->extras_total
                        ?? 0
                    );

                $totalFare =
                    (float) ($fare['total'] ?? 0);

                if ($totalFare <= 0) {
                    throw new \Exception(
                        'Invalid booking total fare.'
                    );
                }

                $booking->total_fare = $totalFare;

                /*
                |--------------------------------------------------------------------------
                | Initial Payment State
                |--------------------------------------------------------------------------
                */

                $booking->paid_amount = 0;
                $booking->due_amount = $totalFare;
                $booking->payment_method = $paymentMethod;
                $booking->payment_status = 'pending';
                $booking->status = 'pending';
                $booking->transaction_id = null;

                $booking->save();

                return $booking;
            });

        } catch (\Throwable $e) {

            Log::error('Booking Creation Failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create booking.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Determine Stripe Amount
        |--------------------------------------------------------------------------
        |
        | card    = full fare
        | cash    = $1 reservation fee
        | deposit = $1 reservation fee
        |
        */

        if ($paymentMethod === 'card') {

            $amountToCharge =
                (float) $booking->total_fare;

        } else {

            $amountToCharge = 1.00;
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Stripe Payment
        |--------------------------------------------------------------------------
        */

        try {

            $stripeSecret =
                config('services.stripe.secret');

            if (empty($stripeSecret)) {
                throw new \Exception(
                    'Stripe secret key is not configured.'
                );
            }

            Stripe::setApiKey($stripeSecret);

            /*
            |--------------------------------------------------------------------------
            | Idempotency Key
            |--------------------------------------------------------------------------
            */

            $idempotencyKey =
                'booking-'
                . $booking->id
                . '-'
                . md5(
                    $booking->booking_no
                    . '|'
                    . $amountToCharge
                    . '|'
                    . $paymentMethod
                );

            /*
            |--------------------------------------------------------------------------
            | Create PaymentIntent
            |--------------------------------------------------------------------------
            */

            $paymentIntent = PaymentIntent::create(
                [
                    'amount' => (int) round(
                        $amountToCharge * 100
                    ),

                    'currency' => 'usd',

                    'payment_method_data' => [
                        'type' => 'card',

                        'card' => [
                            'token' => $request->stripe_token,
                        ],
                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | Automatically capture payment
                    |--------------------------------------------------------------------------
                    */

                    'capture_method' => 'automatic',

                    'confirm' => true,

                    /*
                    |--------------------------------------------------------------------------
                    | IMPORTANT
                    |--------------------------------------------------------------------------
                    |
                    | Prevent Stripe from using redirect-based
                    | payment methods.
                    |
                    */

                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never',
                    ],

                    'description' =>
                        'Booking: ' . $booking->booking_no,

                    'receipt_email' =>
                        $booking->passenger_email,

                    'metadata' => [
                        'booking_id' =>
                            (string) $booking->id,

                        'booking_no' =>
                            (string) $booking->booking_no,

                        'payment_method' =>
                            (string) $paymentMethod,

                        'total_fare' =>
                            (string) $booking->total_fare,

                        'amount_to_charge' =>
                            (string) $amountToCharge,
                    ],
                ],
                [
                    'idempotency_key' =>
                        $idempotencyKey,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Save PaymentIntent ID
            |--------------------------------------------------------------------------
            */

            $booking->transaction_id =
                $paymentIntent->id;

            $booking->save();

            Log::info(
                'Stripe PaymentIntent Created',
                [
                    'booking_id' =>
                        $booking->id,

                    'booking_no' =>
                        $booking->booking_no,

                    'payment_intent_id' =>
                        $paymentIntent->id,

                    'amount' =>
                        $amountToCharge,

                    'status' =>
                        $paymentIntent->status,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 5. Verify Payment
            |--------------------------------------------------------------------------
            */

            if ($paymentIntent->status !== 'succeeded') {

                throw new \Exception(
                    'Stripe payment was not successful. Status: '
                    . $paymentIntent->status
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 6. Verify Amount
            |--------------------------------------------------------------------------
            */

            $amountPaid =
                (float) (
                    ($paymentIntent->amount_received ?? 0)
                    / 100
                );

            if (
                abs(
                    $amountPaid - $amountToCharge
                ) > 0.01
            ) {

                Log::error(
                    'Stripe Payment Amount Mismatch',
                    [
                        'booking_id' =>
                            $booking->id,

                        'booking_no' =>
                            $booking->booking_no,

                        'payment_intent_id' =>
                            $paymentIntent->id,

                        'expected_amount' =>
                            $amountToCharge,

                        'received_amount' =>
                            $amountPaid,
                    ]
                );

                throw new \Exception(
                    'Stripe payment amount mismatch.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 7. Calculate Due Amount
            |--------------------------------------------------------------------------
            */

            $totalFare =
                (float) $booking->total_fare;

            $dueAmount =
                max(
                    0,
                    $totalFare - $amountPaid
                );

            $paymentStatus =
                $dueAmount <= 0.01
                    ? 'paid'
                    : 'partial';

            /*
            |--------------------------------------------------------------------------
            | 8. Card Information
            |--------------------------------------------------------------------------
            |
            | This is optional. PaymentIntent charges may not always
            | contain expanded charge data.
            |
            */

            try {

                $charge =
                    $paymentIntent->charges->data[0]
                    ?? null;

                if ($charge) {

                    $paymentMethodDetails =
                        $charge->payment_method_details
                        ?? null;

                    if (
                        $paymentMethodDetails
                        && isset(
                            $paymentMethodDetails->card
                        )
                    ) {

                        $booking->card_brand =
                            $paymentMethodDetails
                                ->card
                                ->brand
                                ?? null;

                        $booking->card_last_four =
                            $paymentMethodDetails
                                ->card
                                ->last4
                                ?? null;
                    }
                }

            } catch (\Throwable $e) {

                Log::warning(
                    'Unable To Read Stripe Card Details',
                    [
                        'payment_intent_id' =>
                            $paymentIntent->id,

                        'error' =>
                            $e->getMessage(),
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 9. Confirm Booking
            |--------------------------------------------------------------------------
            */

            $booking->paid_amount =
                $amountPaid;

            $booking->due_amount =
                $dueAmount;

            $booking->transaction_id =
                $paymentIntent->id;

            $booking->payment_status =
                $paymentStatus;

            $booking->status =
                'confirmed';

            $booking->save();

            /*
            |--------------------------------------------------------------------------
            | 10. Log Success
            |--------------------------------------------------------------------------
            */

            Log::info(
                'Booking Confirmed Successfully',
                [
                    'booking_id' =>
                        $booking->id,

                    'booking_no' =>
                        $booking->booking_no,

                    'payment_intent_id' =>
                        $paymentIntent->id,

                    'payment_method' =>
                        $paymentMethod,

                    'total_fare' =>
                        $totalFare,

                    'paid_amount' =>
                        $amountPaid,

                    'due_amount' =>
                        $dueAmount,

                    'payment_status' =>
                        $paymentStatus,

                    'booking_status' =>
                        $booking->status,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 11. Send Emails
            |--------------------------------------------------------------------------
            |
            | Email failure must NOT make payment fail.
            |
            */

            try {

                if (!empty($booking->passenger_email)) {

                    Mail::to(
                        $booking->passenger_email
                    )->queue(
                        new BookingConfirmationMail(
                            $booking
                        )
                    );
                }

                $adminEmail =
                     config('mail.from.address');

                if (!empty($adminEmail)) {

                    Mail::to($adminEmail)->queue(
                        new AdminBookingConfirmationMail(
                            $booking
                        )
                    );
                }

            } catch (\Throwable $e) {

                Log::error(
                    'Booking Confirmation Email Failed',
                    [
                        'booking_id' =>
                            $booking->id,

                        'booking_no' =>
                            $booking->booking_no,

                        'error' =>
                            $e->getMessage(),
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 12. Success Redirect
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'home',
                    [
                        'payment' => 'success',
                        'booking' =>
                            $booking->booking_no,
                    ]
                )
                ->with(
                    'notify',
                    [
                        'type' => 'success',

                        'message' =>
                            'Payment successful! Booking confirmed.',
                    ]
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Payment Failed
            |--------------------------------------------------------------------------
            */

            $booking->payment_status =
                'failed';

            $booking->status =
                'pending';

            $booking->save();

            Log::error(
                'Stripe Payment Failed',
                [
                    'booking_id' =>
                        $booking->id,

                    'booking_no' =>
                        $booking->booking_no,

                    'payment_method' =>
                        $paymentMethod,

                    'amount' =>
                        $amountToCharge,

                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Payment Failed Email
            |--------------------------------------------------------------------------
            */

            try {

                if (!empty($booking->passenger_email)) {

                    Mail::to(
                        $booking->passenger_email
                    )->queue(
                        new PaymentFailedMail(
                            $booking
                        )
                    );
                }

            } catch (\Throwable $mailException) {

                Log::error(
                    'Payment Failed Email Failed',
                    [
                        'booking_id' =>
                            $booking->id,

                        'booking_no' =>
                            $booking->booking_no,

                        'message' =>
                            $mailException->getMessage(),
                    ]
                );
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Payment could not be processed. Please try again.'
                );
        }
    }
}
