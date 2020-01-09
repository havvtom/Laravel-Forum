<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationsTest extends TestCase
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

    public function test_a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user(){

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $thread = factory(\App\Thread::class)->create();

        $thread->subscribe();

        $this->assertCount(0, Auth()->user()->fresh()->notifications);

        $thread->addReply([

            'user_id' => Auth()->user()->id,
            'body'      => "Some reply here"
        ]);

        $this->assertCount(0, Auth()->user()->notifications);

        $thread->addReply([

            'user_id' => factory(\App\User::class)->create()->id,
            'body'      => "Some reply here"
        ]);

        $this->assertCount(1, Auth()->user()->fresh()->notifications);
    }

    public function test_a_user_can_clear_notifications(){

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $thread = factory(\App\Thread::class)->create();

        $thread->subscribe();

        $thread->addReply([

            'user_id' => factory(\App\User::class)->create()->id,
            'body'      => "Some reply here"
        ]);

        $this->assertCount(1, Auth()->user()->unreadNotifications);

        $notificationId = Auth()->user()->unreadNotifications->first()->id;

        $this->delete('/profiles/'.Auth()->user()->name.'/notifications/'.$notificationId);

        $this->assertCount(0, Auth()->user()->fresh()->unreadNotifications);
    }

    public function test_a_user_can_mark_a_notification_as_read(){

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $thread = factory(\App\Thread::class)->create();

        $thread->subscribe();

        $thread->addReply([

            'user_id' => factory(\App\User::class)->create()->id,
            'body'      => "Some reply here"
        ]);

        $response = $this->getJson('/profiles/'.Auth()->user()->name.'/notifications/')->json();

        $this->assertCount(1, $response);
    }
}
