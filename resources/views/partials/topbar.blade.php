<div class="fixed-topbar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.65rem; font-size:0.75rem; padding:0.4rem 0.75rem; border-radius:var(--radius-sm); background:linear-gradient(135deg, #0b2e1f, #0d3f2a, #145c3a); color:#ffffff; box-shadow: 0 1px 6px rgba(11,46,31,0.15);">
    
    <div style="font-weight:700; color:#ffffff; display:flex; align-items:center; gap:0.4rem; letter-spacing:0.4px; font-size:0.75rem;">
        <i class="fa-solid fa-leaf" style="font-size:0.7rem;"></i> ASAGROYA YAGUARÁ
        <button onclick="toggleTheme()" id="theme-toggle-topbar" title="Cambiar tema" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); border-radius:var(--radius-sm); cursor:pointer; color:#ffffff; font-size:0.75rem; padding:0.2rem 0.4rem; display:inline-flex; align-items:center; gap:0.2rem; transition:var(--transition); font-family:inherit;" onmouseover="this.style.background='rgba(255,255,255,0.18)';" onmouseout="this.style.background='rgba(255,255,255,0.1)';">
            <i class="fa-solid fa-circle-half-stroke"></i>
        </button>
    </div>
    
    <div style="display:flex; gap:0.5rem; align-items:center;">
        <button onclick="history.back()" style="background:none; border:none; color:rgba(255,255,255,0.7); cursor:pointer; font-family:inherit; font-size:0.75rem; display:inline-flex; align-items:center; gap:0.2rem; transition:var(--transition);" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='rgba(255,255,255,0.7)'" title="Volver">
            <i class="fa-solid fa-arrow-left"></i> Atrás
        </button>
        <span style="color:rgba(255,255,255,0.2); font-size:0.7rem;">|</span>
        <a href="{{ route('dashboard') }}" style="color:rgba(255,255,255,0.7); display:inline-flex; align-items:center; gap:0.2rem; transition:var(--transition);" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='rgba(255,255,255,0.7)'" title="Ir al panel">
            <i class="fa-solid fa-house"></i> Inicio
        </a>
        <span style="color:rgba(255,255,255,0.2); font-size:0.7rem;">|</span>
        <form action="{{ route('logout') }}" method="POST" style="margin:0; padding:0; display:inline;">
            @csrf
            <button type="submit" style="background:none; border:none; color:#feb2b2; font-weight:600; display:inline-flex; align-items:center; gap:0.2rem; transition:var(--transition); cursor:pointer;" onmouseover="this.style.color='#fecaca'" onmouseout="this.style.color='#feb2b2'" title="Cerrar sesión">
                <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
            </button>
        </form>
    </div>
</div>
