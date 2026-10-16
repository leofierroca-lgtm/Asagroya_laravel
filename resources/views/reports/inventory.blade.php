@extends('layouts.app')

@section('content')
<div class="topbar">
    <div class="topbar-title">
        <h1>Valuación de Inventario</h1>
        <p>Reporte patrimonial basado en costos de compra de existencias actuales</p>
    </div>
    <div class="topbar-actions">
        <button type="button" class="btn btn-secondary" onclick="window.print()"><i class="fa-solid fa-print"></i> Exportar / Imprimir</button>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
    <div class="stat-card" style="border-left:4px solid var(--secondary);">
        <div class="stat-card-info">
            <h3>Valor Total del Inventario (Costo)</h3>
            <div class="stat-value">$ {{ number_format($totalValuation, 2) }}</div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--warning);">
        <div class="stat-card-info">
            <h3>Productos con Stock Crítico</h3>
            <div class="stat-value">{{ $lowStockItems->count() }}</div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h2>Desglose de Valuación por Producto</h2>
    </div>
    <div class="panel-body" style="padding:0;">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Producto</th>
                    <th style="text-align:center;">Stock Actual</th>
                    <th style="text-align:right;">Costo Unit.</th>
                    <th style="text-align:right;">Valor Total</th>
                    <th style="text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $p)
                    @php
                        $sub = $p->stock * $p->purchase_price;
                        $status = 'OK';
                        $statusClass = 'badge-success';
                        if ($p->stock <= 0) {
                            $status = 'Agotado';
                            $statusClass = 'badge-danger';
                        } elseif ($p->stock <= $p->min_stock) {
                            $status = 'Bajo';
                            $statusClass = 'badge-warning';
                        }
                    @endphp
                    <tr>
                        <td><code>{{ $p->sku }}</code></td>
                        <td>{{ $p->name }}</td>
                        <td style="text-align:center;">{{ $p->stock }}</td>
                        <td style="text-align:right;">$ {{ number_format($p->purchase_price, 2) }}</td>
                        <td style="text-align:right; font-weight:bold;">$ {{ number_format($sub, 2) }}</td>
                        <td style="text-align:center;">
                            <span class="badge {{ $statusClass }}">{{ $status }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2rem;">No hay productos en inventario.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
