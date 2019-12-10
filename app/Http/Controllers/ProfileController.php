<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Activity;

class ProfileController extends Controller
{
    public function show(User $user){
    	
    	$activities = Activity::feed($user);

    	return view('profile.show', ['profileUser' => $user], compact('activities'));
    }
}
