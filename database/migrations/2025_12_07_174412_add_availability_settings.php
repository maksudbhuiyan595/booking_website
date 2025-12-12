<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->migrator->add('general.booking_status', 'open');
        $this->migrator->add('general.closing_message', 'We are currently closed.');
        $this->migrator->add('general.schedule_type', 'daily');

        $this->migrator->add('general.daily_start_time', null);
        $this->migrator->add('general.daily_end_time', null);

        $this->migrator->add('general.weekly_off_days', []);

        $this->migrator->add('general.closed_start_date', null);
        $this->migrator->add('general.closed_end_date', null);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->migrator->delete('general.booking_status');
        $this->migrator->delete('general.closing_message');
        $this->migrator->delete('general.schedule_type');

        $this->migrator->delete('general.daily_start_time');
        $this->migrator->delete('general.daily_end_time');

        $this->migrator->delete('general.weekly_off_days');

        $this->migrator->delete('general.closed_start_date');
        $this->migrator->delete('general.closed_end_date');
    }
};
