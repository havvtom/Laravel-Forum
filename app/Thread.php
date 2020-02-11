<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Threads\ThreadFilters;
use App\ThreadSubscriptions;
use App\Reply;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadHasNewReply;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordsActivityTrait, RecordsVisitsTrait, Searchable;

    protected $guarded = [];

    protected $appends = ['isSubscribedTo'];

    // protected $casts = [
    //     'locked' => 'boolean'
    // ];

    protected static function boot(){

        parent::boot();

        static::deleting(function($thread){
            $thread->replies->each->delete();
        });

        static::created(function($thread){
            $thread->update(['slug' => $thread->title]);
        });

        
    }
    
    public function path(){

    	return '/threads/'.$this->channel->slug.'/'.$this->slug;
        
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

        $slug = Str::slug($value);

       if(static::whereSlug(Str::slug($slug))->exists()){

            $slug ="{$slug}-".$this->id;
        }

        $this->attributes['slug'] = $slug;

    }

    public function markBestReply(Reply $reply){

        return $this->update(['best_reply_id' => $reply->id]);
    }

    public function toSearchableArray()
    {
        /**
         * Load the categories relation so that it's
         * available in the Laravel toArray() method
         */
        $this->channel;
        $this->owner;

        $array = $this->toArray();
        

        return $array;
    }
}
