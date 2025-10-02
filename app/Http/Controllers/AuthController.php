<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function daftar(){

        return view('Auth.daftar');
    }

    public function login(){
        $title = "Login";
        
        return view('Auth.login', compact("title"));
    }
}
