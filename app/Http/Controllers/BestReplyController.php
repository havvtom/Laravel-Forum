<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Reply;
use \App\Thread;

class BestReplyController extends Controller
{
    public function store(Reply $reply){

    	// abort_if($reply->thread->user_id !== Auth()->user()->id, 401);
    	$this->authorize('update', $reply->thread);

    	$reply->thread->markBestReply($reply);
    }
}
