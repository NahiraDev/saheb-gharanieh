<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The `glyph` columns were sized for emoji (16 bytes). They now hold keys
     * from App\Support\Glyph — `pomegranate`, `fruit-plate` — which fit today
     * but leave no room for a longer name added from the panel's picker.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('glyph', 32)->nullable()->change();
        });

        Schema::table('category_features', function (Blueprint $table) {
            $table->string('glyph', 32)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('glyph', 16)->nullable()->change();
        });

        Schema::table('category_features', function (Blueprint $table) {
            $table->string('glyph', 16)->nullable()->change();
        });
    }
};
