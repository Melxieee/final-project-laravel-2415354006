<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ERP Dashboard') - ERP System</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite compiled Bootstrap & Icons -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6f9;
        }
        /* Sidebar styles (AdminLTE theme) */
        .main-sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            background-color: #fff;
            color: #c2c7d0;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .main-sidebar .brand-link {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            font-size: 1.25rem;
            color: black;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .main-sidebar .nav-link {
            color: black;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            border-radius: 6px;
            margin: 4px 12px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
        }
        .main-sidebar .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }
        .main-sidebar .nav-link:hover {
            background-color: oklch(93.2% 0.032 255.585);
            color: #007bff;
        }
        .main-sidebar .nav-link i {
            font-size: 1.1rem;
        }
        /* Content Wrapper */
        .content-wrapper {
            margin-left: 250px;
            min-height: 100vh;
            background-color: #f8f9fa;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            font-size: 15px;
        }
        /* Navbar */
        .main-header {
            margin-left: 250px;
            height: 56px;
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1029;
            transition: all 0.3s;
        }
        .main-header .navbar-toggler-btn {
            border: 0;
            background: transparent;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 4px 8px;
        }
        /* Toast Notifications */
        #toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1055;
        }

        body {
    font-family: 'Poppins', sans-serif;
}

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .main-sidebar {
                left: -250px;
            }
            .content-wrapper {
                margin-left: 0;
            }
            .main-header {
                margin-left: 0;
            }
            .main-sidebar.show {
                left: 0;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="mainSidebar" class="main-sidebar">
            <div>
                <!-- Brand logo -->
                <a href="#" class="brand-link">
                    <i class="bi bi-grid-fill me-2 text-primary"></i>
                    <span class="fw-bold text-primary">ERP</span>
                </a>
                
                <!-- Nav list -->
                <nav class="mt-4">
                    <!-- <a href="" class="nav-link">
                        <i class="bi bi-people-fill me-3"></i>
                        <span>Users</span>
                    </a> -->
                    <a href="{{ route('web.customers.index') }}" class="nav-link {{ request()->routeIs('web.customers.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge-fill me-3"></i>
                        <span>Customers</span>
                    </a>
                    <a href="{{ route('web.services.index') }}" class="nav-link {{ request()->routeIs('web.services.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam-fill me-3"></i>
                        <span>Services</span>
                    </a>
                    <a href="{{ route('web.subscriptions.index') }}" class="nav-link {{ request()->routeIs('web.subscriptions.*') ? 'active' : '' }}">
                        <i class="bi bi-card-checklist me-3 "></i>
                        <span>Subscription</span>
                    </a>
                </nav>
            </div>
            
            <!-- Bottom menu (Sign Out) -->
            <div class="p-3">
                <a href="#" class="nav-link text-black m-0">
                    <i class="bi bi-box-arrow-left me-3"></i>
                    <span>Sign Out</span>
                </a>
            </div>
        </aside>

        <!-- Navbar -->
        <header class="main-header">
            <div class="d-flex align-items-center">
                <button type="button" class="navbar-toggler-btn me-3 d-lg-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="m-0 font-weight-semibold">@yield('page_title', 'Dashboard')</h5>
            </div>
            <!-- <div class="d-flex align-items-center gap-3">
                <span class="text-muted text-sm d-none d-sm-inline">Logged in as: <strong>2415354006</strong></span>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; font-weight: bold;">
                    A
                </div> -->
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-wrapper">
            <main class="p-4 flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="toast-container">
        @if (session('success'))
            <div class="toast show align-items-center text-white bg-success border-0 mb-2 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="toast show align-items-center text-white bg-danger border-0 mb-2 shadow" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Sidebar Toggler for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('mainSidebar');
            const toggler = document.querySelector('.navbar-toggler-btn');
            if (window.innerWidth < 992 && sidebar.classList.contains('show') && !sidebar.contains(e.target) && !toggler.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });

        // Auto dismiss bootstrap toasts
        document.addEventListener('DOMContentLoaded', () => {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toastEl => {
                setTimeout(() => {
                    const bsToast = bootstrap.Toast.getInstance(toastEl) || new bootstrap.Toast(toastEl);
                    bsToast.hide();
                }, 4000);
            });
        });

        // Global Toast utility for AJAX notifications
        window.showToast = function(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toastEl = document.createElement('div');
            toastEl.className = `toast show align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 mb-2 shadow`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi ${icon} me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            container.appendChild(toastEl);
            
            setTimeout(() => {
                const bsToast = bootstrap.Toast.getInstance(toastEl) || new bootstrap.Toast(toastEl);
                bsToast.hide();
                setTimeout(() => toastEl.remove(), 500);
            }, 4000);
        };
    </script>
    @yield('scripts')
</body>
</html>
