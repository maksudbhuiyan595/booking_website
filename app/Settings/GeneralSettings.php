<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    // Company Profile
    public string $site_name;
    public ?string $site_logo;
    public ?string $company_phone;
    public ?string $company_email;
    public ?string $company_address;

    // Booking Rules (Pricing)
    public float $gratuity_percent;
    public float $tax_percent;
    public float $credit_card_fee;

    // Fixed Add-on Charges
    public float $child_seat_fee;
    public $regular_Seat_rules;
    public float $booster_seat_fee;
    public float $stopover_fee;
    public float $luggage_fee;

    public array $luggage_rules = [];

    // --- AVAILABILITY SETTINGS ---
    public string $booking_status; // 'open', 'closed', 'scheduled'
    public ?string $closing_message;

    public ?string $schedule_type; // 'daily', 'weekly', 'specific_date'

    // Daily
    public ?string $daily_start_time;
    public ?string $daily_end_time;

    // Weekly
    public array $weekly_off_days = [];

    // Specific Date
    public ?string $closed_start_date;
    public ?string $closed_end_date;


    public static function group(): string
    {
        return 'general';
    }
}
