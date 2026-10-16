@extends('layouts.app')

@section('content')
@php
    $isEdit = isset($product);
    $title = $isEdit ? 'Editar Especificaciones de Producto' : 'Registrar Nuevo Producto';
    $formAction = $isEdit ? route('products.update', $product->id) : route('products.store');
@endphp

<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>{{ $title }}</h1>
        <p>{{ $isEdit ? 'Modificar la estructura de precios y alertas de límites mínimos de stock' : 'Agregar nuevos artículos al catálogo de inventario de la bodega' }}</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver al Catálogo
        </a>
    </div>
</div>

<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Formulario de Información de Producto</h2>
        <span style="font-size:0.8rem; color:var(--text-muted);">* Campos obligatorios</span>
    </div>
    <div class="panel-body">
        
        @if ($errors->any())
            <div style="background: rgba(197, 48, 48, 0.1); border-left: 3px solid var(--danger); padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 1rem; color: var(--danger); font-size: 0.85rem;">
                <ul style="margin:0; padding-left:1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ $formAction }}" method="POST">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="form-group grid-2">
                <div>
                    <label for="sku" class="form-label">SKU / Código de Producto *</label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}" class="form-control" placeholder="Ej: PROD-LAP-100" required {{ $isEdit ? 'readonly style=background-color:var(--border-light);cursor:not-allowed;opacity:0.7;' : '' }}>
                    <span style="font-size:0.75rem; color:var(--text-muted);">Identificador único de inventario (no se puede editar después).</span>
                </div>
                <div>
                    <label for="name" class="form-label">Nombre del Producto *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-control" placeholder="Ej: Procesador Intel Core i7 Desktop" required>
                </div>
            </div>

            <div class="form-group grid-2">
                <div>
                    <label for="category" class="form-label">Categoría</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $product->category ?? '') }}" class="form-control" placeholder="Ej: Electrónica, Accesorios, Oficina" list="category-suggestions">
                    <datalist id="category-suggestions">
                        @php
                            $categories = \App\Models\Product::select('category')->distinct()->whereNotNull('category')->pluck('category');
                        @endphp
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="grid-2" style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem;">
                    <div>
                        <label for="stock" class="form-label">Stock Inicial *</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock ?? '0') }}" min="0" class="form-control" required {{ $isEdit ? 'readonly style=background-color:var(--border-light);cursor:not-allowed;opacity:0.7;' : '' }}>
                    </div>
                    <div>
                        <label for="min_stock" class="form-label">Límite Mínimo *</label>
                        <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock ?? '10') }}" min="1" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="form-group grid-2">
                <div>
                    <label for="purchase_price" class="form-label">Precio de Compra ($) *</label>
                    <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}" min="0.01" step="0.01" class="form-control" placeholder="0.00" required>
                </div>
                <div>
                    <label for="sell_price" class="form-label">Precio de Venta ($) *</label>
                    <input type="number" id="sell_price" name="sell_price" value="{{ old('sell_price', $product->sell_price ?? '') }}" min="0.01" step="0.01" class="form-control" placeholder="0.00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Especificaciones / Descripción</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Ingrese especificaciones técnicas, características, detalles de garantía...">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; border-top:1px solid var(--border-color); padding-top:1.5rem; margin-top:1.5rem;">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
