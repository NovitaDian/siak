<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required' 
        ]);

        if(Auth::attempt($attributes))
        {
            session()->regenerate();
            return redirect('home')->with(['success'=>'Selamat Datang.']);
        }
        else{

            return back()->withErrors(['email'=>'Email atau Password Salah.']);
        }
    }
    function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'email tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ]);
        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($infologin)) {
            if (Auth::user()->role == 'adminsystem') {
                return redirect('/adminsystem/home');
            } elseif (Auth::user()->role == 'operator') {
                return redirect('/operator/home');
            } elseif (Auth::user()->role == 'tamu') {
                return redirect('/tamu/home');
            }
        } else {
            return redirect('')->withErrors('Email atau Password salah ');
        }
    }

    
    public function destroy()
    {

        Auth::logout();

        return redirect('/login')->with(['success'=>'Anda Telah Keluar.']);
    }
}
