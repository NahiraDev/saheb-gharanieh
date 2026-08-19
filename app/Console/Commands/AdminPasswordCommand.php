<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use Illuminate\Console\Command;

use function Laravel\Prompts\password;

/**
 * A way back in when the owner forgets the panel password:
 *
 *     php artisan admin:password
 *     php artisan admin:password admin --password=secret
 */
class AdminPasswordCommand extends Command
{
    protected $signature = 'admin:password
        {username? : The admin account to change (defaults to the only one)}
        {--password= : Skip the prompt and use this password}';

    protected $description = 'Set the password for a café panel account';

    public function handle(): int
    {
        $admin = $this->resolveAccount();

        if (! $admin) {
            return self::FAILURE;
        }

        $new = $this->option('password') ?: password(
            label: "New password for “{$admin->username}”",
            required: true,
            validate: fn (string $value) => mb_strlen($value) < 6
                ? 'Use at least 6 characters.'
                : null,
        );

        if (mb_strlen($new) < 6) {
            $this->error('The password must be at least 6 characters.');

            return self::FAILURE;
        }

        // The model's `hashed` cast does the hashing.
        $admin->update(['password' => $new]);

        $this->info("Password updated for “{$admin->username}”.");

        return self::SUCCESS;
    }

    private function resolveAccount(): ?AdminUser
    {
        $username = $this->argument('username');

        if ($username) {
            $admin = AdminUser::query()->where('username', $username)->first();

            if (! $admin) {
                $this->error("No panel account named “{$username}”.");
            }

            return $admin;
        }

        $accounts = AdminUser::query()->orderBy('id')->get();

        if ($accounts->isEmpty()) {
            $this->error('There are no panel accounts yet — run `php artisan db:seed`.');

            return null;
        }

        if ($accounts->count() > 1) {
            $this->error('Several panel accounts exist; name the one to change:');
            $this->line('  '.$accounts->pluck('username')->implode(', '));

            return null;
        }

        return $accounts->first();
    }
}
