<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateGeneralSettings extends SettingsMigration
{
    public function up(): void
    {
        // 1. Company Profile
        $this->migrator->add('general.site_name', 'Boston Logan');
        $this->migrator->add('general.site_logo', null);
        $this->migrator->add('general.company_phone', '+1-555-0199');
        $this->migrator->add('general.company_email', 'softeng.kaziomar@gmail.com');
        $this->migrator->add('general.company_address', '123 Limo Street, NY');

        // 2. Booking Rules
        $this->migrator->add('general.gratuity_percent', 20.0);
        $this->migrator->add('general.tax_percent', 5.0);
        $this->migrator->add('general.credit_card_fee', 3.5);

        // 3. Fixed Charges
        $this->migrator->add('general.child_seat_fee', 10.0);
        $this->migrator->add('general.booster_seat_fee', 10.0);
        $this->migrator->add('general.stopover_fee', 25.0);
        $this->migrator->add('general.luggage_fee', 5.0);
    }

    public function down(): void
    {
        $this->migrator->delete('general.site_name');
        $this->migrator->delete('general.site_logo');
        $this->migrator->delete('general.company_phone');
        $this->migrator->delete('general.company_email');
        $this->migrator->delete('general.company_address');

        $this->migrator->delete('general.gratuity_percent');
        $this->migrator->delete('general.tax_percent');
        $this->migrator->delete('general.credit_card_fee');

        $this->migrator->delete('general.child_seat_fee');
        $this->migrator->delete('general.booster_seat_fee');
        $this->migrator->delete('general.stopover_fee');
        $this->migrator->delete('general.luggage_fee');
    }
}
