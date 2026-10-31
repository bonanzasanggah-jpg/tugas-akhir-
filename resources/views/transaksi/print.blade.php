<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Struk {{ $referensi->invoice_number }}</title>
    <style>
        @page { 
            size: 58mm auto; 
            margin: 0 !important; 
        }
        
        body { 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 11px; 
            
            font-weight: 600; 
            -webkit-font-smoothing: none; 
            
            line-height: 1.15;
            color: #000 !important;
            background: #fff;

            width: 50mm; 
            margin: 0 0 0 1mm !important; 
            
            padding: 3mm 1mm 10mm 1mm !important; 
            
            page-break-after: avoid;
            break-after: avoid;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        
        .flex { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start; 
            width: 100%;
        }
        
        hr { 
            border: none;
            border-top: 1px dashed #000; 
            margin: 3px 0; 
            height: 1px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 2px;
            table-layout: fixed; 
        }
        
        td { 
            vertical-align: top; 
            padding: 1px 0;
            word-wrap: break-word; 
        }

        @media print { 
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body>
    <div class="center">
        <strong style="font-size: 12px;">MINIMARKET BONANZA</strong><br>
        Jl. Daeng Manambon<br>
        <hr>
    </div>
    
    <p style="margin: 3px 0;">
        Inv: {{ $referensi->invoice_number }}<br>
        Tgl: {{ $referensi->created_at->format('d/m/y H:i') }}<br>
        Ksr: {{ $referensi->user->name ?? 'Dahlia' }}
    </p>
    <hr>
    
    <table>
        @foreach($semua_barang as $item)
        <tr>
            <td colspan="2" class="bold">{{ $item->product->name ?? 'Produk Dihapus' }}</td>
        </tr>
        <tr>
            <td>{{ $item->quantity }}x @ {{ number_format($item->product->sell_price ?? 0, 0, ',', '.') }}</td>
            <td class="right bold">{{ number_format($item->total_price, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <hr>
    
    <div class="flex bold" style="font-size: 12px;">
        <span>TOTAL</span>
        <span>Rp {{ number_format($grand_total, 0, ',', '.') }}</span>
    </div>
    
    <div class="flex">
        <span>Bayar ({{ strtolower($referensi->payment_method) == 'qris' ? 'QRIS' : 'Tunai' }})</span>
        <span>Rp {{ number_format($referensi->paid_amount, 0, ',', '.') }}</span>
    </div>
    
    <div class="flex">
        <span>Kembali</span>
        <span>Rp {{ number_format($referensi->change_amount, 0, ',', '.') }}</span>
    </div>
    <hr>
    
    <div class="center" style="font-style: italic; font-size: 10px;">
        Terima Kasih!<br>
        Barang yang sudah dibeli<br>
        tidak dapat ditukar/dikembalikan.
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() { 
            setTimeout(function() {
                window.print();
            }, 500);
        });

        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>