@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-edit mr-2"></i> Edit Kategori: {{ $category->name }}</h3>
    </div>
    
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
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

            <div class="form-group">
                <label>Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $category->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Kolom Deskripsi Baru --}}
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          rows="3" placeholder="Masukkan deskripsi kategori...">{{ old('description', $category->description) }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        
        <div class="card-footer bg-light">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Update Kategori
            </button>
            <a href="{{ route('categories.index') }}" class="btn btn-default ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection