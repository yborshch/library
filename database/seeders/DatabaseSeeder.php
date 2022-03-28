<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Series;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Author::factory()->count(100)->create();
        Category::factory()->count(20)->create();
        Series::factory()->count(20)->create();
        Tag::factory()->count(20)->create();

        $this->call([
            UserSeeder::class,
        ]);
    }
}
