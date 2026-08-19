<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('name');                         // اسپرسو
            $table->string('latin_name')->nullable();       // Espresso
            $table->text('description')->nullable();

            // Price is intentionally nullable: the printed menu leaves "قیمت :" blank,
            // so the site renders a styled placeholder until a price is entered.
            $table->unsignedBigInteger('price')->nullable();

            $table->string('image_path')->nullable();       // NULL renders the ornate empty placeholder
            $table->string('emoji', 16)->nullable();        // small flavour glyph (🍎, 🫐 …)

            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true); // lets staff mark an item "تمام شد"

            $table->timestamps();

            $table->index(['category_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
