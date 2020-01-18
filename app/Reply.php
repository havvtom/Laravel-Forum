<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RecordsActivityTrait;
use Carbon\Carbon;


class Reply extends Model
{

    use RecordsActivityTrait; 

	protected $guarded = [];
    protected $with = ['user', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];
	
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($reply){
            $reply->favorites()->get()->each(function($favorite){
                $favorite->delete();
            });
        });

        static::created(function($reply){
            $reply->thread->increment('replies_count');
        });

        static::deleted(function($reply){
            $reply->thread->decrement('replies_count');
        });
    }

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

    public function unfavorite(){
        $this->favorites()->where(['user_id'=>Auth()->user()->id])->get()->each(function($favorite){
            $favorite->delete();
        });
        
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

    public function getIsFavoritedAttribute(){
        return $this->isFavorited();
    }

    public function path(){
       $url = route('thread', [$this->thread->channel->slug, $this->thread->id])."#reply-".$this->id;
       return $url;
    }

    public function wasJustPublished(){

        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers(){

        preg_match_all('/\@([^\s\.]+)/', $this->body, $matches);

        return $matches[1];
    }
}

