<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        // dd("poke");
        Auth::logout();
        Session::flush();
        session()->flash('success', 'Anda telah logout.');
        return redirect()->route('login');
    }
}
