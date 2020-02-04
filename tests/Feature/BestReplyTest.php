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

        $this->postJson(route('best-reply',[ $replies[1]->id]));


        $this->assertDatabaseHas('threads', ['best_reply_id' => $replies[1]->id]);
    }
}

//"vendor\bin\phpunit" --filter test_a_thread_maker_may_mark_a_reply_as_the_best
