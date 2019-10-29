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
}
