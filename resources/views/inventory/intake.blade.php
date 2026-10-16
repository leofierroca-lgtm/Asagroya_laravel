@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Gestión de Entrada de Stock</h1>
        <p>Registre lotes de inventario entrantes, corrija cantidades y ajuste la estructura de precios de compra</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>

<!-- Tab Navigation Header Controls -->
<div style="display:flex; gap:0.5rem; border-bottom:1px solid var(--border-color); margin-bottom:1.5rem; padding-bottom:0.25rem;">
    <button id="tab-history-btn" onclick="switchIntakeTab('history')" style="background:none; border:none; color:var(--primary); font-weight:700; border-bottom:3px solid var(--primary); border-radius:0; padding:0.65rem 1.25rem; font-size:0.95rem; cursor:pointer; font-family:inherit; transition:var(--transition); outline:none;">
        <i class="fa-solid fa-list-check"></i> Historial de Entradas
    </button>
    <button id="tab-entry-btn" onclick="switchIntakeTab('entry')" style="background:none; border:none; color:var(--text-secondary); border-bottom:3px solid transparent; border-radius:0; padding:0.65rem 1.25rem; font-size:0.95rem; cursor:pointer; font-family:inherit; transition:var(--transition); outline:none;">
        <i class="fa-solid fa-truck-ramp-box"></i> Registrar Entrada de Stock
    </button>
</div>

<!-- Tab Content Wrapper -->
<div class="tabs-container">
    
    <!-- TAB 1: Searchable Intake History Grid -->
    <div id="tab-history">
        <div class="panel">
            <div class="panel-header">
                <h2>Historial de Entradas de Stock (Cultivos y Lotes)</h2>
            </div>
            <div class="panel-body" style="padding:0;">
                <div class="table-responsive">
                    <table class="table" style="line-height: 1.6;">
                        <thead>
                            <tr>
                                <th>Fecha de Ingreso</th>
                                <th>Producto Hidropónico</th>
                                <th style="text-align:center;">Cantidad Recibida</th>
                                <th style="text-align:right;">Costo Unitario Compra</th>
                                <th style="text-align:center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($recentIntakes->isEmpty())
                                <tr>
                                    <td colspan="5" style="text-align:center; padding:2.5rem 1rem; color:var(--text-muted);">No se han registrado entradas de stock aún.</td>
                                </tr>
                            @else
                                @foreach ($recentIntakes as $in)
                                    <tr>
                                        <td style="font-size:0.85rem; white-space:nowrap; vertical-align: middle;">{{ \Carbon\Carbon::parse($in->intake_date)->format('d/m/Y') }}</td>
                                        <td style="vertical-align: middle;">
                                            <div style="font-weight:600; font-size:0.95rem; margin-bottom: 3px;">{{ $in->product->name }}</div>
                                            <div style="font-size:0.75rem; color:var(--text-muted);">SKU: <code>{{ $in->product->sku }}</code></div>
                                        </td>
                                        <td style="text-align:center; font-weight:700; vertical-align: middle;">{{ $in->quantity }}</td>
                                        <td style="text-align:right; font-weight:600; color:var(--secondary); vertical-align: middle;">$ {{ number_format($in->purchase_cost, 2) }}</td>
                                        <td style="text-align:center; vertical-align: middle;">
                                            <div style="display:inline-flex; gap:0.25rem;">
                                                <button onclick="showIntakeCorrection({{ $in->id }})" class="btn btn-sm btn-secondary" title="Corregir detalles de entrada">
                                                    <i class="fa-solid fa-pen-to-square"></i> Corregir
                                                </button>
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
    </div>

    <!-- TAB 2: Log Intake Form -->
    <div id="tab-entry" style="display:none;">
        <div class="panel" style="max-width:700px; margin:0 auto;">
            <div class="panel-header">
                <h2>Registrar Entrada de Stock</h2>
                <span style="font-size:0.8rem; color:var(--text-muted);">* Obligatorio</span>
            </div>
            <div class="panel-body" style="padding: 1.75rem;">
                <form action="{{ route('inventory.storeIntake') }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="product_id" class="form-label" style="display:flex; justify-content:space-between; margin-bottom: 0.5rem;">
                            <span>Seleccionar Producto *</span>
                        </label>
                        <select name="product_id" id="product_id" class="form-control" required>
                            <option value="">-- Seleccione un producto del catálogo --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->name }} ({{ $p->sku }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group grid-2" style="margin-bottom: 1.5rem;">
                        <div>
                            <label for="quantity" class="form-label">Cantidad Recibida *</label>
                            <input type="number" id="quantity" name="quantity" min="1" class="form-control" placeholder="Ej: 50" required>
                        </div>
                        <div>
                            <label for="purchase_cost" class="form-label">Costo de Compra Unitario ($) *</label>
                            <input type="number" id="purchase_cost" name="purchase_cost" min="0.01" step="0.01" class="form-control" placeholder="Ej: 1200.00" required>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="intake_date" class="form-label">Fecha de Entrada *</label>
                        <input type="date" id="intake_date" name="intake_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:0.5rem; border-top:1px solid var(--border-color); padding-top:1.25rem; margin-top:1.5rem;">
                        <button type="reset" class="btn btn-secondary">Limpiar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-truck-moving"></i> Registrar Entrada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- Quantity Correction Modal -->
<div id="intakeCorrectionModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-ruler" style="color:var(--warning);"></i> Corregir Entrada de Stock</h3>
            <span onclick="closeModal('intakeCorrectionModal')" class="modal-close">&times;</span>
        </div>
        <!-- Form action will be updated dynamically via JS -->
        <form id="intake-correction-form" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div style="background-color:rgba(245, 158, 11, 0.05); border:1px solid var(--warning); border-radius:var(--radius-sm); padding:0.75rem; margin-bottom:1.25rem; font-size:0.85rem; color:var(--text-secondary);">
                    <strong>Advertencia:</strong> Ajustar la cantidad de la entrada de stock actualizará automáticamente el balance de inventario disponible para este producto en el almacén.
                </div>

                <div class="form-group grid-2">
                    <div>
                        <label for="correct-quantity" class="form-label">Cantidad Corregida *</label>
                        <input type="number" name="quantity" id="correct-quantity" min="1" class="form-control" required>
                    </div>
                    <div>
                        <label for="correct-purchase-cost" class="form-label">Costo de Compra Corregido ($) *</label>
                        <input type="number" name="purchase_cost" id="correct-purchase-cost" min="0.01" step="0.01" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="correct-intake-date" class="form-label">Fecha Corregida *</label>
                    <input type="date" name="intake_date" id="correct-intake-date" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('intakeCorrectionModal')" class="btn btn-secondary btn-sm">Cancelar</button>
                <button type="submit" class="btn btn-success btn-sm">Guardar Corrección</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchIntakeTab(tab) {
    const histTab = document.getElementById('tab-history');
    const entryTab = document.getElementById('tab-entry');
    const histBtn = document.getElementById('tab-history-btn');
    const entryBtn = document.getElementById('tab-entry-btn');
    
    if (tab === 'history') {
        histTab.style.display = 'block';
        entryTab.style.display = 'none';
        histBtn.style.color = 'var(--primary)';
        histBtn.style.borderBottomColor = 'var(--primary)';
        entryBtn.style.color = 'var(--text-secondary)';
        entryBtn.style.borderBottomColor = 'transparent';
    } else {
        histTab.style.display = 'none';
        entryTab.style.display = 'block';
        entryBtn.style.color = 'var(--primary)';
        entryBtn.style.borderBottomColor = 'var(--primary)';
        histBtn.style.color = 'var(--text-secondary)';
        histBtn.style.borderBottomColor = 'transparent';
    }
}

function showIntakeCorrection(id) {
    fetch(`{{ url('inventory/intake') }}/${id}/json`)
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                const intake = res.data;
                document.getElementById('correct-quantity').value = intake.quantity;
                document.getElementById('correct-purchase-cost').value = intake.purchase_cost;
                // Parse date if needed
                const dDate = new Date(intake.intake_date);
                const isoDate = dDate.toISOString().split('T')[0];
                document.getElementById('correct-intake-date').value = isoDate;
                
                // Update form action
                document.getElementById('intake-correction-form').action = `{{ url('inventory/intake') }}/${id}`;
                
                openModal('intakeCorrectionModal');
            } else {
                alert('No se pudieron cargar los datos de la entrada.');
            }
        });
}
</script>
@endsection
