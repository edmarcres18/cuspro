@extends('layouts.api-app')

@section('title', 'Create Hospital')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create New Hospital</h5>
                <a href="{{ url('/api-client/hospitals') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Hospitals
                </a>
            </div>
            <div class="card-body">
                <form id="createHospitalForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Hospital
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
        const form = document.getElementById('createHospitalForm');
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
            const response = await ApiClient.createHospital({ name });
            hideLoading();
            
            showToast('Hospital created successfully', 'success');
            
            // Redirect to hospitals list
            setTimeout(() => {
                window.location.href = '/api-client/hospitals';
            }, 1000);
        } catch (error) {
            hideLoading();
            
            if (error.errors) {
                displayValidationErrors(error.errors);
            } else {
                showToast(error.message || 'Failed to create hospital', 'danger');
            }
            
            console.error('Hospital creation error:', error);
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