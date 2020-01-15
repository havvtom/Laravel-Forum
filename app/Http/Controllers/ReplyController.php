<?php

namespace App\Http\Controllers;
use App\Thread;
use Illuminate\Support\Str;
use App\Reply;
use App\Inspections\Spam;


use Illuminate\Http\Request;

class ReplyController extends Controller
{
	public function __construct(){
		$this->middleware('auth', ['except' => 'index']);
	}

    public function index($channelId, Thread $thread){
        return $thread->replies()->paginate(20);
    }
	
    public function store($channelID, Thread $thread, Request $request){

        try {

                $this->validate(request(),[            
                'body' => 'required|spamfree'           
                ]);
            

                $reply = $thread->addReply([

                'body' => $request->body,
                'user_id' => Auth()->user()->id

            ]);

        
            return $reply->load('user');
            
        } catch (\Exception $e) {
            
            return response('Sorry, your reply could not be saved this time.', 422);

        }
        
        

    }

    public function destroy(Reply $reply){

        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->expectsJson()){
            return response(['status' => 'Reply deleted']);
        }
        
        return back();
    }

    public function update(Reply $reply){

        $this->authorize('update', $reply);

        try {            

            $this->validate(request(),[            
            'body' => 'required|spamfree'           
            ]);
        
            $reply->update(['body' => request('body')]);
            
        } catch (\Exception $e) {
            
            return response('Sorry, your reply could not be saved this time.', 422);
        }

        
    }

   
}
