<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Threads\ThreadFilters;

class Thread extends Model
{
    protected $guarded = [];

    protected static function boot(){
        parent::boot();

        static::addGlobalScope('replyCount', function(Builder $builder){
            $builder->withCount('replies');
        });
    }
    
    public function path(){
    	return 'threads/'.$this->channel->slug.'/'.$this->id;
    }

    public function replies(){

    	return $this->hasMany(Reply::class);
    }

    public function owner(){

    	return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply){
    	
    	$this->replies()->create($reply);
    }

    public function channel(){
        return $this->belongsTo(\App\Channel::class);
    }

    public function scopeFilter(Builder $builder, $request){

        return (new ThreadFilters($request))->filter($builder);
    }
}
