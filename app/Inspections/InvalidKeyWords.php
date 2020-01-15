<?php

namespace App\Inspections;

class InvalidKeywords{

	protected $invalidKeyWords = [

			'yahoo customer support'

		];

	public function detect($body){

			foreach ($this->invalidKeyWords as $keyword) {
				
				if(Strrpos($body, $keyword) !== false){

	            throw new \Exception("Your message contains spam.");

	            
	        }
		}
	}
}
