<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Threads\ThreadFilters;
use App\ThreadSubscriptions;
use App\Notifications\ThreadWasUpdated;

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

        $this->subscriptions->filter(function ($sub) use ($reply){

            return $sub->user_id != $reply->user_id;

        })->each->notify($reply);
            // ( function ($sub) use ($reply) {

        //     $sub->user->notify(new ThreadWasUpdated($this, $reply));

        // });

        // foreach ($this->subscriptions as $subscription){

        //     if($subscription->user_id != $reply->user_id){

        //         $subscription->user->notify(new ThreadWasUpdated($this, $reply));

        //     }            
      

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
}
