@extends('layouts.api-app')

@section('title', 'Create User')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create New User</h5>
                <a href="{{ url('/api-client/users') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
            <div class="card-body">
                <form id="createUserForm">
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
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" required>
                        <div class="invalid-feedback" id="passwordConfirmationError"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save User
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
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user || user.role !== 'Admin') {
            showToast('Access denied. Admin privileges required.', 'danger');
            setTimeout(() => {
                window.location.href = '/api-client';
            }, 1500);
            return;
        }
        
        // Handle form submission
        const form = document.getElementById('createUserForm');
        form.addEventListener('submit', handleFormSubmit);
    });
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        // Reset errors
        resetErrors();
        
        // Get form data
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const role = document.getElementById('role').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        
        try {
            showLoading();
            const response = await ApiClient.createUser({ 
                name, 
                email, 
                role,
                password, 
                password_confirmation 
            });
            hideLoading();
            
            showToast('User created successfully', 'success');
            
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
                showToast(error.message || 'Failed to create user', 'danger');
            }
            
            console.error('User creation error:', error);
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