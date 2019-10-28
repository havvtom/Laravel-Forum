<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInAForumTest extends TestCase
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

    public function test_a_user_can_participate_in_forum_threads(){

        
        //User has to be authenticated
        $user = factory(\App\User::class)->create();
        $this->be($user);

        //Existing thread
        $thread = factory(\App\Thread::class)->create();

        //A user can post a reply
        $reply = factory(\App\Reply::class)->make();
        $this->post('threads/'.$thread->id.'/replies', $reply->toArray());

        //their reply should be visible on the page

        $this->get($thread->path())
            ->assertSee($reply->body);

    }

}

// public function test_a_user_can_participate_in_forum_threads(){

        
//         // //User has to be authenticated
//         // $user = factory(\App\User::class)->create();
//         // $this->be($user);

//         // //Existing thread
//         // $thread = factory(\App\Thread::class)->create();

//         // //A user can post a reply
//         // $reply = factory(\App\Reply::class)->make();
//         // $this->post('threads/'.$thread->id.'/replies', $reply->toArray());

//         // //their reply should be visible on the page

//         // $this->get($thread->path())
//         //     ->assertSee($reply->body);

//     }