@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Directorio de Clientes</h1>
        <p>Administre perfiles, RUT/NIT y datos de contacto de su cartera de compradores</p>
    </div>
    <div class="topbar-actions" style="display:flex; gap:0.5rem;">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Volver al Panel
        </a>
        <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center;">
            <i class="fa-solid fa-user-plus" style="margin-right:0.25rem;"></i> Registrar Cliente
        </a>
    </div>
</div>

<!-- Search Controls -->
<div class="panel">
    <div class="panel-body">
        <form action="{{ route('customers.index') }}" method="GET" class="search-filter-row">
            
            <div class="search-input-wrapper">
                <label class="form-label">Buscar en Directorio</label>
                <div style="position:relative;">
                    <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--text-muted);">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, documento, teléfono..." class="form-control" style="padding-left:2.2rem;">
                </div>
            </div>

            <div style="display:flex; align-items:flex-end; gap:0.5rem;">
                <button type="submit" class="btn btn-secondary" style="padding-top:0.7rem; padding-bottom:0.7rem;">
                    <i class="fa-solid fa-filter"></i> Buscar
                </button>
                @if (!empty($search))
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary" style="padding-top:0.7rem; padding-bottom:0.7rem; color:var(--danger); border-color:var(--danger);">
                        <i class="fa-solid fa-xmark"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="panel">
    <div class="panel-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Doc. Identidad / NIT</th>
                        <th>Razón Social / Nombre</th>
                        <th>Teléfono / Celular</th>
                        <th>Correo Electrónico</th>
                        <th>Ubicación / Dirección</th>
                        <th style="text-align:center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($customers->isEmpty())
                        <tr>
                            <td colspan="6" style="text-align:center; padding:3rem; color:var(--text-muted);">
                                <i class="fa-solid fa-address-book" style="font-size:2.5rem; display:block; margin-bottom:0.5rem; color:var(--text-muted);"></i>
                                No se encontraron clientes en el directorio.
                            </td>
                        </tr>
                    @else
                        @foreach ($customers as $c)
                            <tr>
                                <td><code>{{ $c->document_id ?: $c->tax_id }}</code></td>
                                <td style="font-weight:600;">{{ $c->name }}</td>
                                <td>
                                    @if ($c->phone)
                                        <i class="fa-solid fa-phone" style="font-size:0.75rem; color:var(--text-muted);"></i> {{ $c->phone }}
                                    @else
                                        <span style="color:var(--text-muted); font-size:0.85rem;">No registrado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($c->email)
                                        <a href="mailto:{{ $c->email }}" style="color:var(--primary); text-decoration:none;">{{ $c->email }}</a>
                                    @else
                                        <span style="color:var(--text-muted); font-size:0.85rem;">No registrado</span>
                                    @endif
                                </td>
                                <td style="font-size:0.9rem; color:var(--text-secondary);">
                                    {{ $c->address ?: 'No registrada' }}
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:inline-flex; gap:0.25rem;">
                                        <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-sm btn-secondary" title="Editar perfil de cliente">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        
                                        @if (auth()->user()->role === 'Admin')
                                            <form action="{{ route('customers.destroy', $c->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este cliente? No será posible si tiene facturas asociadas.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-secondary" style="color:var(--danger);" title="Eliminar del directorio">
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
@endsection
