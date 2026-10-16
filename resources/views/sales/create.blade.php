@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Punto de Venta (POS)</h1>
        <p>Registre facturación a clientes seleccionando productos del catálogo y cantidades</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; align-items:start;">
    
    <!-- Left Column: Cart Form -->
    <div class="panel">
        <div class="panel-header">
            <h2>Generar Nueva Factura de Venta</h2>
            <span style="font-size:0.8rem; color:var(--text-muted);">* Obligatorio</span>
        </div>
        <div class="panel-body">
            <form id="pos-form" action="{{ route('sales.store') }}" method="POST">
                @csrf
                <div class="form-group grid-2">
                    <!-- Client Selection -->
                    <div>
                        <label for="customer_id" class="form-label" style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                            <span>Cliente a Facturar *</span>
                            <a href="{{ route('customers.create') }}" target="_blank" style="color:var(--primary); font-size:0.75rem; text-decoration:underline;">
                                <i class="fa-solid fa-plus-circle"></i> Crear nuevo
                            </a>
                        </label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">-- Seleccione un cliente del directorio --</option>
                            @foreach ($customers as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->name }} ({{ $c->document_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="form-label">Método de Pago *</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Transferencia">Transferencia Bancaria (Nequi/Bancolombia)</option>
                            <option value="Crédito">Crédito a 30 días</option>
                        </select>
                    </div>
                </div>

                <hr style="border:0; border-top:1px dashed var(--border-color); margin:1.5rem 0;">
                
                <h3 style="font-size:0.95rem; margin-bottom:1rem; color:var(--text-secondary);">Detalle de Productos</h3>
                
                <!-- Product Line Items -->
                <div id="cart-items" style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:1.5rem;">
                    <!-- First line item (default) -->
                    <div class="cart-item-row" style="display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:0.5rem; align-items:end;">
                        <div>
                            <label class="form-label" style="font-size:0.75rem;">Producto *</label>
                            <select name="items[0][product_id]" class="form-control pos-product-select" required onchange="updateRowSubtotal(this)">
                                <option value="">-- Seleccionar --</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->sell_price }}" data-stock="{{ $p->stock }}">
                                        {{ $p->name }} (${{ number_format($p->sell_price, 0) }} - {{ $p->stock }} uds)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label" style="font-size:0.75rem;">Cantidad *</label>
                            <input type="number" name="items[0][quantity]" class="form-control pos-qty-input" value="1" min="1" required oninput="updateRowSubtotal(this)">
                        </div>
                        <div>
                            <label class="form-label" style="font-size:0.75rem;">Precio Unit. ($) *</label>
                            <input type="number" name="items[0][unit_price]" class="form-control pos-price-input" min="0.01" step="0.01" required oninput="updateRowSubtotal(this)">
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary" onclick="removeCartRow(this)" style="padding:0.6rem; color:var(--danger);" title="Quitar línea" disabled>
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-start; margin-bottom:1.5rem;">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="addCartRow()" style="font-size:0.8rem;">
                        <i class="fa-solid fa-plus"></i> Añadir otra línea
                    </button>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:1rem; border-top:1px solid var(--border-color); padding-top:1.5rem;">
                    <a href="{{ route('sales.history') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary" id="pos-submit-btn">
                        <i class="fa-solid fa-cash-register"></i> Facturar e Imprimir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Receipt Live Preview -->
    <div class="panel" style="position:sticky; top:1rem; background-color:var(--bg-secondary);">
        <div class="panel-body" style="padding:1.5rem;">
            <div style="text-align:center; margin-bottom:1rem; border-bottom:1px dashed var(--border-color); padding-bottom:1rem;">
                <h3 style="font-family:monospace; font-size:1.1rem; letter-spacing:1px;">FACTURA DE VENTA</h3>
                <div style="font-size:0.75rem; color:var(--text-muted); font-family:monospace;">Asagroya Yaguará S.A.S</div>
            </div>

            <div id="receipt-preview-items" style="font-family:monospace; font-size:0.8rem; margin-bottom:1rem; min-height:100px;">
                <!-- Live preview populated by JS -->
                <div style="color:var(--text-muted); text-align:center; padding:2rem 0;">Añada productos para previsualizar...</div>
            </div>

            <div style="border-top:2px solid var(--border-color); padding-top:1rem;">
                <div style="display:flex; justify-content:space-between; font-size:0.9rem; margin-bottom:0.25rem;">
                    <span style="font-family:monospace;">Subtotal:</span>
                    <span style="font-family:monospace;" id="receipt-subtotal">$ 0.00</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:1.15rem; font-weight:700;">
                    <span style="font-family:monospace;">TOTAL COBRAR:</span>
                    <span style="font-family:monospace; color:var(--primary);" id="receipt-total">$ 0.00</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let rowCount = 1;

function addCartRow() {
    const container = document.getElementById('cart-items');
    const newRow = document.createElement('div');
    newRow.className = 'cart-item-row';
    newRow.style = 'display:grid; grid-template-columns: 2fr 1fr 1fr auto; gap:0.5rem; align-items:end;';
    
    newRow.innerHTML = `
        <div>
            <select name="items[${rowCount}][product_id]" class="form-control pos-product-select" required onchange="updateRowSubtotal(this)">
                <option value="">-- Seleccionar --</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}" data-price="{{ $p->sell_price }}" data-stock="{{ $p->stock }}">
                        {{ $p->name }} (\${{ number_format($p->sell_price, 0) }} - {{ $p->stock }} uds)
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="number" name="items[${rowCount}][quantity]" class="form-control pos-qty-input" value="1" min="1" required oninput="updateRowSubtotal(this)">
        </div>
        <div>
            <input type="number" name="items[${rowCount}][unit_price]" class="form-control pos-price-input" min="0.01" step="0.01" required oninput="updateRowSubtotal(this)">
        </div>
        <div>
            <button type="button" class="btn btn-secondary" onclick="removeCartRow(this)" style="padding:0.6rem; color:var(--danger);" title="Quitar línea">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    
    container.appendChild(newRow);
    rowCount++;
    
    // Enable delete buttons for all if multiple exist
    updateDeleteButtons();
}

function removeCartRow(btn) {
    btn.closest('.cart-item-row').remove();
    updateDeleteButtons();
    recalculateTotal();
}

function updateDeleteButtons() {
    const rows = document.querySelectorAll('.cart-item-row');
    const btns = document.querySelectorAll('.cart-item-row button');
    
    if (rows.length === 1) {
        btns[0].disabled = true;
    } else {
        btns.forEach(b => b.disabled = false);
    }
}

function updateRowSubtotal(element) {
    const row = element.closest('.cart-item-row');
    const select = row.querySelector('.pos-product-select');
    const priceInput = row.querySelector('.pos-price-input');
    const qtyInput = row.querySelector('.pos-qty-input');
    
    // If product changed, auto-fill price and check max stock
    if (element.classList.contains('pos-product-select')) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            priceInput.value = option.getAttribute('data-price');
            qtyInput.max = option.getAttribute('data-stock');
        } else {
            priceInput.value = '';
            qtyInput.max = '';
        }
    }
    
    // Check stock overflow
    const max = parseInt(qtyInput.max);
    const qty = parseInt(qtyInput.value);
    if (max && qty > max) {
        qtyInput.value = max;
        alert('Stock máximo disponible es ' + max);
    }
    
    recalculateTotal();
}

function recalculateTotal() {
    let total = 0;
    const previewContainer = document.getElementById('receipt-preview-items');
    previewContainer.innerHTML = '';
    
    let hasItems = false;
    
    const rows = document.querySelectorAll('.cart-item-row');
    rows.forEach(row => {
        const select = row.querySelector('.pos-product-select');
        const priceInput = row.querySelector('.pos-price-input');
        const qtyInput = row.querySelector('.pos-qty-input');
        
        if (select.value && priceInput.value && qtyInput.value) {
            hasItems = true;
            const productName = select.options[select.selectedIndex].text.split(' ($')[0].substring(0, 20); // truncate
            const price = parseFloat(priceInput.value);
            const qty = parseInt(qtyInput.value);
            const sub = price * qty;
            
            total += sub;
            
            previewContainer.innerHTML += `
                <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                    <span>${qty}x ${productName}</span>
                    <span>$${sub.toFixed(2)}</span>
                </div>
            `;
        }
    });
    
    if (!hasItems) {
        previewContainer.innerHTML = '<div style="color:var(--text-muted); text-align:center; padding:2rem 0;">Añada productos para previsualizar...</div>';
    }
    
    document.getElementById('receipt-subtotal').textContent = `$ ${total.toFixed(2)}`;
    document.getElementById('receipt-total').textContent = `$ ${total.toFixed(2)}`;
}
</script>
@endsection
