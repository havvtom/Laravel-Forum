<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    protected function setUp() :void{

        parent::setUp();

        Redis::del('trending_threads');
    }

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_it_increments_a_thread_score_everytime_its_read(){

        $this->assertCount(0, Redis::zrevrange('n_threads', 0, -1));

        $thread = factory(\App\Thread::class)->create();

        $this->call('GET', $thread->path());

        $this->assertCount(1, $trending = Redis::zrevrange('trending_threads', 0, -1));

        

    }
}

//"vendor\bin\phpunit" --filter TrendingThreadsTest