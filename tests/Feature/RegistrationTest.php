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
}

//"vendor\bin\phpunit" --filter RegistrationTest
//$user = factory(\App\User::class)->create()