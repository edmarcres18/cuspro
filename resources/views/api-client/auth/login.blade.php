<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

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
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo h1 {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 28px;
        }
        
        .logo-image {
            max-width: 200px;
            height: auto;
            filter: drop-shadow(0px 8px 15px rgba(74, 108, 247, 0.3));
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        
        .logo-image:hover {
            transform: translateY(-5px);
            filter: drop-shadow(0px 12px 20px rgba(74, 108, 247, 0.5));
        }
        
        .logo-subtitle {
            margin-top: 10px;
            font-size: 16px;
            color: #666;
            font-weight: 500;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: none;
            overflow: hidden;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 20px;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
        }
        
        .form-control {
            padding: 12px;
            border-radius: 5px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(74, 108, 247, 0.25);
        }
        
        /* Password toggle styling */
        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            color: #6c757d;
            outline: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .form-control.password-input {
            padding-right: 40px;
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
        
        @media (max-width: 576px) {
            .login-container {
                padding: 0 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-logo">
                <div class="mb-3">
                    <img src="{{ asset('vendor/adminlte/dist/img/mhrhci2.png') }}" class="logo-image" alt="MHR Logo">
                </div>
                <h1>MHR CUSTOMER PROFILE</h1>
                <p class="logo-subtitle">Streamlined Customer Management System</p>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Login</h5>
                </div>
                <div class="card-body">
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-container">
                                <input type="password" class="form-control password-input" id="password" required>
                                <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                                    <i class="fas fa-eye" id="passwordToggleIcon"></i>
                                </button>
                                <div class="invalid-feedback" id="passwordError"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is already logged in
            const token = localStorage.getItem('api_token');
            if (token) {
                window.location.href = '/api-client';
                return;
            }
            
            // Handle form submission
            const loginForm = document.getElementById('loginForm');
            loginForm.addEventListener('submit', handleLogin);
            
            // Setup password toggle functionality
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');
            const passwordToggleIcon = document.getElementById('passwordToggleIcon');
            
            passwordToggle.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    passwordToggleIcon.classList.remove('fa-eye');
                    passwordToggleIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    passwordToggleIcon.classList.remove('fa-eye-slash');
                    passwordToggleIcon.classList.add('fa-eye');
                }
            });
        });
        
        async function handleLogin(e) {
            e.preventDefault();
            
            // Clear previous errors
            resetErrors();
            
            // Get form values
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            try {
                showLoading();
                const response = await ApiClient.login({ email, password });
                hideLoading();
                
                showToast('Login successful!', 'success');
                
                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = '/api-client';
                }, 1000);
            } catch (error) {
                hideLoading();
                
                if (error.status === 401) {
                    showToast('Invalid email or password', 'danger');
                } else if (error.errors) {
                    displayValidationErrors(error.errors);
                } else {
                    showToast(error.message || 'Login failed', 'danger');
                }
            }
        }
        
        function resetErrors() {
            document.getElementById('email').classList.remove('is-invalid');
            document.getElementById('password').classList.remove('is-invalid');
            document.getElementById('emailError').textContent = '';
            document.getElementById('passwordError').textContent = '';
        }
        
        function displayValidationErrors(errors) {
            if (errors.email) {
                document.getElementById('email').classList.add('is-invalid');
                document.getElementById('emailError').textContent = errors.email[0];
            }
            
            if (errors.password) {
                document.getElementById('password').classList.add('is-invalid');
                document.getElementById('passwordError').textContent = errors.password[0];
            }
        }
        
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