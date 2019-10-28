<?php

namespace App\Http\Controllers;
use App\Thread;

use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Thread $thread, Request $request){

    	$thread->addReply([

    		'body' => $request->body,
    		'user_id' => Auth()->user()->id

    	]);
    }
}
