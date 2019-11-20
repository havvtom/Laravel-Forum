<?php

namespace App\Filters\Threads;

class PopularFilter {

	public function filter($builder, $value){
		$builder->getQuery()->orders = [];
		return $builder->orderBy('replies_count', 'DESC');
	}
}