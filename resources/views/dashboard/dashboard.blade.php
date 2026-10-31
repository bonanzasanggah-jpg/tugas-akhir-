@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    {{-- Header Selamat Datang dengan Sapaan Dinamis --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="callout callout-info">
                <h5>
                    <i class="fas fa-info-circle"></i> 
                    Selamat Datang, {{ auth()->user()->role == 'admin' ? 'Admin' : 'Karyawan' }} {{ auth()->user()->name }}!
                </h5>
                <p>Halo, selamat bekerja kembali. Ini adalah ringkasan kinerja Minimarket Bonanza untuk hari ini, 
                    <strong>{{ \Carbon\Carbon::now()->format('d F Y') }}</strong>. 
                    Pastikan data transaksi dan stok selalu diperbarui untuk menjaga akurasi laporan.</p>
            </div>
        </div>
    </div>

    {{-- Row Statistik --}}
    <div class="row">
        {{-- Transaksi Hari Ini --}}
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalTransaksiHariIni ?? 0 }}</h3>
                    <p>Transaksi Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <a href="{{ route('transaksi.index') }}" class="small-box-footer">
                    Lihat Transaksi <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Pendapatan Hari Ini --}}
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
                    <p>Pendapatan Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <a href="{{ route('laporan.index') }}" class="small-box-footer">
                    Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Total Data Barang --}}
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalDataBarang ?? 0 }}</h3>
                    <p>Total Data Barang</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <a href="{{ route('barang.index') }}" class="small-box-footer">
                    Lihat Data Barang <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection