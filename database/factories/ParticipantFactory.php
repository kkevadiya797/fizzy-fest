<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Game;
use App\Models\Participant;
use Faker\Generator as Faker;

$factory->define(Participant::class, function (Faker $faker) {
    $games = Game::pluck('id')->toArray();
    $gameId = $faker->randomElement($games);

    return [
        'name' => $faker->name,
        'totalPoints' => random_int(0, 5000),
        'game_id' => $gameId,
        'created_at' => Game::where('id', $gameId)->value('created_at')
    ];
});
