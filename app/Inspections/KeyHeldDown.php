<?php

namespace App\Inspections;

class KeyHeldDown{

	public function detect($body){

		if(preg_match('/(.)\\1{3}/', $body)){

			throw new \Exception("Key might have been held down.");
		}

	}
}