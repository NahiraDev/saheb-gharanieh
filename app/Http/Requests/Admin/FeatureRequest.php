<?php

namespace App\Http\Requests\Admin;

use App\Support\Glyph;
use App\Support\Persian;
use Illuminate\Validation\Rule;

/** The little "همراه سرویس" pills under a section heading. */
class FeatureRequest extends AdminRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'glyph' => ['nullable', Rule::in(Glyph::keys())],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeBooleans(['is_active']);

        $this->merge([
            'sort_order' => Persian::amount($this->input('sort_order')),
            'glyph' => $this->filled('glyph') ? $this->input('glyph') : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function attributesToSave(): array
    {
        return [
            ...$this->safe()->only(['name', 'glyph', 'is_active']),
            'sort_order' => $this->integer('sort_order'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'نام',
            'glyph' => 'نقش',
            'sort_order' => 'ترتیب',
        ];
    }
}
