<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;
use App\Trending;
use Carbon\Carbon;
use App\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

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
    public function index($channelSlug = null, Request $request, Trending $trending)
    {
        if($channelSlug){
            $channelId = Channel::where('slug', $channelSlug)->first()->id;
            $threads = Thread::where('channel_id', $channelId)->paginate(25);          

        }else{
           $threads = Thread::with('channel')->latest()->filter($request)->paginate(25); 
       }    

     // $trending = array_map('json_decode',Redis::zrevrange('trending_threads', 0, 4));
        
        return view('threads.index', ['threads' => $threads, 'trending' => $trending->get()]);
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
            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id'
            ]);      


        $thread = Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => Auth()->user()->id,
            'slug' => $request->title,
            'channel_id' => $request->channel_id
        ]);

        return redirect($thread->path())->with('flash', "Your thread has been published");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show( $channelId, Thread $thread, Trending $trending)
    {     
        //record that a user visited this page 
        //record the time stamp
        if(Auth()->check()){

            Auth()->user()->visitedThreadCacheKey($thread);
            
        }   

        $trending->push($thread);   

        $thread->recordVisit();
         
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
