@extends('layouts.app')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <img src="{{ asset('img/logo.jpg') }}" alt="Asagroya Logo" class="logo" style="width:70px; height:70px; border-radius:var(--radius-sm);">
            <h2>Ingreso al Sistema</h2>
            <p>Bienvenido. Ingrese sus credenciales para continuar.</p>
        </div>

        @if ($errors->any())
            <div style="background: rgba(197, 48, 48, 0.1); border-left: 3px solid var(--danger); padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 1rem; color: var(--danger); font-size: 0.85rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Correo Electrónico</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-envelope" style="position:absolute; left:12px; top:12px; color:var(--text-muted); font-size:0.85rem;"></i>
                    <input type="email" id="email" name="email" class="form-control" style="padding-left:35px;" placeholder="usuario@asagroya.com" required value="{{ old('email') }}" autofocus>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="password" class="form-label">Contraseña</label>
                <div style="position:relative;">
                    <i class="fa-solid fa-lock" style="position:absolute; left:12px; top:12px; color:var(--text-muted); font-size:0.85rem;"></i>
                    <input type="password" id="password" name="password" class="form-control" style="padding-left:35px;" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:0.65rem; font-size:0.9rem;">
                <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
            </button>
        </form>

        <div style="text-align:center; margin-top:1.5rem; font-size:0.8rem; color:var(--text-muted);">
            <p>&copy; 2026 Asagroya Yaguará S.A.S.</p>
            <p style="margin-top:0.2rem;">Sistema de Gestión Agropecuaria</p>
        </div>
    </div>
</div>
<script>
    // Force dark mode switch if requested in old app logic
    if (!localStorage.getItem('asagroya-theme')) {
        localStorage.setItem('asagroya-theme', 'light');
    }
</script>
@endsection
