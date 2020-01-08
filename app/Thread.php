<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Threads\ThreadFilters;

class Thread extends Model
{
    use RecordsActivityTrait;

    protected $guarded = [];

    protected static function boot(){

        parent::boot();

        static::deleting(function($thread){
            $thread->replies->each->delete();
        });

        
    }
    
    public function path(){
    	return 'threads/'.$this->channel->slug.'/'.$this->id;
        // return route('thread', [$this->channel->slug, $this->id]);
    }

    public function replies(){

    	return $this->hasMany(Reply::class);
    }

    public function owner(){

    	return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply){
    	
    	$reply = $this->replies()->create($reply);

        return $reply;
    }

    public function channel(){
        return $this->belongsTo(\App\Channel::class);
    }

    public function scopeFilter(Builder $builder, $request){

        return (new ThreadFilters($request))->filter($builder);
    }

    public function subscribe($userId = null){

        $this->subscriptions()->create(['user_id' => $userId ?: Auth()->user()->id]);
    }

    public function unsubscribe($userId){

        $this->subscriptions()
                            ->where('user_id', $userId ?: Auth()->user()->id)
                            ->delete();
    }

    public function subscriptions(){
        return $this->hasMany(ThreadSubscriptions::class);
    }
}
