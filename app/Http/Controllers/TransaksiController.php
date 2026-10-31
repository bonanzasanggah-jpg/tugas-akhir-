<?php

namespace App\Http\Controllers;

use App\Models\Transaksi; 
use App\Models\Product;
use App\Models\StockLog; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class TransaksiController extends Controller
{
    public function index() {
        $transaksi = Transaksi::with(['user'])
            ->select('invoice_number', 'created_at', 'user_id', 'payment_method', DB::raw('SUM(total_price) as grand_total'))
            ->groupBy('invoice_number', 'created_at', 'user_id', 'payment_method')
            ->latest()
            ->get();

        return view('transaksi.index', compact('transaksi')); 
    }

    public function show($kode) {

        $kode_bersih = trim(urldecode($kode));

        $referensi = Transaksi::with('user')->where('invoice_number', $kode_bersih)->firstOrFail();

        $semua_barang = Transaksi::with('product')
                            ->where('invoice_number', $kode_bersih)
                            ->get();

        $grand_total = $semua_barang->sum('total_price');

        return view('transaksi.show', compact('referensi', 'semua_barang', 'grand_total'));
    }


    public function print($kode)
    {

        $kode_bersih = trim(urldecode($kode));

        $referensi = Transaksi::where('invoice_number', $kode_bersih)->firstOrFail();
        
        $semua_barang = Transaksi::with('product')
                            ->where('invoice_number', $kode_bersih)
                            ->get();
                            
        $grand_total = $semua_barang->sum('total_price');

        return view('transaksi.print', compact('referensi', 'semua_barang', 'grand_total'));
    }


    public function create() {
        abort(403, 'Akses ditolak! Silakan gunakan menu Kasir.');
    }

    public function store(Request $request) {
        abort(403, 'Akses ditolak! Silakan gunakan menu Kasir.');
    }
    public function destroy($kode) {
        $kode_bersih = trim(urldecode($kode));

        $list_transaksi = Transaksi::where('invoice_number', $kode_bersih)->get();

        if ($list_transaksi->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Data transaksi tidak ditemukan!']);
        }

        DB::beginTransaction();
        try {
            foreach ($list_transaksi as $transaksi) {
                $product = Product::find($transaksi->product_id);
                
                if ($product) {
                    $product->increment('stock', $transaksi->quantity);

                    StockLog::create([
                        'product_id' => $product->id,
                        'type'       => 'masuk',
                        'quantity'   => $transaksi->quantity,
                        'note'       => 'Pembatalan transaksi (Admin): ' . $kode_bersih
                    ]);
                }
                $transaksi->delete();
            }

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi ' . $kode_bersih . ' berhasil dibatalkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus arsip: ' . $e->getMessage()]);
        }
    }
}