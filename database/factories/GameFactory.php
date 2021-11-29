<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Fizzy;
use App\Models\Game;
use Faker\Generator as Faker;

$factory->define(Game::class, function (Faker $faker) {
    
    $fizzies = Fizzy::pluck('id')->toArray();

    $pin = rand(100000, 999999);

    return [    
        'fizzy_id' => $faker->randomElement($fizzies),
        'pin' => $pin,
        'is_active' => true,
        'started_at' => $faker->dateTimeBetween('-2 months', 'now'),
        'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
    ];
});
