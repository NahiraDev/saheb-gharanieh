<?php

namespace App\Http\Requests\Admin;

use App\Support\Persian;

/**
 * The inline price box on the items list — the one edit the owner makes most,
 * so it gets its own tiny form instead of a trip through the full editor.
 */
class QuickPriceRequest extends AdminRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'price' => ['nullable', 'integer', 'min:0', 'max:999999999'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['price' => Persian::amount($this->input('price'))]);
    }

    /** Null clears the price, which renders the "قیمت" placeholder on the menu. */
    public function price(): ?int
    {
        $price = $this->validated('price');

        return is_null($price) ? null : (int) $price;
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return ['price' => 'قیمت'];
    }
}
