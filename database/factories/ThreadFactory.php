<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Thread;
use App\Channel;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'body' => $faker->paragraph,
        'channel_id' => function(){
        	return factory(App\Channel::class)->create()->id;
        },
        'user_id' => factory(App\User::class)
        
    ];
});
