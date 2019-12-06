<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityTest extends TestCase
{
	use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_it_records_an_activity_when_thread_is_made(){

    	$this->be(factory(\App\User::class)->create());

    	$thread = factory(\App\Thread::class)->create();

        $activity = \App\Activity::first();

    	$this->assertDatabaseHas('activities', [
    		'type' => 'created_thread',
    		'user_id' => Auth()->user()->id,
    		'subjectable_id' => $thread->id,
    		'subjectable_type' => "App\Thread"
    	]);

        $this->assertEquals($activity->subjectable->id, $thread->id);
    }

    public function test_it_records_activity_when_reply_is_made(){
        $this->be(factory(\App\User::class)->create());

        $reply = factory(\App\Reply::class)->create();

        $this->assertEquals(2, \App\Activity::count());
    }
}
