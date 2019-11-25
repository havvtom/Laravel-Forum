<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Reply extends Model
{
	protected $guarded = [];
	
    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function thread(){
    	return $this->belongsTo(Thread::class);
    }

    public function favorites(){
    	return $this->morphMany(\App\Favorite::class, 'favoritable');
    }
}
