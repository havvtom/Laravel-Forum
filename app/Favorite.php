<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public function favoritable(){
    	return $this->morphTo();
    }
}
