<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    protected static int $number = 1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Test Album #'.self::$number++,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Album $album) {
            for ($i = 1; $i <= 3; $i++) {
                $url = 'https://picsum.photos/200/300?random='.rand(1, 1000);
                $album->addMediaFromUrl($url)
                    ->usingFileName(Str::uuid().'.jpg')
                    ->withCustomProperties([
                        'title' => 'Image '.$i,
                        'active' => 1,
                    ])
                    ->toMediaCollection('images');
            }
        });
    }
}
