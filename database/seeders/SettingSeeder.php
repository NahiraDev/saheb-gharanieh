<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'cafe_name', 'value' => 'کافه صاحبقرانیه', 'group' => 'general', 'label' => 'نام کافه'],
            ['key' => 'cafe_name_latin', 'value' => 'SAHEB GHARANIYEH CAFE', 'group' => 'general', 'label' => 'نام لاتین'],
            ['key' => 'tagline', 'value' => 'قهوه، قلیان و شب‌های دلنشین تهران', 'group' => 'general', 'label' => 'شعار کافه'],
            [
                'key' => 'intro',
                'type' => 'text',
                'group' => 'general',
                'label' => 'معرفی کافه',
                'value' => 'در دل خیابانی آرام، کافه صاحبقرانیه میعادگاه دوستی‌های قدیمی است؛ '
                    .'جایی که عطر قهوه‌ی تازه دم با نقش‌های کاشی و نور فانوس‌ها در هم می‌آمیزد. '
                    .'از دمنوش‌های سنتی و شربت‌های خانگی تا قلیان‌های خوش‌طعم، هر سفارش با وسواس و '
                    .'مهمان‌نوازی ایرانی آماده می‌شود.',
            ],
            ['key' => 'working_hours', 'value' => 'هر روز از ۱۰ صبح تا ۱ بامداد', 'group' => 'contact', 'label' => 'ساعات کاری'],
            ['key' => 'address', 'value' => 'تهران، نیاوران، خیابان صاحبقرانیه', 'group' => 'contact', 'label' => 'نشانی'],
            ['key' => 'phone', 'value' => '۰۲۱-۱۲۳۴۵۶۷۸', 'group' => 'contact', 'label' => 'شماره تماس'],
            ['key' => 'instagram', 'value' => 'sahebgharaniyeh.cafe', 'group' => 'social', 'label' => 'اینستاگرام'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting + ['type' => 'string']
            );
        }
    }
}
