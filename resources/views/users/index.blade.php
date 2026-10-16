@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="topbar">
    <div class="topbar-title">
        <h1>Cuentas de Usuario</h1>
        <p>Administre accesos, roles y permisos de los empleados al sistema</p>
    </div>
    <div class="topbar-actions" style="display:flex; gap:0.5rem;">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Volver al Panel
        </a>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center;">
            <i class="fa-solid fa-user-plus" style="margin-right:0.25rem;"></i> Nuevo Usuario
        </a>
    </div>
</div>

<div class="panel">
    <div class="panel-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre / Empleado</th>
                        <th>Correo Electrónico</th>
                        <th>Rol de Acceso</th>
                        <th style="text-align:center;">Estado</th>
                        <th style="text-align:center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="5" style="text-align:center; padding:3rem; color:var(--text-muted);">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @else
                        @foreach ($users as $u)
                            @php
                                $roleClass = 'badge-secondary';
                                if ($u->role === 'Admin') $roleClass = 'badge-danger';
                                elseif ($u->role === 'Partner') $roleClass = 'badge-primary';
                                elseif ($u->role === 'Seller') $roleClass = 'badge-success';
                            @endphp
                            <tr>
                                <td style="font-weight:600;">{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td><span class="badge {{ $roleClass }}">{{ $u->role }}</span></td>
                                <td style="text-align:center;">
                                    <span class="badge {{ $u->status === 'Active' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $u->status === 'Active' ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:inline-flex; gap:0.25rem;">
                                        <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-secondary" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        @if ($u->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-secondary" style="color:var(--danger);" title="Eliminar">
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
