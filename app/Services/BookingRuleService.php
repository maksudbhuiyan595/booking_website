<?php

namespace App\Services;

use App\Models\ExtraCharge;
use App\Models\Surcharge;
use App\Settings\GeneralSettings;
use Carbon\Carbon;

class BookingRuleService
{
    protected GeneralSettings $settings;

    public function __construct(GeneralSettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Check if Booking is currently OPEN based on Admin Settings
     * (Daily Schedule, Weekly Off, Specific Holidays, etc.)
     */
    public function isBookingOpen(): array
    {
        $status = $this->settings->booking_status; // 'open', 'closed', 'scheduled'

        // Always Open
        if ($status === 'open') {
            return ['is_open' => true];
        }

        //Force Closed
        if ($status === 'closed') {
            return [
                'is_open' => false,
                'message' => $this->settings->closing_message ?? 'We are temporarily closed.'
            ];
        }

        // Scheduled Logic
        if ($status === 'scheduled') {
            $now = Carbon::now();
            $type = $this->settings->schedule_type; // 'daily', 'weekly', 'specific_date'

            // Daily Recurring (Time based)
            if ($type === 'daily') {
                $start = Carbon::parse($this->settings->daily_start_time);
                $end = Carbon::parse($this->settings->daily_end_time);

                // Overnight logic (e.g. 10 PM to 6 AM)
                if ($end->lessThan($start)) {
                    if ($now->gte($start) || $now->lte($end)) {
                        return ['is_open' => false, 'message' => $this->settings->closing_message];
                    }
                } else {
                    // Same day logic (e.g. 2 PM to 5 PM)
                    if ($now->between($start, $end)) {
                        return ['is_open' => false, 'message' => $this->settings->closing_message];
                    }
                }
            }

            //Weekly Recurring (Day based)
            if ($type === 'weekly') {
                $todayName = $now->format('l'); // 'Sunday', 'Monday' etc.
                $offDays = $this->settings->weekly_off_days ?? [];

                if (in_array($todayName, $offDays)) {
                    return ['is_open' => false, 'message' => $this->settings->closing_message ?? "Closed on {$todayName}s."];
                }
            }

            //Specific Date Range (Holiday/Maintenance)
            if ($type === 'specific_date') {
                $start = Carbon::parse($this->settings->closed_start_date);
                $end = Carbon::parse($this->settings->closed_end_date);

                if ($now->between($start, $end)) {
                    return ['is_open' => false, 'message' => $this->settings->closing_message];
                }
            }
        }

        return ['is_open' => true];
    }

    /**
     * Find Luggage Rule based on Passenger Count
     */
    public function getRuleForPassengerCount(int $count): ?array
    {
        $rules = $this->settings->luggage_rules ?? [];

        foreach ($rules as $rule) {
            // Check if passenger count matches exactly
            if ((int) $rule['passenger_count'] === $count) {
                return $rule;
            }
        }
        return null;
    }

    /**
     * Validate if the luggage amount is allowed for passenger count
     */
    public function validateLuggageLimit(int $passengers, int $luggage): array
    {
        $rule = $this->getRuleForPassengerCount($passengers);

        if (!$rule) {
            return ['allowed' => true, 'message' => 'No strict limits defined.'];
        }

        $maxAllowed = (int) ($rule['max_luggage'] ?? 100);

        if ($luggage > $maxAllowed) {
            return [
                'allowed' => false,
                'message' => "For {$passengers} passengers, max luggage allowed is {$maxAllowed}.",
                'max_allowed' => $maxAllowed
            ];
        }

        return ['allowed' => true];
    }

    /**
     * Calculate Extra Luggage Cost (if over free limit)
     */
    public function calculateExtraLuggageCost(int $passengers, int $luggage): float
    {
        $rule = $this->getRuleForPassengerCount($passengers);

        if (!$rule || $luggage <= 0) {
            return 0.0;
        }

        $freeLimit = (int) ($rule['free_luggage'] ?? 0);
        $perBagFee = $this->settings->luggage_fee; // From General Settings

        if ($luggage > $freeLimit) {
            $extraBags = $luggage - $freeLimit;
            return (float) ($extraBags * $perBagFee);
        }

        return 0.0;
    }

    /**
     * Calculate Surcharges (Night Charge, Holiday Charge, etc.)
     * Returns total amount and a breakdown list for invoice
     */
    public function calculateSurcharges(float $baseFare, string $date, string $time): array
    {
        $totalSurcharge = 0;
        $breakdown = [];

        // Fetch all active surcharges from DB
        $surcharges = Surcharge::where('is_active', true)->get();

        $bookingTime = Carbon::parse($time);
        $bookingDate = Carbon::parse($date);

        foreach ($surcharges as $charge) {
            $isApplicable = false;

            // Date Based (Holiday)
            if ($charge->type === 'date') {
                if ($bookingDate->between($charge->start_date, $charge->end_date)) {
                    $isApplicable = true;
                }
            }
            // Time Based (Night/Rush Hour)
            elseif ($charge->type === 'time') {
                $start = Carbon::parse($charge->start_time);
                $end = Carbon::parse($charge->end_time);

                // Overnight logic handling
                if ($end->lessThan($start)) {
                    if ($bookingTime->gte($start) || $bookingTime->lte($end)) {
                        $isApplicable = true;
                    }
                } else {
                    if ($bookingTime->between($start, $end)) {
                        $isApplicable = true;
                    }
                }
            }

            if ($isApplicable) {
                $amount = 0;
                if ($charge->is_percentage) {
                    $amount = ($baseFare * $charge->price) / 100;
                } else {
                    $amount = $charge->price;
                }

                $totalSurcharge += $amount;
                $breakdown[] = [
                    'name' => $charge->name,
                    'amount' => round($amount, 2)
                ];
            }
        }

        return [
            'total' => round($totalSurcharge, 2),
            'breakdown' => $breakdown
        ];
    }

    /**
     * Calculate Zone Extra Charge based on Zip Code
     */
    public function calculateZoneCharge(string $zipCode): array
    {
        $charges = ExtraCharge::where('is_active', true)->get();

        foreach ($charges as $charge) {
            if (in_array($zipCode, $charge->zip_codes ?? [])) {
                return [
                    'found' => true,
                    'name' => $charge->name,
                    'price' => (float) $charge->price,
                    'toll_fee' => (float) $charge->toll_fee
                ];
            }
        }

        return ['found' => false, 'price' => 0, 'toll_fee' => 0];
    }
}
