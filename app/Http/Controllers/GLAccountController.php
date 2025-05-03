<?php

namespace App\Http\Controllers;

use App\Models\Gl_Account;
use Illuminate\Http\Request;

class GLAccountController extends Controller
{
    public function index()
    {
        $gls = Gl_Account::all();
        return view('adminsystem.master.glaccount.index', compact('gls'));
    }

    public function create()
    {
        return view('adminsystem.master.glaccount.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'gl_code' => 'required|string|max:50|unique:gl_account,gl_code',
            'gl_name' => 'required|string|max:100',
            'description' => 'required|string|max:100',
        ]);


        Gl_Account::create([
            'gl_code' => $request->input('gl_code'),
            'gl_name' => $request->input('gl_name'),
            'description' => $request->input('description'),
        ]);

        // Redirect back with a success message
        return redirect()->route('adminsystem.master.glaccount.index')->with('success', ' berhasil dibuat.');
    }


    public function edit($id)
    {
        $gl = Gl_Account::findOrFail($id);
        return view('adminsystem.master.glaccount.edit', compact('gl'));
    }

    public function update(Request $request, $id)
    {
        $gl = Gl_Account::findOrFail($id);

        $request->validate([
            'gl_code' => 'required|string|unique|max:50',
            'gl_name' => 'required|string|max:100',
            'description' => 'required|string|max:100',

        ]);

        $gl->update($request->all());

        return redirect()->route('adminsystem.master.glaccount.index')->with('success', 'GL Account berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gl = Gl_Account::findOrFail($id);
        $gl->delete();

        return redirect()->route('adminsystem.master.glaccount.index')->with('success', 'Purchase Request berhasil dihapus.');
    }
}
