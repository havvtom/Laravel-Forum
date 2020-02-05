<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Thread;
use App\Channel;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {
	$title = $faker->sentence;
    return [
        'title' => $title,
        'body' => $faker->paragraph,
        'slug' => Str::slug($title),
        'locked' => false,
        'channel_id' => function(){
        	return factory(App\Channel::class)->create()->id;
        },
        'user_id' => factory(App\User::class)
        
    ];
});
