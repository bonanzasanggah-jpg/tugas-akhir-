<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Minimarket Bonanza</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        /* Desain Background Modern */
        .login-page {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
            height: 100vh;
        }
        
        /* Modifikasi Kotak Login */
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: none;
        }
        .card-body {
            border-radius: 15px;
            padding: 40px 30px;
        }

        /* Tipografi Kustom */
        .welcome-text {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 0;
        }
        .brand-title {
            color: #007bff;
            font-weight: 900;
            font-size: 1.8rem;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .subtitle-text {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 30px;
        }
        
        /* Tombol Login */
        .btn-login {
            border-radius: 25px;
            padding: 10px;
            font-weight: bold;
            font-size: 1.1rem;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }

        /* Styling tambahan link Lupa Password */
        .forgot-link {
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    
    <div class="card">
        <div class="card-body login-card-body text-center">
            
            <!-- Halaman login utama bersih dari notifikasi reset/OTP -->

            <!-- Teks Header -->
            <p class="welcome-text">Selamat Datang</p>
            <div class="brand-title">Minimarket Bonanza</div>
            <p class="subtitle-text">Anda Bisa Masuk Sebagai Admin / Kasir</p>

            <form action="{{ route('login.process') }}" method="post" class="text-left">
                @csrf
                
                <!-- Input Username -->
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                           placeholder="Username (contoh: admin / karyawan)" value="{{ old('username') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text bg-white"><span class="fas fa-user text-primary"></span></div>
                    </div>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Password -->
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text bg-white"><span class="fas fa-lock text-primary"></span></div>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Error Bawaan Laravel untuk Login Utama (Password/Username Salah) -->
                @if ($errors->has('username') && !session('reset_username'))
                    <div class="alert alert-danger p-2 small text-center mb-3">{{ $errors->first('username') }}</div>
                @endif

                <!-- Baris Ingat Saya & Lupa Password -->
                <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                        <label class="custom-control-label text-muted" for="remember" style="font-weight: normal; cursor: pointer;">Ingat Saya</label>
                    </div>
                    <div>
                        <a href="#" class="forgot-link" data-toggle="modal" data-target="#forgotPasswordModal">Lupa Password?</a>
                    </div>
                </div>

                <!-- Tombol Login -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block btn-login">LOGIN</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- LUPA PASSWORD (OTP TERPUSAT & RESET) -->
<div class="modal fade text-left" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalLabel"><i class="fas fa-shield-alt mr-2"></i> Pemulihan Kata Sandi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-4">
                
                <!-- Notifikasi Pesan Sukses / Status Khusus Ditampilkan Di Dalam Modal -->
                @if (session('otp_status') || session('status'))
                    <div class="alert alert-success small" role="alert">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('otp_status') ?? session('status') }}
                    </div>
                @endif

                <div class="alert alert-info small">
                    <i class="fas fa-info-circle mr-1"></i> <strong>Informasi Keamanan:</strong><br>
                    Sesuai kebijakan Minimarket Bonanza, instruksi pemulihan akun akan dikirimkan secara otomatis ke email Pemilik: <strong>adminbonanza1@gmail.com</strong>.
                </div>

                {{-- Form Tahap 1: Kirim Permintaan OTP --}}
                <form action="{{ route('password.email') }}" method="POST" class="mb-3 border-bottom pb-3">
                    @csrf
                    <p class="text-secondary small mb-2"><strong>Langkah 1:</strong> Masukkan username Anda untuk meminta kode OTP.</p>
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <input type="text" name="username" class="form-control form-control-sm @error('username') is-invalid @enderror" placeholder="contoh: karyawan" value="{{ old('username', session('reset_username')) }}" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary btn-sm">Kirim OTP</button>
                            </div>
                        </div>
                        @if ($errors->has('username') && session('reset_username'))
                            <small class="text-danger font-weight-bold mt-1 d-block">{{ $errors->first('username') }}</small>
                        @endif
                    </div>
                </form>

                {{-- Form Tahap 2: Verifikasi Kode OTP & Input Password Baru --}}
                <form action="{{ route('password.update.otp') }}" method="POST">
                    @csrf
                    <p class="text-secondary small mb-2"><strong>Langkah 2:</strong> Masukkan Kode OTP dan Password Baru Anda.</p>
                    
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Kode OTP <span class="text-danger">*</span></label>
                        <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror" placeholder="Masukkan kode OTP..." required>
                        @error('otp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter..." required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                        <i class="fas fa-key mr-2"></i> Reset Password Sekarang
                    </button>
                </form>
            </div>
            
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- Script Hanya Membuka Modal Jika Terjadi Error/Status pada Proses Lupa Password (Bukan Login Utama) -->
@if ($errors->has('otp') || session('otp_status') || session('status'))
<script>
    $(document).ready(function(){
        $('#forgotPasswordModal').modal('show');
    });
</script>
@endif

</body>
</html>