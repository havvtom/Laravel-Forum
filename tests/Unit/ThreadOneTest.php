<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThreadTest extends TestCase
{
	use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function setUp() :void{

    	parent::setUp();

    	$this->thread = factory(\App\Thread::class)->create();
    }

    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_a_user_can_browse_threads()
    {
    	
        $response = $this->get('/threads')

        	->assertStatus(200);
            

        $response = $this->get('/threads/'.$this->thread->channel->slug.'/'.$this->thread->id)

        	->assertSee($this->thread->title);
    }

    public function test_a_thread_can_have_replies(){

    	$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_a_thread_has_an_owner(){

    	$this->assertInstanceOf('App\User', $this->thread->owner);
    }

    public function test_a_user_can_filter_threads_by_unanswered(){
        $thread = factory(\App\Thread::class)->create();
        $reply = factory(\App\Reply::class)->create(['thread_id' => $thread->id]);

        $response = $this->getJson('/threads?unanswered=1')->json();

        $this->assertCount(1, $response);
    }

    public function test_a_thread_can_be_subscribed_to(){
        $thread = factory(\App\Thread::class)->create();

        $thread->subscribe($userId = 1);

        $this->assertEquals( 1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    public function test_a_thread_can_be_unsubscribed_to(){

        $thread = factory(\App\Thread::class)->create();

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount( 0, $thread->subscriptions);
    }

    public function test_a_thread_can_check_if_an_authenticated_user_has_read_all_replies(){

        $this->be($user = factory(\App\User::class)->create());

        $thread = factory(\App\Thread::class)->create();

        $this->assertTrue($thread->hasUpdatesFor());

        $key = Auth()->user()->visitedThreadCacheKey($thread);
       
        cache()->forever($key, \Carbon\Carbon::now());

        $this->assertFalse($thread->hasUpdatesFor());
    }

}

// "vendor\bin\phpunit" --filter  test_a_thread_can_check_if_an_authenticated_user_has_read_all_replies