@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-plus-circle mr-2"></i> Tambah Barang Baru</h3>
    </div>
    
    <form action="{{ route('barang.store') }}" method="POST" id="barangForm">
        @csrf
        <div class="card-body">

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

            <div class="row">
                <div class="col-md-6">
                    
                    {{-- Pilihan Identitas Barang --}}
                    <div class="form-group">
                        <label>Pilih Tipe Identitas Barang <span class="text-danger">*</span></label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="tipeSKU" name="tipe_identitas" class="custom-control-input" value="sku" checked>
                                <label class="custom-control-label" for="tipeSKU">Gunakan SKU</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="tipeBarcode" name="tipe_identitas" class="custom-control-input" value="barcode">
                                <label class="custom-control-label" for="tipeBarcode">Gunakan Barcode</label>
                            </div>
                        </div>
                    </div>

                    {{-- Form SKU --}}
                    <div class="form-group" id="skuContainer">
                        <label>SKU (Kode Barang) <span class="text-danger">*</span></label>
                        <input type="text" name="sku" id="inputSku" class="form-control @error('sku') is-invalid @enderror" 
                               value="{{ old('sku') }}" placeholder="Contoh: SKU123" 
                               maxlength="10" style="text-transform: uppercase;" required>
                        <small class="text-muted">SKU maksimal 10 karakter (kombinasi huruf dan angka).</small>
                        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Form Barcode --}}
                    <div class="form-group" id="barcodeContainer" style="display: none;">
                        <label>Barcode <span class="text-danger">*</span></label>
                        <input type="text" name="barcode" id="inputBarcode" class="form-control @error('barcode') is-invalid @enderror" 
                               value="{{ old('barcode') }}" placeholder="Ketik Barcode (Min. 13 angka)" 
                               minlength="13" pattern="\d+" title="Masukkan minimal 13 digit angka">
                        <small class="text-muted">Barcode harus berupa angka dengan minimal 13 digit.</small>
                        @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama barang" minlength="4" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                            <input type="text" name="buy_price" id="buy_price" class="form-control" value="{{ old('buy_price') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Harga Jual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                            <input type="text" name="sell_price" id="sell_price" class="form-control" value="{{ old('sell_price') }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Min. Stok <span class="text-danger">*</span></label>
                                <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 5) }}" min="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal Kedaluwarsa</label>
                        <input type="date" name="expired_date" class="form-control" value="{{ old('expired_date') }}">
                        <small class="text-muted">Kosongkan jika barang tidak memiliki tanggal kedaluwarsa</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-light">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan Barang
            </button>
            <a href="{{ route('barang.index') }}" class="btn btn-default ml-2">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // FORMAT RUPIAH
    function formatRupiah(angka) {
        var number_string = angka.toString().replace(/[^0-9]/g, '');
        var sisa = number_string.length % 3;
        var rupiah = number_string.substr(0, sisa);
        var ribuan = number_string.substr(sisa).match(/\d{3}/g);
        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    const buyPrice = document.getElementById('buy_price');
    const sellPrice = document.getElementById('sell_price');

    if(buyPrice) buyPrice.addEventListener('keyup', function() { this.value = formatRupiah(this.value); });
    if(sellPrice) sellPrice.addEventListener('keyup', function() { this.value = formatRupiah(this.value); });

    document.getElementById('barangForm').addEventListener('submit', function() {
        if(buyPrice) buyPrice.value = buyPrice.value.replace(/\./g, '');
        if(sellPrice) sellPrice.value = sellPrice.value.replace(/\./g, '');
    });

    // RADIO BUTTON
    const radioSKU = document.getElementById('tipeSKU');
    const radioBarcode = document.getElementById('tipeBarcode');
    const skuContainer = document.getElementById('skuContainer');
    const barcodeContainer = document.getElementById('barcodeContainer');
    const inputSku = document.getElementById('inputSku');
    const inputBarcode = document.getElementById('inputBarcode');

    function toggleIdentitas() {
        if (radioSKU.checked) {
            skuContainer.style.display = 'block';
            barcodeContainer.style.display = 'none';
            inputSku.setAttribute('required', 'required');
            inputBarcode.removeAttribute('required');
            inputBarcode.value = ''; 
        } else {
            skuContainer.style.display = 'none';
            barcodeContainer.style.display = 'block';
            inputSku.removeAttribute('required');
            inputBarcode.setAttribute('required', 'required');
            inputSku.value = '';
        }
    }

    radioSKU.addEventListener('change', toggleIdentitas);
    radioBarcode.addEventListener('change', toggleIdentitas);
    toggleIdentitas(); 
</script>
@endpush