<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
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

    public function test_a_thread_has_a_path(){

        $thread = factory(\App\Thread::class)->create();
        $this->assertEquals('threads/'.$thread->channel->slug.'/'.$thread->slug, $thread->path());
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

    public function test_a_user_can_request_all_replies_for_a_given_thread(){
        $thread = factory(\App\Thread::class)->create();
        $replies = factory(\App\Reply::class, 2)->create(['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path().'/replies')->json();

        // $this->assertCount(1, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    
    public function test_a_thread_records_each_visit(){
        

        $thread = factory(\App\Thread::class)->make(['thread_id' => 1]);

        $thread->resetVisits();

        $this->assertSame(0, $thread->visits());

        $thread->recordVisit();

        $this->assertEquals(1, $thread->visits());

        $thread->recordVisit();

        $this->assertEquals(2, $thread->visits());
    }

    public function test_a_thread_may_be_locked(){

        $thread = factory(\App\Thread::class)->create();

        $this->assertFalse($thread->locked);

        $thread->lock();

        $this->assertTrue($thread->locked);
    }
}

//"vendor\bin\phpunit" --filter test_a_thread_may_be_locked