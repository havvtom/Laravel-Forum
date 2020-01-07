<?php
namespace App\Filters\Threads;
use App\Thread;

class UnAnsweredFilter {

	public function filter($builder, $value){

		return $builder->where('replies_count', 0); 
	}
}