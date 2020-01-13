<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_it_validates_spam(){

    	$spam = new \App\Inspections\Spam();

    	$this->assertFalse($spam->detect('Innocent reply'));

    	// $this->expectException('Exception');

    	// $spam->detect('Yahoo Customer Support');
    }

    public function test_it_checks_for_any_key_held_down(){

        $spam = new \App\Inspections\Spam(); 

        $this->expectException('Exception');

        $spam->detect('Hello world aaaaaa');


    }
}
 //"vendor\bin\phpunit" --filter SpamTest