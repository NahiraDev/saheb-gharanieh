<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Category;
use App\Models\CategoryFeature;
use App\Models\Product;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Every page of the panel, rendered.
 *
 * Vite is deliberately left switched on — no withoutVite(). The layout calls
 * @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/admin.js']),
 * and an entry missing from public/build/manifest.json throws ViteException, so
 * these tests go red on a 500 rather than passing over a panel nobody can open.
 */
class AdminPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_panel_page_renders_for_a_signed_in_admin(): void
    {
        $admin = AdminUser::factory()->create();

        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create();
        CategoryFeature::factory()->for($category)->create();
        $this->seed(SettingSeeder::class);

        $bound = [
            'category' => $category->id,
            'product' => $product->id,
        ];

        $pages = collect(Route::getRoutes())
            ->filter(fn ($route) => in_array('GET', $route->methods(), true))
            ->filter(fn ($route) => str_starts_with((string) $route->getName(), 'admin.'))
            // The login form is the one GET that answers a signed-in admin with
            // a redirect; AdminAuthTest covers it from both sides.
            ->reject(fn ($route) => $route->getName() === 'admin.login');

        $this->assertGreaterThanOrEqual(9, $pages->count());

        foreach ($pages as $route) {
            $url = route($route->getName(), collect($route->parameterNames())
                ->mapWithKeys(fn ($name) => [$name => $bound[$name]])
                ->all());

            $this->actingAs($admin, 'admin')
                ->get($url)
                ->assertOk("{$url} ({$route->getName()}) did not render.");
        }
    }

    public function test_the_login_page_renders_for_a_guest(): void
    {
        // Its own standalone shell with its own @vite call, so it can break on
        // its own — and it is the only page a locked-out owner can reach.
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('admin-login-form', false)
            ->assertSee('name="username"', false);
    }

    public function test_panel_pages_ask_not_to_be_indexed(): void
    {
        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertSee('noindex, nofollow', false);
    }

    public function test_the_login_page_asks_not_to_be_indexed(): void
    {
        // A test of its own, and it has to stay that way: signing in leaves the
        // session holding an admin, and admin.login answers one with a redirect
        // to the dashboard — a bare redirect page with no <head> to look in.
        $this->get(route('admin.login'))->assertSee('noindex, nofollow', false);
    }

    public function test_a_new_password_needs_the_current_one(): void
    {
        $admin = AdminUser::factory()->create(['username' => 'owner']);
        $hashBefore = $admin->password;

        $this->actingAs($admin, 'admin')
            ->put(route('admin.account.update'), [
                'name' => 'مدیر کافه',
                'username' => 'owner',
                'current_password' => 'not-the-password',
                'password' => 'a-brand-new-one',
                'password_confirmation' => 'a-brand-new-one',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertSame($hashBefore, $admin->fresh()->password);
    }

    public function test_the_right_current_password_changes_it(): void
    {
        $admin = AdminUser::factory()->create(['username' => 'owner']);

        $this->actingAs($admin, 'admin')
            ->put(route('admin.account.update'), [
                'name' => 'مدیر کافه',
                'username' => 'owner',
                'current_password' => 'secret123',
                'password' => 'a-brand-new-one',
                'password_confirmation' => 'a-brand-new-one',
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('a-brand-new-one', $admin->fresh()->password));
    }

    public function test_renaming_the_account_leaves_the_password_alone(): void
    {
        // The same form does both jobs, so an empty password must mean "keep it"
        // rather than "set it to nothing".
        $admin = AdminUser::factory()->create(['username' => 'owner']);
        $hashBefore = $admin->password;

        $this->actingAs($admin, 'admin')
            ->put(route('admin.account.update'), [
                'name' => 'صاحب کافه',
                'username' => 'owner',
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame('صاحب کافه', $admin->fresh()->name);
        $this->assertSame($hashBefore, $admin->fresh()->password);
    }
}
