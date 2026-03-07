<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('page_sections')->cascadeOnDelete();
            $table->string('locale', 5); // en ru uz
            $table->text('value')->nullable();
            $table->timestamps();
            $table->unique(['section_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_section_translations');
    }
};
