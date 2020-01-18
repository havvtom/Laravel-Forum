<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\User;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        //inspect the body for any mentioned name
        

          // preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);
        $mentionedUsers =  $event->reply->mentionedUsers();                
          //for each mentioned user, notify them 

        $names = $mentionedUsers;

        foreach($names as $name){

            $user = User::whereName($name)->first();

            if($user){

            $user->notify(new YouWereMentioned($event->reply));
        }
      }
    }
}
