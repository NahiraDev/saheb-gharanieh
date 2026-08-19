<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Editable site copy (café intro, address, phone, socials …) so the future
     * admin panel can change text without touching Blade templates.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');   // string | text | number | boolean
            $table->string('group')->default('general'); // general | contact | social
            $table->string('label')->nullable();         // human label for the admin form
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
