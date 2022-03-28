<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "category_id" => Category::factory(),
            "description" => $this->faker->text(200),
            "title" => $this->faker->word,
            "series_id" => $this->faker->numberBetween(1, 10),
            "pages" => $this->faker->numberBetween(1, 900),
            "source" => 1,
            "year" => $this->faker->year($max = 'now')
        ];
    }
}
