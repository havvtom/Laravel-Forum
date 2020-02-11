<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
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

    // public function test_a_user_can_search_threads(){

    //     config(['scout.driver' => 'algolia']);

    //     $search = 'foobar';

    //     factory(\App\Thread::class, 2)->create();

    //     factory(\App\Thread::class, 2)->create(['body' => 'A thread containing {$search} word']);

    //     $results = $this->getJson('/threads/search?q={$search}')->json();

    //     $this->assertCount(2, $results['data']);
    // }
}

//"vendor\bin\phpunit" --filter test_a_user_can_search_threads