<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name(),
            'student_id'=>$this->faker->randomDigit(),
            'age'=>$this->faker->randomNumber(),
            'address'=>$this->faker->country(),
            'standard_id'=>1
        ];
    }
}
