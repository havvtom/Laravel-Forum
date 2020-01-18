<?php

namespace App;
use Illuminate\Support\Str;

class Spam{

	public function detect($body){

		$this->detectInvalidKeyWords($body);

		return false;
	}

	public function detectInvalidKeyWords($body){

		$invalidKeyWords = [

			'yahoo customer support'

		];

			foreach ($invalidKeyWords as $keyword) {
				
				if(Strrpos($body, $keyword) !== false){

	            throw new \Exception("Your message contains spam.");
	            
	        }
		}
	}

}