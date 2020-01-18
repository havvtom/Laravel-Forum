<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class ReplyTest extends TestCase
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

    public function test_knows_if_it_was_just_published(){

    	$reply = factory(\App\Reply::class)->create();

    	$this->assertTrue($reply->wasJustPublished());

    	$reply->created_at = Carbon::now()->subMonth();

    	$this->assertFalse($reply->wasJustPublished());

    }

    public function test_it_can_detect_all_mentioned_users_in_the_body(){

    	$this->be($user = factory(\App\User::class)->create(['name' => 'JohnDoe']));

    	$JaneDoe = factory(\App\User::class)->create(['name' => 'JaneDoe']);

    	$thread = factory(\App\Thread::class)->create();

    	$reply = factory(\App\Reply::class)->create([

    		'body' => "@JaneDoe see this"

    	]);

    	$this->assertEquals(['JaneDoe'], $reply->mentionedUsers() );
    }
}
