<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * «حساب مدیر» — how the owner changes the default admin/admin123 login.
 *
 * The password is optional: this same form also renames the account, and asking
 * for a new password just to fix a typo in a name would be a nuisance.
 */
class AccountRequest extends AdminRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:60'],
            'username' => [
                'required', 'string', 'min:3', 'max:60', 'alpha_dash',
                Rule::unique('admin_users', 'username')->ignore($this->user('admin')),
            ],
            'current_password' => ['required_with:password', 'nullable', 'current_password:admin'],
            'password' => ['nullable', 'confirmed', Password::min(6)],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['username' => trim(mb_strtolower((string) $this->input('username')))]);
    }

    /**
     * @return array<string, mixed>
     */
    public function attributesToSave(): array
    {
        $values = $this->safe()->only(['name', 'username']);

        // The model's `hashed` cast takes care of hashing.
        if ($this->filled('password')) {
            $values['password'] = $this->string('password')->toString();
        }

        return $values;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'نام',
            'username' => 'نام کاربری',
            'current_password' => 'رمز عبور فعلی',
            'password' => 'رمز عبور جدید',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            ...parent::messages(),
            'current_password.required_with' => 'برای تغییر رمز، رمز فعلی را وارد کنید.',
            'password.confirmed' => 'تکرار رمز عبور جدید با خودش یکسان نیست.',
            'username.alpha_dash' => 'نام کاربری فقط می‌تواند حرف لاتین، عدد، خط تیره و زیرخط داشته باشد.',
        ];
    }
}
