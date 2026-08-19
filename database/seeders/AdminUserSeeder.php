<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

/**
 * The default manager account: admin / admin123.
 *
 * firstOrCreate on purpose — re-seeding a live café must never reset a password
 * the owner has already changed. To change it: the «حساب مدیر» page in the panel,
 * or `php artisan admin:password admin`.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = AdminUser::firstOrCreate(
            ['username' => 'admin'],
            ['name' => 'مدیر کافه', 'password' => 'admin123'],
        );

        if ($admin->wasRecentlyCreated) {
            $this->command?->info('Admin account created — /wp-admin  ·  admin / admin123');
        }
    }
}
