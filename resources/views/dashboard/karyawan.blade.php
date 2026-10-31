@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="callout callout-success">
                <h5><i class="fas fa-user-check"></i> Selamat Datang, {{ auth()->user()->name }}!</h5>
                <p>Halo, selamat bekerja kembali. Anda saat ini login sebagai <strong>Karyawan (Kasir)</strong>. 
                   Silakan melayani pelanggan dengan ramah dan pastikan transaksi hari ini, 
                   <strong>{{ \Carbon\Carbon::now()->format('d F Y') }}</strong>, berjalan lancar.</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>Kasir</h3>
                    <p>Mulai Transaksi Baru</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cash-register"></i>
                </div>
                <a href="{{ route('kasir.index') }}" class="small-box-footer">
                    Buka Halaman Kasir <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>Riwayat</h3>
                    <p>Arsip Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-history"></i>
                </div>
                <a href="{{ route('transaksi.index') }}" class="small-box-footer">
                    Lihat Arsip Transaksi <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection