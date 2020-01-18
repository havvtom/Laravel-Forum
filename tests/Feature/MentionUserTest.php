<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUserTest extends TestCase
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

    public function test_mentioned_users_in_a_post_are_notified(){

        //given i have a user John Doe,  who is signed in

        $this->be($John = factory(\App\User::class)->create(['name' => 'JohnDoe']));

        //and i have another use Jane Doe

        $Jane = factory(\App\User::class)->create(['name' => 'JaneDoe']);

        //if we have a thread
        
        $thread = factory(\App\Thread::class)->create();

        //and JohnDoe replies and mentions JaneDoe

        $reply = factory(\App\Reply::class)->make([

            'user_id' => $John->id,
            'body'  => '@JaneDoe look at this.'

        ]);

        $this->json('post', $thread->path().'/replies', $reply->toArray());

        //JaneDoe should get a notification

        $this->assertCount(1, $Jane->notifications);


    }
}

//"vendor\bin\phpunit" --filter MentionUserTest