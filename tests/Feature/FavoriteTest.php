<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteTest extends TestCase
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

    public function test_an_authenticated_user_can_favorite_any_reply(){

        $reply = factory(\App\Reply::class)->create();

        $response = $this->post('replies/'.$reply->id.'/favorites');
        $response->assertStatus(200);

        $this->assertCount(1, $reply->favorites);
    }
}
