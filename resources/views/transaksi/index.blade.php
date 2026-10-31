@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-history mr-2"></i> Riwayat Transaksi</h3>
    </div>

    <div class="card-body">
        {{-- Pesan Sukses & Error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="icon fas fa-check"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="icon fas fa-exclamation-triangle"></i> 
                {{ session('error') ?? $errors->first() }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Nama Pengguna</th> 
                        <th>Role</th>
                        <th>Metode Pembayaran</th>
                        <th>Total Pembelian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $i => $t)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><span class="font-weight-bold text-primary">{{ $t->invoice_number }}</span></td> 
                        <td>{{ $t->created_at ? \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') : '-' }}</td>
                        
                        {{-- Nama User --}}
                        <td>{{ $t->user ? $t->user->name : 'Sistem' }}</td>
                        
                        {{-- Menampilkan Role --}}
                        <td>
                            @if($t->user)
                                @if($t->user->role == 'admin')
                                    <span class="badge badge-success">Admin</span>
                                @else
                                    <span class="badge badge-info">Karyawan</span>
                                @endif
                            @else
                                <span class="badge badge-secondary">-</span>
                            @endif
                        </td>
                        
                        <td>
                            @if(strtolower($t->payment_method ?? '') == 'qris')
                                <span class="badge badge-info px-2 py-1"><i class="fas fa-qrcode mr-1"></i> QRIS</span>
                            @else
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-money-bill-wave mr-1"></i> Tunai</span>
                            @endif
                        </td>
                        
                        <td class="font-weight-bold">Rp {{ number_format($t->grand_total ?? $t->total_price, 0, ',', '.') }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('transaksi.show', $t->invoice_number) }}" class="btn btn-info btn-sm mr-1">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                
                                <a href="{{ route('transaksi.print', $t->invoice_number) }}" target="_blank" class="btn btn-warning btn-sm mr-1">
                                    <i class="fas fa-print"></i> Cetak
                                </a>
                                
                                <form action="{{ route('transaksi.destroy', $t->invoice_number) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan transaksi {{ $t->invoice_number }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada arsip transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection