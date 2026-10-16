<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura ASAGROYA #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page {
            margin: 6px 10px 10px 10px;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier New', Courier, 'DejaVu Sans Mono', monospace;
            font-size: 8.5pt;
            line-height: 1.25;
            color: #111111;
            background: #ffffff;
            width: 100%;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        
        .header {
            text-align: center;
            margin-top: 4px;
            margin-bottom: 6px;
        }
        .header .subtitle {
            font-size: 7.5pt;
            letter-spacing: 1px;
            color: #444;
            text-transform: uppercase;
        }
        .header .company-name {
            font-size: 14pt;
            font-weight: 900;
            letter-spacing: 1.5px;
            margin: 2px 0 3px 0;
            color: #000;
        }
        .header .info {
            font-size: 8pt;
            line-height: 1.25;
            color: #222;
        }

        .divider {
            border-top: 1px dashed #444444;
            margin: 6px 0;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            font-size: 7.8pt;
            padding: 1px 0;
            vertical-align: top;
        }

        .items-table {
            margin: 2px 0;
        }
        .items-table th {
            font-size: 7.8pt;
            font-weight: bold;
            padding: 3px 0;
            border-bottom: 1px dashed #444444;
        }
        .items-table td {
            font-size: 8pt;
            padding: 3px 0 2px 0;
            vertical-align: top;
        }
        .prod-name {
            font-weight: 600;
            font-size: 7.8pt;
            line-height: 1.2;
        }
        .prod-unit {
            font-size: 7pt;
            color: #555;
            margin-top: 1px;
        }

        .totals-table td {
            font-size: 8.2pt;
            padding: 2px 0;
        }
        .totals-table .total-row td {
            font-size: 11pt;
            font-weight: 900;
            padding-top: 5px;
            padding-bottom: 3px;
        }

        .payment-info {
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            line-height: 1.4;
            margin: 4px 0;
        }

        .qr-section {
            text-align: center;
            margin-top: 6px;
        }
        .qr-section img {
            display: block;
            margin: 0 auto;
            width: 90px;
            height: 90px;
        }
        .cufe {
            font-size: 6.5pt;
            color: #666;
            margin-top: 4px;
            word-break: break-all;
        }
        .thanks {
            font-size: 8.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-top: 7px;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $qrText = "ASAGROYA | FACTURA #" . str_pad($sale->id, 6, '0', STR_PAD_LEFT) . " | NIT: 900.888.777-2 | FECHA: " . \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') . " | TOTAL: $" . number_format($sale->total_amount, 0, ',', '.') . " | CLIENTE: " . $sale->customer->name;
        $qrSvg = SimpleSoftwareIO\QrCode\Facades\QrCode::size(95)->margin(0)->generate($qrText);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
        $cufe = 'cufe-dian-' . substr(md5($sale->id . $sale->sale_date . 'ASAGROYA'), 0, 24);
        
        $customerDoc = $sale->customer->document_id ?: $sale->customer->tax_id;
        $sellerName = $sale->user->name ?? '01';
    @endphp

    <!-- Header -->
    <div class="header">
        <div class="subtitle">DIRECTORIO LOCAL</div>
        <div class="company-name">ASAGROYA</div>
        <div class="info">
            NIT: 900.888.777-2<br>
            TEL: 300 123 4567<br>
            Yaguará, Huila
        </div>
    </div>

    <div class="divider"></div>

    <!-- Metadata -->
    <table class="meta-table">
        <tr>
            <td class="text-left">FECHA: {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
            <td class="text-right">{{ \Carbon\Carbon::parse($sale->sale_date)->format('h:i:s A') }}</td>
        </tr>
        <tr>
            <td class="text-left">CAJA: 01</td>
            <td class="text-right">TICKET: #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td colspan="2">CLIENTE: {{ $sale->customer->name }}</td>
        </tr>
        @if ($customerDoc)
        <tr>
            <td colspan="2">NIT/CC: {{ $customerDoc }}</td>
        </tr>
        @endif
        @if ($sale->customer->phone)
        <tr>
            <td colspan="2">TEL: {{ $sale->customer->phone }}</td>
        </tr>
        @endif
        @if ($sale->customer->address)
        <tr>
            <td colspan="2">DIR: {{ Str::limit($sale->customer->address, 38) }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 14%; text-align: left;">CANT</th>
                <th style="width: 54%; text-align: left;">DESCRIPCION</th>
                <th style="width: 32%; text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td style="text-align: left; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="text-align: left;">
                        <div class="prod-name">{{ $item->product->name }}</div>
                        <div class="prod-unit">$ {{ number_format($item->unit_price, 0, ',', '.') }} c/u</div>
                    </td>
                    <td style="text-align: right; font-weight: bold;">
                        $ {{ number_format($item->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- Totals Table -->
    <table class="totals-table">
        <tr>
            <td class="text-left">SUBTOTAL:</td>
            <td class="text-right">$ {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-left">IVA (INCL):</td>
            <td class="text-right">$ {{ number_format($sale->total_amount * 0.19 / 1.19, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="totals-table">
        <tr class="total-row">
            <td class="text-left bold">TOTAL:</td>
            <td class="text-right bold">$ {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Payment details -->
    <div class="payment-info">
        <div>PAGO: {{ strtoupper($sale->payment_method ?? 'EFECTIVO') }}</div>
        <div>ESTADO: PAGADO</div>
        <div>TIPO: PUNTO DE VENTA</div>
    </div>

    <div class="divider"></div>

    <!-- QR Code & CUFE -->
    <div class="qr-section">
        <img src="{{ $qrBase64 }}" alt="QR Ticket" />
        <div class="cufe">CUFE: {{ $cufe }}</div>
    </div>

    <div class="thanks">¡GRACIAS POR SU COMPRA!</div>
</body>
</html>
