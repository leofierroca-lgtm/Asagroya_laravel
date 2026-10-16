@php
$route = request()->route()->getName();
$role = auth()->user()->role ?? '';
$name = auth()->user()->name ?? 'User';
@endphp
<div class="sidebar">
    <div class="sidebar-brand" style="flex-direction:column; padding: 0.75rem 0.5rem; text-align:center; height:auto; gap:0.25rem; border-bottom:1px solid rgba(255,255,255,0.08);">
        <img src="{{ asset('img/logo.jpg') }}" alt="Logo" style="max-height:42px; max-width:90%; border-radius:var(--radius-sm);">
        <div class="sidebar-brand-name" style="font-size:0.82rem; font-weight:700; color:#ffffff; letter-spacing:0.5px; text-transform:uppercase;">ASAGROYA</div>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ $route === 'dashboard' ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Panel de Control</span>
            </a>
        </li>

        @if ($role === 'Admin')
            <!-- ADMIN CONTROLS -->
            <li class="sidebar-item-header">Control de Inventario</li>
            <li>
                <a href="{{ route('products.index') }}" class="sidebar-link {{ in_array($route, ['products.index', 'products.show']) ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Catálogo de Productos</span>
                </a>
            </li>
            <li>
                <a href="{{ route('inventory.intake') }}" class="sidebar-link {{ $route === 'inventory.intake' ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                    <span>Entrada de Stock</span>
                </a>
            </li>

            <li class="sidebar-item-header">Operaciones de Venta</li>
            <li>
                <a href="{{ route('sales.create') }}" class="sidebar-link {{ $route === 'sales.create' ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Registrar Venta</span>
                </a>
            </li>
            <li>
                <a href="{{ route('sales.history') }}" class="sidebar-link {{ $route === 'sales.history' ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Historial de Ventas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}" class="sidebar-link {{ in_array($route, ['customers.index', 'customers.show']) ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i>
                    <span>Directorio de Clientes</span>
                </a>
            </li>

            <li class="sidebar-item-header">Reportes de Negocio</li>
            <li>
                <a href="{{ route('reports.sales') }}" class="sidebar-link {{ $route === 'reports.sales' ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Reportes de Ventas</span>
                </a>
            </li>
            <li>
                <a href="{{ route('reports.inventory') }}" class="sidebar-link {{ $route === 'reports.inventory' ? 'active' : '' }}">
                    <i class="fa-solid fa-coins"></i>
                    <span>Valuación de Inventario</span>
                </a>
            </li>

            <li class="sidebar-item-header">Control de Acceso</li>
            <li>
                <a href="{{ route('users.index') }}" class="sidebar-link {{ in_array($route, ['users.index']) ? 'active' : '' }}">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>Cuentas de Usuario</span>
                </a>
            </li>

        @elseif ($role === 'Partner')
            <!-- PARTNER CONTROLS -->
            <li class="sidebar-item-header">Catálogo y Directorio</li>
            <li>
                <a href="{{ route('products.index') }}" class="sidebar-link {{ in_array($route, ['products.index']) ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Lista de Productos</span>
                </a>
            </li>
            <li>
                <a href="{{ route('inventory.check') }}" class="sidebar-link {{ $route === 'inventory.check' ? 'active' : '' }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Consultar Inventario</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}" class="sidebar-link {{ in_array($route, ['customers.index']) ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i>
                    <span>Lista de Clientes</span>
                </a>
            </li>

            <li class="sidebar-item-header">Operaciones de Venta</li>
            <li>
                <a href="{{ route('sales.create') }}" class="sidebar-link {{ $route === 'sales.create' ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Registrar Venta</span>
                </a>
            </li>
            <li>
                <a href="{{ route('sales.history') }}" class="sidebar-link {{ $route === 'sales.history' ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Log de Ventas</span>
                </a>
            </li>

        @elseif ($role === 'Seller')
            <!-- SELLER CONTROLS -->
            <li class="sidebar-item-header">Bodega y Almacén</li>
            <li>
                <a href="{{ route('inventory.check') }}" class="sidebar-link {{ $route === 'inventory.check' ? 'active' : '' }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Consultar Stock</span>
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}" class="sidebar-link {{ in_array($route, ['customers.index']) ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i>
                    <span>Lista de Clientes</span>
                </a>
            </li>

            <li class="sidebar-item-header">Operaciones de Venta</li>
            <li>
                <a href="{{ route('sales.create') }}" class="sidebar-link {{ $route === 'sales.create' ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Registrar Venta</span>
                </a>
            </li>
            <li>
                <a href="{{ route('sales.history') }}" class="sidebar-link {{ $route === 'sales.history' ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Historial de Ventas</span>
                </a>
            </li>
        @endif
    </ul>

    <div class="sidebar-footer">
        <div class="sidebar-user" style="margin-bottom: 0.25rem;">
            <div class="sidebar-user-avatar">
                {{ strtoupper(substr($name, 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name">{{ $name }}</span>
                <span class="sidebar-user-role">
                    @php
                        $roleMap = ['Admin' => 'Administrador', 'Partner' => 'Socio', 'Seller' => 'Vendedor'];
                        echo $roleMap[$role] ?? $role;
                    @endphp
                </span>
            </div>
        </div>
        <button onclick="toggleTheme()" id="theme-toggle-btn" class="sidebar-link" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.7); font-family:inherit; font-size:0.78rem; padding:0.35rem 0.6rem;">
            <i class="fa-solid fa-moon" id="theme-icon"></i>
            <span id="theme-label">Modo Oscuro</span>
        </button>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0;">
            @csrf
            <button type="submit" class="sidebar-link" style="width: 100%; background:none; border:none; text-align:left; color:#feb2b2; font-size:0.78rem; cursor:pointer;">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar Sesión</span>
            </button>
        </form>
    </div>
</div>

<script>
function toggleTheme() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme');
    const newTheme = current === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('asagroya-theme', newTheme);
    updateThemeButton(newTheme);
}

function updateThemeButton(theme) {
    const icon = document.getElementById('theme-icon');
    const label = document.getElementById('theme-label');
    if (icon && label) {
        if (theme === 'dark') {
            icon.className = 'fa-solid fa-sun';
            label.textContent = 'Modo Claro';
        } else {
            icon.className = 'fa-solid fa-moon';
            label.textContent = 'Modo Oscuro';
        }
    }
}

// Apply saved theme on load
(function() {
    const saved = localStorage.getItem('asagroya-theme');
    if (saved) {
        document.documentElement.setAttribute('data-theme', saved);
        updateThemeButton(saved);
    }
})();
</script>
