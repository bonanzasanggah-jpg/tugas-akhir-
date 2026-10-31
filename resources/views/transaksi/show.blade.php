@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title mt-1"><i class="fas fa-file-invoice mr-2"></i> Detail Transaksi: <strong>{{ $referensi->invoice_number }}</strong></h3>
            </div>
            
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 col-6">
                        <h6 class="text-muted mb-2 font-weight-bold">Informasi Pembayaran:</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th style="width: 130px; padding-left: 0;">Nama Pengguna</th>
                                <td>: {{ $referensi->user->name ?? 'Sistem' }}</td>
                            </tr>
                            <tr>
                                <th style="padding-left: 0;">Role</th>
                                <td>: 
                                    @if($referensi->user)
                                        @if($referensi->user->role == 'admin')
                                            <span class="badge badge-success">Admin</span>
                                        @else
                                            <span class="badge badge-info">Karyawan</span>
                                        @endif
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th style="padding-left: 0;">Tanggal</th>
                                <td>: {{ $referensi->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th style="padding-left: 0;">Metode</th>
                                <td>: <span class="badge badge-info">{{ ucfirst($referensi->payment_method) }}</span></td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6 col-6">
                        <h6 class="text-muted mb-2 font-weight-bold">Ringkasan Uang:</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th style="width: 140px; padding-left: 0;">Nominal Diterima</th>
                                <td>: Rp {{ number_format($referensi->paid_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th style="padding-left: 0;">Kembalian</th>
                                <td>: Rp {{ number_format($referensi->change_amount, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h6 class="text-muted mb-2 font-weight-bold">Daftar Barang Dibeli:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Nama Barang</th>
                                <th class="text-right">Harga Satuan</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($semua_barang as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->product->name ?? 'Barang telah dihapus' }}</td>
                                <td class="text-right">Rp {{ number_format($item->product->sell_price ?? 0, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right font-weight-bold">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-light">
                                <th colspan="4" class="text-right h5 mb-0 pt-3 pb-3">Total Uang:</th>
                                <th class="text-right text-danger h5 mb-0 pt-3 pb-3">Rp {{ number_format($grand_total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white">
                <a href="{{ route('transaksi.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat Transaksi </a>
            
                <a href="{{ route('transaksi.print', $referensi->invoice_number) }}" target="_blank" class="btn btn-warning float-right">
                    <i class="fas fa-print mr-1"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>
</div>
@endsection