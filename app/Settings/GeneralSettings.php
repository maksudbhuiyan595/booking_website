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
    public float $booster_seat_fee;
    public float $stopover_fee;
    public float $luggage_fee;

    public array $luggage_rules;

    public static function group(): string
    {
        return 'general';
    }
}
