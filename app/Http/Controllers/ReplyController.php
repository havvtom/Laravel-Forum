<?php

namespace App\Http\Controllers;
use App\Thread;

use Illuminate\Http\Request;

class ReplyController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
	}
	
    public function store($channelID, Thread $thread, Request $request){

    	$thread->addReply([

    		'body' => $request->body,
    		'user_id' => Auth()->user()->id

    	]);

        return redirect($thread->path());
    }
}
