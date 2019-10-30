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

    public function test_a_user_can_see_replies_associated_with_thread(){

    	$reply = factory(\App\Reply::class)->create(['thread_id' => $this->thread->id]);

    	$response= $this->get('/threads/'.$this->thread->channel->slug.'/'.$this->thread->id)

        	->assertSee($reply->body);

    }

    public function test_a_thread_can_have_replies(){

    	$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_a_thread_has_an_owner(){

    	$this->assertInstanceOf('App\User', $this->thread->owner);
    }

}
