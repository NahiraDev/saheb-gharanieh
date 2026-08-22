<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Category;
use App\Models\CategoryFeature;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * The panel's front door.
 *
 * `AdminRequest::authorize()` returns true for every form request in the panel,
 * on the stated grounds that "authorisation is the route's job". That job is one
 * line — `Route::middleware('auth:admin')` in routes/web.php — wrapped around
 * everything except the login form. Nothing but this file checks that a route
 * added later ends up inside it.
 */
class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    private const LOGIN_URL = '/wp-admin/login';

    /** The one message both wrong-username and wrong-password get. */
    private const REJECTED = 'نام کاربری یا رمز عبور درست نیست.';

    public function test_every_panel_route_turns_a_guest_away(): void
    {
        // Real records, because route-model binding runs before the auth check:
        // an unknown id would 404 and quietly hide whether the route is guarded.
        $category = Category::factory()->create();
        $bound = [
            'category' => $category->id,
            'product' => Product::factory()->for($category)->create()->id,
            'feature' => CategoryFeature::factory()->for($category)->create()->id,
        ];

        $guarded = collect(Route::getRoutes())
            ->filter(fn ($route) => str_starts_with((string) $route->getName(), 'admin.'))
            ->reject(fn ($route) => in_array($route->getName(), ['admin.login', 'admin.login.store'], true));

        // A refactor that renames the prefix must not turn this into a no-op.
        $this->assertGreaterThanOrEqual(25, $guarded->count());

        foreach ($guarded as $route) {
            $url = route($route->getName(), collect($route->parameterNames())
                ->mapWithKeys(fn ($name) => [$name => $bound[$name]])
                ->all());

            $method = collect($route->methods())->first(fn ($verb) => $verb !== 'HEAD');

            $this->call($method, $url)->assertRedirect(
                self::LOGIN_URL,
                "{$method} {$url} ({$route->getName()}) is reachable without signing in."
            );
        }
    }

    public function test_a_site_visitor_is_not_a_cafe_admin(): void
    {
        // Two separate guards, and only `admin` opens the panel. Signing in on
        // the default `web` guard must count for nothing here.
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertRedirect(self::LOGIN_URL);
    }

    public function test_an_unknown_username_is_refused_without_saying_so(): void
    {
        AdminUser::factory()->create(['username' => 'owner']);

        $this->post(route('admin.login.store'), [
            'username' => 'nobody',
            'password' => 'secret123',
        ])
            ->assertSessionHasErrors(['username' => self::REJECTED])
            ->assertSessionDoesntHaveErrors('password');

        $this->assertGuest('admin');
    }

    public function test_a_wrong_password_is_refused_with_the_very_same_message(): void
    {
        AdminUser::factory()->create(['username' => 'owner']);

        // Identical to the reply above: telling the two apart would hand a
        // stranger a list of which usernames exist.
        $this->post(route('admin.login.store'), [
            'username' => 'owner',
            'password' => 'not-the-password',
        ])
            ->assertSessionHasErrors(['username' => self::REJECTED])
            ->assertSessionDoesntHaveErrors('password');

        $this->assertGuest('admin');
    }

    public function test_signing_in_lands_on_the_dashboard_and_replaces_the_session(): void
    {
        $admin = AdminUser::factory()->create(['username' => 'owner']);

        $this->get(route('admin.login'))->assertOk();
        $sessionBefore = session()->getId();

        $this->post(route('admin.login.store'), [
            'username' => 'owner',
            'password' => 'secret123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admin');

        // Session fixation: whatever id the visitor arrived holding is not the
        // id they leave signed in with.
        $this->assertNotSame($sessionBefore, session()->getId());

        $this->assertNotNull($admin->fresh()->last_login_at);
    }

    public function test_logging_out_closes_the_panel_again(): void
    {
        $admin = AdminUser::factory()->create();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest('admin');

        $this->get(route('admin.dashboard'))->assertRedirect(self::LOGIN_URL);
    }

    public function test_the_login_form_stops_taking_guesses(): void
    {
        AdminUser::factory()->create(['username' => 'owner']);

        $guess = fn () => $this->post(route('admin.login.store'), [
            'username' => 'owner',
            'password' => 'wrong',
        ]);

        // throttle:10,1 — ten a minute, from one address.
        for ($attempt = 1; $attempt <= 10; $attempt++) {
            $guess()->assertRedirect();
        }

        $guess()->assertStatus(429);
    }

    public function test_a_signed_in_admin_is_sent_past_the_login_form(): void
    {
        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
