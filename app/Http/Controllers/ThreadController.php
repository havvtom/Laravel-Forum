<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use App\Channel;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($channelSlug = null, Request $request)
    {
        if($channelSlug){
            $channel = Channel::where('slug', $channelSlug)->first();
            $threads = $channel->threads;          

        }else{
           $threads = Thread::with('channel')->latest()->filter($request)->get(); 
       }       

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
            ]);


        $thread = Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth()->user()->id,
            'channel_id' => $request->channel_id
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show( $channelId, Thread $thread)
    {     

        $replies = $thread->replies()->paginate(10);
        return view('threads.show', compact('thread', 'replies'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channelId, Thread $thread)
    {
            $this->authorize('update', $thread);
            // $thread->replies()->delete();
            $thread->delete();

            if(request()->wantsJson()){
                return response([], 204);
                }

            return redirect('/threads');
        
        

       
    }
}
