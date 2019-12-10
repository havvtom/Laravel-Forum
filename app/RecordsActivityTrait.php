<?php

namespace App;

trait RecordsActivityTrait{

	public static function bootRecordsActivityTrait(){
		foreach (static::getActivitiesToRecord() as $event){
				static::$event(function($model) use ($event){
           		$model->recordActivity($event);
        	});
		}

		static::deleting(function($model){
			$model->activities()->delete();
		});
		
	}

	public function recordActivity($event){
		if(Auth()->check()){
 
           $this->activities()->create([

           		'user_id' => Auth()->user()->id,
           		'type' => $this->getActivityType('created')

                       ]);}return;

	}

	public static function getActivitiesToRecord(){
		return ['created'];
	}

	public function getActivityType($event){
		return $event.'_'. strtolower((new \ReflectionClass($this))->getShortName());
	}

	public function activities(){
		return $this->morphMany('App\Activity', 'subjectable');
	}

}