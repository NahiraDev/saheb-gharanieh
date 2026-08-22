<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Category;
use App\Models\Product;
use App\Support\UploadLimit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The image field used to advertise 6 MB in its hint while PHP's own
 * upload_max_filesize capped uploads far below that — and the field, being the one
 * control not wrapped in x-admin.field, had nowhere to show the refusal. A photo
 * off a phone went in and the form came back looking untouched.
 *
 * These tests hold the two halves together: the size the hint promises is the size
 * the validator enforces, and a refusal is legible wherever it comes from.
 */
class ProductImageUploadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Everything a new item needs apart from the photo.
     *
     * @return array<string, mixed>
     */
    private function fields(Category $category): array
    {
        return [
            'category_id' => $category->id,
            'name' => 'لاتهٔ آزمایشی',
            'sort_order' => 0,
        ];
    }

    public function test_the_hint_promises_the_size_the_validator_enforces(): void
    {
        // The regression this whole file exists for: two numbers, one of them wrong.
        $category = Category::factory()->create();

        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->get(route('admin.products.create', ['category' => $category->id]))
            ->assertOk()
            ->assertSee('تا '.UploadLimit::megabytesLabel().' مگابایت', false)
            ->assertSee('data-image-max-bytes="'.UploadLimit::bytes().'"', false);
    }

    public function test_the_advertised_limit_never_exceeds_what_php_will_accept(): void
    {
        // Clamped, not hardcoded — a hint PHP cannot honour is the original bug.
        $this->assertLessThanOrEqual(UploadLimit::WANTED_KILOBYTES, UploadLimit::kilobytes());
        $this->assertGreaterThan(0, UploadLimit::kilobytes());
        $this->assertSame(UploadLimit::kilobytes() * 1024, UploadLimit::bytes());
    }

    public function test_the_limit_reads_as_a_persian_number_of_megabytes(): void
    {
        // Written into the hint, the validation message and the JS, so it has to read
        // as a number rather than as a calculation: Persian digits, and a decimal
        // place only where the cap genuinely has one.
        $label = UploadLimit::megabytesLabel();

        $this->assertMatchesRegularExpression('/^[۰-۹]+(٫[۰-۹])?$/u', $label);

        if (UploadLimit::kilobytes() % 1024 === 0) {
            $this->assertStringNotContainsString('٫', $label, 'A whole number of megabytes should not read as "۶٫۰".');
        }
    }

    public function test_a_photo_up_to_the_advertised_size_is_stored(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();

        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->post(route('admin.products.store'), [
                ...$this->fields($category),
                'image' => UploadedFile::fake()->image('photo.jpg')->size(UploadLimit::kilobytes()),
            ])
            ->assertSessionHasNoErrors();

        $product = Product::query()->sole();

        $this->assertNotNull($product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_a_photo_over_the_limit_is_refused_in_megabytes(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();

        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), [
                ...$this->fields($category),
                'image' => UploadedFile::fake()->image('photo.jpg')->size(UploadLimit::kilobytes() + 1),
            ])
            ->assertSessionHasErrors([
                // Kilobytes is not how anyone thinks about a photo.
                'image' => 'حجم تصویر نمی‌تواند بیشتر از '.UploadLimit::megabytesLabel().' مگابایت باشد.',
            ]);

        $this->assertSame(0, Product::query()->count());
    }

    public function test_a_photo_php_threw_out_is_explained_in_persian(): void
    {
        // What upload_max_filesize actually does: the file arrives with an error code
        // and no usable content, so Laravel reports `uploaded` and stops — never
        // reaching `max`. Untranslated that message reads "The تصویر failed to
        // upload.", which is both English and silent about the size.
        $category = Category::factory()->create();

        $broken = new UploadedFile(
            UploadedFile::fake()->image('photo.jpg')->getPathname(),
            'photo.jpg',
            'image/jpeg',
            UPLOAD_ERR_INI_SIZE,
            test: true,
        );

        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->from(route('admin.products.create'))
            ->post(route('admin.products.store'), [
                ...$this->fields($category),
                'image' => $broken,
            ])
            ->assertSessionHasErrors('image');

        $message = session('errors')->first('image');

        $this->assertStringNotContainsString('failed to upload', $message);
        $this->assertStringContainsString('مگابایت', $message);
    }

    public function test_the_refusal_is_rendered_on_the_field_itself(): void
    {
        Storage::fake('public');

        $category = Category::factory()->create();

        // Not just flashed to the session: the message has to survive the redirect
        // onto the markup of the field that caused it.
        $this->actingAs(AdminUser::factory()->create(), 'admin')
            ->from(route('admin.products.create'))
            ->followingRedirects()
            ->post(route('admin.products.store'), [
                ...$this->fields($category),
                'image' => UploadedFile::fake()->image('photo.jpg')->size(UploadLimit::kilobytes() + 1),
            ])
            ->assertOk()
            ->assertSee('admin-image--bad', false)
            ->assertSee('حجم تصویر نمی‌تواند بیشتر از', false);
    }
}
