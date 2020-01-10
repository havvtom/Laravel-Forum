<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserNotifications extends Controller
{
	public function __construct(){

		$this->middleware('auth');

	}
    public function destroy(User $user, $notificationId){

    	Auth()->user()->notifications()->findorFail($notificationId)->markAsRead();

    }

    public function index(){

    	return Auth()->user()->unreadNotifications;
    }
}
