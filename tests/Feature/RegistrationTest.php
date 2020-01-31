<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use App\Events\Registered;
use Tests\TestCase;

class RegistrationTest extends TestCase
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

    public function test_an_email_is_sent_upon_registration(){

        Mail::fake();

        event(new Registered($user = factory(\App\User::class)->create()));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    public function test_users_can_fully_confirm_their_email_addresses(){

         $user = [
              'name' => 'Joe',
              'email' => 'testemail@test.com',
              'password' => 'passwordtest',
              'password_confirmation' => 'passwordtest'
            ];

        $response = $this->post('/register', $user);
        $user = \App\User::whereName('Joe')->first();
        
        $this->assertDatabaseHas('users', ['name' => 'Joe']);

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        //let user confirm their account
        $response = $this->get('register/confirm?token='.$user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }
}

//"vendor\bin\phpunit" --filter RegistrationTest
