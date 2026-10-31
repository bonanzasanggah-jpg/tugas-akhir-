<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    // Menampilkan form edit profil sendiri
    public function edit() 
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }
    public function editUser($id) 
    {
        $user = User::findOrFail($id);
        return view('profile.edit', ['user' => $user]);
    }

    public function update(Request $request, $id = null) 
    {
        $user = $id ? User::findOrFail($id) : Auth::user();
        
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'name'     => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ], [
            // Pesan kustom dalam Bahasa Indonesia
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username ini sudah digunakan oleh akun lain.',
            'name.required'      => 'Nama lengkap wajib diisi.',
            'password.min'       => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->username = $request->username;
        $user->name = $request->name;

        if($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Data profil berhasil diperbarui!');
    }
}