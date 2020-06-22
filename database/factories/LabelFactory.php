<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Label::class, function (Faker $faker) {
    return [
        'name' => \Illuminate\Support\Str::title($faker->word),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
