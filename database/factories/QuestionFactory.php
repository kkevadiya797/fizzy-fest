<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Fizzy;
use App\Models\Question;
use Faker\Generator as Faker;

$factory->define(Question::class, function (Faker $faker) {
    $fizzies = Fizzy::pluck('id');
    $timer = [5, 10, 20, 30];
    $points = [500, 1000, 1500, 2000];

    return [
        'question' => $faker->sentence,
        'timer' => $faker->randomElement($timer),
        'points' => $faker->randomElement($points),
        'created_at' => $faker->dateTimeBetween('-1 months', 'now'),
        'fizzy_id' => $faker->randomElement($fizzies),
    ];
});
