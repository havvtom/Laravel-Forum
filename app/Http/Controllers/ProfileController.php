<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfileController extends Controller
{
    public function show(User $user){

    	$threads = $user->threads()->paginate(30);

    	return view('profile.show', ['profileUser' => $user], compact('threads'));
    }
}
