<?php

namespace App\Models;

use App\Support\Glyph;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'latin_name',
        'description',
        'price',
        'image_path',
        'glyph',
        'sort_order',
        'is_active',
        'is_featured',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
        ];
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @param  Builder<$this>  $query */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @param  Builder<$this>  $query */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function hasImage(): bool
    {
        return filled($this->image_path);
    }

    /**
     * Public URL for the product photo.
     *
     * Stored values are normally relative paths such as products/foo.png, but
     * older/imported records may already contain /storage/ or a full URL.
     * Normalize those forms so the rendered menu always points at the same
     * public storage endpoint.
     */
    public function imageUrl(): ?string
    {
        if (! $this->hasImage()) {
            return null;
        }

        $path = trim((string) $this->image_path);

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '//')
        ) {
            return $path;
        }

        $path = preg_replace('#^(?:/)?storage/#', '', $path) ?? $path;
        $path = ltrim($path, '/');

        return Storage::disk('public')->url($path);
    }

    public function hasPrice(): bool
    {
        return ! is_null($this->price);
    }

    /**
     * The hand-drawn mark shown beside the item: its own when it has one, else
     * the one standing in for its section.
     */
    public function glyphKey(): string
    {
        return Glyph::resolve($this->glyph)
            ?? ($this->category ? Glyph::forCategory($this->category) : 'cup');
    }
}
