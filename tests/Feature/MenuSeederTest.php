<?php

namespace Tests\Feature;

use App\Enums\CategoryKind;
use App\Models\Category;
use App\Models\CategoryFeature;
use App\Models\Product;
use App\Models\Setting;
use Database\Seeders\CategoryFeatureSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Guards the menu transcribed from the printed reference: the counts and the
 * section layout are the contract an admin panel will later edit.
 */
class MenuSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([CategorySeeder::class, ProductSeeder::class, CategoryFeatureSeeder::class, SettingSeeder::class]);
    }

    public function test_it_seeds_the_four_menu_sections(): void
    {
        $this->assertSame(
            ['hot-drinks', 'cold-drinks', 'hookah-normal', 'hookah-deluxe'],
            Category::ordered()->pluck('slug')->all()
        );
    }

    public function test_it_seeds_every_item_from_the_printed_menu(): void
    {
        $counts = Category::withCount('products')->ordered()->pluck('products_count', 'slug')->all();

        $this->assertSame([
            'hot-drinks' => 15,
            'cold-drinks' => 18,
            'hookah-normal' => 16,
            'hookah-deluxe' => 16,
        ], $counts);

        $this->assertSame(65, Product::count());
    }

    public function test_prices_are_left_blank_for_staff_to_fill_in(): void
    {
        $this->assertSame(0, Product::whereNotNull('price')->count());
    }

    public function test_the_three_landing_cards_are_promoted_in_order(): void
    {
        $cards = Category::active()->onLanding()->get();

        $this->assertCount(3, $cards);
        $this->assertSame(
            ['نوشیدنی‌های گرم', 'نوشیدنی‌های سرد', 'قلیان'],
            $cards->map->cardTitle()->all()
        );
    }

    public function test_hookah_sections_use_the_list_layout(): void
    {
        $hookah = Category::where('kind', CategoryKind::Hookah)->get();

        $this->assertCount(2, $hookah);
        $this->assertTrue($hookah->every(fn (Category $category) => ! $category->usesGrid()));
    }

    public function test_the_deluxe_service_lists_its_extras(): void
    {
        $deluxe = Category::where('slug', 'hookah-deluxe')->firstOrFail();

        $this->assertSame(8, $deluxe->features()->count());
        $this->assertSame(0, CategoryFeature::where('category_id', '!=', $deluxe->id)->count());
    }

    public function test_site_copy_lives_in_editable_settings(): void
    {
        $this->assertNotEmpty(Setting::get('intro'));
        $this->assertSame('کافه صاحبقرانیه', Setting::get('cafe_name'));
    }

    public function test_seeders_are_idempotent(): void
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, CategoryFeatureSeeder::class, SettingSeeder::class]);

        $this->assertSame(4, Category::count());
        $this->assertSame(65, Product::count());
        $this->assertSame(8, CategoryFeature::count());
    }
}
