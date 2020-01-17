<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
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

    public function test_a_user_can_fetch_their_most_recent_reply(){

    	$this->be($user = factory(\App\User::class)->create());

    	$reply = factory(\App\Reply::class)->create(['user_id' => $user]);

    	$this->assertEquals($reply->id, $user->lastReply->id);
    }

    public function test_knows_if_it_was_just_published(){

    	$this->be($user = factory(\App\User::class)->create());

    	$reply = factory(\App\Reply::class)->create(['user_id' => $user]);

    	$this->assertTrue($user->lastReply->wasJustPublished());

    	$reply->created_at = \Carbon\Carbon::now()->subMonth();

		$this->assertFalse($reply->wasJustPublished());
    }
}

//"vendor\bin\phpunit" --filter UserTest