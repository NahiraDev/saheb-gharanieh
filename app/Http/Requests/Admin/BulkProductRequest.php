<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

/**
 * Act on several selected items at once. `action` decides which of the extra
 * fields matter, so the rules stay conditional rather than always-required.
 */
class BulkProductRequest extends AdminRequest
{
    public const ACTIONS = ['delete', 'category', 'activate', 'deactivate', 'available', 'unavailable'];

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(self::ACTIONS)],
            'ids' => ['required', 'array', 'min:1', 'max:500'],
            'ids.*' => ['integer', Rule::exists('products', 'id')],
            'category_id' => [
                'required_if:action,category', 'nullable', 'integer',
                Rule::exists('categories', 'id'),
            ],
        ];
    }

    /**
     * @return array<int, int>
     */
    public function ids(): array
    {
        return array_map('intval', $this->safe()->array('ids'));
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'action' => 'عملیات',
            'ids' => 'مورد انتخاب‌شده',
            'ids.*' => 'مورد انتخاب‌شده',
            'category_id' => 'دسته مقصد',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            ...parent::messages(),
            'ids.required' => 'هیچ موردی انتخاب نشده است.',
            'ids.min' => 'هیچ موردی انتخاب نشده است.',
            'category_id.required_if' => 'برای جابه‌جایی، دسته مقصد را انتخاب کنید.',
        ];
    }
}
