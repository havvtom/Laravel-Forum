<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
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

    public function test_profile_displays_threads_created_by_associated_user(){

        $user = factory(\App\User::class)->create();

        $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);

        $this->get('/profiles/'.$user->name)
                ->assertSee($thread->title);
    }

    public function test_guest_cannot_delete_threads(){

        $thread = factory(\App\Thread::class)->create();

        $response =$this->delete($thread->path());

        $response->assertRedirect('/login');

    }

    public function test_a_user_can_delete_their_thread(){
        $user = factory(\App\User::class)->create();
        $this->be($user);

        $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);
        $reply = factory(\App\Reply::class)->create(['thread_id' => $thread->id]);

        $this->json('DELETE', $thread->path());

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

    }

    public function test_unauthorised_users_cannot_delete_threads(){        

        $thread = factory(\App\Thread::class)->create();

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $response = $this->delete($thread->path());
        $response->assertStatus(403);
    }
}
