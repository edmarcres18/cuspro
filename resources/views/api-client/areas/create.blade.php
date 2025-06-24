@extends('layouts.api-app')

@section('title', 'Create Area')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create New Area</h5>
                <a href="{{ url('/api-client/areas') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Areas
                </a>
            </div>
            <div class="card-body">
                <form id="createAreaForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Area
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
        
        // Handle form submission
        const form = document.getElementById('createAreaForm');
        form.addEventListener('submit', handleFormSubmit);
    });
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        // Reset errors
        resetErrors();
        
        // Get form data
        const name = document.getElementById('name').value;
        
        try {
            showLoading();
            const response = await ApiClient.createArea({ name });
            hideLoading();
            
            showToast('Area created successfully', 'success');
            
            // Redirect to areas list
            setTimeout(() => {
                window.location.href = '/api-client/areas';
            }, 1000);
        } catch (error) {
            hideLoading();
            
            if (error.errors) {
                displayValidationErrors(error.errors);
            } else {
                showToast(error.message || 'Failed to create area', 'danger');
            }
            
            console.error('Area creation error:', error);
        }
    }
    
    function resetErrors() {
        document.getElementById('name').classList.remove('is-invalid');
        document.getElementById('nameError').textContent = '';
    }
    
    function displayValidationErrors(errors) {
        if (errors.name) {
            document.getElementById('name').classList.add('is-invalid');
            document.getElementById('nameError').textContent = errors.name[0];
        }
    }
</script>
@endsection 