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

       factory(\App\Thread::class, 2)->create();

       $thread = factory(\App\Thread::class)->create(['title' => 'Foo Bar']);

       $this->assertEquals($thread->fresh()->slug, 'foo-bar');

       $thread = $this->postJson('/threads', $thread->toArray())->json();

       $this->assertEquals('foo-bar-'.$thread['id'], $thread['slug']);

       $this->assertDatabaseHas('threads', ['slug' => 'foo-bar-4']);

       
    }

    public function test_unauthorized_users_may_not_update_threads(){

        $this->be($user = factory(\App\User::class)->create(['id' => 25]));

        $thread = factory(\App\Thread::class)->create(['user_id' => 28]);

        $this->patch(route('thread.update', [$thread->channel, $thread]), ['title' => 'changed', 'body' => 'body changed'])->assertStatus(403);

    }

    public function test_a_thread_can_be_updated(){

        $this->be($user = factory(\App\User::class)->create());

        $thread = factory(\App\Thread::class)->create(['user_id' => $user->id]);

        $this->patch('threads/'.$thread->channel->slug.'/'.$thread->slug, ['title' => 'changed', 'body' => 'body changed'])->assertStatus(200);

        $this->assertEquals($thread->fresh()->title, 'changed');

    }
}
//"vendor\bin\phpunit" --filter test_a_thread_can_be_updated