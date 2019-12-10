<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function subjectable(){
    	return $this->morphTo();
    }

    public static function feed($user, $take=50){

    	$activities = $user->activities()->latest()->with('subjectable')->take($take)->get()->groupBy(function($activity){
    		return $activity->created_at->format('Y-m-d');
    	});

    	return $activities;
    }
}
