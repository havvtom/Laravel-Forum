<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path', 'confirmation_token', 
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'confirmed' =>  'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads(){
        return $this->hasMany(Thread::class)->latest();
    }

    public function activities(){
        return $this->hasMany(Activity::class);
    }

    public function visitedThreadCacheKey($thread){

        $key = sprintf("users.%s.visits.%s", $this->id, $thread->id);

        cache()->forever($key, Carbon::now());
    }

    public function lastReply(){

        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarPathAttribute($avatar){

        if(!$avatar) return asset('images/avatars/default.png');

        return asset('/storage/'.$avatar);
    }

    public function confirm(){

        $this->confirmed = true;

        $this->email_verified_at = Carbon::now();

        $this->confirmation_token = null;

        $this->save();
    }

     public function isAdmin(){

        return in_array($this->name, ['Tom', 'Sharon']);
    }
}
