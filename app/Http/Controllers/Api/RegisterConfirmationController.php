<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterConfirmationController extends Controller
{
    public function index(){

    	try {

    		$user = \App\User::where('confirmation_token', request('token'))

    			->firstOrFail()

    			->confirm(); 
    		
    	} catch (\Exception $e) {
    		
    		return redirect('/threads')->with('flash', 'Unkown token');

    	}
    	 

    	return redirect('/threads')->with('flash', 'Your account is now confirmed. You may now post to the forum.');                      

    }
}
