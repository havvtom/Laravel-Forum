<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Channel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::composer('*', function($view){
            \Cache::forget('channels');
            $channels = Channel::all();
            // $channels = \Cache::rememberForever('channels', function(){
            //     return Channel::all();
            // });
            $view->with('channels', $channels);
        });

        \Validator::extend('spamfree', 'App\Rules\Spamfree@passes');
    }
}
