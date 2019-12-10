<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

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
        $this->be($user);

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

        $this->assertDatabaseMissing('activities', [
            'subjectable_id' => $thread->id,
            'subjectable_type' => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subjectable_id' => $reply->id,
            'subjectable_type' => get_class($reply)
        ]);

    }

    public function test_unauthorised_users_cannot_delete_threads(){        

        $thread = factory(\App\Thread::class)->create();

        $user = factory(\App\User::class)->create();
        $this->be($user);

        $response = $this->delete($thread->path());
        $response->assertStatus(403);
    }

    public function test_fetches_a_feed_for_any_user(){

        $this->be($user = factory(\App\User::class)->create());

        factory(\App\Thread::class, 2)->create(['user_id' => $user->id]);
        $user->activities->first()->update(['created_at' => Carbon::now()->subWeek()]);
        $feed = \App\Activity::feed($user);


        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
