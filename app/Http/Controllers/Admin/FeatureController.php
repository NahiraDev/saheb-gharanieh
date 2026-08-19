<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeatureRequest;
use App\Models\Category;
use App\Models\CategoryFeature;
use Illuminate\Http\RedirectResponse;

/**
 * The extras printed under a section heading — «باقلوا»، «یخ»، «فویل».
 *
 * They are edited straight from the sections list, so there is no separate
 * index or form: only store / update / destroy.
 */
class FeatureController extends Controller
{
    public function store(FeatureRequest $request, Category $category): RedirectResponse
    {
        $feature = $category->features()->create([
            ...$request->attributesToSave(),
            'sort_order' => $request->filled('sort_order')
                ? $request->integer('sort_order')
                : (int) $category->features()->max('sort_order') + 1,
        ]);

        return back()->with('status', "«{$feature->name}» به «{$category->name}» اضافه شد.");
    }

    public function update(FeatureRequest $request, CategoryFeature $feature): RedirectResponse
    {
        $feature->update($request->attributesToSave());

        return back()->with('status', "«{$feature->name}» ذخیره شد.");
    }

    public function destroy(CategoryFeature $feature): RedirectResponse
    {
        $feature->delete();

        return back()->with('status', "«{$feature->name}» حذف شد.");
    }
}
