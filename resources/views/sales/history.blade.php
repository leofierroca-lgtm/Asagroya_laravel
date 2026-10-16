@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Historial de Ventas</h1>
        <p>Consulte registros de facturación, detalles de transacciones y reimpresión de comprobantes</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nº Factura</th>
                        <th>Fecha de Operación</th>
                        <th>Cliente (Razón Social)</th>
                        <th>Vendedor</th>
                        <th>Medio de Pago</th>
                        <th style="text-align:right;">Monto Total</th>
                        <th style="text-align:center;">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($sales->isEmpty())
                        <tr>
                            <td colspan="7" style="text-align:center; padding:3rem; color:var(--text-muted);">
                                <i class="fa-solid fa-receipt" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; color:var(--text-muted);"></i>
                                Aún no se han registrado ventas en el sistema.
                            </td>
                        </tr>
                    @else
                        @foreach ($sales as $sale)
                            <tr>
                                <td style="font-weight:700;"><code>#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</code></td>
                                <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</td>
                                <td style="font-weight:600;">{{ $sale->customer->name }}</td>
                                <td>{{ $sale->user->name ?? 'Usuario Sistema' }}</td>
                                <td>
                                    @php
                                        $pmClass = '';
                                        if ($sale->payment_method === 'Efectivo') $pmClass = 'badge-success';
                                        elseif ($sale->payment_method === 'Transferencia') $pmClass = 'badge-info';
                                        elseif ($sale->payment_method === 'Crédito') $pmClass = 'badge-warning';
                                    @endphp
                                    <span class="badge {{ $pmClass }}">{{ $sale->payment_method }}</span>
                                </td>
                                <td style="text-align:right; font-weight:700; color:var(--primary); font-size:1.05rem;">
                                    $ {{ number_format($sale->total_amount, 2) }}
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:inline-flex; gap:0.25rem;">
                                        <!-- Detalles interactivos (HU-03 Scenario 2) -->
                                        <button onclick="showSaleDetails({{ $sale->id }})" class="btn btn-sm btn-secondary" title="Ver desglose">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <!-- Descargar PDF (HU-03 Scenario 4) -->
                                        <a href="{{ route('sales.generateInvoice', $sale->id) }}" class="btn btn-sm btn-secondary" title="Descargar Factura PDF">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>
                                        <!-- Enviar por correo (HU-03 Scenario 5) -->
                                        <a href="{{ route('sales.sendInvoiceEmail', $sale->id) }}" class="btn btn-sm btn-secondary" title="Enviar factura por email">
                                            <i class="fa-solid fa-envelope"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detailed Breakdown Modal -->
<div id="saleDetailsModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Desglose de Facturación</h3>
            <span onclick="closeModal('saleDetailsModal')" class="modal-close">&times;</span>
        </div>
        <div class="modal-body" id="sale-details-content">
            <!-- Loaded dynamically via app.js AJAX fetch -->
        </div>
        <div class="modal-footer">
            <button onclick="closeModal('saleDetailsModal')" class="btn btn-secondary btn-sm">Cerrar</button>
        </div>
    </div>
</div>
<script>
function showSaleDetails(saleId) {
    const container = document.getElementById('sale-details-content');
    if (!container) return;

    container.innerHTML = `<div style="text-align:center; padding:2rem; color:var(--text-secondary);">Cargando factura...</div>`;
    openModal('saleDetailsModal');

    fetch(`{{ url('sales') }}/${saleId}/details-json`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const s = res.data;
                const dateF = new Date(s.sale_date).toLocaleString('es-CO');
                
                let itemsHtml = `
                    <table class="table" style="font-size:0.85rem; margin-top:1rem; width:100%; border-collapse:collapse;">
                        <thead style="background:var(--bg-secondary); border-bottom:1px solid var(--border-color);">
                            <tr>
                                <th style="text-align:left; padding:0.5rem;">Producto</th>
                                <th style="text-align:center; padding:0.5rem;">Cant.</th>
                                <th style="text-align:right; padding:0.5rem;">P. Unit.</th>
                                <th style="text-align:right; padding:0.5rem;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                s.items.forEach(item => {
                    itemsHtml += `
                        <tr style="border-bottom:1px solid rgba(0,0,0,0.05);">
                            <td style="padding:0.5rem; font-weight:600;">${item.product.name}</td>
                            <td style="text-align:center; padding:0.5rem;">${item.quantity}</td>
                            <td style="text-align:right; padding:0.5rem;">$${parseFloat(item.unit_price).toFixed(2)}</td>
                            <td style="text-align:right; padding:0.5rem; font-weight:700;">$${parseFloat(item.subtotal).toFixed(2)}</td>
                        </tr>
                    `;
                });
                
                itemsHtml += `
                        </tbody>
                    </table>
                `;
                
                container.innerHTML = `
                    <div style="margin-bottom:1rem; border-bottom:1px dashed var(--border-color); padding-bottom:1rem; display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase;">Factura #</div>
                            <div style="font-size:1.1rem; font-weight:700;">${s.id.toString().padStart(6, '0')}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase;">Fecha Operación</div>
                            <div style="font-size:0.95rem;">${dateF}</div>
                        </div>
                        <div>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase;">Cliente</div>
                            <div style="font-size:0.95rem; font-weight:600;">${s.customer.name}</div>
                            <div style="font-size:0.8rem; color:var(--text-secondary);">NIT/CC: ${s.customer.document_id}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; text-transform:uppercase;">Vendedor / POS</div>
                            <div style="font-size:0.95rem;">${s.user ? s.user.name : 'Sistema'}</div>
                        </div>
                    </div>
                    
                    ${itemsHtml}
                    
                    <div style="text-align:right; margin-top:1rem; padding-top:1rem; border-top:2px solid var(--border-color);">
                        <div style="display:flex; justify-content:flex-end; align-items:center; gap:1rem; margin-bottom:0.25rem;">
                            <span style="font-weight:600; font-size:0.9rem;">Medio de Pago:</span>
                            <span class="badge" style="background:var(--bg-secondary); color:var(--text-primary); border:1px solid var(--border-color);">${s.payment_method}</span>
                        </div>
                        <div style="display:flex; justify-content:flex-end; align-items:center; gap:1rem;">
                            <span style="font-weight:700; font-size:1.1rem;">TOTAL FACTURADO:</span>
                            <span style="font-weight:800; font-size:1.5rem; color:var(--primary);">$${parseFloat(s.total_amount).toFixed(2)}</span>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = `<div style="color:var(--danger); padding:2rem; text-align:center;">${res.message}</div>`;
            }
        });
}
</script>
@endsection
