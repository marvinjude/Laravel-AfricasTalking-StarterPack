<?php

use Faker\Generator as Faker;

$factory->define(App\ScheduledMessage::class, function (Faker $faker) {
    return [
        "sent" => '0',
        "priority"=> '1',
        "message"=> $faker->sentence(),
    ];
});
