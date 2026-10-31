@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <!-- Ikon diganti menjadi fas fa-th-list agar sesuai dengan sidebar -->
        <h3 class="card-title mt-1"><i class="fas fa-th-list mr-2"></i> Data Kategori</h3>
        
        <div class="card-tools d-flex align-items-center">
            {{-- Form Pencarian Kategori --}}
            <form action="{{ route('categories.index') }}" method="GET" class="mr-2 mb-0">
                <div class="input-group input-group-sm" style="width: 220px;">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari kategori..." 
                           value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-default" type="submit">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('categories.index') }}" class="btn btn-default">
                                <i class="fas fa-times text-danger"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Kategori
            </a>
        </div>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;" class="text-center">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th> 
                        <th style="width: 180px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm mr-1">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Data kategori tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection