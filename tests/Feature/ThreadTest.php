<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThreadTest extends TestCase
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

    public function test_a_thread_can_make_a_string_path(){

        $thread = factory(\App\Thread::class)->create();
        $this->assertEquals('threads/'.$thread->channel->slug.'/'.$thread->id, $thread->path());
    }

    public function test_a_thread_can_add_a_reply(){

        $this->thread = factory(\App\Thread::class)->create();

        
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);

    }

    public function test_a_thread_belongs_to_a_channel(){

        $thread = factory(\App\Thread::class)->create();

        $this->assertInstanceOf(\App\Channel::class, $thread->channel);
    }

    public function test_a_user_can_filter_threads_according_to_a_tag(){
        $channel = factory(\App\Thread::class)->create();
        $threadInChannel = factory(\App\Thread::class)->create(['channel_id' => $channel->id]);
        $threadNotInChannel = factory(\App\Thread::class)->create();

        $this->get('/threads/'.$channel->slug)
            ->assertSee($threadInChannel->title);
           
    }

    public function test_a_user_can_filter_threads_by_username(){
        $user = factory(\App\User::class)->create(['name' => 'JohnDoe']);
        $this->be($user);

        $threadByJohn = factory(\App\Thread::class)->create(['user_id' => $user->id]);
        $threadNotByJohn = factory(\App\Thread::class)->create();

        $this->get('/threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}
