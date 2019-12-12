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
        $user = factory(\App\User::class)->create();
        $this->be($user);

        $reply = factory(\App\Reply::class)->create();

        $response = $this->post('/replies/'.$reply->id.'/favorites');
        $response->assertStatus(200);

        $this->assertCount(1, $reply->favorites);
    }

    public function test_an_authenticated_user_can_unfavorite_any_reply(){
        $user = factory(\App\User::class)->create();
        $this->be($user);

        $reply = factory(\App\Reply::class)->create();

        $response = $this->post('/replies/'.$reply->id.'/favorites');
        
        $this->assertCount(1, $reply->favorites);

        $response = $this->delete('/replies/'.$reply->id.'/favorites');
        $response->assertStatus(200);
        $this->assertCount(0, $reply->fresh()->favorites);

    }

    public function test_a_user_can_only_favorite_a_reply_once(){
       $user = factory(\App\User::class)->create();
        $this->be($user);

        $reply = factory(\App\Reply::class)->create();

        $this->post('/replies/'.$reply->id.'/favorites');
        $this->post('/replies/'.$reply->id.'/favorites');

        $this->assertCount(1, $reply->favorites); 
    }
}
