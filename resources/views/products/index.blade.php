@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Catálogo de Productos</h1>
        <p>Gestionar especificaciones, estructura de precios y límites mínimos de alerta de stock</p>
    </div>
    <div class="topbar-actions" style="display:flex; gap:0.5rem;">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Volver al Panel
        </a>
        @if (auth()->user()->role === 'Admin')
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center;">
                <i class="fa-solid fa-circle-plus" style="margin-right:0.25rem;"></i> Registrar Producto
            </a>
        @endif
    </div>
</div>

<!-- Search & Filter Controls -->
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('products.index') }}" method="GET" class="search-filter-row">
            
            <div class="search-input-wrapper">
                <label class="form-label">Buscar Producto</label>
                <div style="position:relative;">
                    <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--text-muted);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar SKU, nombre o palabras clave..." class="form-control" style="padding-left:2.2rem;">
                </div>
            </div>

            <div style="width:200px;">
                <label class="form-label">Categoría</label>
                <select name="category" class="form-control">
                    <option value="">Todas las Categorías</option>
                    @php
                        $categories = \App\Models\Product::select('category')->distinct()->whereNotNull('category')->pluck('category');
                    @endphp
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; align-items:flex-end; gap:0.5rem;">
                <button type="submit" class="btn btn-secondary" style="padding-top:0.7rem; padding-bottom:0.7rem;">
                    <i class="fa-solid fa-filter"></i> Filtrar
                </button>
                @if (request('search') || request('category'))
                    <a href="{{ route('products.index') }}" class="btn btn-secondary" style="padding-top:0.7rem; padding-bottom:0.7rem; color:var(--danger); border-color:var(--danger);">
                        <i class="fa-solid fa-xmark"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Product Table -->
<div class="panel">
    <div class="panel-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:60px;">Foto</th>
                        <th>SKU / Código</th>
                        <th>Nombre del Producto</th>
                        <th>Categoría</th>
                        <th style="text-align:right;">Precio Compra</th>
                        <th style="text-align:right;">Precio Venta</th>
                        <th style="text-align:center;">Nivel Stock</th>
                        <th style="text-align:center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($products->isEmpty())
                        <tr>
                            <td colspan="8" style="text-align:center; padding:3rem; color:var(--text-muted);">
                                <i class="fa-solid fa-folder-open" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; color:var(--text-muted);"></i>
                                No se encontraron productos registrados con los filtros seleccionados.
                            </td>
                        </tr>
                    @else
                        @foreach ($products as $p)
                            @php
                                $isOutOfStock = $p->stock <= 0;
                                $isLowStock = !$isOutOfStock && ($p->stock <= $p->min_stock);
                                $badgeClass = $isOutOfStock ? 'badge-danger' : ($isLowStock ? 'badge-warning' : 'badge-success');
                                $badgeText = $isOutOfStock ? 'Agotado' : ($isLowStock ? 'Stock Bajo' : 'Stock OK');
                                
                                $skuImageMap = [
                                    'HID-TOM' => 'tomate.jpg',
                                    'HID-LEC' => 'lechuga.jpg',
                                    'HID-CIL' => 'cilantro.jpg',
                                    'HID-API' => 'apio.jpg',
                                    'HID-ACE' => 'acelga.jpg',
                                ];
                                $skuPrefix = substr($p->sku, 0, 7);
                                $productImg = $skuImageMap[$skuPrefix] ?? null;
                            @endphp
                            <tr>
                                <td style="padding:0.4rem; text-align:center; vertical-align:middle;">
                                    @if ($productImg && file_exists(public_path('img/products/' . $productImg)))
                                        <img src="{{ asset('img/products/' . $productImg) }}" alt="" style="width:45px; height:45px; object-fit:cover; border-radius:var(--radius-sm); border:1px solid var(--border-color);">
                                    @else
                                        <div style="width:45px; height:45px; background:var(--bg-secondary); border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; margin:0 auto; border:1px solid var(--border-color);">
                                            <i class="fa-solid fa-seedling" style="color:var(--primary); font-size:1.1rem;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><code>{{ $p->sku }}</code></td>
                                <td style="font-weight:600;">{{ $p->name }}</td>
                                <td>{{ $p->category ?: 'Sin Categoría' }}</td>
                                <td style="text-align:right;">$ {{ number_format($p->purchase_price, 2) }}</td>
                                <td style="text-align:right; font-weight:600; color:var(--secondary);">$ {{ number_format($p->sell_price, 2) }}</td>
                                <td style="text-align:center;">
                                    <span class="badge {{ $badgeClass }}">{{ $p->stock }} uds</span>
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:inline-flex; gap:0.25rem;">
                                        <button onclick="showProductDetails({{ $p->id }})" class="btn btn-sm btn-secondary" title="Ver especificaciones">
                                            <i class="fa-solid fa-eye"></i> Ver
                                        </button>
                                        
                                        @if (auth()->user()->role === 'Admin')
                                            <a href="{{ route('products.edit', $p->id) }}" class="btn btn-sm btn-secondary" title="Editar especificaciones">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $p->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este producto? Se validarán las dependencias en transacciones y compras.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-secondary" style="color:var(--danger);" title="Eliminar producto">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
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

<!-- Details Modal -->
<div id="productDetailsModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Especificaciones de Producto</h3>
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
        })
        .catch(err => {
            container.innerHTML = `<div style="color:var(--danger); padding:2rem; text-align:center;">Error al cargar las especificaciones del producto.</div>`;
        });
}
</script>
@endsection
