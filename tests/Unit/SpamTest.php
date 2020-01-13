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

    	$spam = new \App\Spam();

    	$this->assertFalse($spam->detect('Innocent reply'));

    	$this->expectException('Exception');

    	$spam->detect('Yahoo Customer Support');
    }
}
 //"vendor\bin\phpunit" --filter SpamTest