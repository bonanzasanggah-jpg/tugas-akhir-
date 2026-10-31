@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-file-invoice-dollar mr-2"></i> Laporan Keuangan Penjualan</h3>
    </div>

    <div class="card-body">
        {{-- FORM FILTER --}}
        <form action="{{ route('laporan.index') }}" method="GET" class="mb-4 bg-light p-3 rounded border">
            <div class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="text-muted">Berdasarkan</label>
                    <select name="filter_type" id="filterType" class="form-control">
                        <option value="semua" {{ $filterType == 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="hari" {{ $filterType == 'hari' ? 'selected' : '' }}>Harian</option>
                        <option value="bulan" {{ $filterType == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="rentang" {{ $filterType == 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                    </select>
                </div>

                {{-- Input Tanggal (Harian) --}}
                <div class="col-md-3 mb-2 filter-input" id="inputHari" style="display: none;">
                    <label class="text-muted">Pilih Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>

                {{-- Input Bulan (Bulanan) --}}
                <div class="col-md-3 mb-2 filter-input" id="inputBulan" style="display: none;">
                    <label class="text-muted">Pilih Bulan</label>
                    <input type="month" name="bulan" class="form-control" value="{{ $bulan }}">
                </div>

                {{-- Input Rentang (Tanggal Awal & Akhir) --}}
                <div class="col-md-3 mb-2 filter-input" id="inputRentangStart" style="display: none;">
                    <label class="text-muted">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3 mb-2 filter-input" id="inputRentangEnd" style="display: none;">
                    <label class="text-muted">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>

                <div class="col-md-12 mt-3">
                    <button type="submit" class="btn btn-primary mr-1"><i class="fas fa-filter"></i> Terapkan Filter</button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-secondary mr-2">Reset</a>
                    
                    {{-- Tombol Cetak yang membawa data filter ke rute laporan.print --}}
                    <button type="submit" formaction="{{ route('laporan.print') ?? url('laporan/print') }}" formtarget="_blank" class="btn btn-warning float-right font-weight-bold">
                        <i class="fas fa-print mr-1"></i> Cetak Laporan
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Barang</th>
                        <th>Metode Bayar</th> 
                        <th>Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $i => $t)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><span class="font-weight-bold">{{ $t->invoice_number }}</span></td>
                        <td>{{ $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $t->product ? $t->product->name : 'Barang tidak ditemukan' }}</td>
                        
                        {{-- Logika Menampilkan Metode Bayar --}}
                        <td>
                            @if(strtolower($t->payment_method ?? '') == 'qris')
                                <span class="badge badge-info px-2 py-1"><i class="fas fa-qrcode mr-1"></i> QRIS</span>
                            @else
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-money-bill-wave mr-1"></i> Tunai</span>
                            @endif
                        </td>

                        <td class="font-weight-bold">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data transaksi pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-light">
                    <tr class="font-weight-bold">
                        <td colspan="5" class="text-right h5 mb-0 pt-3 pb-3">Total Pendapatan:</td>
                        <td class="text-success h5 mb-0 pt-3 pb-3">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterType = document.getElementById('filterType');
        const inputs = document.querySelectorAll('.filter-input');

        function toggleInputs() {
            inputs.forEach(el => el.style.display = 'none');
            
            const val = filterType.value;
            if (val === 'hari') {
                document.getElementById('inputHari').style.display = 'block';
            } else if (val === 'bulan') {
                document.getElementById('inputBulan').style.display = 'block';
            } else if (val === 'rentang') {
                document.getElementById('inputRentangStart').style.display = 'block';
                document.getElementById('inputRentangEnd').style.display = 'block';
            }
        }


        filterType.addEventListener('change', toggleInputs);

        toggleInputs(); 
    });
</script>
@endpush
@endsection