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

    public function test_a_user_can_determine_their_avatar_path(){

        $this->be($user = factory(\App\User::class)->create());

        $this->assertEquals('images/avatars/default.png', $user->avatar());

        $this->be($user = factory(\App\User::class)->create(['avatar_path' => 'avatars/me.jpg']));

        $this->assertEquals('avatars/me.jpg', $user->avatar());
    }
}

//"vendor\bin\phpunit" --filter UserTest