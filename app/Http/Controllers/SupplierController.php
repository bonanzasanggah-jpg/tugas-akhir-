<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request) {
        $search = trim($request->get('search'));

        $suppliers = Supplier::when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('phone_number', 'LIKE', "%{$search}%")
                             ->orWhere('address', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('supplier.index', compact('suppliers'));
    }

    public function create() {
        return view('supplier.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'         => 'required|string|min:4|max:50',
            'phone_number' => 'required|numeric|digits_between:10,15',
            'address'      => 'nullable|string|min:5|max:100'
        ], [
            'name.required'         => 'Nama supplier wajib diisi.',
            'name.min'              => 'Nama supplier minimal 4 karakter.',
            'name.max'              => 'Nama supplier maksimal 50 karakter.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.numeric'  => 'Nomor telepon harus berupa angka.',
            'phone_number.digits_between' => 'Nomor telepon harus antara 10 sampai 15 digit.',
            'address.min'           => 'Alamat minimal 5 karakter.',
            'address.max'           => 'Alamat maksimal 100 karakter.',
        ]);

        Supplier::create([
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
            'address'      => $request->address,
        ]);
        
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambah.');
    }

    public function edit($id) {
        $supplier = Supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'         => 'required|string|min:4|max:50',
            'phone_number' => 'required|numeric|digits_between:10,15',
            'address'      => 'nullable|string|min:5|max:100'
        ], [
            'name.required'         => 'Nama supplier wajib diisi.',
            'name.min'              => 'Nama supplier minimal 4 karakter.',
            'name.max'              => 'Nama supplier maksimal 50 karakter.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.numeric'  => 'Nomor telepon harus berupa angka.',
            'phone_number.digits_between' => 'Nomor telepon harus antara 10 sampai 15 digit.',
            'address.min'           => 'Alamat minimal 5 karakter.',
            'address.max'           => 'Alamat maksimal 100 karakter.',
        ]);

        Supplier::findOrFail($id)->update([
            'name'         => $request->name,
            'phone_number' => $request->phone_number,
            'address'      => $request->address,
        ]);
        
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diupdate.');
    }

    public function destroy($id) {
        Supplier::findOrFail($id)->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier dihapus.');
    }
}