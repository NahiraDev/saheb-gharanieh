<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Contracts\View\View;

class MenuController extends Controller
{
    /**
     * The whole menu on a single scrollable page; $section is the anchor the
     * page should jump to on load (set when arriving from a landing card).
     */
    public function __invoke(?string $section = null): View
    {
        $categories = Category::query()
            ->active()
            ->ordered()
            ->with([
                'activeProducts',
                'features' => fn ($query) => $query->where('is_active', true),
            ])
            ->get();

        // Ignore unknown anchors rather than 404-ing — the menu itself is still valid.
        if ($section && ! $categories->contains('slug', $section)) {
            $section = null;
        }

        return view('menu', [
            'categories' => $categories,
            'activeSection' => $section,
            'settings' => Setting::map(),
        ]);
    }
}
