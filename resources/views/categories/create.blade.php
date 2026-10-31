@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-plus-circle mr-2"></i> Tambah Kategori Baru</h3>
    </div>
    
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="card-body">
            {{-- Pesan Error Validasi (Desain Ringkas) --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show py-2 px-3" style="font-size: 14px;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="padding: 0.5rem 1rem;">&times;</button>
                    <strong><i class="fas fa-exclamation-circle mr-1"></i> Mohon periksa kembali:</strong>
                    <ul class="mb-0 pl-3 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Nama Kategori --}}
            <div class="form-group">
                <label>Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" placeholder="Contoh: Minuman" maxlength="255" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Deskripsi Kategori (Baru) --}}
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3" placeholder="Masukkan keterangan singkat tentang kategori ini" maxlength="500">{{ old('description') }}</textarea>
                <small class="text-muted">Maksimal 500 karakter.</small>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        
        <div class="card-footer bg-light">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan Kategori
            </button>
            <a href="{{ route('categories.index') }}" class="btn btn-default ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection