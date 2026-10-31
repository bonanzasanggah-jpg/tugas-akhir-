@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-truck mr-2"></i> Tambah Supplier Baru</h3>
    </div>

    <form action="{{ route('supplier.store') }}" method="POST">
        @csrf
        <div class="card-body">
            {{-- Nama Supplier --}}
            <div class="form-group">
                <label for="name">Nama Supplier <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       id="name" value="{{ old('name') }}" placeholder="Contoh: PT. Sumber Makmur" maxlength="100" required>
                <small class="text-muted">Maksimal 100 karakter.</small>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Telepon --}}
            <div class="form-group">
                <label for="phone_number">Telepon <span class="text-danger">*</span></label>
                <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                       id="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 081234567890" minlength="10" maxlength="15" pattern="\d+" title="Masukkan 10-15 digit angka" required>
                <small class="text-muted">Hanya angka (10 hingga 15 digit).</small>
                @error('phone_number')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Alamat --}}
            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                          id="address" rows="3" placeholder="Masukkan alamat lengkap supplier" maxlength="255">{{ old('address') }}</textarea>
                <small class="text-muted">Maksimal 255 karakter.</small>
                @error('address')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card-footer bg-light">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan Supplier
            </button>
            <a href="{{ route('supplier.index') }}" class="btn btn-default ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection