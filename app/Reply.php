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

    public function favorite(){

        if(!$this->favorites()->where(['user_id'=>Auth()->user()->id])->exists()){
            return $this->favorites()->create(['user_id' => Auth()->user()->id]);
        }
        
    }

    public function isFavorited(){
    	return $this->favorites()->where(['user_id'=>Auth()->user()->id])->exists();
    }
}
