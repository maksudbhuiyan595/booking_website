<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        $this->migrator->add('general.regular_Seat_rules', []);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->migrator->delete('general.regular_Seat_rules');
    }


};
