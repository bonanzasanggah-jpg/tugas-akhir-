<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\User;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1.LOGIN KARYAWAN
        if (Auth::user()->role === 'karyawan') {
            return view('dashboard.karyawan'); 
        }

        // 2.LOGIN ADMIN
        $hariIni = Carbon::today();

        $totalTransaksiHariIni = Transaksi::whereDate('created_at', $hariIni)
                                          ->distinct()
                                          ->count('invoice_number');

        // Menghitung total pendapatan
        $pendapatanHariIni = Transaksi::whereDate('created_at', $hariIni)->sum('total_price');
        
        // Menghitung total seluruh data barang saat ini
        $totalDataBarang = Product::count();


        // === [FITUR DASHBOARD ADMIN] ===
        
        // A. Peringatan Stok Menipis (Stok <= Batas Minimum)
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->get();

        // B. Status Kedaluwarsa Produk
        $expiredProducts = Product::whereIn('expired_status', ['hampir kadarluasa', 'kadaluarsa'])->get();

        // C. Produk Terlaris (7 Hari Terakhir)
        $topProducts = Transaksi::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->where('status', 'selesai')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->with('product')
            ->get();

$salesData = Transaksi::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Format data agar mudah dibaca oleh Chart.js di view
        $chartLabels = $salesData->pluck('date');
        $chartValues = $salesData->pluck('total_revenue');

        // Mengirimkan semua variabel (lama & baru) ke view dashboard.index
        return view('dashboard.index', compact(
            'totalTransaksiHariIni', 
            'pendapatanHariIni', 
            'totalDataBarang',
            'lowStockProducts',
            'expiredProducts',
            'topProducts',
            'chartLabels',
            'chartValues'
        ));
    }

    public function karyawan()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses.');
        }

        $users = User::all(); 
        return view('dashboard.karyawan', compact('users'));
    }
}