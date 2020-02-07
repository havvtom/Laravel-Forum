<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadsTest extends TestCase
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

    public function test_non_administrators_my_not_lock_threads(){

        $this->be($user = factory(\App\User::class)->create());

        $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);

        $this->post(route('locked-threads.store', $thread))

            ->assertStatus(403);

        $this->assertFalse(!!$thread->fresh()->locked);
    }

    public function test_adminstrators_may_lock_and_unlock_threads(){

        $this->be($user = factory(\App\User::class)->states('administrator')->create());

        $thread = factory(\App\Thread::class)->create();

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue(!!$thread->fresh()->locked);

        $this->delete(route('locked-threads.delete', $thread))

            ->assertStatus(200);

        $this->assertFalse(!!$thread->fresh()->locked);

    }


    public function test_once_locked_a_thread_cannot_receive_replies(){

        $thread = factory(\App\Thread::class)->create(['locked'=> true]);

        $this->be($user = factory(\App\User::class)->create());

        $this->assertTrue(!!$thread->locked);

        $reply = factory(\App\Reply::class)->make();

        $this->post($thread->path().'/replies', $reply->toArray())

            ->assertStatus(422);

        
    }
}

//"vendor\bin\phpunit" --filter LockThreadsTest