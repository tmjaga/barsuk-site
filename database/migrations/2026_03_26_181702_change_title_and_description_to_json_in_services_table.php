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
        Schema::table('services', function (Blueprint $table) {
            $table->json('title_json')->nullable()->after('title');
            $table->json('description_json')->nullable()->after('description');
        });

        DB::table('services')->get()->each(function ($service) {
            DB::table('services')->where('id', $service->id)->update([
                'title_json' => json_encode([config('logat.default') => $service->title]),
                'description_json' => json_encode([config('logat.default') => $service->description]),
            ]);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('title_json', 'title');
            $table->renameColumn('description_json', 'description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('title_old')->nullable()->after('title');
            $table->text('description_old')->nullable()->after('description');
        });

        DB::table('services')->get()->each(function ($service) {
            $title = json_decode($service->title, true)[config('logat.default')] ?? '';
            $description = json_decode($service->description, true)[config('logat.default')] ?? null;

            DB::table('services')->where('id', $service->id)->update([
                'title_old' => $title,
                'description_old' => $description,
            ]);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('title_old', 'title');
            $table->renameColumn('description_old', 'description');
        });
    }
};
