<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The menu used emoji for its little marks, which meant every phone drew the
 * café differently. They are replaced by keys into the hand-drawn set in
 * App\Support\Glyph, so the columns are renamed to match and the emoji already
 * in the database are translated to their glyph.
 */
return new class extends Migration
{
    /** Emoji → glyph key, per table (🍇 is a flavour on an item, a fruit plate on an extra). */
    private const CATEGORY_MAP = [
        '☕' => 'cup',
        '🥤' => 'glass',
        '💧' => 'hookah',
        '👑' => 'crown',
    ];

    private const PRODUCT_MAP = [
        '🍎' => 'apple',
        '🍒' => 'cherry',
        '🍏' => 'apple-ice',
        '🫐' => 'berry',
        '🍬' => 'cinnamon',
        '🍋' => 'lemon',
        '🍦' => 'ice-cream',
        '🍭' => 'lollipop',
        '🌙' => 'moon',
        '❤️' => 'heart',
        '🍊' => 'orange',
        '🍮' => 'cream',
        '🏛️' => 'palace',
        '🍈' => 'melon',
        '🍉' => 'watermelon',
        '🍇' => 'grape',
    ];

    private const FEATURE_MAP = [
        '🫖' => 'teapot',
        '🍇' => 'fruit-plate',
        '🥮' => 'pastry',
        '🧴' => 'wipe',
        '🔥' => 'flame',
        '🧊' => 'ice',
        '📄' => 'foil',
        '🥢' => 'tongs',
    ];

    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('icon', 'glyph');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('emoji', 'glyph');
        });

        Schema::table('category_features', function (Blueprint $table) {
            $table->renameColumn('emoji', 'glyph');
        });

        $this->translate('categories', self::CATEGORY_MAP);
        $this->translate('products', self::PRODUCT_MAP);
        $this->translate('category_features', self::FEATURE_MAP);

        // Anything we cannot name draws the rosette, so blank it rather than
        // leaving a stray emoji in the column.
        foreach (['categories', 'products', 'category_features'] as $table) {
            DB::table($table)
                ->whereNotNull('glyph')
                ->whereNotIn('glyph', \App\Support\Glyph::keys())
                ->update(['glyph' => null]);
        }
    }

    public function down(): void
    {
        $this->translate('categories', array_flip(self::CATEGORY_MAP));
        $this->translate('products', array_flip(self::PRODUCT_MAP));
        $this->translate('category_features', array_flip(self::FEATURE_MAP));

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('glyph', 'icon');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('glyph', 'emoji');
        });

        Schema::table('category_features', function (Blueprint $table) {
            $table->renameColumn('glyph', 'emoji');
        });
    }

    /** @param  array<string, string>  $map */
    private function translate(string $table, array $map): void
    {
        foreach ($map as $from => $to) {
            DB::table($table)->where('glyph', $from)->update(['glyph' => $to]);
        }
    }
};
