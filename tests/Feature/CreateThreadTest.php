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
    public function test_only_authenticated_users_can_post_a_thread(){
            $this->expectException('Illuminate\Auth\AuthenticationException');
            $thread = factory(\App\Thread::class)->make();
            $this->post('/threads', $thread->toArray());
    }

    public function test_an_authenticated_user_can_add_a_thread(){
        //user has to be authenticated
        $user = factory(\App\User::class)->create();
        $this->be($user);

        //hit the end point(post a thread)
        $thread = factory(\App\Thread::class)->make();
        $this->post('/threads', $thread->toArray());

        //see the thread when you hit the threads endpoint
        $response = $this->get($thread->path());

        $response->assertSee($thread->title)
                ->assertSee($thread->body);




    }
}
