<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Threads\ThreadFilters;
use App\ThreadSubscriptions;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadHasNewReply;

class Thread extends Model
{
    use RecordsActivityTrait;

    protected $guarded = [];

    protected $appends = ['isSubscribedTo'];

    protected static function boot(){

        parent::boot();

        static::deleting(function($thread){
            $thread->replies->each->delete();
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
    	
    	$reply = $this->replies()->create($reply);

        //prepare notification for all subscribers

      
        $this->notifySubscribers($reply);             
      

        return $reply;
    }

    public function channel(){
        return $this->belongsTo(\App\Channel::class);
    }

    public function scopeFilter(Builder $builder, $request){

        return (new ThreadFilters($request))->filter($builder);
    }

    public function subscribe($userId = null){

        return $this->subscriptions()->create(['user_id' => $userId ?: Auth()->user()->id]);
    }

    public function unsubscribe($userId = null){

        $this->subscriptions()
                            ->where(['user_id' => $userId ?: Auth()->user()->id])
                            ->delete();
    }

    public function subscriptions(){
        return $this->hasMany(ThreadSubscriptions::class);
    }

    public function getIsSubscribedToAttribute(){

        return $this->subscriptions()
                        ->where('user_id', Auth()->user()->id)
                        ->exists();
    }

    public function notifySubscribers($reply){

        $this->subscriptions

                    ->where( 'user_id', '!=', $reply->user_id )

                    ->each

                    ->notify($reply);
    }

    public function hasUpdatesFor(){

        //look in the cache for a proper key

        //compare carbon instance with $thread->updated_at

        return $this->updated_at > cache($key);
    }
}
