<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search'));

        $products = Product::with(['category', 'supplier'])
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('sku', 'LIKE', "%{$search}%")
                      ->orWhere('barcode', 'LIKE', "%{$search}%")
                      ->orWhereHas('category', function ($catQuery) use ($search) {
                          $catQuery->where(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($search) . '%');
                      });
                });
            })
            ->latest()
            ->get();

        return view('barang.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('barang.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            'sku'          => 'nullable|alpha_num|max:10|unique:products,sku',
            'barcode'      => 'nullable|numeric|min_digits:13|unique:products,barcode',
            'name'         => 'required|string|min:4|max:255',
            'buy_price'    => 'required|numeric|min:0',
            'sell_price'   => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'min_stock'    => 'required|integer|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $data = $request->all();

        $data['sku'] = $request->filled('sku') ? strtoupper($request->sku) : null;
        $data['barcode'] = $request->filled('barcode') ? $request->barcode : null;
        $data['expired_status'] = $this->getExpiredStatus($request->expired_date);

        Product::create($data);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('barang.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            'sku'          => 'nullable|alpha_num|max:10|unique:products,sku,' . $id,
            'barcode'      => 'nullable|numeric|min_digits:13|unique:products,barcode,' . $id,
            'name'         => 'required|string|min:4|max:255',
            'buy_price'    => 'required|numeric|min:0',
            'sell_price'   => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'min_stock'    => 'required|integer|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->all();

        $data['sku'] = $request->filled('sku') ? strtoupper($request->sku) : null;
        $data['barcode'] = $request->filled('barcode') ? $request->barcode : null;
        $data['expired_status'] = $this->getExpiredStatus($request->expired_date);
        
        $product->update($data);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus!');
    }

    private function getExpiredStatus($expired_date)
    {
        if (!$expired_date) return 'aman';
        
        $daysLeft = Carbon::now()->diffInDays(Carbon::parse($expired_date), false);
        
        if ($daysLeft < 0) {
            return 'kedaluwarsa';
        } 
        
        if ($daysLeft <= 30) {
            return 'hampir kedaluwarsa';
        }
        
        return 'aman';
    }

    public function findBySku(Request $request)
    {
        $product = Product::with('category')
            ->where('sku', $request->sku)
            ->first();

        if ($product) {
            return response()->json($product);
        }
        return response()->json(['message' => 'Produk tidak ditemukan'], 404);
    }
}