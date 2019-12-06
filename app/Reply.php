<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RecordsActivityTrait;


class Reply extends Model
{

    use RecordsActivityTrait; 

	protected $guarded = [];
    protected $with = ['user', 'favorites'];
	
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
        if(!Auth()->user()){
            return true;
        }
    	return $this->favorites->where('user_id', Auth()->user()->id)->count();
    }

    public function getFavoritesCountAttribute(){
        return $this->favorites->count();
    }
}

