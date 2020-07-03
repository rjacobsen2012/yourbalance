<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Balance::class, function (Faker $faker) {
    $amount = $faker->randomFloat(2, 1, 5000);

    if (mt_rand(0, 1)) {
        $amount = -$amount;
    }

    return [
        'user_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
        'amount' => $amount,
        'label_id' => function () {
            return factory(\App\Models\Label::class)->create()->id;
        },
        'date' => $faker->dateTime(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
