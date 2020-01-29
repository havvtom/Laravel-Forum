<?php

namespace App;
use Illuminate\Support\Facades\Redis;

trait RecordsVisitsTrait {

	public function recordVisit(){

        Redis::incrby($this->cacheKey(), 1);

        return $this;
    }

    public function visits(){


        return Redis::get($this->cacheKey()) ?: 0;
    }

    public function resetVisits(){

        Redis::del($this->cacheKey());

        return $this;
    }

    protected function cacheKey(){

        return 'threads'.$this->id.'visits';
    }
}