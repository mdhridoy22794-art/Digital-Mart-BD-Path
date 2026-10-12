<?php

namespace Tests\Feature;

use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DatabaseSeederWhatsAppTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that an existing admin-configured WhatsApp number is NOT overwritten by the seeder.
     */
    public function test_existing_custom_whatsapp_number_is_preserved_during_seeding(): void
    {
        // Simulate admin setting a custom WhatsApp number
        Setting::updateOrCreate(
            ['key' => 'whatsapp_number'],
            ['value' => '+880 1711-223344']
        );

        $seeder = new DatabaseSeeder();
        $seeder->run();

        $freshValue = Setting::get('whatsapp_number');
        $this->assertEquals('+880 1711-223344', $freshValue, 'Custom WhatsApp number set by admin must remain unchanged.');
    }

    /**
     * Test that a missing WhatsApp setting receives the default customer service number.
     */
    public function test_missing_whatsapp_number_receives_default_during_seeding(): void
    {
        // Delete whatsapp_number to simulate a clean/missing setting
        Setting::where('key', 'whatsapp_number')->delete();

        $seeder = new DatabaseSeeder();
        $seeder->run();

        $freshValue = Setting::get('whatsapp_number');
        $this->assertEquals('+880 1934-779775', $freshValue, 'Missing WhatsApp number must be seeded with default value.');
    }
}
