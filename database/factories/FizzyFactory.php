<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Fizzy;
use App\User;
use Faker\Generator as Faker;

$factory->define(Fizzy::class, function (Faker $faker) {
    $users = User::pluck('id')->toArray();
    $fizzyType = ['Maths', 'Science', 'Entertainment', 'Trivia', 'History', 'English', 'Earth & Space', 'Nat Geo Edu'];

    return [
        'title' => $faker->jobTitle,
        'description' => $faker->sentence,
        'fizzy_token' => Str::uuid(32),
        'type' => $faker->randomElement($fizzyType),
        'user_id' => $faker->randomElement($users),
        'publish_at' => $faker->dateTimeBetween('-1 months', 'now'),
        'created_at' => $faker->dateTimeBetween('-2 months', 'now')
    ];
});
