<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - LG Planta A</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --lg-red: #e5002b;
            --lg-red-dark: #c40024;
            --lg-red-light: rgba(229, 0, 43, 0.08);
            --lg-gray-100: #f8f9fa;
            --lg-gray-200: #e9ecef;
            --lg-gray-800: #343a40;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--lg-red) !important;
            transition: opacity 0.25s ease;
        }
        .navbar-brand:hover { opacity: 0.85; }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.08), 0 2px 4px -2px rgb(0 0 0 / 0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.08);
        }

        .btn-lg-primary {
            background: var(--lg-red);
            color: white;
            border: none;
            transition: all 0.25s ease;
        }
        .btn-lg-primary:hover {
            background: var(--lg-red-dark);
            color: white;
            transform: translateY(-1px);
        }

        .filter-btn {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
        }
        .filter-btn:hover:not(.active) {
            background: var(--lg-red-light) !important;
            border-color: var(--lg-red) !important;
            color: var(--lg-red) !important;
            transform: translateY(-2px);
        }
        .filter-btn.active {
            background: var(--lg-red) !important;
            color: white !important;
            border-color: var(--lg-red) !important;
            box-shadow: 0 2px 8px rgba(229, 0, 43, 0.35);
        }

        .table tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: var(--lg-red-light) !important;
        }

        .efficiency-badge {
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 6px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .table tbody tr:hover .efficiency-badge {
            transform: scale(1.05);
        }

        .efficiency-high { background: #d1fae5; color: #065f46; }
        .efficiency-medium { background: #fef3c7; color: #92400e; }
        .efficiency-low { background: #fee2e2; color: #991b1b; }

        .btn-download, .btn-export-csv {
            transition: all 0.25s ease;
        }
        .btn-download:hover, .btn-export-csv:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        }
        @media (max-width: 767.98px) {
            .btn-export-csv { width: 100%; min-height: 44px; }
        }
        @media (min-width: 768px) {
            .btn-export-csv { width: auto; flex-shrink: 0; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            opacity: 0;
            animation: fadeInUp 0.4s ease-out forwards;
        }
        .animate-delay-1 { animation-delay: 0.05s; }
        .animate-delay-2 { animation-delay: 0.1s; }
        .animate-delay-3 { animation-delay: 0.15s; }

        /* Responsividade - telas menores */
        @media (max-width: 767.98px) {
            .navbar .navbar-text { font-size: 0.7rem; }
            .navbar-brand { font-size: 1rem; }
            .container { padding-left: 0.75rem; padding-right: 0.75rem; max-width: 100%; }
            main.container { padding-top: 1rem; padding-bottom: 1.5rem; }
            .card-body { padding: 1rem; }
            .filter-btn { min-height: 44px; padding: 0.5rem 1rem; }
            .table th, .table td { font-size: 0.875rem; padding: 0.6rem 0.5rem; white-space: nowrap; }
        }

        /* Cards em mobile (alternativa à tabela) */
        .dashboard-mobile-cards .production-card {
            border-radius: 10px;
            border: 1px solid #e9ecef;
            padding: 1rem;
            margin-bottom: 0.75rem;
            background: #fff;
            transition: background-color 0.2s ease;
        }
        .dashboard-mobile-cards .production-card:hover {
            background: var(--lg-red-light);
        }
        .dashboard-mobile-cards .production-card:last-child { margin-bottom: 0; }
        .dashboard-mobile-cards .card-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.35rem 0;
            font-size: 0.9rem;
        }
        .dashboard-mobile-cards .card-row:not(:last-child) { border-bottom: 1px solid #f1f3f5; }
        .dashboard-mobile-cards .card-label { color: #6c757d; }
        .dashboard-mobile-cards .card-value { font-weight: 600; }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-2 py-lg-3">
        <div class="container">
            <a class="navbar-brand py-0" href="{{ route('dashboard.index') }}">
                <i class="bi bi-speedometer2 me-1 me-lg-2"></i>LG Planta A
            </a>
            <span class="navbar-text text-muted small d-none d-sm-inline">
                Eficiência de Produção — Janeiro 2026
            </span>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
