<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterConfirmationController extends Controller
{
    public function index(){

        $user = \App\User::where('confirmation_token', request('token'))->first();

    	if( ! $user ){

                return redirect('/threads')->with('flash', 'Unkown token');
            }   			

    		$user->confirm(); 		
 
    	return redirect('/threads')->with('flash', 'Your account is now confirmed. You may now post to the forum.');                      

    }
}
