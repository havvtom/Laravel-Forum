<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadTest extends TestCase
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
    // public function test_only_authenticated_users_can_post_a_thread(){
    //         $this->expectException('Illuminate\Auth\AuthenticationException');
    //         $thread = factory(\App\Thread::class)->make();
    //         $this->post('/threads', $thread->toArray());
    // }

    public function test_an_authenticated_user_can_add_a_thread(){
        //user has to be authenticated
        $user = factory(\App\User::class)->create(['confirmed' => true]);
        $this->be($user);

        //hit the end point(post a thread)
        $thread = factory(\App\Thread::class)->make();
        $response = $this->post('/threads', $thread->toArray());
        $response->headers->get('Location');

        //see the thread when you hit the threads endpoint
        $this->get($response->headers->get('Location'))
                ->assertSee($thread->title)
                ->assertSee($thread->body);
    }

    public function publishThread($overrides = []){
        $user = factory(\App\User::class)->create(['confirmed' => true]);
        $this->be($user);

        $thread = factory(\App\Thread::class)->make($overrides);

         return $this->post('/threads', $thread->toArray());
            
    }

    public function test_a_thread_requires_a_title(){

        $this->publishThread(['title' => ""])->assertStatus(302)
                            ->assertSessionHasErrors('title');
        
    }

     public function test_a_thread_requires_a_body(){

        $this->publishThread(['body' => ""])->assertStatus(302)
                            ->assertSessionHasErrors('body');
        
    }

    public function test_a_thread_requires_a_valid_channel(){

        $channel = factory(\App\Channel::class, 2)->create();
        $this->publishThread(['channel_id' => 999])->assertStatus(302)
                            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => ""])->assertStatus(302)
                            ->assertSessionHasErrors('channel_id');
    }

    public function test_authenticated_users_must_first_confirm_their_email_before_publishing_a_thread(){

        $this->be($user = factory(\App\User::class)->create());

        $thread = factory(\App\User::class)->make(['user_id' => $user->id]);

        $this->post('/threads', $thread->toArray())

                ->assertRedirect('/threads')

                ->assertSessionHas('flash', 'You need to confirm your email address first.');
    }

    public function test_a_thread_requires_a_unique_slug(){

       $this->be($user = factory(\App\User::class)->create(['confirmed' => true]));

       $thread = factory(\App\Thread::class)->create(['title' => 'Foo Bar', 'slug' => 'foo-bar']);

       $this->assertEquals($thread->fresh()->slug, 'foo-bar');

       $this->post('/threads', $thread->toArray());

       $this->assertDatabaseHas('threads', ['slug' => 'foo-bar-2']);

       $this->post('/threads', $thread->toArray());

       $this->assertTrue(\App\Thread::whereSlug('foo-bar-3')->exists());
    }
}
//"vendor\bin\phpunit" --filter test_a_thread_requires_a_unique_slug