<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Threads\ThreadFilters;
use App\ThreadSubscriptions;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadHasNewReply;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class Thread extends Model
{
    use RecordsActivityTrait, RecordsVisitsTrait;

    protected $guarded = [];

    protected $appends = ['isSubscribedTo'];

    protected static function boot(){

        parent::boot();

        static::deleting(function($thread){
            $thread->replies->each->delete();
        });

        
    }
    
    public function path(){

    	return 'threads/'.$this->channel->slug.'/'.$this->slug;
        
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
        event(new ThreadHasNewReply($this, $reply));             
      

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
        if(Auth()->check())

            { return $this->subscriptions()
                               ->where('user_id', Auth()->user()->id)
                               ->exists();
                     }
                               
        return null;
    }

    public function notifySubscribers($reply){

        $this->subscriptions

                    ->where( 'user_id', '!=', $reply->user_id )

                    ->each

                    ->notify($reply);
    }

    public function hasUpdatesFor($user = null){

        $user = $user ?: Auth()->user();
        //look in the cache for a proper key

        //compare carbon instance with $thread->updated_at
        $key = sprintf("users.%s.visits.%s", Auth()->user()->id, $this->id);
       
        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName(){

        return 'slug';
    }

    public function setSlugAttribute($value){

        if(static::whereSlug($slug = Str::slug($value))->exists()){

            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug' => $slug];

    }

    public function incrementSlug($str){


    }
}
