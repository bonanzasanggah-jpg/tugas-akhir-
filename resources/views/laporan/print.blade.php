<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan - Bonanza</title>
    <style>
        @page { 
            size: A4; 
            margin: 0; 
        }
        
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 11pt; 
            color: #333; 
            padding: 2cm; 
        }
        
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 24pt; color: #2c3e50; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 10pt; color: #666; }
        .laporan-info { margin-bottom: 20px; }
        .laporan-info table { width: 100%; }
        .laporan-info td { padding: 3px 0; }
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-data th, .table-data td { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
        .table-data th { background-color: #f8f9fa; font-weight: bold; text-transform: uppercase; font-size: 10pt; }
        .table-data .text-right { text-align: right; }
        .table-data .text-center { text-align: center; }
        .total-row th { background-color: #e9ecef; font-size: 12pt; }
        .footer { margin-top: 50px; text-align: right; font-size: 10pt; }
        .ttd-area { margin-top: 80px; font-weight: bold; text-decoration: underline; }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>MINIMARKET BONANZA</h2>
        <p>Jl. Daeng Manambon | Telp: 08575709507</p>
        <p>Laporan Penjualan Keuangan Minimarket Bonanza </p>
    </div>

    <div class="laporan-info">
        <table>
            <tr>
                <td style="width: 120px;"><strong>Periode Laporan</strong></td>
                <td>: {{ $periodeCetak }}</td>
                <td class="text-right"><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d F Y, H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Dicetak Oleh</strong></td>
                <td>: {{ $pencetak }}</td>
                <td></td>
            </tr>
        </table>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Kode Transaksi</th>
                <th>Tanggal & Waktu</th>
                <th>Barang</th>
                <th>Metode</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $i => $t)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $t->invoice_number }}</td>
                <td>{{ $t->created_at ? $t->created_at->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $t->product ? $t->product->name : 'Barang Terhapus' }}</td>
                <td>{{ strtoupper($t->payment_method ?? 'TUNAI') }}</td>
                <td class="text-right">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada transaksi pada periode yang dipilih.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="5" class="text-right">TOTAL PENDAPATAN :</th>
                <th class="text-right">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <div class="ttd-area">
            ( Dahlia )
        </div>
        <p style="margin-top: 5px; color: #666; font-size: 9pt; text-decoration: none;">Pemilik Minimarket Bonanza</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>