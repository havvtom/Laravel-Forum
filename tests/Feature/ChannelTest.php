<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChannelTest extends TestCase
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

    public function test_a_channel_consists_of_threads(){

        $channel = factory(\App\Channel::class)->create();
        $thread = factory(\App\Thread::class)->create(['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
        
    }
}
