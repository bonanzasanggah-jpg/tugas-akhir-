@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-2"></i> 
                        {{ $user->id == Auth::id() ? 'Edit Profil Saya' : 'Edit Profil Karyawan: ' . $user->name }}
                    </h3>
                </div>

                <form action="{{ $user->id != Auth::id() ? route('admin.profile.update', $user->id) : route('profile.update') }}" method="POST">
                    @csrf 
                    @method('PUT')
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                            </div>
                        @endif

                        {{-- Tampilan kotak peringatan (error) --}}
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                                <p class="mb-2">Mohon periksa kembali isian Anda:</p>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                        </div>

                        <hr>
                        <p class="text-muted small"><i>Kosongkan password jika tidak ingin mengubahnya.</i></p>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password Baru</label>
                                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-left">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection