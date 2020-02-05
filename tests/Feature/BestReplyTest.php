<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_a_thread_maker_may_mark_a_reply_as_the_best(){

        $this->be($user = factory(\App\User::class)->create());

        $thread = factory(\App\Thread::class)->create();

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

        $replies = factory(\App\Reply::class, 2)->create(['thread_id' => $thread->id]);

        $this->assertFalse($replies[1]->fresh()->isBestReply());

        $this->postJson(route('best-reply',[ $replies[1]->id]));

        $this->assertTrue($replies[1]->fresh()->isBestReply());
    }

    public function test_only_thread_creator_my_mark_the_reply_as_best(){

        $this->be($user = factory(\App\User::class)->create());

        $thread = factory(\App\Thread::class)->create(['user_id' => Auth()->user()->id]);

        $this->be($JohnD = factory(\App\User::class)->create());

        $reply = factory(\App\Reply::class)->create(['thread_id' => $thread->id]);

        $this->postJson(route('best-reply',[ $reply->id]))->assertStatus(403);

        $this->assertFalse($reply->fresh()->isBestReply());
    }

    public function test_if_best_reply_is_deleted_the_thread_is_properly_updated(){
        $this->be($user = factory(\App\User::class)->create());

        // $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);

        $replies = factory(\App\Reply::class)->create(['user_id' => $user->id]);

        $this->postJson(route('best-reply', $replies->id));

        $this->deleteJson('/replies/'.$replies->id)->assertStatus(200);

        // dd($replies->isBestReply);

        $this->assertSame(null, $replies->thread->fresh()->best_reply_id);
    }
}

//"vendor\bin\phpunit" --filter test_if_best_reply_is_deleted_the_thread_is_properly_updated
