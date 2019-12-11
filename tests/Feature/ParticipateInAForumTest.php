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
        $this->post($thread->path().'/replies', $reply->toArray());

        //their reply should be visible on the page

        $this->get($thread->path())
            ->assertSee($reply->body);

    }

    public function test_a_reply_needs_a_body(){

    	$thread = factory(\App\Thread::class)->create();

        $reply = factory(\App\Reply::class)->make(['body' => null]);
        // dd($reply->thread);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertStatus(302)
            ->assertSessionHasErrors('body');
    }

    public function test_unauthorised_users_cannot_delete_replies(){
      

        $reply = factory(\App\Reply::class)->create();
        $this->delete('/replies/'.$reply->id)

            ->assertRedirect('/login');

        $this->be(factory(\App\User::class)->create());
        $this->delete('/replies/'.$reply->id)

            ->assertStatus(403);

    }

    public function test_authorized_users_can_delete_replies(){
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $reply = factory(\App\Reply::class)->create(['user_id' => $user->id]);
        
        $this->delete('/replies/'.$reply->id);
        
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

    }

}

    