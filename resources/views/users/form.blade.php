@extends('layouts.app')

@section('content')
@php
    $isEdit = isset($user);
    $title = $isEdit ? 'Editar Cuenta de Usuario' : 'Crear Cuenta de Usuario';
    $formAction = $isEdit ? route('users.update', $user->id) : route('users.store');
@endphp

<div class="topbar">
    <div class="topbar-title">
        <h1>{{ $title }}</h1>
        <p>Complete los datos para configurar el acceso al sistema</p>
    </div>
    <div class="topbar-actions">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver a Usuarios
        </a>
    </div>
</div>

<div class="panel" style="max-width: 600px; margin: 0 auto;">
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

            <div class="form-group">
                <label for="name" class="form-label">Nombre Completo *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" required>
            </div>

            <div class="form-group grid-2">
                <div>
                    <label for="role" class="form-label">Rol del Sistema *</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="Seller" {{ old('role', $user->role ?? '') === 'Seller' ? 'selected' : '' }}>Vendedor</option>
                        <option value="Partner" {{ old('role', $user->role ?? '') === 'Partner' ? 'selected' : '' }}>Socio</option>
                        <option value="Admin" {{ old('role', $user->role ?? '') === 'Admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="form-label">Estado de Cuenta *</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Active" {{ old('status', $user->status ?? 'Active') === 'Active' ? 'selected' : '' }}>Activo (Permitir Acceso)</option>
                        <option value="Inactive" {{ old('status', $user->status ?? '') === 'Inactive' ? 'selected' : '' }}>Inactivo (Bloquear)</option>
                    </select>
                </div>
            </div>

            <div class="form-group grid-2">
                <div>
                    <label for="password" class="form-label">Contraseña {{ $isEdit ? '(Opcional)' : '*' }}</label>
                    <input type="password" id="password" name="password" class="form-control" {{ $isEdit ? '' : 'required' }}>
                </div>
                <div>
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña {{ $isEdit ? '' : '*' }}</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" {{ $isEdit ? '' : 'required' }}>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:0.75rem; border-top:1px solid var(--border-color); padding-top:1.5rem; margin-top:1.5rem;">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
