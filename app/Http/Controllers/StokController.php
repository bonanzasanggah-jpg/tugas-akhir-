<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StokController extends Controller
{
    /**
     * Menampilkan daftar riwayat stok
     */
    public function index()
    {
        $stoks = Stok::with(['product', 'user'])->latest()->get();
        return view('stok.index', compact('stoks'));
    }

    /**
     * Menampilkan form input stok
     */
    public function create()
    {
        $products = Product::all();
        return view('stok.create', compact('products'));
    }

    /**
     * Menyimpan perubahan stok
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:IN,OUT',
            'quantity'   => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();
            $data['user_id'] = Auth::id(); 

            Stok::create($data);

            $product = Product::find($request->product_id);
            
            // Logika penambahan atau pengurangan stok
            if ($request->type == 'IN') {
                $product->increment('stock', $request->quantity);
            } else {
                if ($product->stock < $request->quantity) {
                    throw new \Exception("Stok barang tidak mencukupi!");
                }
                $product->decrement('stock', $request->quantity);
            }

            DB::commit();
            return redirect()->route('stok.index')->with('success', 'Stok berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
} 