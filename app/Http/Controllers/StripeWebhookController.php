<?php

namespace App\Http\Controllers;

use App\Mail\AdminBookingConfirmationMail;
use App\Mail\BookingConfirmationMail;
use App\Mail\PaymentFailedMail;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MAIN WEBHOOK HANDLER
    |--------------------------------------------------------------------------
    */

    public function handle(Request $request)
    {
        $payload = $request->getContent();

        $signature =
            $request->header('Stripe-Signature');

        $webhookSecret =
            config('services.stripe.webhook_secret');


        /*
        |--------------------------------------------------------------------------
        | Verify Stripe Signature
        |--------------------------------------------------------------------------
        */

        try {

            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );

        } catch (\UnexpectedValueException $e) {

            Log::error(
                'Stripe Webhook Invalid Payload',
                [
                    'error' =>
                        $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Invalid payload',
            ], 400);

        } catch (SignatureVerificationException $e) {

            Log::error(
                'Stripe Webhook Invalid Signature',
                [
                    'error' =>
                        $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 400);
        }


        Log::info(
            'Stripe Webhook Received',
            [
                'event_id' =>
                    $event->id,

                'event_type' =>
                    $event->type,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Process Event
        |--------------------------------------------------------------------------
        */

        try {

            switch ($event->type) {

                /*
                |--------------------------------------------------------------------------
                | Payment Requires Capture
                |--------------------------------------------------------------------------
                */

                case 'payment_intent.requires_capture':

                    $paymentIntent =
                        $event->data->object;

                    $this->capturePaymentIntent(
                        $paymentIntent
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Capturable Amount Updated
                |--------------------------------------------------------------------------
                */

                case 'payment_intent.amount_capturable_updated':

                    $paymentIntent =
                        $event->data->object;

                    $this->capturePaymentIntent(
                        $paymentIntent
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Payment Successfully Captured
                |--------------------------------------------------------------------------
                */

                case 'payment_intent.succeeded':

                    $paymentIntent =
                        $event->data->object;

                    $this->paymentSucceeded(
                        $paymentIntent
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Payment Failed
                |--------------------------------------------------------------------------
                */

                case 'payment_intent.payment_failed':

                    $paymentIntent =
                        $event->data->object;

                    $this->paymentFailed(
                        $paymentIntent
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Payment Canceled
                |--------------------------------------------------------------------------
                */

                case 'payment_intent.canceled':

                    $paymentIntent =
                        $event->data->object;

                    $this->paymentCanceled(
                        $paymentIntent
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Charge Succeeded
                |--------------------------------------------------------------------------
                */

                case 'charge.succeeded':

                    Log::info(
                        'Stripe Charge Succeeded',
                        [
                            'charge_id' =>
                                $event->data->object->id
                                ?? null,

                            'payment_intent_id' =>
                                $event->data->object
                                    ->payment_intent
                                    ?? null,
                        ]
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Charge Captured
                |--------------------------------------------------------------------------
                */

                case 'charge.captured':

                    Log::info(
                        'Stripe Charge Captured',
                        [
                            'charge_id' =>
                                $event->data->object->id
                                ?? null,

                            'payment_intent_id' =>
                                $event->data->object
                                    ->payment_intent
                                    ?? null,
                        ]
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | Other Events
                |--------------------------------------------------------------------------
                */

                default:

                    Log::info(
                        'Stripe Webhook Event Ignored',
                        [
                            'event_type' =>
                                $event->type,
                        ]
                    );

                    break;
            }


            return response()->json([
                'success' => true,
                'message' =>
                    'Webhook handled successfully',
            ], 200);


        } catch (\Throwable $e) {

            Log::error(
                'Stripe Webhook Processing Error',
                [
                    'event_id' =>
                        $event->id ?? null,

                    'event_type' =>
                        $event->type ?? null,

                    'error' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),
                ]
            );


            return response()->json([
                'success' => false,
                'message' =>
                    'Webhook processing failed',
            ], 200);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | FIND BOOKING
    |--------------------------------------------------------------------------
    */

    private function findBooking(
        $paymentIntent
    ): ?Booking {

        $metadata =
            $paymentIntent->metadata
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | By Booking ID
        |--------------------------------------------------------------------------
        */

        $bookingId =
            $metadata->booking_id
            ?? null;


        if ($bookingId) {

            $booking =
                Booking::find($bookingId);

            if ($booking) {
                return $booking;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | By Booking Number
        |--------------------------------------------------------------------------
        */

        $bookingNo =
            $metadata->booking_no
            ?? null;


        if ($bookingNo) {

            $booking =
                Booking::where(
                    'booking_no',
                    $bookingNo
                )->first();

            if ($booking) {
                return $booking;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | By Payment Intent ID
        |--------------------------------------------------------------------------
        */

        if (
            isset($paymentIntent->id)
        ) {

            return Booking::where(
                'transaction_id',
                $paymentIntent->id
            )->first();
        }


        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | CAPTURE PAYMENT
    |--------------------------------------------------------------------------
    */

    private function capturePaymentIntent(
        $paymentIntent
    ): void {

        if (
            !isset($paymentIntent->id)
        ) {

            Log::error(
                'Stripe PaymentIntent ID Missing'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Check Status
        |--------------------------------------------------------------------------
        */

        if (
            $paymentIntent->status !==
            'requires_capture'
        ) {

            Log::info(
                'Stripe PaymentIntent Does Not Require Capture',
                [
                    'payment_intent_id' =>
                        $paymentIntent->id,

                    'status' =>
                        $paymentIntent->status,
                ]
            );

            return;
        }


        try {

            $stripe =
                new \Stripe\StripeClient(
                    config('services.stripe.secret')
                );


            /*
            |--------------------------------------------------------------------------
            | Get Latest PaymentIntent
            |--------------------------------------------------------------------------
            */

            $latestPaymentIntent =
                $stripe->paymentIntents->retrieve(
                    $paymentIntent->id,
                    []
                );


            /*
            |--------------------------------------------------------------------------
            | Check Again
            |--------------------------------------------------------------------------
            */

            if (
                $latestPaymentIntent->status !==
                'requires_capture'
            ) {

                Log::info(
                    'PaymentIntent Already Captured Or Changed',
                    [
                        'payment_intent_id' =>
                            $latestPaymentIntent->id,

                        'status' =>
                            $latestPaymentIntent->status,
                    ]
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Capture
            |--------------------------------------------------------------------------
            */

            $capturedPaymentIntent =
                $stripe->paymentIntents->capture(
                    $latestPaymentIntent->id,
                    []
                );


            Log::info(
                'Stripe PaymentIntent Captured Successfully',
                [
                    'payment_intent_id' =>
                        $capturedPaymentIntent->id,

                    'status' =>
                        $capturedPaymentIntent->status,

                    'amount_received' =>
                        $capturedPaymentIntent
                            ->amount_received
                            ?? 0,
                ]
            );


        } catch (\Throwable $e) {

            Log::error(
                'Stripe PaymentIntent Capture Failed',
                [
                    'payment_intent_id' =>
                        $paymentIntent->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            throw $e;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT SUCCESS
    |--------------------------------------------------------------------------
    */

    private function paymentSucceeded(
        $paymentIntent
    ): void {

        $booking =
            $this->findBooking(
                $paymentIntent
            );


        /*
        |--------------------------------------------------------------------------
        | Booking Not Found
        |--------------------------------------------------------------------------
        */

        if (!$booking) {

            Log::error(
                'Stripe Payment Booking Not Found',
                [
                    'payment_intent_id' =>
                        $paymentIntent->id
                        ?? null,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Amount Paid
        |--------------------------------------------------------------------------
        */

        $amountPaid =
            (float) (
                ($paymentIntent->amount_received ?? 0)
                / 100
            );


        $totalFare =
            (float) $booking->total_fare;


        /*
        |--------------------------------------------------------------------------
        | Payment Method
        |--------------------------------------------------------------------------
        */

        $paymentMethod =
            $paymentIntent->metadata->payment_method
            ?? $booking->payment_method;


        /*
        |--------------------------------------------------------------------------
        | Expected Stripe Amount
        |--------------------------------------------------------------------------
        |
        | deposit = $1
        | cash    = $1
        | card    = full fare
        |
        */

        $expectedAmount =
            $paymentMethod === 'card'
                ? $totalFare
                : 1.00;


        /*
        |--------------------------------------------------------------------------
        | Amount Security Check
        |--------------------------------------------------------------------------
        */

        if (
            abs(
                $amountPaid -
                $expectedAmount
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

                    'payment_method' =>
                        $paymentMethod,

                    'expected_amount' =>
                        $expectedAmount,

                    'received_amount' =>
                        $amountPaid,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Zero Payment Protection
        |--------------------------------------------------------------------------
        */

        if ($amountPaid <= 0) {

            Log::error(
                'Stripe Payment Amount Is Zero',
                [
                    'booking_id' =>
                        $booking->id,

                    'payment_intent_id' =>
                        $paymentIntent->id,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Duplicate Protection
        |--------------------------------------------------------------------------
        */

        if (
            $booking->transaction_id ===
                $paymentIntent->id
            &&
            in_array(
                $booking->payment_status,
                [
                    'partial',
                    'paid',
                ],
                true
            )
        ) {

            Log::info(
                'Stripe Payment Already Processed',
                [
                    'booking_id' =>
                        $booking->id,

                    'payment_intent_id' =>
                        $paymentIntent->id,
                ]
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Due Amount
        |--------------------------------------------------------------------------
        */

        $dueAmount =
            max(
                0,
                $totalFare - $amountPaid
            );


        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        $paymentStatus =
            $dueAmount <= 0.01
                ? 'paid'
                : 'partial';


        /*
        |--------------------------------------------------------------------------
        | Update Booking
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


        /*
        |--------------------------------------------------------------------------
        | Card Information
        |--------------------------------------------------------------------------
        */

        try {

            $charge =
                $paymentIntent
                    ->charges
                    ->data[0]
                    ?? null;


            if ($charge) {

                $paymentMethodDetails =
                    $charge
                        ->payment_method_details
                        ?? null;


                if (
                    $paymentMethodDetails
                    &&
                    isset(
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
        | Save Final Booking
        |--------------------------------------------------------------------------
        */

        $booking->save();


        /*
        |--------------------------------------------------------------------------
        | Log Success
        |--------------------------------------------------------------------------
        */

        Log::info(
            'Stripe Payment Succeeded',
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
        | Send Confirmation Email
        |--------------------------------------------------------------------------
        */

        try {

            Mail::to(
                $booking->passenger_email
            )->send(
                new BookingConfirmationMail(
                    $booking
                )
            );
          Mail::to(config('mail.from.address'))
            ->send(
                new AdminBookingConfirmationMail($booking)
            );

        } catch (\Throwable $e) {

            Log::error(
                'Booking Confirmation Email Failed',
                [
                    'booking_id' =>
                        $booking->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT FAILED
    |--------------------------------------------------------------------------
    */

    private function paymentFailed(
        $paymentIntent
    ): void {

        $booking =
            $this->findBooking(
                $paymentIntent
            );


        if (!$booking) {

            Log::error(
                'Failed Payment Booking Not Found',
                [
                    'payment_intent_id' =>
                        $paymentIntent->id
                        ?? null,
                ]
            );

            return;
        }


        $booking->payment_status =
            'failed';

        $booking->status =
            'pending';

        $booking->save();


        Log::warning(
            'Stripe Payment Failed',
            [
                'booking_id' =>
                    $booking->id,

                'booking_no' =>
                    $booking->booking_no,

                'payment_intent_id' =>
                    $paymentIntent->id
                    ?? null,

                'error_message' =>
                    $paymentIntent
                        ->last_payment_error
                        ->message
                        ?? null,
            ]
        );


        try {

            Mail::to(
                $booking->passenger_email
            )->send(
                new PaymentFailedMail(
                    $booking
                )
            );


        } catch (\Throwable $e) {

            Log::error(
                'Payment Failed Email Error',
                [
                    'booking_id' =>
                        $booking->id,

                    'error' =>
                        $e->getMessage(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT CANCELED
    |--------------------------------------------------------------------------
    */

    private function paymentCanceled(
        $paymentIntent
    ): void {

        $booking =
            $this->findBooking(
                $paymentIntent
            );


        if (!$booking) {
            return;
        }


        $booking->payment_status =
            'failed';

        $booking->status =
            'pending';

        $booking->save();


        Log::warning(
            'Stripe Payment Canceled',
            [
                'booking_id' =>
                    $booking->id,

                'booking_no' =>
                    $booking->booking_no,

                'payment_intent_id' =>
                    $paymentIntent->id
                    ?? null,
            ]
        );
    }
}

