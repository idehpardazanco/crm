<?php
namespace Database\Seeders;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'sms_sender', 'value' => '9982008568', 'group' => 'sms'],
            ['key' => 'sms_username', 'value' => 'zarinpayamak', 'group' => 'sms'],
            ['key' => 'sms_password', 'value' => 'esmaeil321', 'group' => 'sms'],
            ['key' => 'sms_api_url', 'value' => 'http://payammatni.com/webservice/url/send.php', 'group' => 'sms'],

            ['key' => 'company_name', 'value' => 'شرکت', 'group' => 'general'],
            ['key' => 'demo_link', 'value' => 'https://example.com/demo', 'group' => 'general'],
            ['key' => 'order_link', 'value' => 'https://example.com/order', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            Setting::setValue($setting['key'], $setting['value'], $setting['group']);
        }
    }
}
