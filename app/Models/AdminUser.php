<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * A café manager who signs in at /wp-admin with a username — no email, no
 * password reset mail: the owner changes the password from the panel itself
 * (or with `php artisan admin:password`).
 */
class AdminUser extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\AdminUserFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }
}
