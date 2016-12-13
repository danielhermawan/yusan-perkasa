<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Alert;

class AuthController extends Controller
{
    public function index()
    {
        return view("Guests.login");
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email'=> 'required',
            'password'=> 'required'
        ]);
        $email = $request->email;
        $password = $request->password;
        $remember = $request->remember;
        if(Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            return redirect('/');
        } else {
            Alert::error('Username atau Password salah')->flash();
            return back()->withInput()->with('login', 'Username atau Password salah');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
