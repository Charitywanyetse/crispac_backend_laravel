<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');

Route::get('/debug-login', function() {
    $user = App\Models\User::first();
    if ($user) {
        Auth::login($user);
        return redirect('/dashboard');
    }
    return "No user found. Please create one first.";
});    

});

