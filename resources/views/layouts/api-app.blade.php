<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'API Client')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4A6CF7;
            --primary-hover: #3857d4;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --transition-speed: 0.3s;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        #sidebar-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: var(--sidebar-width);
            background-color: #2c3e50;
            z-index: 1040;
            transition: all var(--transition-speed) ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transform: translateX(0);
        }
        
        #sidebar-wrapper.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        #sidebar-wrapper .sidebar-heading {
            padding: 1rem;
            font-size: 1.2rem;
            color: white;
            background-color: #1a2533;
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-heading .toggle-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0;
        }
        
        #sidebar-wrapper .list-group {
            width: 100%;
            padding: 0;
        }
        
        #sidebar-wrapper .list-group-item {
            border: none;
            border-radius: 0;
            background-color: transparent;
            color: #ecf0f1;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        #sidebar-wrapper .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        #sidebar-wrapper .list-group-item.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        #sidebar-wrapper .list-group-item i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        #sidebar-wrapper.collapsed .list-group-item span {
            display: none;
        }
        
        #sidebar-wrapper.collapsed .sidebar-heading span {
            display: none;
        }
        
        /* Main Content Wrapper */
        #page-content-wrapper {
            width: 100%;
            padding-left: var(--sidebar-width);
            transition: padding-left var(--transition-speed) ease;
        }
        
        #page-content-wrapper.expanded {
            padding-left: var(--sidebar-collapsed-width);
        }
        
        /* Topbar Styles */
        .topbar {
            background-color: white;
            height: var(--topbar-height);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            padding: 0 1rem;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        
        .topbar .navbar-toggler {
            display: none;
            border: none;
            padding: 0;
            font-size: 1.5rem;
            color: #6c757d;
        }
        
        .topbar-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            flex-grow: 1;
            padding-left: 1rem;
        }
        
        /* Card Styles */
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 1rem;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
        
        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s linear 0.25s, opacity 0.25s;
        }
        
        .loading-overlay.active {
            visibility: visible;
            opacity: 1;
            transition-delay: 0s;
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        
        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }
        
        .user-menu .dropdown-toggle {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
        }
        
        .user-menu .dropdown-toggle::after {
            display: none;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .user-info {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            line-height: 1.2;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: #6c757d;
        }
        
        /* Main Content Area */
        .main-content {
            padding: 1.5rem;
            min-height: calc(100vh - var(--topbar-height));
        }
        
        /* Responsive Styles */
        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                transform: translateX(-100%);
            }
            
            #sidebar-wrapper.mobile-show {
                transform: translateX(0);
            }
            
            #page-content-wrapper {
                padding-left: 0;
            }
            
            #page-content-wrapper.expanded {
                padding-left: 0;
            }
            
            .topbar .navbar-toggler {
                display: block;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1035;
                display: none;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <span>{{ config('app.name', 'Laravel') }}</span>
                <button class="toggle-btn" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="list-group">
                <a href="{{ url('/api-client') }}" class="list-group-item {{ request()->is('api-client') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ url('/api-client/areas') }}" class="list-group-item {{ request()->is('api-client/areas*') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Areas</span>
                </a>
                <a href="{{ url('/api-client/hospitals') }}" class="list-group-item {{ request()->is('api-client/hospitals*') ? 'active' : '' }}">
                    <i class="fas fa-hospital"></i>
                    <span>Hospitals</span>
                </a>
                <a href="{{ url('/api-client/phss') }}" class="list-group-item {{ request()->is('api-client/phss*') ? 'active' : '' }}">
                    <i class="fas fa-user-md"></i>
                    <span>PHSS</span>
                </a>
                <a href="{{ url('/api-client/customers') }}" class="list-group-item {{ request()->is('api-client/customers*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
                <a href="{{ url('/api-client/users') }}" class="list-group-item {{ request()->is('api-client/users*') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i>
                    <span>Users</span>
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navigation -->
            <div class="topbar">
                <button class="navbar-toggler" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="topbar-title">@yield('title', 'API Client')</h5>
                
                <!-- User Menu (Right) -->
                <div class="user-menu dropdown" id="userDropdown" style="display: none;">
                    <a href="#" class="dropdown-toggle" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <span id="userInitials">U</span>
                        </div>
                        <div class="user-info d-none d-md-flex">
                            <span class="user-name" id="currentUserName">User</span>
                            <span class="user-role">API Client</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuDropdown">
                        <li><a class="dropdown-item" href="#" id="profileMenuItem"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="#" id="settingsMenuItem"><i class="fas fa-cog me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="logoutButton"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
                
                <!-- Login Link (when not logged in) -->
                <a href="{{ url('/api-client/login') }}" class="btn btn-primary btn-sm" id="loginMenuItem">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
        </div>
        
        <!-- Sidebar Overlay (for mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Toast Container for notifications -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main API Script -->
    <script src="{{ asset('js/api-client.js') }}"></script>
    
    <!-- Page-specific scripts -->
    @yield('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is already logged in
            const token = localStorage.getItem('api_token');
            const user = JSON.parse(localStorage.getItem('user'));
            
            if (token && user) {
                document.getElementById('loginMenuItem').style.display = 'none';
                document.getElementById('userDropdown').style.display = 'flex';
                document.getElementById('currentUserName').textContent = user.name;
                
                // Set user initials for avatar
                if (user.name) {
                    const nameParts = user.name.split(' ');
                    let initials = nameParts[0].charAt(0).toUpperCase();
                    if (nameParts.length > 1) {
                        initials += nameParts[nameParts.length - 1].charAt(0).toUpperCase();
                    }
                    document.getElementById('userInitials').textContent = initials;
                }
            } else {
                document.getElementById('loginMenuItem').style.display = 'block';
                document.getElementById('userDropdown').style.display = 'none';
            }
            
            // Logout functionality
            document.getElementById('logoutButton').addEventListener('click', function(e) {
                e.preventDefault();
                ApiClient.logout()
                    .then(() => {
                        showToast('Logged out successfully', 'success');
                        localStorage.removeItem('api_token');
                        localStorage.removeItem('user');
                        window.location.href = '/api-client/login';
                    })
                    .catch(error => {
                        console.error('Logout error:', error);
                        showToast('Logout failed', 'danger');
                    });
            });
            
            // Coming soon functionality for Profile and Settings
            document.getElementById('profileMenuItem').addEventListener('click', function(e) {
                e.preventDefault();
                showToast('Profile feature is coming soon!', 'info');
            });
            
            document.getElementById('settingsMenuItem').addEventListener('click', function(e) {
                e.preventDefault();
                showToast('Settings feature is coming soon!', 'info');
            });
            
            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarWrapper = document.getElementById('sidebar-wrapper');
            const pageContentWrapper = document.getElementById('page-content-wrapper');
            
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                sidebarWrapper.classList.toggle('collapsed');
                pageContentWrapper.classList.toggle('expanded');
                
                // Save state to localStorage
                localStorage.setItem('sidebar_collapsed', sidebarWrapper.classList.contains('collapsed'));
            });
            
            // Mobile menu toggle
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            mobileMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                sidebarWrapper.classList.toggle('mobile-show');
                sidebarOverlay.classList.toggle('active');
            });
            
            sidebarOverlay.addEventListener('click', function() {
                sidebarWrapper.classList.remove('mobile-show');
                sidebarOverlay.classList.remove('active');
            });
            
            // Restore sidebar state from localStorage
            const sidebarCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
            if (sidebarCollapsed) {
                sidebarWrapper.classList.add('collapsed');
                pageContentWrapper.classList.add('expanded');
            }
            
            // Handle window resize
            function handleResize() {
                if (window.innerWidth < 992) {
                    sidebarWrapper.classList.remove('mobile-show');
                    sidebarOverlay.classList.remove('active');
                }
            }
            
            window.addEventListener('resize', handleResize);
        });
        
        // Global toast function
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            const toastContent = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toast.innerHTML = toastContent;
            toastContainer.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast, {
                delay: 3000
            });
            
            bsToast.show();
            
            // Remove toast from DOM after it's hidden
            toast.addEventListener('hidden.bs.toast', function() {
                toast.remove();
            });
        }
        
        // Global loading indicator functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }
        
        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }
    </script>
</body>
</html> 