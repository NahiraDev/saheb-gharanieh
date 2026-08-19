<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base for every panel form. The app runs in English (`APP_LOCALE=en`) because
 * only the café's own copy is Persian, so the messages the owner reads are
 * defined here rather than through a half-translated language pack.
 *
 * Authorisation is the route's job: every one of these sits behind `auth:admin`.
 */
abstract class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'پر کردن «:attribute» الزامی است.',
            'required_if' => 'پر کردن «:attribute» الزامی است.',
            'string' => '«:attribute» باید متن باشد.',
            'integer' => '«:attribute» باید عدد باشد.',
            'boolean' => '«:attribute» معتبر نیست.',
            'array' => '«:attribute» معتبر نیست.',
            'in' => '«:attribute» انتخاب‌شده معتبر نیست.',
            'exists' => '«:attribute» انتخاب‌شده وجود ندارد.',
            'unique' => 'این «:attribute» قبلاً ثبت شده است.',
            'confirmed' => 'تکرار «:attribute» با خودش یکسان نیست.',
            'current_password' => 'رمز عبور فعلی درست نیست.',
            'alpha_dash' => '«:attribute» فقط می‌تواند حرف، عدد، خط تیره و زیرخط داشته باشد.',
            'image' => '«:attribute» باید یک تصویر باشد.',
            'mimes' => 'فرمت «:attribute» باید یکی از این‌ها باشد: :values',
            'max.string' => '«:attribute» نمی‌تواند بیشتر از :max نویسه باشد.',
            'max.numeric' => '«:attribute» نمی‌تواند بیشتر از :max باشد.',
            'max.file' => 'حجم «:attribute» نمی‌تواند بیشتر از :max کیلوبایت باشد.',
            'min.string' => '«:attribute» نمی‌تواند کمتر از :min نویسه باشد.',
            'min.numeric' => '«:attribute» نمی‌تواند کمتر از :min باشد.',
        ];
    }

    /**
     * Checkboxes are absent from the request when they are off, which validates
     * as "missing" rather than "false". Normalise the ones a form declares.
     *
     * @param  array<int, string>  $keys
     */
    protected function mergeBooleans(array $keys): void
    {
        $this->merge(array_map(fn (string $key) => $this->boolean($key), array_combine($keys, $keys)));
    }
}
