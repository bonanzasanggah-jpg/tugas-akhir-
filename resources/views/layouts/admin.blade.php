<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minimarket Bonanza</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.3/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        .main-sidebar, .content-wrapper, .main-header {
            transition: transform 0.3s ease-in-out, margin-left 0.3s ease-in-out !important;
        }
    </style>
</head>

<body class="sidebar-mini layout-fixed">
<div class="wrapper">
    
    <!-- Navbar Topbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- Right navbar links (Profile & Logout) -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fas fa-user-circle fa-lg mr-2"></i>
                    <span>
                        @if(auth()->check())
                            {{ auth()->user()->name }}
                        @else
                            Guest
                        @endif
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <span class="dropdown-header">Pengaturan Akun</span>
                    <div class="dropdown-divider"></div>
                    
                    <!-- Edit Profil -->
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user-edit mr-2 text-primary"></i> Profil Saya
                    </a>
                    
                    <div class="dropdown-divider"></div>
                    
                    <!-- Logout -->
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/" class="brand-link text-center">
            <span class="brand-text font-weight-light font-weight-bold">Minimarket Bonanza</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <li class="nav-item">
                        <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    <li class="nav-header text-uppercase text-secondary mt-2" style="font-size: 0.75rem;">TRANSAKSI</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('kasir.index') }}" class="nav-link {{ request()->is('kasir*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Kasir</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('transaksi.index') }}" class="nav-link {{ request()->is('transaksi*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i> 
                            <p>Riwayat Transaksi</p>
                        </a>
                    </li>

                    @if(auth()->check() && auth()->user()->role == 'admin')
                        <li class="nav-header text-uppercase text-secondary mt-2" style="font-size: 0.75rem;">MANAJEMEN</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-th-list"></i> <p>Data Kategori</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('supplier.index') }}" class="nav-link {{ request()->is('supplier*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-truck"></i> <p>Data Supplier</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('barang.index') }}" class="nav-link {{ request()->is('barang*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i> <p>Data Barang</p>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i> <p>Laporan Keuangan</p>
                        </a>
                    </li>

                    @if(auth()->check() && auth()->user()->role == 'admin')
                        <li class="nav-header text-uppercase text-secondary mt-2" style="font-size: 0.75rem;">MANAJEMEN AKUN</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i> <p>Kelola Karyawan</p>
                            </a>
                        </li>
                    @endif

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.3/js/jquery.overlayScrollbars.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    $(document).ready(function() {
        $('.sidebar').overlayScrollbars({
            className: "os-theme-light",
            sizeAutoCapable: true,
            scrollbars: { autoHide: "leave", clickScrolling: true }
        });
    });
</script>

@yield('scripts')
@stack('scripts')

</body>
</html>