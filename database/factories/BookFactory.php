<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Book;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Book::class;

    public function definition()
    {
        return [
            'book_name' => $this->faker->name,
            'author' => $this->faker->name,
            'cover_image' => $this->faker->url,
        ];
    }
}
