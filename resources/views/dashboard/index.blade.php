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

    {{-- Row Fitur Analitik & Peringatan (Khusus Admin) --}}
    <div class="row">
        {{-- Grafik Penjualan 7 Hari Terakhir --}}
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Grafik Penjualan (7 Hari Terakhir)</h3>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <canvas id="salesChart" 
                        data-labels='{!! json_encode($chartLabels ?? []) !!}' 
                        data-values='{!! json_encode($chartValues ?? []) !!}' 
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;">
                    </canvas>
                </div>
            </div>
        </div>

        {{-- 5 Produk Terlaris --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-star text-warning mr-1"></i> 5 Produk Terlaris</h3>
                </div>
                <div class="card-body p-0 d-flex flex-column justify-content-center">
                    <ul class="list-group list-group-flush w-100">
                        @forelse($topProducts ?? [] as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item->product->name ?? 'Produk Dihapus' }}
                                <span class="badge badge-primary badge-pill">Terjual: {{ $item->total_sold }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada data penjualan minggu ini.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        {{-- Peringatan Stok Menipis --}}
        <div class="col-lg-6">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-exclamation-triangle text-warning mr-1"></i> Peringatan Stok Menipis</h3>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 300px;">
                    <table class="table table-striped table-valign-middle table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Stok Tersisa</th>
                                <th>Batas Minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts ?? [] as $lowStock)
                            <tr>
                                <td>{{ $lowStock->name }}</td>
                                <td class="text-danger font-weight-bold">{{ $lowStock->stock }}</td>
                                <td>{{ $lowStock->min_stock }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Semua stok barang dalam kondisi aman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Peringatan Status Kedaluwarsa --}}
        <div class="col-lg-6">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-times text-danger mr-1"></i> Peringatan Kedaluwarsa</h3>
                </div>
                <div class="card-body table-responsive p-0" style="max-height: 300px;">
                    <table class="table table-striped table-valign-middle table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Tgl Kedaluwarsa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expiredProducts ?? [] as $expired)
                            <tr>
                                <td>{{ $expired->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($expired->expired_date)->format('d-m-Y') }}</td>
                                <td>
                                    @if($expired->expired_status == 'kadaluarsa')
                                        <span class="badge badge-danger">Kedaluwarsa</span>
                                    @else
                                        <span class="badge badge-warning">Hampir Kedaluwarsa</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada produk yang mendekati masa kedaluwarsa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart');
        if (ctx) {
            const labels = JSON.parse(ctx.dataset.labels);
            const values = JSON.parse(ctx.dataset.values);

            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels, 
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: values,
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0, 123, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        pointBackgroundColor: '#007bff',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    });
</script>
@endsection