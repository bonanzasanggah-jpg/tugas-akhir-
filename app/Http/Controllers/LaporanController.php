<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filterType = $request->input('filter_type', 'semua'); 
        $tanggal    = $request->input('tanggal', date('Y-m-d')); 
        $bulan      = $request->input('bulan', date('Y-m'));     
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');

        $query = Transaksi::with(['product', 'user']);

        // 3. Logika Filter
        if ($filterType == 'hari') {
            $query->whereDate('created_at', $tanggal);
        } elseif ($filterType == 'bulan') {
            $year = date('Y', strtotime($bulan));
            $month = date('m', strtotime($bulan));
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        } elseif ($filterType == 'rentang' && $startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $laporan = $query->latest()->get();
        $totalPendapatan = $laporan->sum('total_price');

        return view('laporan.index', compact('laporan', 'totalPendapatan', 'filterType', 'tanggal', 'bulan', 'startDate', 'endDate'));
    }

    public function print(Request $request)
    {
        $filterType = $request->input('filter_type', 'semua');
        $tanggal    = $request->input('tanggal', date('Y-m-d'));
        $bulan      = $request->input('bulan', date('Y-m'));
        $startDate  = $request->input('start_date');
        $endDate    = $request->input('end_date');

        $query = Transaksi::with(['product', 'user']);
        $periodeCetak = "Semua Waktu";

        if ($filterType == 'hari') {
            $query->whereDate('created_at', $tanggal);
            $periodeCetak = "Tanggal: " . date('d F Y', strtotime($tanggal));
        } elseif ($filterType == 'bulan') {
            $year = date('Y', strtotime($bulan));
            $month = date('m', strtotime($bulan));
            $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            $periodeCetak = "Bulan: " . date('F Y', strtotime($bulan));
        } elseif ($filterType == 'rentang' && $startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $periodeCetak = "Periode: " . date('d M Y', strtotime($startDate)) . " s/d " . date('d M Y', strtotime($endDate));
        }

        $laporan = $query->latest()->get();
        $totalPendapatan = $laporan->sum('total_price');
        $pencetak = Auth::user() ? Auth::user()->name : 'Admin';

        return view('laporan.print', compact('laporan', 'totalPendapatan', 'periodeCetak', 'pencetak'));
    }
}