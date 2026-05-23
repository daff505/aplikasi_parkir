<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir - #{{ $struk->nomor_struk }}</title>
    <style>
        @page { size: 80mm auto; margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 70mm; 
            margin: 0 auto; 
            padding: 20px 5px; 
            font-size: 11px;
            line-height: 1.2;
            color: #000;
            background: #fff;
        }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .header { margin-bottom: 15px; }
        .header h1 { font-size: 15px; margin: 0; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 9px; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .flex { display: flex; justify-content: space-between; }
        .content { margin: 10px 0; }
        .footer { margin-top: 20px; font-size: 9px; }
        
        .print-btn { 
            display: block; 
            width: 100%; 
            background: #10b981; 
            color: #fff; 
            text-align: center; 
            padding: 12px; 
            text-decoration: none; 
            margin-bottom: 20px;
            font-family: sans-serif;
            font-weight: bold;
            border-radius: 10px;
            font-size: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        @media print {
            .print-btn { display: none; }
            body { width: 100%; padding: 0; margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    
    <div class="no-print">
        <a href="javascript:window.print()" class="print-btn">KLIK UNTUK CETAK STRUK</a>
    </div>

    <div class="header text-center">
        @php
            $lines = explode("\n", $sys_settings['struk_header'] ?? "Parkir Digital Pro");
        @endphp
        @foreach($lines as $line)
            @if($loop->first)
                <h1>{{ $line }}</h1>
            @else
                <p>{{ $line }}</p>
            @endif
        @endforeach
    </div>

    <div class="divider"></div>
    
    <div class="content">
        <div class="flex">
            <span>No. Struk</span>
            <span class="bold">{{ $struk->nomor_struk }}</span>
        </div>
        <div class="flex">
            <span>No. Tiket (Masuk)</span>
            <span class="bold">{{ $struk->nomor_tiket ?? '-' }}</span>
        </div>
        <div class="flex">
            <span>No. Plat (Keluar)</span>
            <span class="bold">{{ $struk->plat_nomor ?? '-' }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="flex">
            <span>Masuk</span>
            <span>{{ date('d/m/y H:i', strtotime($struk->created_at)) }}</span>
        </div>
        <div class="flex">
            <span>Keluar</span>
            <span>{{ $struk->waktu_keluar ? date('d/m/y H:i', strtotime($struk->waktu_keluar)) : '-' }}</span>
        </div>
        <div class="flex">
            <span>Durasi</span>
            <span>{{ $struk->durasi_jam ?? 0 }} Jam</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="flex bold" style="font-size: 13px;">
            <span>TOTAL BAYAR</span>
            <span>Rp {{ number_format($struk->biaya_total ?? 0, 0, ',', '.') }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="flex">
            <span>Metode Bayar</span>
            <span class="bold uppercase">{{ $struk->metode_bayar ?? 'Tunai' }}</span>
        </div>
        <div class="flex">
            <span>Status</span>
            <span class="bold">LUNAS</span>
        </div>
    </div>

    <div class="footer text-center">
        <p>Simpan struk ini sebagai bukti pembayaran sah.</p>
        <p>Terima kasih atas kunjungan Anda!</p>
        <div class="divider"></div>
        <p style="font-size: 8px;">Dicetak oleh: {{ $struk->nama_petugas }}<br>{{ date('d/m/Y H:i:s') }}</p>
    </div>

</body>
</html>
