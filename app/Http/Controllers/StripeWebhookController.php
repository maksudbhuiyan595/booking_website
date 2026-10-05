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

// class StripeWebhookController extends Controller
// {
//     /*
//     |--------------------------------------------------------------------------
//     | MAIN WEBHOOK HANDLER
//     |--------------------------------------------------------------------------
//     */

//     public function handle(Request $request)
//     {
//         $payload = $request->getContent();

//         $signature =
//             $request->header('Stripe-Signature');

//         $webhookSecret =
//             config('services.stripe.webhook_secret');


//         /*
//         |--------------------------------------------------------------------------
//         | Verify Stripe Signature
//         |--------------------------------------------------------------------------
//         */

//         try {

//             $event = Webhook::constructEvent(
//                 $payload,
//                 $signature,
//                 $webhookSecret
//             );

//         } catch (\UnexpectedValueException $e) {

//             Log::error(
//                 'Stripe Webhook Invalid Payload',
//                 [
//                     'error' =>
//                         $e->getMessage(),
//                 ]
//             );

//             return response()->json([
//                 'success' => false,
//                 'message' => 'Invalid payload',
//             ], 400);

//         } catch (SignatureVerificationException $e) {

//             Log::error(
//                 'Stripe Webhook Invalid Signature',
//                 [
//                     'error' =>
//                         $e->getMessage(),
//                 ]
//             );

//             return response()->json([
//                 'success' => false,
//                 'message' => 'Invalid signature',
//             ], 400);
//         }


//         Log::info(
//             'Stripe Webhook Received',
//             [
//                 'event_id' =>
//                     $event->id,

//                 'event_type' =>
//                     $event->type,
//             ]
//         );


//         /*
//         |--------------------------------------------------------------------------
//         | Process Event
//         |--------------------------------------------------------------------------
//         */

//         try {

//             switch ($event->type) {

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Payment Requires Capture
//                 |--------------------------------------------------------------------------
//                 */

//                 case 'payment_intent.requires_capture':

//                     $paymentIntent =
//                         $event->data->object;

//                     $this->capturePaymentIntent(
//                         $paymentIntent
//                     );

//                     break;


//                 /*
//                 |--------------------------------------------------------------------------
//                 | Capturable Amount Updated
//                 |--------------------------------------------------------------------------
//                 */

//                 case 'payment_intent.amount_capturable_updated':

//                     $paymentIntent =
//                         $event->data->object;

//                     $this->capturePaymentIntent(
//                         $paymentIntent
//                     );

//                     break;


//                 /*
//                 |--------------------------------------------------------------------------
//                 | Payment Successfully Captured
//                 |--------------------------------------------------------------------------
//                 */

//                 case 'payment_intent.succeeded':

//                     $paymentIntent =
//                         $event->data->object;

//                     $this->paymentSucceeded(
//                         $paymentIntent
//                     );

//                     break;


//                 /*
//                 |--------------------------------------------------------------------------
//                 | Payment Failed
//                 |--------------------------------------------------------------------------
//                 */

//                 case 'payment_intent.payment_failed':

//                     $paymentIntent =
//                         $event->data->object;

//                     $this->paymentFailed(
//                         $paymentIntent
//                     );

//                     break;


//                 /*
//                 |--------------------------------------------------------------------------
//                 | Payment Canceled
//                 |--------------------------------------------------------------------------
//                 */

//                 case 'payment_intent.canceled':

//                     $paymentIntent =
//                         $event->data->object;

//                     $this->paymentCanceled(
//                         $paymentIntent
//                     );

//                     break;


//                 /*
//                 |--------------------------------------------------------------------------
//                 | Charge Succeeded
//                 |--------------------------------------------------------------------------
//                 */

//                 case 'charge.succeeded':

//                     Log::info(
//                         'Stripe Charge Succeeded',
//                         [
//                             'charge_id' =>
//                                 $event->data->object->id
//                                 ?? null,

//                             'payment_intent_id' =>
//                                 $event->data->object
//                                     ->payment_intent
//                                 ?? null,
//                         ]
//                     );

//                     break;


//                 /*
//                 |--------------------------------------------------------------------------
//                 | Charge Captured
//                 |--------------------------------------------------------------------------
//                 */

//                 case 'charge.captured':

//                     Log::info(
//                         'Stripe Charge Captured',
//                         [
//                             'charge_id' =>
//                                 $event->data->object->id
//                                 ?? null,

//                             'payment_intent_id' =>
//                                 $event->data->object
//                                     ->payment_intent
//                                 ?? null,
//                         ]
//                     );

//                     break;


//                 /*
//                 |--------------------------------------------------------------------------
//                 | Other Events
//                 |--------------------------------------------------------------------------
//                 */

//                 default:

//                     Log::info(
//                         'Stripe Webhook Event Ignored',
//                         [
//                             'event_type' =>
//                                 $event->type,
//                         ]
//                     );

//                     break;
//             }


//             return response()->json([
//                 'success' => true,
//                 'message' =>
//                     'Webhook handled successfully',
//             ], 200);


//         } catch (\Throwable $e) {

//             Log::error(
//                 'Stripe Webhook Processing Error',
//                 [
//                     'event_id' =>
//                         $event->id ?? null,

//                     'event_type' =>
//                         $event->type ?? null,

//                     'error' =>
//                         $e->getMessage(),

//                     'file' =>
//                         $e->getFile(),

//                     'line' =>
//                         $e->getLine(),
//                 ]
//             );


//             return response()->json([
//                 'success' => false,
//                 'message' =>
//                     'Webhook processing failed',
//             ], 200);
//         }
//     }


//     /*
//     |--------------------------------------------------------------------------
//     | FIND BOOKING
//     |--------------------------------------------------------------------------
//     */

//     private function findBooking(
//         $paymentIntent
//     ): ?Booking {

//         $metadata =
//             $paymentIntent->metadata
//             ?? null;


//         /*
//         |--------------------------------------------------------------------------
//         | By Booking ID
//         |--------------------------------------------------------------------------
//         */

//         $bookingId =
//             $metadata->booking_id
//             ?? null;


//         if ($bookingId) {

//             $booking =
//                 Booking::find($bookingId);

//             if ($booking) {
//                 return $booking;
//             }
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | By Booking Number
//         |--------------------------------------------------------------------------
//         */

//         $bookingNo =
//             $metadata->booking_no
//             ?? null;


//         if ($bookingNo) {

//             $booking =
//                 Booking::where(
//                     'booking_no',
//                     $bookingNo
//                 )->first();

//             if ($booking) {
//                 return $booking;
//             }
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | By Payment Intent ID
//         |--------------------------------------------------------------------------
//         */

//         if (
//             isset($paymentIntent->id)
//         ) {

//             return Booking::where(
//                 'transaction_id',
//                 $paymentIntent->id
//             )->first();
//         }


//         return null;
//     }


//     /*
//     |--------------------------------------------------------------------------
//     | CAPTURE PAYMENT
//     |--------------------------------------------------------------------------
//     */

//     private function capturePaymentIntent(
//         $paymentIntent
//     ): void {

//         if (
//             !isset($paymentIntent->id)
//         ) {

//             Log::error(
//                 'Stripe PaymentIntent ID Missing'
//             );

//             return;
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Check Status
//         |--------------------------------------------------------------------------
//         */

//         if (
//             $paymentIntent->status !==
//             'requires_capture'
//         ) {

//             Log::info(
//                 'Stripe PaymentIntent Does Not Require Capture',
//                 [
//                     'payment_intent_id' =>
//                         $paymentIntent->id,

//                     'status' =>
//                         $paymentIntent->status,
//                 ]
//             );

//             return;
//         }


//         try {

//             $stripe =
//                 new \Stripe\StripeClient(
//                     config('services.stripe.secret')
//                 );


//             /*
//             |--------------------------------------------------------------------------
//             | Get Latest PaymentIntent
//             |--------------------------------------------------------------------------
//             */

//             $latestPaymentIntent =
//                 $stripe->paymentIntents->retrieve(
//                     $paymentIntent->id,
//                     []
//                 );


//             /*
//             |--------------------------------------------------------------------------
//             | Check Again
//             |--------------------------------------------------------------------------
//             */

//             if (
//                 $latestPaymentIntent->status !==
//                 'requires_capture'
//             ) {

//                 Log::info(
//                     'PaymentIntent Already Captured Or Changed',
//                     [
//                         'payment_intent_id' =>
//                             $latestPaymentIntent->id,

//                         'status' =>
//                             $latestPaymentIntent->status,
//                     ]
//                 );

//                 return;
//             }


//             /*
//             |--------------------------------------------------------------------------
//             | Capture
//             |--------------------------------------------------------------------------
//             */

//             $capturedPaymentIntent =
//                 $stripe->paymentIntents->capture(
//                     $latestPaymentIntent->id,
//                     []
//                 );


//             Log::info(
//                 'Stripe PaymentIntent Captured Successfully',
//                 [
//                     'payment_intent_id' =>
//                         $capturedPaymentIntent->id,

//                     'status' =>
//                         $capturedPaymentIntent->status,

//                     'amount_received' =>
//                         $capturedPaymentIntent
//                             ->amount_received
//                         ?? 0,
//                 ]
//             );


//         } catch (\Throwable $e) {

//             Log::error(
//                 'Stripe PaymentIntent Capture Failed',
//                 [
//                     'payment_intent_id' =>
//                         $paymentIntent->id,

//                     'error' =>
//                         $e->getMessage(),
//                 ]
//             );

//             throw $e;
//         }
//     }


//     /*
//     |--------------------------------------------------------------------------
//     | PAYMENT SUCCESS
//     |--------------------------------------------------------------------------
//     */

//     private function paymentSucceeded(
//         $paymentIntent
//     ): void {

//         $booking =
//             $this->findBooking(
//                 $paymentIntent
//             );


//         /*
//         |--------------------------------------------------------------------------
//         | Booking Not Found
//         |--------------------------------------------------------------------------
//         */

//         if (!$booking) {

//             Log::error(
//                 'Stripe Payment Booking Not Found',
//                 [
//                     'payment_intent_id' =>
//                         $paymentIntent->id
//                         ?? null,
//                 ]
//             );

//             return;
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Amount Paid
//         |--------------------------------------------------------------------------
//         */

//         $amountPaid =
//             (float) (
//                 ($paymentIntent->amount_received ?? 0)
//                 / 100
//             );


//         $totalFare =
//             (float) $booking->total_fare;


//         /*
//         |--------------------------------------------------------------------------
//         | Payment Method
//         |--------------------------------------------------------------------------
//         */

//         $paymentMethod =
//             $paymentIntent->metadata->payment_method
//             ?? $booking->payment_method;


//         /*
//         |--------------------------------------------------------------------------
//         | Expected Stripe Amount
//         |--------------------------------------------------------------------------
//         |
//         | deposit = $1
//         | cash    = $1
//         | card    = full fare
//         |
//         */

//         $expectedAmount =
//             $paymentMethod === 'card'
//             ? $totalFare
//             : 1.00;


//         /*
//         |--------------------------------------------------------------------------
//         | Amount Security Check
//         |--------------------------------------------------------------------------
//         */

//         if (
//             abs(
//                 $amountPaid -
//                 $expectedAmount
//             ) > 0.01
//         ) {

//             Log::error(
//                 'Stripe Payment Amount Mismatch',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'booking_no' =>
//                         $booking->booking_no,

//                     'payment_intent_id' =>
//                         $paymentIntent->id,

//                     'payment_method' =>
//                         $paymentMethod,

//                     'expected_amount' =>
//                         $expectedAmount,

//                     'received_amount' =>
//                         $amountPaid,
//                 ]
//             );

//             return;
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Zero Payment Protection
//         |--------------------------------------------------------------------------
//         */

//         if ($amountPaid <= 0) {

//             Log::error(
//                 'Stripe Payment Amount Is Zero',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'payment_intent_id' =>
//                         $paymentIntent->id,
//                 ]
//             );

//             return;
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Duplicate Protection
//         |--------------------------------------------------------------------------
//         */

//         if (
//             $booking->transaction_id ===
//             $paymentIntent->id
//             &&
//             in_array(
//                 $booking->payment_status,
//                 [
//                     'partial',
//                     'paid',
//                 ],
//                 true
//             )
//         ) {

//             Log::info(
//                 'Stripe Payment Already Processed',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'payment_intent_id' =>
//                         $paymentIntent->id,
//                 ]
//             );

//             return;
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Calculate Due Amount
//         |--------------------------------------------------------------------------
//         */

//         $dueAmount =
//             max(
//                 0,
//                 $totalFare - $amountPaid
//             );


//         /*
//         |--------------------------------------------------------------------------
//         | Payment Status
//         |--------------------------------------------------------------------------
//         */

//         $paymentStatus =
//             $dueAmount <= 0.01
//             ? 'paid'
//             : 'partial';


//         /*
//         |--------------------------------------------------------------------------
//         | Update Booking
//         |--------------------------------------------------------------------------
//         */

//         $booking->paid_amount =
//             $amountPaid;

//         $booking->due_amount =
//             $dueAmount;

//         $booking->transaction_id =
//             $paymentIntent->id;

//         $booking->payment_status =
//             $paymentStatus;

//         $booking->status =
//             'confirmed';


//         /*
//         |--------------------------------------------------------------------------
//         | Card Information
//         |--------------------------------------------------------------------------
//         */

//         try {

//             $charge =
//                 $paymentIntent
//                     ->charges
//                     ->data[0]
//                 ?? null;


//             if ($charge) {

//                 $paymentMethodDetails =
//                     $charge
//                         ->payment_method_details
//                     ?? null;


//                 if (
//                     $paymentMethodDetails
//                     &&
//                     isset(
//                     $paymentMethodDetails->card
//                 )
//                 ) {

//                     $booking->card_brand =
//                         $paymentMethodDetails
//                             ->card
//                             ->brand
//                         ?? null;

//                     $booking->card_last_four =
//                         $paymentMethodDetails
//                             ->card
//                             ->last4
//                         ?? null;
//                 }
//             }

//         } catch (\Throwable $e) {

//             Log::warning(
//                 'Unable To Read Stripe Card Details',
//                 [
//                     'payment_intent_id' =>
//                         $paymentIntent->id,

//                     'error' =>
//                         $e->getMessage(),
//                 ]
//             );
//         }


//         /*
//         |--------------------------------------------------------------------------
//         | Save Final Booking
//         |--------------------------------------------------------------------------
//         */

//         $booking->save();


//         /*
//         |--------------------------------------------------------------------------
//         | Log Success
//         |--------------------------------------------------------------------------
//         */

//         Log::info(
//             'Stripe Payment Succeeded',
//             [
//                 'booking_id' =>
//                     $booking->id,

//                 'booking_no' =>
//                     $booking->booking_no,

//                 'payment_intent_id' =>
//                     $paymentIntent->id,

//                 'payment_method' =>
//                     $paymentMethod,

//                 'total_fare' =>
//                     $totalFare,

//                 'paid_amount' =>
//                     $amountPaid,

//                 'due_amount' =>
//                     $dueAmount,

//                 'payment_status' =>
//                     $paymentStatus,

//                 'booking_status' =>
//                     $booking->status,
//             ]
//         );


//         /*
//         |--------------------------------------------------------------------------
//         | Send Confirmation Email
//         |--------------------------------------------------------------------------
//         */

//         try {

//             Mail::to(
//                 $booking->passenger_email
//             )->send(
//                     new BookingConfirmationMail(
//                         $booking
//                     )
//                 );
//             Mail::to(config('mail.from.address'))
//                 ->send(
//                     new AdminBookingConfirmationMail($booking)
//                 );

//         } catch (\Throwable $e) {

//             Log::error(
//                 'Booking Confirmation Email Failed',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'error' =>
//                         $e->getMessage(),
//                 ]
//             );
//         }
//     }


//     /*
//     |--------------------------------------------------------------------------
//     | PAYMENT FAILED
//     |--------------------------------------------------------------------------
//     */

//     private function paymentFailed(
//         $paymentIntent
//     ): void {

//         $booking =
//             $this->findBooking(
//                 $paymentIntent
//             );


//         if (!$booking) {

//             Log::error(
//                 'Failed Payment Booking Not Found',
//                 [
//                     'payment_intent_id' =>
//                         $paymentIntent->id
//                         ?? null,
//                 ]
//             );

//             return;
//         }


//         $booking->payment_status =
//             'failed';

//         $booking->status =
//             'pending';

//         $booking->save();


//         Log::warning(
//             'Stripe Payment Failed',
//             [
//                 'booking_id' =>
//                     $booking->id,

//                 'booking_no' =>
//                     $booking->booking_no,

//                 'payment_intent_id' =>
//                     $paymentIntent->id
//                     ?? null,

//                 'error_message' =>
//                     $paymentIntent
//                         ->last_payment_error
//                         ->message
//                     ?? null,
//             ]
//         );


//         try {

//             Mail::to(
//                 $booking->passenger_email
//             )->send(
//                     new PaymentFailedMail(
//                         $booking
//                     )
//                 );


//         } catch (\Throwable $e) {

//             Log::error(
//                 'Payment Failed Email Error',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'error' =>
//                         $e->getMessage(),
//                 ]
//             );
//         }
//     }


//     /*
//     |--------------------------------------------------------------------------
//     | PAYMENT CANCELED
//     |--------------------------------------------------------------------------
//     */

//     private function paymentCanceled(
//         $paymentIntent
//     ): void {

//         $booking =
//             $this->findBooking(
//                 $paymentIntent
//             );


//         if (!$booking) {
//             return;
//         }


//         $booking->payment_status =
//             'failed';

//         $booking->status =
//             'pending';

//         $booking->save();


//         Log::warning(
//             'Stripe Payment Canceled',
//             [
//                 'booking_id' =>
//                     $booking->id,

//                 'booking_no' =>
//                     $booking->booking_no,

//                 'payment_intent_id' =>
//                     $paymentIntent->id
//                     ?? null,
//             ]
//         );
//     }
//      public function confirmBooking(Request $request)
//     {
//         /*
//         |--------------------------------------------------------------------------
//         | 1. Validate Request
//         |--------------------------------------------------------------------------
//         */

//         $validated = $request->validate([
//             'stripe_token' => [
//                 'required',
//                 'string',
//             ],

//             'payment_method' => [
//                 'required',
//                 'in:cash,deposit,card',
//             ],

//             'passenger_name' => [
//                 'required',
//                 'string',
//                 'max:255',
//             ],

//             'passenger_email' => [
//                 'required',
//                 'email',
//                 'max:255',
//             ],

//             'phone_number' => [
//                 'required',
//                 'string',
//                 'max:50',
//             ],
//         ]);

//         $paymentMethod = $validated['payment_method'];

//         /*
//         |--------------------------------------------------------------------------
//         | 2. Stripe Token Required
//         |--------------------------------------------------------------------------
//         |
//         | ALL payment options use Stripe.
//         |
//         | cash    = $1 Stripe reservation fee
//         | deposit = $1 Stripe reservation fee
//         | card    = Full fare Stripe payment
//         |
//         */

//         if (empty($request->stripe_token)) {
//             return back()
//                 ->withInput()
//                 ->with(
//                     'error',
//                     'Stripe payment information is required.'
//                 );
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | 3. Create Booking
//         |--------------------------------------------------------------------------
//         */

//         try {

//             $booking = DB::transaction(function () use ($request, $paymentMethod) {

//                 $booking = new Booking();

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Generate Booking Number
//                 |--------------------------------------------------------------------------
//                 */

//                 // $lastBooking = Booking::lockForUpdate()
//                 //     ->orderByDesc('id')
//                 //     ->first();

//                 // if (
//                 //     $lastBooking &&
//                 //     preg_match(
//                 //         '/BLAT-(\d+)/',
//                 //         (string) $lastBooking->booking_no,
//                 //         $matches
//                 //     )
//                 // ) {
//                 //     $nextNumber = ((int) $matches[1]) + 1;
//                 // } else {
//                 //     $nextNumber = 1;
//                 // }

//                 // $booking->booking_no = 'BLAT-' . str_pad(
//                 //     $nextNumber,
//                 //     4,
//                 //     '0',
//                 //     STR_PAD_LEFT
//                 // );
//                 $lastNumber = Booking::where('booking_no', 'like', 'BLAT-%')
//                     ->selectRaw("
//                         MAX(
//                             CAST(
//                                 SUBSTRING(booking_no, 6)
//                                 AS UNSIGNED
//                             )
//                         ) as max_number
//                     ")
//                     ->value('max_number');

//                 $nextNumber = ((int) ($lastNumber ?? 0)) + 1;

//                 $booking->booking_no = 'BLAT-' . str_pad(
//                     $nextNumber,
//                     4,
//                     '0',
//                     STR_PAD_LEFT
//                 );

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Passenger
//                 |--------------------------------------------------------------------------
//                 */

//                 $booking->passenger_name =
//                     $request->passenger_name;

//                 $booking->passenger_email =
//                     $request->passenger_email;

//                 $booking->passenger_phone =
//                     $request->phone_number;

//                 $booking->phone_country_code =
//                     $request->phone_country_code;

//                 $booking->alternate_phone =
//                     $request->alternate_phone;

//                 $booking->mailing_address =
//                     $request->mailing_address;

//                 $booking->special_needs =
//                     $request->special_needs;

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Trip
//                 |--------------------------------------------------------------------------
//                 */

//                 $booking->trip_type =
//                     $request->trip_type;

//                 $booking->pickup_date =
//                     $request->date;

//                 $booking->pickup_time =
//                     $request->time;

//                 $booking->pickup_address =
//                     $request->pickup
//                     ?? $request->fromAddress;

//                 $booking->dropoff_address =
//                     $request->dropoff
//                     ?? $request->to_address;

//                 $booking->distance_miles =
//                     (float) ($request->distance_miles ?? 0);

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Flight
//                 |--------------------------------------------------------------------------
//                 */

//                 $booking->airline_name =
//                     $request->airline_name;

//                 $booking->flight_number =
//                     $request->flight_number;

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Vehicle
//                 |--------------------------------------------------------------------------
//                 */

//                 $booking->vehicle_id =
//                     $request->vehicle_id;

//                 $booking->vehicle_type =
//                     $request->vehicle_type;

//                 $booking->vehicles_used =
//                     (int) ($request->vehicles_used ?? 1);

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Passengers
//                 |--------------------------------------------------------------------------
//                 */

//                 $booking->adults =
//                     (int) ($request->adults ?? 0);

//                 $booking->children =
//                     (int) ($request->children ?? 0);

//                 $booking->total_passengers =
//                     (int) (
//                         $request->total_passengers
//                         ?? $request->reqPassengers
//                         ?? 0
//                     );

//                 $booking->luggage =
//                     (int) ($request->luggage ?? 0);

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Extras
//                 |--------------------------------------------------------------------------
//                 */

//                 $booking->booster_seat_count =
//                     (int) (
//                         $request->booster_seat ?? 0
//                     );

//                 $booking->infant_seat_count =
//                     (int) (
//                         $request->infant_seat ?? 0
//                     );

//                 $booking->front_seat_count =
//                     (int) (
//                         $request->front_seat ?? 0
//                     );

//                 $booking->stopover_count =
//                     (int) (
//                         $request->stopover ?? 0
//                     );

//                 $booking->pet_count =
//                     (int) (
//                         $request->pets ?? 0
//                     );

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Billing
//                 |--------------------------------------------------------------------------
//                 */

//                 $booking->card_holder_name =
//                     $request->card_holder_name;

//                 $booking->billing_phone =
//                     $request->billing_phone;

//                 $booking->billing_address =
//                     $request->billing_address;

//                 $booking->billing_city =
//                     $request->billing_city;

//                 $booking->billing_state =
//                     $request->billing_state;

//                 $booking->billing_zip =
//                     $request->billing_zip;

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Fare
//                 |--------------------------------------------------------------------------
//                 */

//                 $fare = $request->fare ?? [];

//                 $booking->estimated_fare =
//                     (float) (
//                         $fare['estimatedFare']
//                         ?? $fare['estimated_fare']
//                         ?? 0
//                     );

//                 $booking->gratuity =
//                     (float) (
//                         $fare['gratuity'] ?? 0
//                     );

//                 $booking->pickup_tax =
//                     (float) (
//                         $fare['pickup_tax'] ?? 0
//                     );

//                 $booking->dropoff_tax =
//                     (float) (
//                         $fare['dropoff_tax'] ?? 0
//                     );

//                 $booking->parking_fee =
//                     (float) (
//                         $fare['parking_fee'] ?? 0
//                     );

//                 $booking->toll_fee =
//                     (float) (
//                         $fare['toll_fee'] ?? 0
//                     );

//                 $booking->surcharge_fee =
//                     (float) (
//                         $fare['surcharge_fee'] ?? 0
//                     );

//                 $booking->extra_luggage_fee =
//                     (float) (
//                         $fare['extra_luggage_fee'] ?? 0
//                     );

//                 $booking->child_seat_fee =
//                     (float) (
//                         $fare['child_seat_fee'] ?? 0
//                     );

//                 $booking->booster_seat_fee =
//                     (float) (
//                         $fare['booster_seat_fee'] ?? 0
//                     );

//                 $booking->front_seat_fee =
//                     (float) (
//                         $fare['front_seat_fee'] ?? 0
//                     );

//                 $booking->stopover_fee =
//                     (float) (
//                         $fare['stopover_fee'] ?? 0
//                     );

//                 $booking->extras_total =
//                     (float) (
//                         $fare['extras_total']
//                         ?? $request->extras_total
//                         ?? 0
//                     );

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Total Fare
//                 |--------------------------------------------------------------------------
//                 */

//                 $totalFare =
//                     (float) (
//                         $fare['total'] ?? 0
//                     );

//                 if ($totalFare <= 0) {
//                     throw new \Exception(
//                         'Invalid booking total fare.'
//                     );
//                 }

//                 $booking->total_fare =
//                     $totalFare;

//                 /*
//                 |--------------------------------------------------------------------------
//                 | Initial Payment State
//                 |--------------------------------------------------------------------------
//                 |
//                 | DO NOT mark booking as confirmed here.
//                 |
//                 | Stripe webhook will confirm after successful capture.
//                 |
//                 */

//                 $booking->paid_amount = 0;

//                 $booking->due_amount =
//                     $totalFare;

//                 $booking->payment_method =
//                     $paymentMethod;

//                 $booking->payment_status =
//                     'pending';

//                 $booking->status =
//                     'pending';

//                 $booking->transaction_id =
//                     null;

//                 $booking->save();

//                 return $booking;
//             });

//         } catch (\Throwable $e) {

//             Log::error(
//                 'Booking Creation Failed',
//                 [
//                     'message' =>
//                         $e->getMessage(),

//                     'file' =>
//                         $e->getFile(),

//                     'line' =>
//                         $e->getLine(),
//                 ]
//             );

//             return back()
//                 ->withInput()
//                 ->with(
//                     'error',
//                     'Unable to create booking.'
//                 );
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | 4. Determine Stripe Amount
//         |--------------------------------------------------------------------------
//         |
//         | CASH    = $1.00 reservation fee
//         | DEPOSIT = $1.00 reservation fee
//         | CARD    = Full Fare
//         |
//         */

//         if ($paymentMethod === 'card') {

//             $amountToCharge =
//                 (float) $booking->total_fare;

//         } elseif (
//             in_array(
//                 $paymentMethod,
//                 ['cash', 'deposit'],
//                 true
//             )
//         ) {

//             $amountToCharge = 1.00;

//         } else {

//             $booking->payment_status =
//                 'failed';

//             $booking->status =
//                 'pending';

//             $booking->save();

//             Log::error(
//                 'Invalid Payment Method',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'payment_method' =>
//                         $paymentMethod,
//                 ]
//             );

//             return back()
//                 ->withInput()
//                 ->with(
//                     'error',
//                     'Invalid payment method.'
//                 );
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | 5. Validate Stripe Amount
//         |--------------------------------------------------------------------------
//         */

//         if ($amountToCharge <= 0) {

//             $booking->payment_status =
//                 'failed';

//             $booking->status =
//                 'pending';

//             $booking->save();

//             Log::error(
//                 'Invalid Stripe Payment Amount',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'booking_no' =>
//                         $booking->booking_no,

//                     'amount' =>
//                         $amountToCharge,

//                     'payment_method' =>
//                         $paymentMethod,
//                 ]
//             );

//             return back()
//                 ->withInput()
//                 ->with(
//                     'error',
//                     'Invalid payment amount.'
//                 );
//         }

//         /*
//         |--------------------------------------------------------------------------
//         | 6. Create Stripe PaymentIntent
//         |--------------------------------------------------------------------------
//         */

//         try {

//             $stripeSecret =
//                 config('services.stripe.secret');

//             if (empty($stripeSecret)) {

//                 throw new \Exception(
//                     'Stripe secret key is not configured.'
//                 );
//             }

//             Stripe::setApiKey(
//                 $stripeSecret
//             );

//             /*
//             |--------------------------------------------------------------------------
//             | Stripe Idempotency Key
//             |--------------------------------------------------------------------------
//             */

//             $idempotencyKey =
//                 'booking-' .
//                 $booking->id .
//                 '-' .
//                 md5(
//                     $booking->booking_no .
//                     '|' .
//                     $amountToCharge .
//                     '|' .
//                     $paymentMethod
//                 );

//             /*
//             |--------------------------------------------------------------------------
//             | Create PaymentIntent
//             |--------------------------------------------------------------------------
//             */

//             $paymentIntent =
//                 PaymentIntent::create(
//                     [

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Amount
//                         |--------------------------------------------------------------------------
//                         */

//                         'amount' =>
//                             (int) round(
//                                 $amountToCharge * 100
//                             ),

//                         'currency' =>
//                             'usd',

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Card Payment
//                         |--------------------------------------------------------------------------
//                         */

//                         'payment_method_data' => [
//                             'type' => 'card',

//                             'card' => [
//                                 'token' =>
//                                     $request->stripe_token,
//                             ],
//                         ],

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Manual Confirmation
//                         |--------------------------------------------------------------------------
//                         */

//                         'confirmation_method' =>
//                             'manual',

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Manual Capture
//                         |--------------------------------------------------------------------------
//                         */

//                         'capture_method' =>
//                             'manual',

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Confirm Immediately
//                         |--------------------------------------------------------------------------
//                         */

//                         'confirm' =>
//                             true,

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Return URL
//                         |--------------------------------------------------------------------------
//                         */

//                         'return_url' =>
//                             route('home'),

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Description
//                         |--------------------------------------------------------------------------
//                         */

//                         'description' =>
//                             'Booking: ' .
//                             $booking->booking_no,

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Receipt Email
//                         |--------------------------------------------------------------------------
//                         */

//                         'receipt_email' =>
//                             $booking->passenger_email,

//                         /*
//                         |--------------------------------------------------------------------------
//                         | Metadata
//                         |--------------------------------------------------------------------------
//                         */

//                         'metadata' => [

//                             'booking_id' =>
//                                 (string) $booking->id,

//                             'booking_no' =>
//                                 (string) $booking->booking_no,

//                             'payment_method' =>
//                                 (string) $paymentMethod,

//                             'phone' =>
//                                 (string) (
//                                     $booking->passenger_phone
//                                     ?? ''
//                                 ),

//                             'total_fare' =>
//                                 (string) $booking->total_fare,

//                             'amount_to_charge' =>
//                                 (string) $amountToCharge,
//                         ],
//                     ],

//                     /*
//                     |--------------------------------------------------------------------------
//                     | Stripe Request Options
//                     |--------------------------------------------------------------------------
//                     */

//                     [
//                         'idempotency_key' =>
//                             $idempotencyKey,
//                     ]
//                 );

//             /*
//             |--------------------------------------------------------------------------
//             | 7. Save Stripe PaymentIntent ID
//             |--------------------------------------------------------------------------
//             */

//             $booking->transaction_id =
//                 $paymentIntent->id;

//             $booking->save();

//             /*
//             |--------------------------------------------------------------------------
//             | 8. Log PaymentIntent
//             |--------------------------------------------------------------------------
//             */

//             Log::info(
//                 'Stripe PaymentIntent Created',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'booking_no' =>
//                         $booking->booking_no,

//                     'payment_intent_id' =>
//                         $paymentIntent->id,

//                     'amount' =>
//                         $amountToCharge,

//                     'amount_cents' =>
//                         $paymentIntent->amount,

//                     'payment_method' =>
//                         $paymentMethod,

//                     'status' =>
//                         $paymentIntent->status,
//                 ]
//             );

//             /*
//             |--------------------------------------------------------------------------
//             | 9. Handle Immediate Stripe Status
//             |--------------------------------------------------------------------------
//             */

//             if (
//                 $paymentIntent->status ===
//                 'requires_capture'
//             ) {

//                 Log::info(
//                     'Stripe Payment Authorization Successful',
//                     [
//                         'booking_id' =>
//                             $booking->id,

//                         'booking_no' =>
//                             $booking->booking_no,

//                         'payment_intent_id' =>
//                             $paymentIntent->id,

//                         'status' =>
//                             $paymentIntent->status,
//                     ]
//                 );
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | 10. Return Processing Response
//             |--------------------------------------------------------------------------
//             |
//             | IMPORTANT:
//             |
//             | DO NOT confirm booking here.
//             |
//             | Stripe webhook will:
//             |
//             | requires_capture
//             |       ↓
//             | capture()
//             |       ↓
//             | payment_intent.succeeded
//             |       ↓
//             | paid / partial
//             |       ↓
//             | confirmed
//             |
//             */


//             return redirect()->route('home', [
//                 'payment' => 'success',
//                 'booking' => $booking->booking_no
//             ])->with('notify', [
//                         'type' => 'success',
//                         'message' => 'Payment successful! Booking confirmed.'
//                     ]);

//         } catch (\Throwable $e) {

//             /*
//             |--------------------------------------------------------------------------
//             | 11. Stripe Payment Failed
//             |--------------------------------------------------------------------------
//             */

//             $booking->payment_status =
//                 'failed';

//             $booking->status =
//                 'pending';

//             $booking->save();

//             Log::error(
//                 'Stripe Payment Creation Failed',
//                 [
//                     'booking_id' =>
//                         $booking->id,

//                     'booking_no' =>
//                         $booking->booking_no,

//                     'payment_method' =>
//                         $paymentMethod,

//                     'amount' =>
//                         $amountToCharge,

//                     'message' =>
//                         $e->getMessage(),

//                     'file' =>
//                         $e->getFile(),

//                     'line' =>
//                         $e->getLine(),
//                 ]
//             );

//             /*
//             |--------------------------------------------------------------------------
//             | 12. Send Payment Failed Email
//             |--------------------------------------------------------------------------
//             */

//             try {

//                 Mail::to(
//                     $booking->passenger_email
//                 )->send(
//                         new PaymentFailedMail(
//                             $booking
//                         )
//                     );

//             } catch (\Throwable $mailException) {

//                 Log::error(
//                     'Payment Failed Email Failed',
//                     [
//                         'booking_id' =>
//                             $booking->id,

//                         'booking_no' =>
//                             $booking->booking_no,

//                         'message' =>
//                             $mailException->getMessage(),
//                     ]
//                 );
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | 13. Return Error
//             |--------------------------------------------------------------------------
//             */

//             return back()
//                 ->withInput()
//                 ->with(
//                     'error',
//                     'Payment could not be processed. Please try again.'
//                 );
//         }
//     }
// }

