@extends('layouts.app')

@section('content')
@php
    $isEdit = isset($customer);
    $title = $isEdit ? 'Actualizar Ficha del Cliente' : 'Inscribir Nuevo Cliente';
    $formAction = $isEdit ? route('customers.update', $customer->id) : route('customers.store');
@endphp

<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>{{ $title }}</h1>
        <p>{{ $isEdit ? 'Actualice los datos de contacto y facturación del cliente seleccionado' : 'Añada una nueva razón social o persona natural al directorio para permitir facturación' }}</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver al Directorio
        </a>
    </div>
</div>

<div class="panel" style="max-width: 750px; margin: 0 auto;">
    <div class="panel-header">
        <h2>Formulario de Datos Tributarios y Contacto</h2>
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
                    <label for="document_id" class="form-label">RUT / NIT / C.C. *</label>
                    <input type="text" id="document_id" name="document_id" value="{{ old('document_id', $customer->document_id ?? '') }}" class="form-control" placeholder="Ej: 900.123.456-7" required>
                    <span style="font-size:0.75rem; color:var(--text-muted);">Documento necesario para emitir factura legal.</span>
                </div>
                <div>
                    <label for="name" class="form-label">Razón Social / Nombre Completo *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $customer->name ?? '') }}" class="form-control" placeholder="Ej: Supermercados La Despensa" required>
                </div>
            </div>

            <div class="form-group grid-2">
                <div>
                    <label for="phone" class="form-label">Teléfono Celular / Fijo</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" class="form-control" placeholder="Ej: 310 123 4567">
                </div>
                <div>
                    <label for="email" class="form-label">Correo Electrónico Comercial</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email ?? '') }}" class="form-control" placeholder="contacto@empresa.com">
                    <span style="font-size:0.75rem; color:var(--text-muted);">Para envío de cotizaciones y facturas electrónicas.</span>
                </div>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Dirección Fiscal / Ubicación</label>
                <input type="text" id="address" name="address" value="{{ old('address', $customer->address ?? '') }}" class="form-control" placeholder="Ej: Carrera 5 # 12-34, Yaguará, Huila">
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; border-top:1px solid var(--border-color); padding-top:1.5rem; margin-top:1.5rem;">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> {{ $isEdit ? 'Guardar Cambios' : 'Completar Registro' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
