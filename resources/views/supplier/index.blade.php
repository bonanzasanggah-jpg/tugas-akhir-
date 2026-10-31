@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-truck mr-2"></i> Daftar Supplier</h3>
        
        <div class="card-tools d-flex align-items-center">
            {{-- Form Pencarian Supplier --}}
            <form action="{{ route('supplier.index') }}" method="GET" class="mr-2 mb-0">
                <div class="input-group input-group-sm" style="width: 220px;">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari supplier / telp..." 
                           value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-default" type="submit">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('supplier.index') }}" class="btn btn-default">
                                <i class="fas fa-times text-danger"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <a href="{{ route('supplier.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Supplier
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
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th style="width: 180px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $i => $s)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $s->name }}</td>
                        <td><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->phone_number) }}" target="_blank"><i class="fab fa-whatsapp text-success mr-1"></i>{{ $s->phone_number }}</a></td>
                        <td>{{ $s->address }}</td>
                        <td class="text-center">
                            <a href="{{ route('supplier.edit', $s->id) }}" class="btn btn-warning btn-sm mr-1">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('supplier.destroy', $s->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus supplier ini?')">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Data supplier tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection