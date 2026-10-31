@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-edit mr-2"></i> Edit Supplier: {{ $supplier->name }}</h3>
    </div>

    <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Nama Supplier --}}
            <div class="form-group">
                <label for="name">Nama Supplier <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       id="name" value="{{ old('name', $supplier->name) }}" required>
                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- Telepon --}}
            <div class="form-group">
                <label for="phone_number">Telepon <span class="text-danger">*</span></label>
                <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                       id="phone_number" value="{{ old('phone_number', $supplier->phone_number) }}" required>
                @error('phone_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            {{-- Alamat --}}
            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                          id="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
                @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="card-footer bg-light">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Update Data
            </button>
            <a href="{{ route('supplier.index') }}" class="btn btn-default ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection