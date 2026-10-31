<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori dengan fitur pencarian
    public function index(Request $request)
    {
        $search = trim($request->get('search'));

        $categories = Category::when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('categories.index', compact('categories'));
    }

    // Menampilkan form tambah kategori
    public function create()
    {
        return view('categories.create');
    }

    // Menyimpan kategori baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|unique:categories,name|max:255',
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Nama kategori sudah terdaftar.',
            'name.required' => 'Nama kategori wajib diisi.',
        ]);

        Category::create([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Menampilkan form edit
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    // Mengupdate data kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.unique' => 'Nama kategori sudah digunakan.',
        ]);

        $category = Category::findOrFail($id);
        
        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Menghapus kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}