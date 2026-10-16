<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Asagroya - Gestión de Inventario y Ventas')</title>
    
    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Style System with cache-busting -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
</head>
<body>
    <script>
        // Apply saved theme instantly to prevent flash
        (function() {
            var saved = localStorage.getItem('asagroya-theme');
            if (saved) document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>

    <!-- Load Dynamic Toast Alerts -->
    @include('partials.alert')

    <div class="app-container">
        
        <!-- Sidebar Navigation -->
        @auth
            @include('partials.sidebar')
        @endauth
        
        <!-- Main Content Area Wrapper -->
        <div class="main-wrapper" @guest style="margin-left: 0;" @endguest>
            @auth
                @include('partials.topbar')
            @endauth
            
            <!-- Mobile Navigation Toggle Header (Hidden on Desktop) -->
            @auth
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; padding:0.5rem 0.75rem; background:linear-gradient(135deg, #0b2e1f, #0d3f2a); border-radius:var(--radius-sm); border:1px solid rgba(255,255,255,0.05);" class="mobile-only-header">
                <button id="menu-toggle" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#ffffff;">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div style="font-weight:700; font-size:1.1rem; color:#ffffff; letter-spacing:0.5px;">ASAGROYA YAGUARÁ</div>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:var(--danger); font-size:1.2rem; cursor:pointer; display:inline-flex; align-items:center;" title="Cerrar Sesión">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
            @endauth

            <!-- CSS style for helper hide/show inline -->
            <style>
                .mobile-only-header { display: none !important; }
                @media(max-width: 768px) {
                    .mobile-only-header { display: flex !important; }
                }
            </style>

            @yield('content')

        </div> <!-- Close .main-wrapper -->
    </div> <!-- Close .app-container -->

    <!-- Load JavaScript Utility Engine with cache-busting -->
    <script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
</body>
</html>
