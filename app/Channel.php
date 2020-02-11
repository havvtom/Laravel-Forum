<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    // use Searchable;

    public function threads(){
    	
    	return $this->hasMany(\App\Thread::class);
    }

    public function getRouteKeyName(){

    	return 'slug';
    }
}
