@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users mr-2"></i> Manajemen Karyawan
            </h3>
            
            {{-- Bagian Card Tools --}}
            <div class="card-tools">
                {{-- Tombol Kembali mengarah ke edit profil --}}
                <a href="{{ route('profile.edit') }}" class="btn btn-default btn-sm mr-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Profil
                </a>
                {{-- Tombol Tambah --}}
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Karyawan
                </a>
            </div>
        </div>
        
        <div class="card-body">
            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-check"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nama</th>
                            <th>Username</th> 
                            <th>Role</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td><code>{{ $user->username }}</code></td> 
                            <td>
                                <span class="badge {{ $user->role == 'admin' ? 'badge-danger' : 'badge-info' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.profile.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                @if($user->id != auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus karyawan {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada data karyawan ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection