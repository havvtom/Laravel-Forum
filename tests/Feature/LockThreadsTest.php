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

    public function test_an_administrator_can_lock_any_thread(){

        $thread = factory(\App\Thread::class)->create();

        $this->be($user = factory(\App\User::class)->create());

        $thread->lock();

        $reply = factory(\App\Reply::class)->make();

        $this->post($thread->path().'/replies', $reply->toArray())

            ->assertStatus(422);

        
    }
}

//"vendor\bin\phpunit" --filter test_an_administrator_can_lock_any_thread