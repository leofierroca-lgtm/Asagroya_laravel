@extends('layouts.app')

@section('content')
<div class="topbar">
    <div class="topbar-title">
        <h1>Reportes Financieros de Ventas</h1>
        <p>Analíticas y consolidación de ingresos por transacciones</p>
    </div>
</div>

<div class="panel">
    <div class="panel-body">
        <form action="{{ route('reports.sales') }}" method="GET" style="display:flex; gap:1rem; align-items:flex-end;">
            <div>
                <label class="form-label">Desde</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div>
                <label class="form-label">Hasta</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Generar</button>
                <button type="button" class="btn btn-secondary" onclick="window.print()"><i class="fa-solid fa-print"></i> Imprimir</button>
            </div>
        </form>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
    <div class="stat-card" style="border-left:4px solid var(--primary);">
        <div class="stat-card-info">
            <h3>Total Ingresos (Rango)</h3>
            <div class="stat-value">$ {{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--info);">
        <div class="stat-card-info">
            <h3>Total Transacciones</h3>
            <div class="stat-value">{{ $totalTransactions }}</div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h2>Detalle de Ventas Registradas</h2>
    </div>
    <div class="panel-body" style="padding:0;">
        <table class="table">
            <thead>
                <tr>
                    <th>Factura</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Medio de Pago</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</td>
                        <td>{{ $sale->customer->name }}</td>
                        <td>{{ $sale->user->name ?? 'Sistema' }}</td>
                        <td>{{ $sale->payment_method }}</td>
                        <td style="text-align:right; font-weight:bold;">$ {{ number_format($sale->total_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2rem;">No hay ventas en este rango de fechas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
