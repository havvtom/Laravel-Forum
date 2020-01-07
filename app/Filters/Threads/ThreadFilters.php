<?php

namespace App\Filters\Threads;
use App\Filters\FiltersAbstract;
use App\Filters\Threads\ByFilter;
use App\Filters\Threads\PopularFilter;

class ThreadFilters extends FiltersAbstract{

	protected $filters = [

		'by' => ByFilter::class,
		'popular' => PopularFilter::class,
		'unanswered' =>UnAnsweredFilter::class,
	];
}