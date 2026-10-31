@extends('layouts.admin')

@section('content')
<div class="container-fluid pt-3">
    <div class="row">
        
        {{-- BAGIAN KIRI: DAFTAR PRODUK --}}
        <div class="col-md-7">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title mt-1"><i class="fas fa-cash-register mr-2"></i> Daftar Menu / Barang</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" id="searchBarang" class="form-control float-right" placeholder="Cari nama atau kategori...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row" id="productList">
                        {{-- Mengurutkan produk murni berdasarkan Abjad Nama Produk (A-Z) --}}
                        @forelse($products->sortBy('name') as $product)
                            {{-- PERUBAHAN: Menambahkan atribut data-category agar bisa dibaca oleh JavaScript --}}
                            <div class="col-md-4 col-sm-6 col-6 mb-3 product-item" data-category="{{ $product->category->name ?? '' }}">
                                <div class="card bg-light border h-100 mb-0 btn-add-cart" 
                                     data-id="{{ $product->id }}"
                                     data-name="{{ $product->name }}"
                                     data-price="{{ $product->sell_price }}"
                                     data-stock="{{ $product->stock }}"
                                     style="cursor: pointer; transition: 0.2s;">
                                    <div class="card-body text-center p-2">
                                        <h6 class="font-weight-bold mb-1 product-name" style="font-size: 14px;">{{ $product->name }}</h6>
                                        <small class="text-muted d-block mb-1">Stok: <span class="badge badge-info">{{ $product->stock }}</span></small>
                                        <h6 class="text-primary mb-1">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</h6>
                                        <small class="text-success" style="font-size: 11px;"><i class="fas fa-plus-circle"></i> Klik untuk tambah</small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-4">
                                <i class="fas fa-box-open fa-3x mb-3 text-light"></i>
                                <p>Belum ada barang yang tersedia atau stok habis.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: KERANJANG & PEMBAYARAN --}}
        <div class="col-md-5">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title mt-1"><i class="fas fa-shopping-cart mr-2"></i> Keranjang Pesanan</h3>
                    <div class="card-tools">
                        <button class="btn btn-sm btn-danger" onclick="kosongkanKeranjang()">
                            <i class="fas fa-trash"></i> Kosongkan
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div id="emptyCartMessage" class="text-center text-muted py-4">
                        <i class="fas fa-shopping-basket fa-3x mb-3 text-light"></i>
                        <p>Belum ada pesanan.</p>
                    </div>
                    <ul class="list-group list-group-flush" id="cartItems" style="max-height: 35vh; overflow-y: auto;"></ul>
                </div>

                <div class="card-footer bg-light border-top">
                    <form action="{{ route('kasir.store') }}" method="POST" id="formCheckout">
                        @csrf
                        <input type="hidden" name="cart_data" id="cartDataInput">
                        <input type="hidden" name="status" value="selesai">
                        <input type="hidden" name="change_amount" id="changeAmountInput" value="0">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="font-weight-bold mb-0">Total Pembayaran</h5>
                            <h4 class="font-weight-bold text-danger mb-0" id="totalHarga">Rp 0</h4>
                        </div>

                        <hr class="my-2">

                        <div class="form-group mb-2">
                            <label class="mb-1 text-muted" style="font-size: 13px;">Metode Pembayaran</label>
                            <select name="payment_method" id="paymentMethod" class="form-control form-control-sm" required>
                                <option value="tunai" selected>Tunai / Cash</option>
                                <option value="qris">Non Tunai / QRIS</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="mb-1 text-muted" style="font-size: 13px;">Nominal Uang Diterima (Rp)</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold">Rp</span>
                                </div>
                                <input type="text" id="paidAmountFormatted" class="form-control font-weight-bold text-lg" placeholder="10.000" required>
                                <input type="hidden" name="paid_amount" id="paidAmount">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-white border rounded">
                            <h6 class="font-weight-bold mb-0 text-muted">Kembalian</h6>
                            <h5 class="font-weight-bold text-success mb-0" id="changeAmountText">Rp 0</h5>
                        </div>

                        <button type="button" class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm" onclick="prosesPembayaran()" id="btnBayar" disabled>
                            <i class="fas fa-check-circle mr-2"></i> Proses Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>

@if(session('success') && session('invoice'))
<div class="modal fade" id="modalSuksesTransaksi" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <div class="modal-header border-0 pb-0 justify-content-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mt-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-check fa-3x"></i>
                </div>
            </div>
            <div class="modal-body pt-3 pb-4">
                <h4 class="font-weight-bold mb-3">Transaksi Berhasil!</h4>
                <p class="mb-1 text-muted">No. Invoice:</p>
                <h5 class="font-weight-bold text-primary mb-3">{{ session('invoice') }}</h5>
                
                <div class="bg-light p-3 rounded mb-3 text-left">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kembalian:</span>
                        <strong class="text-success h5 mb-0">Rp {{ number_format(session('change_amount'), 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <a href="{{ route('transaksi.print', session('invoice')) }}" target="_blank" class="btn btn-warning btn-lg mr-2 font-weight-bold">
                        <i class="fas fa-print"></i> Cetak Struk
                    </a>
                    <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<style>
    .btn-add-cart:hover { background-color: #e9ecef !important; border-color: #007bff !important; }
    #cartItems::-webkit-scrollbar { width: 6px; }
    #cartItems::-webkit-scrollbar-track { background: #f1f1f1; }
    #cartItems::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
    #cartItems::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
</style>
<script>
    let keranjang = [];
    let totalBelanja = 0;
    
    const hasSuccessSession = {{ session('success') ? 'true' : 'false' }};

    // PERUBAHAN: Modifikasi pencarian agar mengecek nama ATAU kategori
    $('#searchBarang').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $('.product-item').filter(function() {
            let namaBarang = $(this).find('.product-name').text().toLowerCase();
            // Mengambil data kategori yang disisipkan di HTML sebelumnya
            let kategoriBarang = $(this).data('category').toString().toLowerCase();
            
            // Menampilkan barang jika keyword cocok dengan nama ATAU kategori
            $(this).toggle(namaBarang.indexOf(value) > -1 || kategoriBarang.indexOf(value) > -1);
        });
    });

    function formatRibuan(angka) {
        return angka.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    $('#paidAmountFormatted').on('input', function() {
        let rawValue = $(this).val();
        let cleanValue = rawValue.replace(/\D/g, "");
        $(this).val(formatRibuan(cleanValue));
        $('#paidAmount').val(cleanValue);
        hitungKembalian();
    });

    $(document).ready(function() {
        if (hasSuccessSession) {
            $('#modalSuksesTransaksi').modal('show');
        }

        $('#paymentMethod').on('change', function() {
            let method = $(this).val();
            if (method === 'qris') {
                $('#paidAmountFormatted').val(totalBelanja.toLocaleString('id-ID').replace(/,/g, ".")).prop('readonly', true);
                $('#paidAmount').val(totalBelanja);
            } else {
                $('#paidAmountFormatted').val('').prop('readonly', false);
                $('#paidAmount').val(0);
            }
            hitungKembalian();
        });
    });

    $(document).on('click', '.btn-add-cart', function(e) {
        e.preventDefault(); 
        let id = $(this).data('id');
        let nama = $(this).data('name');
        let harga = parseInt($(this).data('price'));
        let stokMax = parseInt($(this).data('stock'));
        
        let itemIndex = keranjang.findIndex(item => item.id == id);
        if (itemIndex > -1) {
            if(keranjang[itemIndex].qty < stokMax) keranjang[itemIndex].qty += 1;
        } else {
            if(stokMax > 0) keranjang.push({ id: id, nama: nama, harga: harga, qty: 1, stokMax: stokMax });
        }
        renderKeranjang();
    });

    function renderKeranjang() {
        let cartList = $('#cartItems');
        let emptyMsg = $('#emptyCartMessage');
        cartList.empty();
        totalBelanja = 0;

        if (keranjang.length === 0) {
            emptyMsg.show();
            $('#btnBayar').prop('disabled', true);
        } else {
            emptyMsg.hide();
            keranjang.forEach((item, index) => {
                let subtotal = item.harga * item.qty;
                totalBelanja += subtotal;
                cartList.append(`
                    <li class="list-group-item px-3 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>${item.nama}</strong>
                            <div class="input-group input-group-sm" style="width: 100px;">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQty(${index}, -1)">-</button>
                                </div>
                                <input type="text" class="form-control text-center" value="${item.qty}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQty(${index}, 1)">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Rp ${item.harga.toLocaleString('id-ID')} / item</small>
                            <strong>Rp ${subtotal.toLocaleString('id-ID')}</strong>
                        </div>
                    </li>
                `);
            });
        }
        $('#totalHarga').text('Rp ' + totalBelanja.toLocaleString('id-ID'));
        
        if ($('#paymentMethod').val() === 'qris') {
            $('#paidAmountFormatted').val(totalBelanja.toLocaleString('id-ID').replace(/,/g, "."));
            $('#paidAmount').val(totalBelanja);
        }
        hitungKembalian();
    }

    function updateQty(index, change) {
        let item = keranjang[index];
        let newQty = item.qty + change;
        if (newQty > 0 && newQty <= item.stokMax) {
            item.qty = newQty;
        } else if (newQty <= 0) {
            keranjang.splice(index, 1);
        } else {
            alert('Stok tidak mencukupi!');
        }
        renderKeranjang();
    }

    function hitungKembalian() {
        let paid = parseInt($('#paidAmount').val()) || 0;
        let change = paid - totalBelanja;
        if (keranjang.length > 0 && paid >= totalBelanja) {
            $('#changeAmountText').text('Rp ' + change.toLocaleString('id-ID'));
            $('#changeAmountInput').val(change);
            $('#btnBayar').prop('disabled', false);
        } else {
            $('#changeAmountText').text(paid > 0 ? 'Uang Kurang!' : 'Rp 0');
            $('#btnBayar').prop('disabled', true);
        }
    }

    function prosesPembayaran() {
        $('#cartDataInput').val(JSON.stringify(keranjang));
        $('#formCheckout').submit();
    }

    function kosongkanKeranjang() {
        if(keranjang.length > 0 && confirm('Yakin ingin mengosongkan pesanan?')) {
            keranjang = [];
            $('#paidAmountFormatted').val('');
            $('#paidAmount').val('');
            renderKeranjang();
        }
    }
</script>
@endsection