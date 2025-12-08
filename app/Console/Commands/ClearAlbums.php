<?php

namespace App\Console\Commands;

use App\Models\Album;
use Illuminate\Console\Command;

class ClearAlbums extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-albums';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all Albums with all media files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('Clearing albums and media...');

        Album::query()->each(function ($album) {
            $album->clearMediaCollection();
            $album->delete();
        });

        $this->info('All albums & media cleared successfully.');
    }
}
