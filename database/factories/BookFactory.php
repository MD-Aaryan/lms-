<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'author' => fake()->name(),
            'isbn' => fake()->unique()->numerify('978#########'),
            'published_year' => fake()->numberBetween(1950, 2024),
            'stock' => fake()->numberBetween(1, 5),
            'description' => fake()->sentence(),
        ];
    }
}
