<?php

namespace App\Http\Controllers;
use App\Thread;
use App\Reply;


use Illuminate\Http\Request;

class ReplyController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
	}
	
    public function store($channelID, Thread $thread, Request $request){
        
        $request->validate([            
            'body' => 'required'           
            ]);

    	$reply = $thread->addReply([

    		'body' => $request->body,
    		'user_id' => Auth()->user()->id

    	]);

        if(request()->expectsJson()){
            return $reply->load('user');
        }

        return redirect($thread->path())->with('flash', 'Your reply was posted successfully');
    }

    public function destroy(Reply $reply){

        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->expectsJson()){
            return response(['status' => 'Reply deleted']);
        }
        
        return back();
    }

    public function update(Reply $reply, Request $request){

        $this->authorize('update', $reply);
        
        $reply->update(['body' => request('body')]);
    }
}
