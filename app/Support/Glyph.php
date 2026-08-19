<?php

namespace App\Support;

use App\Models\Category;

/**
 * The café's hand-drawn icon set.
 *
 * Nothing on the site uses emoji: an emoji is drawn by the phone, so the same
 * menu would come out Apple-blue on one table and Google-flat on the next. These
 * are gold line-art glyphs on a 64×64 grid, in the same hand as the section
 * icons on the landing cards.
 *
 * A key is stored in `categories.glyph`, `products.glyph` and
 * `category_features.glyph`, and drawn by <x-icon.glyph :name="…" />.
 * The admin picker is built straight from GROUPS, so adding a glyph means
 * adding one case to the component and one line here.
 */
class Glyph
{
    /**
     * Every glyph with its Persian label, grouped the way the admin picker
     * shows them.
     *
     * @var array<string, array<string, string>>
     */
    public const GROUPS = [
        'نوشیدنی و سرویس' => [
            'cup' => 'فنجان قهوه',
            'tea-glass' => 'استکان چای',
            'teapot' => 'قوری',
            'glass' => 'لیوان سرد',
            'milk' => 'شیر',
            'coffee-bean' => 'دانه قهوه',
            'hookah' => 'قلیان',
            'crown' => 'تاج',
        ],

        'میوه' => [
            'apple' => 'سیب',
            'apple-ice' => 'سیب یخ',
            'cherry' => 'آلبالو',
            'berry' => 'بلوبری',
            'strawberry' => 'توت فرنگی',
            'lemon' => 'لیمو',
            'orange' => 'پرتقال',
            'grape' => 'انگور',
            'melon' => 'طالبی',
            'watermelon' => 'هندوانه',
            'pomegranate' => 'انار',
        ],

        'شیرینی و ادویه' => [
            'ice-cream' => 'بستنی',
            'lollipop' => 'آبنبات',
            'cream' => 'خامه',
            'pastry' => 'باقلوا',
            'chocolate' => 'شکلات',
            'cinnamon' => 'دارچین',
            'saffron' => 'زعفران',
            'mint' => 'نعناع',
            'rose' => 'گل محمدی',
            'leaf' => 'برگ',
        ],

        'نقش و لوازم' => [
            'star' => 'ستاره',
            'moon' => 'ماه',
            'heart' => 'قلب',
            'palace' => 'عمارت',
            'flame' => 'شعله',
            'ice' => 'یخ',
            'water' => 'قطره',
            'fruit-plate' => 'میوه فصل',
            'foil' => 'فویل',
            'tongs' => 'انبر',
            'wipe' => 'دستمال مرطوب',
        ],
    ];

    /**
     * Flat key => Persian label.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        return array_merge(...array_values(self::GROUPS));
    }

    /** @return array<int, string> */
    public static function keys(): array
    {
        return array_keys(self::all());
    }

    public static function has(?string $key): bool
    {
        return $key !== null && $key !== '' && array_key_exists($key, self::all());
    }

    /** The key itself when we can draw it, otherwise NULL (draw nothing / fall back). */
    public static function resolve(?string $key): ?string
    {
        return self::has($key) ? $key : null;
    }

    public static function label(?string $key): ?string
    {
        return self::all()[$key] ?? null;
    }

    /**
     * The glyph that stands for a whole section: the one the admin picked, or a
     * guess from the section itself so a new category is never blank.
     */
    public static function forCategory(Category $category): string
    {
        return self::resolve($category->glyph) ?? match (true) {
            $category->isHookah() => 'hookah',
            str_contains($category->slug, 'cold'), str_contains($category->name, 'سرد') => 'glass',
            default => 'cup',
        };
    }
}
