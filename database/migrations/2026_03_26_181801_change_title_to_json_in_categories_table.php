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
        Schema::table('categories', function (Blueprint $table) {
            $table->json('title_json')->nullable()->after('title');
        });

        DB::table('categories')->get()->each(function ($category) {
            DB::table('categories')->where('id', $category->id)->update([
                'title_json' => json_encode([config('logat.default') => $category->title]),
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['title']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('title_json', 'title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('title_old')->nullable()->after('title');
        });

        DB::table('categories')->get()->each(function ($category) {
            $title = json_decode($category->title, true)[config('logat.default')] ?? '';

            DB::table('categories')->where('id', $category->id)->update([
                'title_old' => $title,
            ]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['title']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('title_old', 'title');
        });
    }
};
