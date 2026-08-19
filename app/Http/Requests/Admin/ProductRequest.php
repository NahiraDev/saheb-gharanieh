<?php

namespace App\Http\Requests\Admin;

use App\Support\Glyph;
use App\Support\Persian;
use Illuminate\Validation\Rule;

/** Add and edit a menu item. */
class ProductRequest extends AdminRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:120'],
            'latin_name' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:400'],
            'price' => ['nullable', 'integer', 'min:0', 'max:999999999'],
            'glyph' => ['nullable', Rule::in(Glyph::keys())],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
            'is_available' => ['boolean'],
            'is_featured' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:6144'],
            'remove_image' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeBooleans(['is_active', 'is_available', 'is_featured', 'remove_image']);

        $this->merge([
            'price' => Persian::amount($this->input('price')),
            'sort_order' => Persian::amount($this->input('sort_order')),
            'glyph' => $this->filled('glyph') ? $this->input('glyph') : null,
        ]);
    }

    /**
     * The saved columns — the image is handled separately by the controller.
     *
     * @return array<string, mixed>
     */
    public function attributesToSave(): array
    {
        return [
            ...$this->safe()->only([
                'category_id', 'name', 'latin_name', 'description',
                'price', 'glyph', 'is_active', 'is_available', 'is_featured',
            ]),
            'sort_order' => $this->integer('sort_order'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'دسته',
            'name' => 'نام',
            'latin_name' => 'نام لاتین',
            'description' => 'توضیح کوتاه',
            'price' => 'قیمت',
            'glyph' => 'نقش',
            'sort_order' => 'ترتیب',
            'image' => 'تصویر',
        ];
    }
}
