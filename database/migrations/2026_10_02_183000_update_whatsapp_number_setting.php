<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::updateOrCreate(
            ['key' => 'whatsapp_number'],
            ['value' => '+880 1934-779775']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep active number
    }
};
