@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Niveles de Stock de Almacén</h1>
        <p>Consulte las cantidades de productos disponibles antes de facturar, verifique límites y especificaciones</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>

<!-- Search Controls -->
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('inventory.check') }}" method="GET" class="search-filter-row">

            <div class="search-input-wrapper">
                <label class="form-label">Consulta en Tiempo Real</label>
                <div style="position:relative;">
                    <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--text-muted);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <!-- Connects to live-search filter in app.js -->
                    <input type="text" id="live-search" data-target-table="check-inventory-table" name="search" value="{{ $search }}" placeholder="Escriba nombre del producto o SKU..." class="form-control" style="padding-left:2.2rem;">
                </div>
            </div>

            <div style="display:flex; align-items:flex-end; gap:0.5rem;">
                <button type="submit" class="btn btn-secondary" style="padding-top:0.7rem; padding-bottom:0.7rem;">
                    <i class="fa-solid fa-filter"></i> Buscar
                </button>
                @if (!empty($search))
                    <a href="{{ route('inventory.check') }}" class="btn btn-secondary" style="padding-top:0.7rem; padding-bottom:0.7rem; color:var(--danger); border-color:var(--danger);">
                        <i class="fa-solid fa-xmark"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Stock Quantities Table -->
<div class="panel">
    <div class="panel-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table" id="check-inventory-table">
                <thead>
                    <tr>
                        <th>SKU / Código</th>
                        <th>Nombre del Producto</th>
                        <th>Categoría</th>
                        <th style="text-align:right;">Precio Venta Unitario</th>
                        <th>Balance de Stock</th>
                        <th style="text-align:center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($allProducts->isEmpty())
                        <tr class="no-records">
                            <td colspan="6" style="text-align:center; padding:3.5rem 1rem; color:var(--text-muted);">
                                <i class="fa-solid fa-box-open" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; color:var(--text-muted);"></i>
                                No se encontraron registros en el inventario.
                            </td>
                        </tr>
                    @else
                        @foreach ($allProducts as $p)
                            @php
                                $isOutOfStock = $p->stock <= 0;
                                $isLowStock = !$isOutOfStock && ($p->stock <= $p->min_stock);
                                $badgeClass = $isOutOfStock ? 'badge-danger' : ($isLowStock ? 'badge-warning' : 'badge-success');
                                $badgeText = $isOutOfStock ? 'Agotado' : ($isLowStock ? 'Stock Bajo' : 'Disponible');
                            @endphp
                            <tr>
                                <td><code>{{ $p->sku }}</code></td>
                                <td style="font-weight:600;">{{ $p->name }}</td>
                                <td>{{ $p->category ?: 'Sin Categoría' }}</td>
                                <td style="text-align:right; font-weight:700; color:var(--secondary); font-size:0.95rem;">
                                    $ {{ number_format($p->sell_price, 2) }}
                                </td>
                                <td>
                                    <span class="badge {{ $badgeClass }}" style="margin-right:0.5rem;">
                                        {{ $p->stock }} uds
                                    </span>
                                    <span style="font-size:0.75rem; color:var(--text-secondary);">
                                        (mínimo: {{ $p->min_stock }})
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <button onclick="showProductDetails({{ $p->id }})" class="btn btn-sm btn-secondary">
                                        <i class="fa-solid fa-rectangle-list"></i> Especificaciones
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        <!-- Hidden placeholder row for JS live filters -->
                        <tr class="no-records" style="display:none;">
                            <td colspan="6" style="text-align:center; padding:3.5rem 1rem; color:var(--text-muted);">
                                No se encontraron registros que coincidan con la búsqueda.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Specifications Details Modal -->
<div id="productDetailsModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Especificaciones del Producto</h3>
            <span onclick="closeModal('productDetailsModal')" class="modal-close">&times;</span>
        </div>
        <div class="modal-body" id="product-details-content">
            <!-- Loaded dynamically in app.js -->
        </div>
        <div class="modal-footer">
            <button onclick="closeModal('productDetailsModal')" class="btn btn-secondary btn-sm">Cerrar</button>
        </div>
    </div>
</div>
<script>
function showProductDetails(productId) {
    const container = document.getElementById('product-details-content');
    if (!container) return;

    container.innerHTML = `<div style="text-align:center; padding:2rem; color:var(--text-secondary);"><i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem; display:block; margin-bottom:0.5rem; color:var(--primary);"></i> Cargando especificaciones...</div>`;
    openModal('productDetailsModal');

    fetch(`{{ url('products') }}/${productId}/details-json`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const p = res.data;
                const stockBadgeClass = p.stock <= 0 ? 'badge-danger' : (p.stock <= p.min_stock ? 'badge-warning' : 'badge-success');
                const pricePurchase = parseFloat(p.purchase_price).toLocaleString('es-CO', { minimumFractionDigits: 2 });
                const priceSell = parseFloat(p.sell_price).toLocaleString('es-CO', { minimumFractionDigits: 2 });

                const imageHtml = p.image_url ? `
                    <div style="width:130px; height:130px; min-width:130px; border-radius:var(--radius-md); overflow:hidden; border:2px solid var(--border-color); background:var(--input-bg); box-shadow:var(--shadow-md); display:flex; align-items:center; justify-content:center;">
                        <img src="${p.image_url}" alt="${p.name}" style="width:100%; height:100%; object-fit:cover; transition:transform 0.3s ease;" onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                    </div>
                ` : `
                    <div style="width:130px; height:130px; min-width:130px; border-radius:var(--radius-md); background:rgba(52,211,153,0.1); border:2px dashed var(--primary); display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--primary); gap:0.35rem;">
                        <i class="fa-solid fa-seedling" style="font-size:2rem;"></i>
                        <span style="font-size:0.68rem; font-weight:600; text-transform:uppercase;">Sin Imagen</span>
                    </div>
                `;

                container.innerHTML = `
                    <div style="display:flex; flex-wrap:wrap; gap:1.2rem; align-items:center; margin-bottom:1.25rem;">
                        ${imageHtml}
                        <div style="flex:1; min-width:180px;">
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px; margin-bottom:2px;">SKU / Código</div>
                            <div style="font-size:1.15rem; font-weight:700; color:var(--primary); margin-bottom:0.4rem;">
                                <code>${p.sku}</code>
                            </div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; letter-spacing:0.5px; margin-bottom:2px;">Producto</div>
                            <div style="font-size:1.2rem; font-weight:700; color:var(--text-primary); line-height:1.35;">${p.name}</div>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.85rem; margin-bottom:1rem; background:rgba(52,211,153,0.04); padding:0.75rem 1rem; border-radius:var(--radius-sm); border:1px solid var(--border-light);">
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Categoría</div>
                            <div style="font-weight:600; color:var(--text-secondary); margin-top:2px;">${p.category || 'Sin Categoría'}</div>
                        </div>
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Nivel de Stock</div>
                            <div style="margin-top:2px;">
                                <span class="badge ${stockBadgeClass}">${p.stock} uds</span> 
                                <span style="font-size:0.75rem; color:var(--text-secondary); margin-left:4px;">(Mínimo: ${p.min_stock})</span>
                            </div>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; border-top:1px solid var(--border-color); border-bottom:1px solid var(--border-color); padding:0.85rem 0; margin-bottom:1rem;">
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Precio de Compra</div>
                            <div style="font-size:1.15rem; font-weight:700; color:var(--text-primary); margin-top:2px;">$ ${pricePurchase}</div>
                        </div>
                        <div>
                            <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Precio de Venta</div>
                            <div style="font-size:1.25rem; font-weight:800; color:var(--secondary); margin-top:2px;">$ ${priceSell}</div>
                        </div>
                    </div>

                    <div>
                        <div style="font-size:0.72rem; text-transform:uppercase; color:var(--text-muted); font-weight:700; margin-bottom:4px;">Especificaciones y Descripción</div>
                        <p style="font-size:0.9rem; color:var(--text-secondary); line-height:1.5; white-space:pre-line; background:var(--input-bg); padding:0.75rem 1rem; border-radius:var(--radius-sm); border:1px solid var(--border-light); margin:0;">${p.description || 'Sin especificaciones adicionales registradas.'}</p>
                    </div>
                `;
            } else {
                container.innerHTML = `<div style="color:var(--danger); padding:2rem; text-align:center;">Error: ${res.message}</div>`;
            }
        });
}
</script>
@endsection
