<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('slug')->unique();               // used as the #anchor on the menu page
            $table->string('name');                         // نوشیدنی‌های گرم
            $table->string('short_name')->nullable();       // tighter label for the chip nav
            $table->string('latin_name')->nullable();       // HOT DRINKS
            $table->string('subtitle')->nullable();         // منوی نوشیدنی های گرم
            $table->text('description')->nullable();

            // Presentation
            $table->string('kind')->default('drink');       // drink | hookah  (see App\Enums\CategoryKind)
            $table->string('layout')->default('grid');      // grid | list     (see App\Enums\CategoryLayout)
            $table->string('icon')->nullable();             // small emoji/glyph shown next to the title
            $table->string('image_path')->nullable();       // optional hero image for the section

            // A single service price for the whole section (used by the hookah sections)
            $table->unsignedBigInteger('price')->nullable();
            $table->string('price_note')->nullable();

            // Landing page card. NULL card_order = not shown on the landing page.
            $table->unsignedSmallInteger('card_order')->nullable();
            $table->string('card_title')->nullable();       // overrides `name` on the landing card
            $table->string('card_subtitle')->nullable();
            $table->string('card_latin')->nullable();       // overrides `latin_name` on the card

            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
