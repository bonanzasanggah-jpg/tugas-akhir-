@extends('layouts.admin')

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title mt-1"><i class="fas fa-edit mr-2"></i> Edit Data: {{ $product->name }}</h3>
    </div>
    
    <form action="{{ route('barang.update', $product->id) }}" method="POST" id="barangForm">
        @csrf
        @method('PUT') 
        
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show py-2 px-3">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><i class="fas fa-exclamation-circle mr-1"></i> Mohon periksa kembali:</strong>
                    <ul class="mb-0 pl-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    @php
                        $hasBarcode = !empty($product->barcode) && empty($product->sku);
                    @endphp
                    
                    <div class="form-group">
                        <label>Pilih Tipe Identitas Barang <span class="text-danger">*</span></label>
                        <div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="tipeSKU" name="tipe_identitas" class="custom-control-input" value="sku" {{ !$hasBarcode ? 'checked' : '' }}>
                                <label class="custom-control-label" for="tipeSKU">Gunakan SKU</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="tipeBarcode" name="tipe_identitas" class="custom-control-input" value="barcode" {{ $hasBarcode ? 'checked' : '' }}>
                                <label class="custom-control-label" for="tipeBarcode">Gunakan Barcode</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="skuContainer">
                        <label>SKU (Kode Barang) <span class="text-danger">*</span></label>
                        <input type="text" name="sku" id="inputSku" class="form-control" 
                               value="{{ old('sku', $product->sku) }}" 
                               placeholder="Contoh: SKU123" maxlength="10" 
                               style="text-transform: uppercase;">
                    </div>

                    <div class="form-group" id="barcodeContainer" style="display: none;">
                        <label>Barcode <span class="text-danger">*</span></label>
                        <input type="text" name="barcode" id="inputBarcode" class="form-control" 
                               value="{{ old('barcode', $product->barcode) }}" 
                               placeholder="Minimal 13 angka" minlength="13">
                    </div>

                    <div class="form-group">
                        <label>Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Supplier <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-control" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ (old('supplier_id', $product->supplier_id) == $supplier->id) ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Harga Beli <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                            <input type="text" name="buy_price" id="buy_price" class="form-control" 
                                   value="{{ number_format($product->buy_price, 0, ',', '.') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Harga Jual <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                            <input type="text" name="sell_price" id="sell_price" class="form-control" 
                                   value="{{ number_format($product->sell_price, 0, ',', '.') }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Min. Stok <span class="text-danger">*</span></label>
                        <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', $product->min_stock) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal Kedaluwarsa</label>
                        <input type="date" name="expired_date" class="form-control" value="{{ old('expired_date', $product->expired_date ? \Carbon\Carbon::parse($product->expired_date)->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-light text-left">
            <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-save mr-1"></i> Update Data</button>
            <a href="{{ route('barang.index') }}" class="btn btn-default"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(angka) {
        let angkaMurni = angka.toString().replace(/\D/g, "");
        return angkaMurni.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    const buyPrice = document.getElementById('buy_price');
    const sellPrice = document.getElementById('sell_price');
    const form = document.getElementById('barangForm');

    buyPrice.value = formatRupiah(buyPrice.value);
    sellPrice.value = formatRupiah(sellPrice.value);

    buyPrice.addEventListener('input', function() { this.value = formatRupiah(this.value); });
    sellPrice.addEventListener('input', function() { this.value = formatRupiah(this.value); });

    form.addEventListener('submit', function() {
        buyPrice.value = buyPrice.value.replace(/\./g, '');
        sellPrice.value = sellPrice.value.replace(/\./g, '');
        if(radioSKU.checked) {
            document.getElementById('inputBarcode').value = '';
        } else {
            document.getElementById('inputSku').value = '';
        }
    });

    // SKU/Barcode
    const radioSKU = document.getElementById('tipeSKU');
    const radioBarcode = document.getElementById('tipeBarcode');
    const skuContainer = document.getElementById('skuContainer');
    const barcodeContainer = document.getElementById('barcodeContainer');

    function toggleIdentitas() {
        if (radioSKU.checked) {
            skuContainer.style.display = 'block';
            barcodeContainer.style.display = 'none';
        } else {
            skuContainer.style.display = 'none';
            barcodeContainer.style.display = 'block';
        }
    }

    radioSKU.addEventListener('change', toggleIdentitas);
    radioBarcode.addEventListener('change', toggleIdentitas);
    toggleIdentitas();
</script>
@endpush