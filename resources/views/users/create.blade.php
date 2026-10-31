@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus mr-2"></i> Tambah Karyawan Baru
                    </h3>
                </div>
                
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
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
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="contoh: karyawan" required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="karyawan">Karyawan</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-left">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-save mr-1"></i> Simpan Data
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-default">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection