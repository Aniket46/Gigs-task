<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = User::class;

    public function definition()
    {
        return [
        'firstname' => $this->faker->name,
        'lastname' => $this->faker->name,
        'mobile' => $this->faker->numerify('#########'),
        'email' => $this->faker->email,
        'age' => $this->faker->numerify('##'),
        'gender' => 'm',
        'city' => $this->faker->city,
        'password' => $this->faker->password()
        ];
    }
}
