@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <img src="{{ asset('img/logo.jpg') }}" alt="Asagroya Logo" class="logo" style="width:70px; height:70px; border-radius:var(--radius-sm);">
            <h2>Registro de Usuario</h2>
            <p>El registro libre se encuentra deshabilitado. Por favor, contacte a un administrador.</p>
        </div>

        @if (session('error'))
            <div style="background: rgba(197, 48, 48, 0.1); border-left: 3px solid var(--danger); padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 1rem; color: var(--danger); font-size: 0.85rem;">
                {{ session('error') }}
            </div>
        @endif

        <div style="text-align:center; margin-top:1.5rem;">
            <a href="{{ route('login') }}" class="btn btn-secondary">Volver al Login</a>
        </div>
    </div>
</div>
@endsection
