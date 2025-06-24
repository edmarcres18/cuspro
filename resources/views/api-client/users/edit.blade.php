@extends('layouts.api-app')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit User</h5>
                <a href="{{ url('/api-client/users') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
            <div class="card-body">
                <div id="loadingIndicator" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading user data...</p>
                </div>
                
                <form id="editUserForm" style="display: none;">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" required>
                            <option value="User">User</option>
                            <option value="Admin">Admin</option>
                        </select>
                        <div class="invalid-feedback" id="roleError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (leave blank to keep current)</label>
                        <input type="password" class="form-control" id="password">
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation">
                        <div class="invalid-feedback" id="passwordConfirmationError"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if token exists, redirect to login if not
        const token = localStorage.getItem('api_token');
        if (!token) {
            window.location.href = '/api-client/login';
            return;
        }
        
        // Check if user is admin
        const currentUser = JSON.parse(localStorage.getItem('user'));
        if (!currentUser || currentUser.role !== 'Admin') {
            showToast('Access denied. Admin privileges required.', 'danger');
            setTimeout(() => {
                window.location.href = '/api-client';
            }, 1500);
            return;
        }
        
        // Get user ID from URL
        const urlParts = window.location.pathname.split('/');
        const userId = urlParts[urlParts.length - 2];
        
        // Handle form submission
        const form = document.getElementById('editUserForm');
        form.addEventListener('submit', function(e) {
            handleFormSubmit(e, userId);
        });
        
        // Load user data
        loadUserData(userId);
    });
    
    async function loadUserData(userId) {
        try {
            showLoading();
            const response = await ApiClient.getUser(userId);
            hideLoading();
            
            if (response && response.data) {
                // Populate form
                document.getElementById('name').value = response.data.name;
                document.getElementById('email').value = response.data.email;
                if (response.data.role) {
                    document.getElementById('role').value = response.data.role;
                }
                
                // Show form
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('editUserForm').style.display = 'block';
            }
        } catch (error) {
            hideLoading();
            
            if (error.status === 403) {
                showToast('Access denied. Admin privileges required.', 'danger');
                setTimeout(() => {
                    window.location.href = '/api-client';
                }, 1500);
                return;
            }
            
            showToast('Failed to load user data', 'danger');
            console.error('User data loading error:', error);
            
            // If unauthorized, redirect to login
            if (error.status === 401) {
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            } else {
                // Redirect back to users list
                setTimeout(() => {
                    window.location.href = '/api-client/users';
                }, 1500);
            }
        }
    }
    
    async function handleFormSubmit(e, userId) {
        e.preventDefault();
        
        // Reset errors
        resetErrors();
        
        // Get form data
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const role = document.getElementById('role').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        
        // Prepare data object - only include password if provided
        const userData = { name, email, role };
        if (password) {
            userData.password = password;
            userData.password_confirmation = password_confirmation;
        }
        
        try {
            showLoading();
            const response = await ApiClient.updateUser(userId, userData);
            hideLoading();
            
            showToast('User updated successfully', 'success');
            
            // If current user was updated, update local storage
            const currentUser = JSON.parse(localStorage.getItem('user'));
            if (currentUser && currentUser.id == userId) {
                currentUser.name = name;
                currentUser.email = email;
                currentUser.role = role;
                localStorage.setItem('user', JSON.stringify(currentUser));
                
                // Update nav username
                document.getElementById('currentUserName').textContent = name;
            }
            
            // Redirect to users list
            setTimeout(() => {
                window.location.href = '/api-client/users';
            }, 1000);
        } catch (error) {
            hideLoading();
            
            if (error.status === 403) {
                showToast('Access denied. Admin privileges required.', 'danger');
                setTimeout(() => {
                    window.location.href = '/api-client';
                }, 1500);
                return;
            }
            
            if (error.errors) {
                displayValidationErrors(error.errors);
            } else {
                showToast(error.message || 'Failed to update user', 'danger');
            }
            
            console.error('User update error:', error);
        }
    }
    
    function resetErrors() {
        document.getElementById('name').classList.remove('is-invalid');
        document.getElementById('email').classList.remove('is-invalid');
        document.getElementById('role').classList.remove('is-invalid');
        document.getElementById('password').classList.remove('is-invalid');
        document.getElementById('password_confirmation').classList.remove('is-invalid');
        
        document.getElementById('nameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('roleError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('passwordConfirmationError').textContent = '';
    }
    
    function displayValidationErrors(errors) {
        if (errors.name) {
            document.getElementById('name').classList.add('is-invalid');
            document.getElementById('nameError').textContent = errors.name[0];
        }
        
        if (errors.email) {
            document.getElementById('email').classList.add('is-invalid');
            document.getElementById('emailError').textContent = errors.email[0];
        }
        
        if (errors.role) {
            document.getElementById('role').classList.add('is-invalid');
            document.getElementById('roleError').textContent = errors.role[0];
        }
        
        if (errors.password) {
            document.getElementById('password').classList.add('is-invalid');
            document.getElementById('passwordError').textContent = errors.password[0];
        }
        
        if (errors.password_confirmation) {
            document.getElementById('password_confirmation').classList.add('is-invalid');
            document.getElementById('passwordConfirmationError').textContent = errors.password_confirmation[0];
        }
    }
</script>
@endsection 