@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-box-open mr-2"></i> Daftar Barang</h3>
        
        <div class="card-tools d-flex align-items-center">
            
            <form action="{{ route('barang.index') }}" method="GET" class="mr-2 mb-0">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari barang atau kategori..." 
                           value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-default" type="submit">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('barang.index') }}" class="btn btn-default">
                                <i class="fas fa-times text-danger"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Barang
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="icon fas fa-check mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="barangTable">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 30px;" class="text-center">No</th>
                        <th>SKU / Barcode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Tgl Kadaluarsa</th>
                        <th>Status</th>
                        <th style="width: 180px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products->sortBy('created_at') as $product)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        
                        <td>
                            @if(!empty($product->sku))
                                <code>{{ $product->sku }}</code><br>
                                <small class="text-muted">{{ $product->barcode ?? 'Stock Keeping Unit' }}</small>
                            @else
                                <code>{{ $product->barcode }}</code><br>
                                <small class="text-muted">Barcode</small>
                            @endif
                        </td>

                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ $product->supplier->name ?? '-' }}</td>
                        <td>Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                        <td>
                            @if($product->stock <= $product->min_stock)
                                <span class="badge badge-danger p-2">{{ $product->stock }}</span>
                            @else
                                <span class="badge badge-success p-2">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td>{{ $product->expired_date ? \Carbon\Carbon::parse($product->expired_date)->format('d/m/Y') : '-' }}</td>
                        
                        <td>
                            @php
                                $status = 'aman';
                                if ($product->expired_date) {
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($product->expired_date), false);
                                    if ($daysLeft < 0) { $status = 'kedaluwarsa'; } 
                                    elseif ($daysLeft <= 30) { $status = 'hampir kedaluwarsa'; }
                                }
                            @endphp

                            @if($status == 'aman')
                                <span class="badge badge-success">Aman</span>
                            @elseif($status == 'hampir kedaluwarsa')
                                <span class="badge badge-warning text-white">Hampir Kedaluwarsa</span>
                            @else
                                <span class="badge badge-danger">Kedaluwarsa</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                            <a href="{{ route('barang.edit', $product->id) }}" class="btn btn-warning btn-sm mr-1">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('barang.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data barang ini?')">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">Data barang tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection