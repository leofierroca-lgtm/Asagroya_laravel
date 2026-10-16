<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASAGROYA YAGUARÁ | Hidroponía y Hortalizas</title>
    <meta name="description" content="Sistema inteligente para la gestión de forraje verde hidropónico, tomate, lechuga y otras hortalizas bajo producción moderna y trazable." />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ─── CSS Reset & Custom Properties ─── */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --forest-900: #0b2e1f;
            --forest-800: #0d3f2a;
            --forest-700: #145c3a;
            --forest-600: #1a7a4e;
            --forest-500: #22a268;
            --forest-400: #3cc484;
            --forest-300: #6ddbaa;
            --forest-200: #a8eece;
            --forest-100: #d4f7e6;
            --forest-50:  #edfcf4;

            --earth-900: #3b2510;
            --earth-800: #5c3a1a;
            --earth-700: #7a5230;
            --earth-600: #9a6d45;
            --earth-500: #b8895e;
            --earth-400: #c9a47e;
            --earth-300: #dbc0a0;
            --earth-200: #e8d6c0;
            --earth-100: #f4ebe0;
            --earth-50:  #faf6f0;

            --sand-bg: #f5f0e8;
            --sand-light: #faf7f2;

            --text-dark: #1a2e1f;
            --text-body: #3d5a47;
            --text-muted: #7a917f;
            --text-light: #c5d4c9;

            --radius-sm: 12px;
            --radius-md: 18px;
            --radius-lg: 24px;
            --radius-xl: 32px;
            --radius-2xl: 40px;

            --shadow-sm: 0 2px 8px rgba(11, 46, 31, 0.06);
            --shadow-md: 0 8px 24px rgba(11, 46, 31, 0.08);
            --shadow-lg: 0 16px 48px rgba(11, 46, 31, 0.12);
            --shadow-xl: 0 24px 64px rgba(11, 46, 31, 0.16);
            --shadow-glow: 0 8px 32px rgba(34, 162, 104, 0.25);

            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', system-ui, -apple-system, sans-serif;
            background: var(--sand-bg);
            color: var(--text-dark);
            overflow-x: hidden;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Utility ─── */
        .container { max-width: 1280px; margin: 0 auto; padding: 0 24px; }
        @media (min-width: 768px) { .container { padding: 0 40px; } }
        @media (min-width: 1024px) { .container { padding: 0 56px; } }

        /* ─── NAVBAR ─── */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 16px 0;
            transition: var(--transition);
        }
        .navbar.scrolled {
            background: rgba(245, 240, 232, 0.92);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            box-shadow: 0 1px 0 rgba(11, 46, 31, 0.06);
        }
        .navbar-inner {
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; color: var(--text-dark);
        }
        .nav-brand-logo {
            width: 48px; height: 48px; border-radius: 16px;
            object-fit: cover;
            box-shadow: var(--shadow-md);
        }
        .nav-brand-text h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem; font-weight: 800;
            letter-spacing: 0.12em;
            color: var(--forest-900);
            line-height: 1.1;
        }
        .nav-brand-text span {
            font-size: 0.65rem; font-weight: 600;
            letter-spacing: 0.25em;
            color: var(--forest-600);
            text-transform: uppercase;
        }
        .nav-links {
            display: none; align-items: center; gap: 32px; list-style: none;
        }
        .nav-links a {
            text-decoration: none; font-size: 0.9rem; font-weight: 500;
            color: var(--text-body); transition: var(--transition);
            position: relative;
        }
        .nav-links a::after {
            content: ''; position: absolute; bottom: -4px; left: 0;
            width: 0; height: 2px; background: var(--forest-500);
            transition: width 0.3s ease;
        }
        .nav-links a:hover { color: var(--forest-700); }
        .nav-links a:hover::after { width: 100%; }

        .nav-login-btn {
            display: none; align-items: center; gap: 8px;
            padding: 12px 28px;
            background: linear-gradient(135deg, var(--forest-800), var(--forest-600));
            color: #fff; font-weight: 600; font-size: 0.9rem;
            border: none; border-radius: var(--radius-lg); cursor: pointer;
            box-shadow: var(--shadow-glow);
            transition: var(--transition);
            text-decoration: none;
            font-family: 'Outfit', sans-serif;
        }
        .nav-login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 36px rgba(34, 162, 104, 0.35);
        }

        /* Mobile menu button */
        .nav-mobile-toggle {
            display: flex; align-items: center; justify-content: center;
            width: 44px; height: 44px; border-radius: var(--radius-sm);
            border: 1px solid rgba(11, 46, 31, 0.1);
            background: rgba(255,255,255,0.7); backdrop-filter: blur(8px);
            cursor: pointer; color: var(--forest-800);
            transition: var(--transition);
        }
        .nav-mobile-toggle:hover { background: rgba(255,255,255,0.95); }

        .mobile-menu {
            display: none; position: fixed; top: 80px; left: 0; right: 0;
            background: rgba(245, 240, 232, 0.97);
            backdrop-filter: blur(20px);
            padding: 24px;
            border-bottom: 1px solid rgba(11, 46, 31, 0.08);
            z-index: 99;
        }
        .mobile-menu.active { display: block; }
        .mobile-menu a {
            display: block; padding: 12px 16px; font-size: 1rem;
            font-weight: 500; color: var(--text-body);
            text-decoration: none; border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        .mobile-menu a:hover { background: rgba(34, 162, 104, 0.08); color: var(--forest-700); }
        .mobile-menu .mobile-login-btn {
            display: block; text-align: center; margin-top: 16px;
            padding: 14px; background: linear-gradient(135deg, var(--forest-800), var(--forest-600));
            color: #fff; font-weight: 600; border-radius: var(--radius-md); text-decoration: none;
        }

        @media (min-width: 768px) {
            .nav-links { display: flex; }
            .nav-login-btn { display: inline-flex; }
            .nav-mobile-toggle { display: none; }
        }

        /* ─── HERO SECTION ─── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex; align-items: center;
            padding: 120px 0 80px;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute; inset: 0;
            background: linear-gradient(155deg, var(--forest-800) 0%, var(--forest-900) 35%, #0a2318 100%);
            z-index: 0;
        }
        .hero-bg::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse at 70% 30%, rgba(34, 162, 104, 0.15) 0%, transparent 60%),
                        radial-gradient(ellipse at 20% 80%, rgba(184, 137, 94, 0.1) 0%, transparent 50%);
        }
        .hero-leaf-image {
            position: absolute; right: -5%; top: 5%;
            width: 55%; max-width: 700px; height: auto;
            object-fit: contain;
            opacity: 0.4;
            z-index: 1;
            pointer-events: none;
            animation: heroFloat 8s ease-in-out infinite;
        }
        @keyframes heroFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        .hero-content {
            position: relative; z-index: 2;
        }
        .hero-grid {
            display: grid; gap: 48px;
            align-items: center;
        }
        @media (min-width: 1024px) {
            .hero-grid { grid-template-columns: 1.2fr 0.8fr; gap: 64px; }
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 20px; border-radius: 50px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            font-size: 0.8rem; font-weight: 500; color: var(--forest-200);
            margin-bottom: 24px;
        }
        .hero-badge-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--forest-400);
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.3); }
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 900;
            line-height: 1.05;
            color: #fff;
            letter-spacing: -0.02em;
            margin-bottom: 20px;
        }
        .hero-title em {
            font-style: italic;
            color: var(--forest-300);
        }

        .hero-desc {
            font-size: 1.1rem; line-height: 1.7;
            color: rgba(255, 255, 255, 0.65);
            max-width: 520px;
            margin-bottom: 32px;
        }

        .hero-actions {
            display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 40px;
        }
        .btn-hero-primary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 36px;
            background: linear-gradient(135deg, var(--earth-500), var(--earth-600));
            color: #fff; font-weight: 700; font-size: 1rem;
            border: none; border-radius: 50px; cursor: pointer;
            box-shadow: 0 8px 24px rgba(184, 137, 94, 0.35);
            transition: var(--transition);
            font-family: 'Outfit', sans-serif;
            text-decoration: none;
        }
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 36px rgba(184, 137, 94, 0.5);
        }
        .btn-hero-secondary {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 16px 36px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.85); font-weight: 600; font-size: 1rem;
            border-radius: 50px; cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-family: 'Outfit', sans-serif;
        }
        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        /* Hero stats */
        .hero-stats {
            display: flex; flex-wrap: wrap; gap: 32px;
        }
        .hero-stat {
            display: flex; align-items: center; gap: 10px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
        }
        .hero-stat-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--forest-400);
        }

        /* Hero right panel - glassmorphism card */
        .hero-panel {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-2xl);
            padding: 32px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .hero-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .hero-panel-header h3 {
            color: #fff; font-size: 1.15rem; font-weight: 700;
        }
        .hero-panel-badge {
            padding: 4px 12px; border-radius: 50px;
            background: rgba(34, 162, 104, 0.15);
            border: 1px solid rgba(34, 162, 104, 0.2);
            color: var(--forest-300); font-size: 0.7rem; font-weight: 600;
        }
        .hero-panel-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
        }
        .hero-panel-stat {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: var(--radius-md);
            padding: 16px;
        }
        .hero-panel-stat-label {
            font-size: 0.7rem; text-transform: uppercase;
            letter-spacing: 0.15em; color: var(--forest-200);
            margin-bottom: 8px;
        }
        .hero-panel-stat-value {
            font-size: 1.5rem; font-weight: 800; color: #fff;
        }

        /* ─── FEATURES ROW ─── */
        .features-bar {
            position: relative; z-index: 10;
            margin-top: -60px;
            padding: 0 0 60px;
        }
        .features-bar-inner {
            background: linear-gradient(135deg, var(--forest-800), var(--forest-700));
            border-radius: var(--radius-2xl);
            padding: 40px;
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 32px;
            box-shadow: var(--shadow-xl);
        }
        .feature-item {
            text-align: center; color: #fff;
        }
        .feature-icon {
            width: 52px; height: 52px; margin: 0 auto 16px;
            border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: var(--forest-200);
            transition: var(--transition);
        }
        .feature-item:hover .feature-icon {
            background: rgba(255,255,255,0.18);
            transform: translateY(-4px);
        }
        .feature-item h4 {
            font-size: 0.95rem; font-weight: 700; margin-bottom: 6px;
        }
        .feature-item p {
            font-size: 0.8rem; color: rgba(255,255,255,0.55); line-height: 1.5;
        }

        /* ─── ABOUT SECTION ─── */
        .about {
            padding: 80px 0;
        }
        .about-grid {
            display: grid; gap: 48px;
        }
        @media (min-width: 1024px) {
            .about-grid { grid-template-columns: 1fr 1fr; align-items: center; }
        }
        .about-tag {
            display: inline-block;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.2em; color: var(--forest-600);
            margin-bottom: 16px;
        }
        .about-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 900; line-height: 1.15;
            color: var(--text-dark);
            margin-bottom: 20px;
        }
        .about-desc {
            font-size: 1.05rem; color: var(--text-body);
            line-height: 1.8; margin-bottom: 28px;
        }
        .about-highlights {
            display: flex; flex-direction: column; gap: 16px;
        }
        .about-highlight {
            display: flex; align-items: flex-start; gap: 14px;
        }
        .about-highlight-icon {
            flex-shrink: 0; width: 40px; height: 40px;
            border-radius: var(--radius-sm);
            background: var(--forest-50);
            display: flex; align-items: center; justify-content: center;
            color: var(--forest-600); font-size: 0.9rem;
        }
        .about-highlight h5 {
            font-size: 0.95rem; font-weight: 700; color: var(--text-dark);
            margin-bottom: 2px;
        }
        .about-highlight p {
            font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;
        }

        /* About image card */
        .about-image-card {
            position: relative; border-radius: var(--radius-2xl); overflow: hidden;
            box-shadow: var(--shadow-xl);
        }
        .about-image-card img {
            width: 100%; height: 400px; object-fit: cover;
            display: block;
        }
        .about-image-overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 32px;
            background: linear-gradient(0deg, rgba(11, 46, 31, 0.85) 0%, transparent 100%);
        }
        .about-image-overlay h4 {
            color: #fff; font-size: 1.2rem; font-weight: 700;
        }
        .about-image-overlay p {
            color: rgba(255,255,255,0.65); font-size: 0.85rem; margin-top: 4px;
        }

        /* ─── GALLERY / PRODUCTS GRID ─── */
        .gallery {
            padding: 0 0 80px;
        }
        .gallery-header {
            text-align: center; margin-bottom: 48px;
        }
        .gallery-tag {
            display: inline-block;
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.2em; color: var(--forest-600);
            margin-bottom: 12px;
        }
        .gallery-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 3vw, 2.5rem);
            font-weight: 900; color: var(--text-dark);
        }

        .gallery-grid {
            display: grid; gap: 20px;
            grid-template-columns: 1fr;
        }
        @media (min-width: 640px) {
            .gallery-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (min-width: 1024px) {
            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: auto auto;
            }
            .gallery-card:first-child {
                grid-column: span 2;
                grid-row: span 2;
            }
        }

        .gallery-card {
            position: relative;
            border-radius: var(--radius-xl);
            overflow: hidden;
            cursor: pointer;
            transition: var(--transition);
        }
        .gallery-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-xl); }

        .gallery-card img {
            width: 100%; height: 240px; object-fit: cover; display: block;
            transition: transform 0.6s ease;
        }
        .gallery-card:first-child img { height: 100%; min-height: 500px; }
        .gallery-card:hover img { transform: scale(1.05); }

        .gallery-card-overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 24px;
            background: linear-gradient(0deg, rgba(11, 46, 31, 0.8) 0%, transparent 100%);
        }
        .gallery-card-overlay h4 {
            color: #fff; font-size: 1rem; font-weight: 700;
        }
        .gallery-card-overlay p {
            color: rgba(255,255,255,0.6); font-size: 0.8rem; margin-top: 4px;
        }

        /* Variation cards with solid bg */
        .gallery-card-solid {
            background: linear-gradient(135deg, var(--earth-700), var(--earth-800));
            display: flex; flex-direction: column; justify-content: center;
            padding: 32px;
        }
        .gallery-card-solid .gcs-icon {
            width: 48px; height: 48px; border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.12);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; color: var(--earth-200);
            margin-bottom: 16px;
        }
        .gallery-card-solid h4 {
            color: #fff; font-size: 1.05rem; font-weight: 700;
            margin-bottom: 8px;
        }
        .gallery-card-solid p {
            color: rgba(255,255,255,0.55); font-size: 0.85rem; line-height: 1.5;
        }

        .gallery-card-forest {
            background: linear-gradient(135deg, var(--forest-700), var(--forest-800));
        }

        /* ─── FOOTER ─── */
        .site-footer {
            border-top: 1px solid rgba(11, 46, 31, 0.08);
            padding: 40px 0;
        }
        .footer-inner {
            display: flex; flex-wrap: wrap;
            align-items: center; justify-content: space-between; gap: 20px;
        }
        .footer-brand {
            font-weight: 800; font-size: 1rem; letter-spacing: 0.12em;
            color: var(--forest-900);
        }
        .footer-brand span {
            display: block; font-size: 0.8rem; font-weight: 400;
            color: var(--text-muted); letter-spacing: normal; margin-top: 4px;
        }
        .footer-links {
            display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
        }
        .footer-links a {
            font-size: 0.85rem; color: var(--text-body);
            text-decoration: none; transition: var(--transition);
        }
        .footer-links a:hover { color: var(--forest-600); }
        .footer-version {
            font-size: 0.75rem; font-weight: 600;
            padding: 4px 12px; border-radius: 50px;
            background: var(--forest-50); color: var(--forest-700);
            border: 1px solid rgba(34, 162, 104, 0.15);
        }

        /* ─── LOGIN MODAL ─── */
        .modal-overlay {
            display: none; position: fixed; inset: 0; z-index: 1000;
            background: rgba(11, 46, 31, 0.55);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            align-items: center; justify-content: center;
            padding: 24px;
            animation: fadeIn 0.3s ease;
        }
        .modal-overlay.active { display: flex; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-card {
            position: relative;
            width: 100%; max-width: 440px;
            background: var(--sand-light);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            animation: slideUp 0.4s ease;
        }

        .modal-header-visual {
            position: relative;
            background: linear-gradient(135deg, var(--forest-800), var(--forest-900));
            padding: 32px 32px 48px;
            text-align: center;
            overflow: hidden;
        }
        .modal-header-visual::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(34, 162, 104, 0.2), transparent 60%);
        }
        .modal-header-visual img {
            position: relative; z-index: 1;
            max-height: 100px; border-radius: var(--radius-md);
            filter: drop-shadow(0 4px 16px rgba(0,0,0,0.3));
            margin-bottom: 16px;
        }
        .modal-header-visual h2 {
            position: relative; z-index: 1;
            color: #fff; font-size: 1.35rem; font-weight: 800;
        }
        .modal-header-visual p {
            position: relative; z-index: 1;
            color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-top: 6px;
        }

        .modal-close {
            position: absolute; top: 16px; right: 16px; z-index: 2;
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.7); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            transition: var(--transition);
        }
        .modal-close:hover {
            background: rgba(255,255,255,0.2); color: #fff;
        }

        .modal-body {
            padding: 32px;
            margin-top: -20px;
            position: relative;
            background: var(--sand-light);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        }

        .modal-form-group {
            margin-bottom: 18px;
        }
        .modal-form-group label {
            display: block; font-size: 0.8rem; font-weight: 600;
            color: var(--text-body); margin-bottom: 6px;
            letter-spacing: 0.02em;
        }
        .modal-input-wrap {
            position: relative;
        }
        .modal-input-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted); font-size: 0.85rem;
        }
        .modal-input-wrap input {
            width: 100%; padding: 14px 16px 14px 42px;
            background: var(--sand-bg);
            border: 1.5px solid rgba(11, 46, 31, 0.1);
            border-radius: var(--radius-sm);
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem; color: var(--text-dark);
            transition: var(--transition);
            outline: none;
        }
        .modal-input-wrap input::placeholder { color: var(--text-muted); }
        .modal-input-wrap input:focus {
            border-color: var(--forest-500);
            box-shadow: 0 0 0 4px rgba(34, 162, 104, 0.1);
            background: #fff;
        }

        .modal-submit {
            width: 100%; padding: 16px;
            background: linear-gradient(135deg, var(--forest-700), var(--forest-600));
            color: #fff; font-weight: 700; font-size: 1rem;
            border: none; border-radius: var(--radius-md); cursor: pointer;
            box-shadow: var(--shadow-glow);
            transition: var(--transition);
            font-family: 'Outfit', sans-serif;
            margin-top: 8px;
        }
        .modal-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 36px rgba(34, 162, 104, 0.35);
        }
        .modal-submit:active { transform: translateY(0); }

        .modal-footer-text {
            text-align: center; margin-top: 20px;
            font-size: 0.85rem; color: var(--text-muted);
        }
        .modal-footer-text a {
            color: var(--forest-600); font-weight: 700;
            text-decoration: underline; transition: var(--transition);
        }
        .modal-footer-text a:hover { color: var(--forest-800); }

        .modal-demo-info {
            text-align: center; margin-top: 16px; padding-top: 16px;
            border-top: 1px solid rgba(11, 46, 31, 0.06);
            font-size: 0.75rem; color: var(--text-muted); line-height: 1.7;
        }

        /* ─── ALERT TOAST (duplicated so it works on this page) ─── */
        .home-toast-container {
            position: fixed; top: 20px; right: 20px; z-index: 2000;
        }
        .home-toast {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 20px;
            border-radius: var(--radius-md);
            background: var(--sand-light);
            box-shadow: var(--shadow-lg);
            font-size: 0.9rem; color: var(--text-dark);
            animation: slideInRight 0.4s ease;
            border-left: 4px solid var(--forest-500);
        }
        .home-toast.error { border-left-color: #e53e3e; }
        .home-toast.warning { border-left-color: #d69e2e; }
        .home-toast.success { border-left-color: var(--forest-500); }
        .home-toast-close {
            cursor: pointer; color: var(--text-muted);
            background: none; border: none; font-size: 0.9rem;
            margin-left: 8px;
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ─── SCROLL ANIMATIONS ─── */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1; transform: translateY(0);
        }
    </style>
</head>
<body>

    <!-- Alert Toast (from session) -->
    @if(session('error') || session('success'))
    <div class="home-toast-container" id="home-toast-container">
        <div class="home-toast {{ session('error') ? 'error' : 'success' }}">
            <span>{{ session('error') ?? session('success') }}</span>
            <button class="home-toast-close" onclick="this.parentElement.parentElement.style.display='none'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
    <script>setTimeout(function(){ var t = document.getElementById('home-toast-container'); if(t) t.style.display='none'; }, 5000);</script>
    @endif

    <!-- NAVBAR -->
    <nav class="navbar" id="navbar">
        <div class="container navbar-inner">
            <a href="#" class="nav-brand">
                <img src="{{ asset('img/logo.jpg') }}" alt="ASAGROYA" class="nav-brand-logo">
                <div class="nav-brand-text">
                    <h1>ASAGROYA</h1>
                    <span>YAGUARÁ</span>
                </div>
            </a>

            <ul class="nav-links">
                <li><a href="#hero">Inicio</a></li>
                <li><a href="#about">Nosotros</a></li>
                <li><a href="#gallery">Productos</a></li>
                <li><a href="#footer">Contacto</a></li>
            </ul>

            <button class="nav-login-btn" id="open-login-modal" type="button">
                <i class="fa-solid fa-right-to-bracket"></i>
                Iniciar Sesión
            </button>

            <button class="nav-mobile-toggle" id="mobile-toggle" type="button" aria-label="Abrir menú">
                <i class="fa-solid fa-bars" id="mobile-icon"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobile-menu">
        <a href="#hero" class="mobile-menu-link">Inicio</a>
        <a href="#about" class="mobile-menu-link">Nosotros</a>
        <a href="#gallery" class="mobile-menu-link">Productos</a>
        <a href="#footer" class="mobile-menu-link">Contacto</a>
        <a href="#" class="mobile-login-btn" id="mobile-login-trigger">
            <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
        </a>
    </div>

    <!-- HERO -->
    <section class="hero" id="hero">
        <div class="hero-bg"></div>
        <img src="{{ asset('img/hero_leaves.jpg') }}" alt="" class="hero-leaf-image" aria-hidden="true">
        <div class="container hero-content">
            <div class="hero-grid">
                <div>
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        Agricultura inteligente e hidroponía moderna
                    </div>

                    <h2 class="hero-title">
                        Asagroya<br><em>Yaguará</em>
                    </h2>

                    <p class="hero-desc">
                        Gestión de forraje verde hidropónico, tomate, lechuga y hortalizas con
                        trazabilidad, control de riego, nutrición y ventas en una sola plataforma.
                    </p>

                    <div class="hero-actions">
                        <button class="btn-hero-primary" id="hero-login-btn" type="button">
                            <i class="fa-solid fa-leaf"></i>
                            Acceso al Sistema
                        </button>
                        <a href="#about" class="btn-hero-secondary">
                            <i class="fa-solid fa-arrow-down"></i>
                            Conocer Más
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span class="hero-stat-dot"></span>
                            95% rendimiento promedio
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-dot"></span>
                            Lotes trazables
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-dot"></span>
                            Monitoreo 24/7
                        </div>
                    </div>
                </div>

                <div class="hero-panel">
                    <div class="hero-panel-header">
                        <h3><i class="fa-solid fa-seedling" style="margin-right:8px; color:var(--forest-300)"></i> Panel Operativo</h3>
                        <span class="hero-panel-badge">Online</span>
                    </div>
                    <div class="hero-panel-grid">
                        <div class="hero-panel-stat">
                            <div class="hero-panel-stat-label">Rendimiento</div>
                            <div class="hero-panel-stat-value">95%</div>
                        </div>
                        <div class="hero-panel-stat">
                            <div class="hero-panel-stat-label">Bandejas</div>
                            <div class="hero-panel-stat-value">120</div>
                        </div>
                        <div class="hero-panel-stat">
                            <div class="hero-panel-stat-label">Riego</div>
                            <div class="hero-panel-stat-value" style="font-size:1.1rem;">Automático</div>
                        </div>
                        <div class="hero-panel-stat">
                            <div class="hero-panel-stat-label">Lotes</div>
                            <div class="hero-panel-stat-value" style="font-size:1.1rem;">Trazables</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES BAR -->
    <section class="features-bar reveal">
        <div class="container">
            <div class="features-bar-inner">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <h4>Estadísticas</h4>
                    <p>Métricas de rendimiento en tiempo real</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-recycle"></i>
                    </div>
                    <h4>Reciclamiento</h4>
                    <p>Optimización del uso de agua y recursos</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-wheat-awn"></i>
                    </div>
                    <h4>Cultivos</h4>
                    <p>FVH, tomate, lechuga y más</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <h4>Medio Ambiente</h4>
                    <p>Producción sostenible y responsable</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="reveal">
                    <span class="about-tag">Sobre la producción</span>
                    <h2 class="about-title">
                        Agricultura inteligente para FVH, tomate, lechuga y hortalizas de alto rendimiento.
                    </h2>
                    <p class="about-desc">
                        Automatizamos la gestión de lotes, riego, nutrición, trazabilidad y ventas para maximizar la producción con menor consumo de agua y mayor control operativo.
                    </p>
                    <div class="about-highlights">
                        <div class="about-highlight">
                            <div class="about-highlight-icon">
                                <i class="fa-solid fa-droplet"></i>
                            </div>
                            <div>
                                <h5>Alta Productividad</h5>
                                <p>Ciclos rápidos con rendimiento, calidad y respuesta comercial mejorada.</p>
                            </div>
                        </div>
                        <div class="about-highlight">
                            <div class="about-highlight-icon">
                                <i class="fa-solid fa-bolt"></i>
                            </div>
                            <div>
                                <h5>Ahorro de Recursos</h5>
                                <p>Optimiza agua, reduce desperdicios y fortalece producción limpia y sostenible.</p>
                            </div>
                        </div>
                        <div class="about-highlight">
                            <div class="about-highlight-icon">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>
                            <div>
                                <h5>Control Operativo</h5>
                                <p>Centraliza lote, riego, nutrientes, inventario, ventas y reportes de rendimiento.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about-image-card reveal">
                    <img src="{{ asset('img/card_forest.jpg') }}" alt="Naturaleza y sostenibilidad">
                    <div class="about-image-overlay">
                        <h4>Producción Sostenible</h4>
                        <p>Comprometidos con el medio ambiente y la comunidad de Yaguará</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GALLERY -->
    <section class="gallery" id="gallery">
        <div class="container">
            <div class="gallery-header reveal">
                <span class="gallery-tag">Nuestros Productos</span>
                <h2 class="gallery-title">Producción hidropónica de calidad para el mercado local</h2>
            </div>
            <div class="gallery-grid">
                <!-- Large card -->
                <div class="gallery-card reveal">
                    <img src="{{ asset('img/card_hydroponics.jpg') }}" alt="Producción Hidropónica">
                    <div class="gallery-card-overlay">
                        <h4>Forraje Verde Hidropónico</h4>
                        <p>Producción intensiva con tecnología de punta</p>
                    </div>
                </div>
                <!-- Small cards -->
                <div class="gallery-card reveal">
                    <img src="{{ asset('img/card_tomatoes.jpg') }}" alt="Tomates Frescos">
                    <div class="gallery-card-overlay">
                        <h4>Tomates Frescos</h4>
                        <p>Cultivo controlado y trazable</p>
                    </div>
                </div>
                <div class="gallery-card reveal">
                    <img src="{{ asset('img/card_lettuce.jpg') }}" alt="Lechuga Hidropónica">
                    <div class="gallery-card-overlay">
                        <h4>Lechugas Hidropónicas</h4>
                        <p>Frescas, limpias y nutritivas</p>
                    </div>
                </div>
                <!-- Solid cards -->
                <div class="gallery-card gallery-card-solid reveal">
                    <div class="gcs-icon"><i class="fa-solid fa-seedling"></i></div>
                    <h4>Forraje FVH</h4>
                    <p>Alimentación animal de alta calidad con ciclo de 12 días.</p>
                </div>
                <div class="gallery-card gallery-card-solid gallery-card-forest reveal">
                    <div class="gcs-icon"><i class="fa-solid fa-sun"></i></div>
                    <h4>Otras Hortalizas</h4>
                    <p>Variedad de cultivos adaptados a la región del Huila.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer" id="footer">
        <div class="container footer-inner">
            <div class="footer-brand">
                ASAGROYA YAGUARÁ
                <span>Agricultura inteligente para producción hidropónica de alto rendimiento.</span>
            </div>
            <div class="footer-links">
                <a href="#hero">Inicio</a>
                <a href="#about">Nosotros</a>
                <a href="#" id="footer-login-trigger">Acceso al Sistema</a>
                <span class="footer-version">Sistema v1.0</span>
            </div>
        </div>
        <div class="container" style="text-align:center; margin-top:20px; padding-top:16px; border-top: 1px solid rgba(11,46,31,0.06);">
            <span style="font-size:0.8rem; color:var(--text-muted);">
                © <span id="copyright-year"></span> ASAGROYA YAGUARÁ — Todos los derechos reservados
            </span>
        </div>
    </footer>

    <!-- LOGIN MODAL -->
    <div class="modal-overlay" id="login-modal">
        <div class="modal-card">
            <div class="modal-header-visual">
                <button class="modal-close" id="close-login-modal" type="button" aria-label="Cerrar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img src="{{ asset('img/logo.jpg') }}" alt="ASAGROYA YAGUARÁ">
                <h2>Bienvenido a Asagroya</h2>
                <p>Inicia sesión para gestionar la producción</p>
            </div>
            <div class="modal-body">
                <form action="{{ route('login') }}" method="POST" novalidate id="modal-login-form">
                    @csrf
                    <div class="modal-form-group">
                        <label for="modal-email">Correo Electrónico</label>
                        <div class="modal-input-wrap">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" id="modal-email" name="email" placeholder="admin@asagroya.com" required>
                        </div>
                    </div>
                    <div class="modal-form-group">
                        <label for="modal-password">Contraseña</label>
                        <div class="modal-input-wrap">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" id="modal-password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <button type="submit" class="modal-submit">
                        <i class="fa-solid fa-right-to-bracket" style="margin-right:6px;"></i>
                        Iniciar Sesión
                    </button>
                </form>

                <div class="modal-footer-text">
                    ¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ─── Navbar scroll effect ───
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 40);
        });

        // ─── Mobile menu toggle ───
        const mobileToggle = document.getElementById('mobile-toggle');
        const mobileIcon = document.getElementById('mobile-icon');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('active');
            mobileMenu.classList.toggle('active');
            mobileIcon.className = isOpen ? 'fa-solid fa-bars' : 'fa-solid fa-xmark';
        });

        // Close mobile menu on link click
        document.querySelectorAll('.mobile-menu-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                mobileIcon.className = 'fa-solid fa-bars';
            });
        });

        // ─── Login Modal ───
        const loginModal = document.getElementById('login-modal');
        const openModalBtns = [
            document.getElementById('open-login-modal'),
            document.getElementById('hero-login-btn'),
            document.getElementById('mobile-login-trigger'),
            document.getElementById('footer-login-trigger')
        ];
        const closeModalBtn = document.getElementById('close-login-modal');

        function openLoginModal(e) {
            if (e) e.preventDefault();
            loginModal.classList.add('active');
            document.body.style.overflow = 'hidden';
            // Close mobile menu if open
            mobileMenu.classList.remove('active');
            mobileIcon.className = 'fa-solid fa-bars';
            // Focus email field
            setTimeout(() => document.getElementById('modal-email').focus(), 400);
        }

        function closeLoginModal() {
            loginModal.classList.remove('active');
            document.body.style.overflow = '';
        }

        openModalBtns.forEach(btn => {
            if (btn) btn.addEventListener('click', openLoginModal);
        });

        closeModalBtn.addEventListener('click', closeLoginModal);

        // Close on overlay click
        loginModal.addEventListener('click', (e) => {
            if (e.target === loginModal) closeLoginModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && loginModal.classList.contains('active')) {
                closeLoginModal();
            }
        });

        // ─── Smooth scroll for anchor links ───
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', (e) => {
                const targetId = link.getAttribute('href');
                if (!targetId || targetId === '#') return;
                const target = document.querySelector(targetId);
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // ─── Scroll reveal animations ───
        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        revealElements.forEach(el => revealObserver.observe(el));

        // ─── Copyright year ───
        const yearEl = document.getElementById('copyright-year');
        if (yearEl) yearEl.textContent = new Date().getFullYear();
    </script>
</body>
</html>
