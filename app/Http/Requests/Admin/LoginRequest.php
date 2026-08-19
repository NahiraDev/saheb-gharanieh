<?php

namespace App\Http\Requests\Admin;

class LoginRequest extends AdminRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:60'],
            'password' => ['required', 'string', 'max:200'],
            'remember' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeBooleans(['remember']);

        // Phone keyboards love to capitalise the first letter of a username.
        $this->merge(['username' => trim(mb_strtolower((string) $this->input('username')))]);
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'username' => 'نام کاربری',
            'password' => 'رمز عبور',
        ];
    }
}
