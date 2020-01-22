<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AvatarTest extends TestCase
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

    public function test_only_members_can_add_avatars(){

        $this->json('POST', 'api/users/1/avatar')->assertStatus(401);

    }

    public function test_an_avatar_must_be_provided(){

        $this->be($user = factory(\App\User::class)->create());

        $this->json('POST', 'api/users/'.$user->id.'/avatar', [

            'avatar' => 'not an image'

        ])->assertStatus(422);
    }

    public function test_a_user_may_add_an_avatar(){

        $this->be($user = factory(\App\User::class)->create());

        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->json('POST', 'api/users/'.$user->id.'/avatar', [

            'avatar' => $file

        ]);

        Storage::disk('public')->assertExists('avatars/'.$file->hashName());

        $this->assertEquals('avatars/'.$file->hashName(), Auth()->user()->avatar_path);

    }
}

// "vendor\bin\phpunit" --filter AvatarTest