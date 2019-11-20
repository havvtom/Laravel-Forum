<?php

namespace App\Filters;

abstract class FiltersAbstract{

	protected $request, $filters = [];


	public function __construct($request){
		$this->request = $request;
	}

	public function filter($builder){
		foreach ($this->getFilters() as $filter => $value){
			$this->resolveFilter($filter)->filter($builder, $value);
		}
	}

	protected function resolveFilter($filter){
		return new $this->filters[$filter];
	}

	protected function getFilters(){
		return $this->filterFilters($this->filters);
	}

	protected function filterFilters($filters){
		return array_filter($this->request->only(array_keys($this->filters)));
	}
}