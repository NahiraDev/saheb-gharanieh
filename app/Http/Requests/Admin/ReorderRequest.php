<?php

namespace App\Http\Requests\Admin;

/**
 * The new order of the sections list, posted as an array of category ids in the
 * order they now appear (drag-and-drop on desktop, ↑/↓ buttons on a phone).
 */
class ReorderRequest extends AdminRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1', 'max:200'],
            'ids.*' => ['integer', 'exists:categories,id'],
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
        return ['ids' => 'ترتیب', 'ids.*' => 'دسته'];
    }
}
