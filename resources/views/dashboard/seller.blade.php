@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Bienvenido, {{ auth()->user()->name }}</h1>
        <p>Rol: <strong style="color:rgba(255,255,255,0.9);">
            @php
                $roleMap = ['Admin' => 'Administrador', 'Partner' => 'Socio', 'Seller' => 'Vendedor'];
                echo $roleMap[auth()->user()->role] ?? auth()->user()->role;
            @endphp
        </strong> | Credenciales de acceso aprobadas.</p>
    </div>
    <div class="topbar-actions" style="display:flex; align-items:center;">
        <span style="font-size:0.9rem; color:rgba(255,255,255,0.75); font-weight:500;">
            <i class="fa-solid fa-calendar-days"></i> {{ date('d/m/Y') }}
        </span>
    </div>
</div>

<!-- Quick Shortcuts -->
<div class="dashboard-grid">
    <a href="{{ route('sales.create') }}" class="stat-card" style="cursor:pointer; background: linear-gradient(135deg, rgba(22, 163, 74, 0.08), transparent); border-left: 4px solid var(--primary);">
        <div class="stat-card-info">
            <h3 style="color:var(--primary); font-weight:700;">Punto de Venta</h3>
            <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:2px;">Registrar ventas y facturas de clientes</div>
        </div>
        <div class="stat-card-icon" style="background-color:var(--primary); color:#ffffff;">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
    </a>

    <a href="{{ route('sales.history') }}" class="stat-card" style="cursor:pointer; background: linear-gradient(135deg, rgba(20, 92, 58, 0.08), transparent); border-left: 4px solid var(--primary-light);">
        <div class="stat-card-info">
            <h3 style="color:var(--primary-light); font-weight:700;">Historial de Ventas</h3>
            <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:2px;">Consultar ventas realizadas y descargar facturas</div>
        </div>
        <div class="stat-card-icon" style="background-color:var(--primary-light); color:#ffffff;">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </div>
    </a>

    <a href="{{ route('inventory.check') }}" class="stat-card" style="cursor:pointer; background: linear-gradient(135deg, rgba(15, 81, 50, 0.08), transparent); border-left: 4px solid var(--secondary);">
        <div class="stat-card-info">
            <h3 style="color:var(--secondary); font-weight:700; filter: brightness(1.25);">Consultar Inventario</h3>
            <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:2px;">Buscar y ver detalles del stock de almacén</div>
        </div>
        <div class="stat-card-icon" style="background-color:var(--secondary); color:#ffffff;">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
    </a>

    <a href="{{ route('customers.index') }}" class="stat-card" style="cursor:pointer; background: linear-gradient(135deg, rgba(255, 255, 255, 0.02), transparent);">
        <div class="stat-card-info">
            <h3 style="font-weight:700;">Directorio de Clientes</h3>
            <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:2px;">Gestionar clientes frecuentes y RUT/NIT</div>
        </div>
        <div class="stat-card-icon info">
            <i class="fa-solid fa-user-group"></i>
        </div>
    </a>
</div>

<!-- Stock Look-up Tool Widget -->
<div class="panel">
    <div class="panel-header">
        <h2>Catálogo de Stock Rápido</h2>
        <div style="display:flex; gap:0.5rem; align-items:center;">
            <div style="position:relative;">
                <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.85rem;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="live-search" data-target-table="dashboard-stock-table" placeholder="Filtrar por nombre o SKU..." class="form-control" style="padding-left:1.8rem; font-size:0.85rem; width:220px; padding-top:0.4rem; padding-bottom:0.4rem;">
            </div>
        </div>
    </div>
    <div class="panel-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table" id="dashboard-stock-table">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nombre del Producto</th>
                        <th>Categoría</th>
                        <th>Precio Venta</th>
                        <th>Nivel de Stock</th>
                        <th style="text-align:center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($allProducts->isEmpty())
                        <tr class="no-records">
                            <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-muted);">No se encontraron registros en el inventario.</td>
                        </tr>
                    @else
                        @foreach ($allProducts as $p)
                            @php
                                $isOutOfStock = $p->stock <= 0;
                                $isLowStock = !$isOutOfStock && ($p->stock <= $p->min_stock);
                                $badgeClass = $isOutOfStock ? 'badge-danger' : ($isLowStock ? 'badge-warning' : 'badge-success');
                                $badgeText = $isOutOfStock ? 'Agotado' : ($isLowStock ? 'Stock Bajo' : 'Stock OK');
                            @endphp
                            <tr>
                                <td><code>{{ $p->sku }}</code></td>
                                <td style="font-weight:600;">{{ $p->name }}</td>
                                <td>{{ $p->category ?: 'Sin Categoría' }}</td>
                                <td style="font-weight:700; color:var(--secondary);">$ {{ number_format($p->sell_price, 2) }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $p->stock }} uds</span>
                                </td>
                                <td style="text-align:center;">
                                    <button onclick="showProductDetails({{ $p->id }})" class="btn btn-sm btn-secondary">
                                        <i class="fa-solid fa-circle-info"></i> Detalles
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        <!-- Hidden search placeholder row -->
                        <tr class="no-records" style="display:none;">
                            <td colspan="6" style="text-align:center; padding:2rem; color:var(--text-muted);">No hay productos que coincidan.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="productDetailsModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Especificaciones del Producto</h3>
            <span onclick="closeModal('productDetailsModal')" class="modal-close">&times;</span>
        </div>
        <div class="modal-body" id="product-details-content">
            <!-- Loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button onclick="closeModal('productDetailsModal')" class="btn btn-secondary btn-sm">Cerrar</button>
        </div>
    </div>
</div>
@endsection
