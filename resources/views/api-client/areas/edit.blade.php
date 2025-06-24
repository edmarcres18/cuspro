@extends('layouts.api-app')

@section('title', 'Edit Area')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Area</h5>
                <a href="{{ url('/api-client/areas') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Areas
                </a>
            </div>
            <div class="card-body">
                <div id="loadingIndicator" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading area data...</p>
                </div>
                
                <form id="editAreaForm" style="display: none;">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Area
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
        
        // Get area ID from URL
        const urlParts = window.location.pathname.split('/');
        const areaId = urlParts[urlParts.length - 2];
        
        // Handle form submission
        const form = document.getElementById('editAreaForm');
        form.addEventListener('submit', function(e) {
            handleFormSubmit(e, areaId);
        });
        
        // Load area data
        loadAreaData(areaId);
    });
    
    async function loadAreaData(areaId) {
        try {
            showLoading();
            const response = await ApiClient.getArea(areaId);
            hideLoading();
            
            if (response && response.data) {
                // Populate form
                document.getElementById('name').value = response.data.name;
                
                // Show form
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('editAreaForm').style.display = 'block';
            }
        } catch (error) {
            hideLoading();
            
            showToast('Failed to load area data', 'danger');
            console.error('Area data loading error:', error);
            
            // If unauthorized, redirect to login
            if (error.status === 401) {
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            } else {
                // Redirect back to areas list
                setTimeout(() => {
                    window.location.href = '/api-client/areas';
                }, 1500);
            }
        }
    }
    
    async function handleFormSubmit(e, areaId) {
        e.preventDefault();
        
        // Reset errors
        resetErrors();
        
        // Get form data
        const name = document.getElementById('name').value;
        
        try {
            showLoading();
            const response = await ApiClient.updateArea(areaId, { name });
            hideLoading();
            
            showToast('Area updated successfully', 'success');
            
            // Redirect to areas list
            setTimeout(() => {
                window.location.href = '/api-client/areas';
            }, 1000);
        } catch (error) {
            hideLoading();
            
            if (error.errors) {
                displayValidationErrors(error.errors);
            } else {
                showToast(error.message || 'Failed to update area', 'danger');
            }
            
            console.error('Area update error:', error);
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