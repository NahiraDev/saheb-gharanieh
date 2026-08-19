<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => $this->stats(),
            'categories' => $this->categoriesWithCounts(),
            'recent' => Product::query()
                ->with('category:id,name')
                ->latest('id')
                ->limit(6)
                ->get(),
            'needsAttention' => Product::query()
                ->with('category:id,name')
                ->where(fn ($query) => $query->whereNull('price')->orWhereNull('image_path'))
                ->orderBy('category_id')
                ->orderBy('sort_order')
                ->limit(8)
                ->get(),
        ]);
    }

    /**
     * @return array<string, int>
     */
    private function stats(): array
    {
        // One row of aggregates rather than five separate COUNT queries.
        $products = Product::query()
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when is_active = 1 then 1 else 0 end) as active')
            ->selectRaw('sum(case when price is null then 1 else 0 end) as no_price')
            ->selectRaw('sum(case when image_path is null then 1 else 0 end) as no_image')
            ->selectRaw('sum(case when is_available = 0 then 1 else 0 end) as sold_out')
            ->first();

        $categories = Category::query()
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when is_active = 1 then 1 else 0 end) as active')
            ->first();

        return [
            'products' => (int) $products->total,
            'products_active' => (int) $products->active,
            'products_hidden' => (int) $products->total - (int) $products->active,
            'products_no_price' => (int) $products->no_price,
            'products_no_image' => (int) $products->no_image,
            'products_sold_out' => (int) $products->sold_out,
            'categories' => (int) $categories->total,
            'categories_active' => (int) $categories->active,
            'categories_hidden' => (int) $categories->total - (int) $categories->active,
        ];
    }

    /**
     * Sections with their item counts, in menu order.
     *
     * @return Collection<int, Category>
     */
    private function categoriesWithCounts(): Collection
    {
        return Category::query()
            ->ordered()
            ->withCount([
                'products',
                'products as active_products_count' => fn ($query) => $query->where('is_active', true),
            ])
            ->get();
    }
}
