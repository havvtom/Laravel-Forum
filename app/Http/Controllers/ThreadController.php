<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use Carbon\Carbon;
use App\Channel;
use App\Inspections\Spam;
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
    public function store(Request $request, Spam $spam)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
            ]);

        $spam->detect(request('body'));


        $thread = Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth()->user()->id,
            'channel_id' => $request->channel_id
        ]);

        return redirect()->route('thread', [$thread->channel->slug, $thread->id])->with('flash', "Your thread has been published");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show( $channelId, Thread $thread)
    {     
        //record that a user visited this page 
        //record the time stamp
        if(Auth()->check()){

            Auth()->user()->visitedThreadCacheKey($thread);
            
        }
        

        return view('threads.show', compact('thread'));

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
