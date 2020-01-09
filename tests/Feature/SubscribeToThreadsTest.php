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

    public function test_it_knows_if_authenticated_user_is_subscribed_to_it(){

        $thread = factory(\App\Thread::class)->create();

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);

    }

     public function test_a_user_can_unsubscribe_from_threads(){

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $thread = factory(\App\Thread::class)->create();

        $thread->subscribe();

        $this->delete($thread->path().'/subscriptions');

        
        $this->assertCount(0, $thread->subscriptions);
     }
}
