<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan daftar semua karyawan
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Menampilkan form tambah karyawan
    public function create()
    {
        return view('users.create');
    }

    // Menyimpan data karyawan baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|min:6',
            'role'     => 'required'
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    // Menghapus karyawan
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}