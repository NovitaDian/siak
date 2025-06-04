<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Store method for creating new users
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Password confirmation
            'role' => 'required|string|in:adminsystem,operator,tamu',
        ]);

        // Store plain password temporarily in the session
        session(['plain_password' => $request->password]);

        // Hash the password before saving
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password); // Hash the password
        $user->role = $request->role;
        $user->save();

        return redirect()->route('adminsystem.user.index')->with('success', 'User created successfully!');
    }

    // Update method for editing user details
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // Password confirmation
            'role' => 'required|string|in:adminsystem,operator,tamu',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // If a new password is provided, hash and save it
        if ($request->password) {
            session(['plain_password' => $request->password]); // Store plain password temporarily
            $user->password = Hash::make($request->password);
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->route('adminsystem.user.index')->with('success', 'User updated successfully!');
    }

    // Index method to view the users
    public function index()
    {
        $users = User::all(); // Retrieve all users
        return view('adminsystem.user.index', compact('users'));
    }



    // Show the form to create a new user
    public function create()
    {
        return view('adminsystem.user.create');
    }

    // Show the form to edit a user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('adminsystem.user.edit', compact('user'));
    }

    // Update the user information


    // Delete the user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('adminsystem.user.index')->with('success', 'User deleted successfully!');
    }
}
