<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaksi;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('kasir.index', compact('products'));
    }

    public function getProductData(Request $request)
    {
        $product = Product::where('sku', $request->sku)->first();
        if ($product) {
            return response()->json(['success' => true, 'data' => $product]);
        }
        return response()->json(['success' => false, 'message' => 'Barang tidak ditemukan!']);
    }

    public function store(Request $request)
    {
        $cart = json_decode($request->cart_data, true);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang pesanan masih kosong!');
        }

        $invoice = 'INV-' . time(); 

        DB::beginTransaction();
        try {
            foreach ($cart as $item) {
                Transaksi::create([
                    'invoice_number' => $invoice,
                    'product_id'     => $item['id'],
                    'user_id'        => auth()->id(),
                    'quantity'       => $item['qty'],
                    'total_price'    => $item['harga'] * $item['qty'],
                    'paid_amount'    => $request->paid_amount,
                    'change_amount'  => $request->change_amount,
                    'payment_method' => $request->payment_method,
                    'status'         => 'selesai'
                ]);

                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['qty']);
                    
                    StockLog::create([
                        'product_id' => $product->id,
                        'type'       => 'keluar',
                        'quantity'   => $item['qty'],
                        'note'       => 'Terjual via Kasir (Inv: ' . $invoice . ')'
                    ]); 
                }
            }

            DB::commit();
            
            return redirect()->route('kasir.index')->with([
                'success' => 'Transaksi berhasil diproses!',
                'invoice' => $invoice,
                'change_amount' => $request->change_amount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            dd("TERJADI ERROR: " . $e->getMessage());
        }
    }
}