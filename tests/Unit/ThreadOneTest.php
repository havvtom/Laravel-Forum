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

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response);
    }

}
