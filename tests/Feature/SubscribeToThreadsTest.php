<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
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

    public function test_a_user_can_subscribe_to_threads(){

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $thread = factory(\App\Thread::class)->create();

        $this->post($thread->path().'/subscriptions');

        $thread->addReply([

            'user_id' => Auth()->user()->id,
            'body'      => "Some reply here"
        ]);

        // $this->assertCount(1, Auth()->user()->notifications());
    }
}
