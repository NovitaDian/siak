<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('session.register');
    }

    public function store()
    {
        $validated = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:5', 'max:20'],
            'agreement' => ['accepted']
        ]);
    
        // Jangan simpan 'agreement' ke database
        $attributes = collect($validated)->except('agreement')->toArray();
    
        $attributes['password'] = bcrypt($attributes['password']);
        $attributes['role'] = 'tamu';

        // Buat user tanpa login otomatis
        $user = User::create($attributes);
    
        session()->flash('success', 'Your account has been created.');
    
        // Redirect ke halaman login, tidak auto-login
        return redirect('/login');
    }
    
    
}
