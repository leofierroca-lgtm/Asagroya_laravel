@extends('layouts.app')

@section('content')
<!-- Topbar Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Panel de Control Ejecutivo</h1>
        <p>Resumen de analíticas y alertas de inventario en tiempo real</p>
    </div>
    <div class="topbar-actions" style="display:flex; align-items:center;">
        <span style="font-size:0.9rem; color:rgba(255,255,255,0.75); font-weight:500;">
            <i class="fa-solid fa-calendar-days"></i> {{ date('d/m/Y') }}
        </span>
    </div>
</div>

<!-- KPI Summary Widgets -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-card-info">
            <h3>Ingresos Totales</h3>
            <div class="stat-value">$ {{ number_format($totalRevenue, 2) }}</div>
        </div>
        <div class="stat-card-icon success">
            <i class="fa-solid fa-hand-holding-dollar"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-info">
            <h3>Ventas Procesadas</h3>
            <div class="stat-value">{{ $totalSales }}</div>
        </div>
        <div class="stat-card-icon info">
            <i class="fa-solid fa-receipt"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-info">
            <h3>Alertas de Stock Bajo</h3>
            <div class="stat-value" style="color: {{ count($lowStockAlerts) > 0 ? 'var(--danger)' : 'inherit' }};">
                {{ count($lowStockAlerts) }}
            </div>
        </div>
        <div class="stat-card-icon warning">
            <i class="fa-solid fa-bell"></i>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-info">
            <h3>Clientes Registrados</h3>
            <div class="stat-value">{{ $totalCustomers }}</div>
        </div>
        <div class="stat-card-icon">
            <i class="fa-solid fa-user-group"></i>
        </div>
    </div>
</div>

<!-- Double Column Panel Layout -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap:1.5rem;">
    
    <!-- Alertas de Stock Widget -->
    <div class="panel">
        <div class="panel-header" style="background-color:rgba(239, 68, 68, 0.03);">
            <h2 style="color:var(--danger); display:flex; align-items:center; gap:0.5rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> Alertas de Stock Activas
            </h2>
            <span class="badge badge-danger">{{ count($lowStockAlerts) }} ítems</span>
        </div>
        <div class="panel-body" style="display:flex; flex-direction:column; gap:0.75rem; max-height: 420px; overflow-y:auto;">
            @if ($lowStockAlerts->isEmpty())
                <div style="text-align:center; padding:2rem; color:var(--text-muted);">
                    <i class="fa-solid fa-circle-check" style="font-size:2rem; color:var(--success); display:block; margin-bottom:0.5rem;"></i>
                    Nivel de inventario óptimo. Sin alertas de stock.
                </div>
            @else
                @foreach ($lowStockAlerts as $item)
                    @php
                        $isOutOfStock = $item->stock <= 0;
                        $statusClass = $isOutOfStock ? 'badge-danger' : 'badge-warning';
                        $statusText = $isOutOfStock ? 'Agotado' : 'Stock Bajo';
                    @endphp
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:0.75rem; border:1px solid var(--border-color); border-radius:var(--radius-md); background:var(--card-bg); transition:var(--transition);" onmouseover="this.style.borderColor='var(--danger)'" onmouseout="this.style.borderColor='var(--border-color)'">
                        <div>
                            <div style="font-weight:600; font-size:0.95rem;">{{ $item->name }}</div>
                            <div style="font-size:0.8rem; color:var(--text-muted);">SKU: <code>{{ $item->sku }}</code> | Categoría: {{ $item->category ?: 'N/A' }}</div>
                        </div>
                        <div style="text-align:right;">
                            <span class="badge {{ $statusClass }}" style="margin-bottom:0.25rem;">{{ $statusText }}</span>
                            <div style="font-size:0.85rem; font-weight:700; color:var(--text-primary);">Stock: {{ $item->stock }} / {{ $item->min_stock }}</div>
                        </div>
                    </div>
                @endforeach
                
                <div style="margin-top:0.5rem; text-align:center;">
                    <a href="{{ route('inventory.intake') }}" class="btn btn-sm btn-secondary" style="width:100%;">
                        <i class="fa-solid fa-plus"></i> Ir a Entrada de Stock (Abastecer)
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Sales Widget -->
    <div class="panel">
        <div class="panel-header">
            <h2>Transacciones Recientes</h2>
            <a href="{{ route('sales.history') }}" class="btn btn-sm btn-secondary">Ver Historial</a>
        </div>
        <div class="panel-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Factura</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th style="text-align:center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($recentSales->isEmpty())
                            <tr>
                                <td colspan="4" style="text-align:center; padding:2rem; color:var(--text-muted);">No se han registrado ventas aún.</td>
                            </tr>
                        @else
                            @foreach ($recentSales as $sale)
                                <tr>
                                    <td><code>#{{ $sale->id }}</code></td>
                                    <td>
                                        <div style="font-weight:600; font-size:0.9rem;">{{ $sale->customer->name }}</div>
                                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m, H:i') }}</div>
                                    </td>
                                    <td style="font-weight:700; color:var(--primary);">$ {{ number_format($sale->total_amount, 2) }}</td>
                                    <td style="text-align:center;">
                                        <button onclick="showSaleDetails({{ $sale->id }})" class="btn btn-sm btn-secondary">
                                            <i class="fa-solid fa-eye"></i> Detalles
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="saleDetailsModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Detalles de Facturación</h3>
            <span onclick="closeModal('saleDetailsModal')" class="modal-close">&times;</span>
        </div>
        <div class="modal-body" id="sale-details-content">
            <!-- Populated via AJAX -->
        </div>
        <div class="modal-footer">
            <button onclick="closeModal('saleDetailsModal')" class="btn btn-secondary btn-sm">Cerrar</button>
        </div>
    </div>
</div>
@endsection
