<?php

namespace App\Filters\Threads;
use App\User;

class ByFilter {

	public function filter($builder, $value){

		$userId = User::where('name', $value)->first()->id;
		return $builder->where('user_id', $userId); 
	}
}