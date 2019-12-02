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

    	$this->assertDatabaseHas('activities', [
    		'type' => 'created_thread',
    		'user_id' => Auth()->user()->id,
    		'subject_id' => $thread->id,
    		'subject_type' => '\App\Thread'
    	]);
    }
}
