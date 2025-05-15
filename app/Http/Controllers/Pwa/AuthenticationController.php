<?php

namespace App\Http\Controllers\Pwa;

use App\Helpers\CodeHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Modules\Space\Models\Space;

class AuthenticationController extends Controller
{

    public function signin(){
        // echo "Hello PWA";
        return view('pwa/auth/signin');
    }


    public function signup(){
        // echo "Hello PWA";
        return view('pwa/auth/signup');
    }


    public function home(){
        return view('pwa/home');
    }

}
