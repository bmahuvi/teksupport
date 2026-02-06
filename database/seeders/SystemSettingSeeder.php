<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'send_email_for_ticket_replied',
                'value' => true,
                'type' => 'boolean',
                'description' => 'Send email when someone replies to a ticket',
            ]
        ];
        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate($setting);
        }
    }
}
