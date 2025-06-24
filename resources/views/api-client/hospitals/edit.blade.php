@extends('layouts.api-app')

@section('title', 'Edit Hospital')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Hospital</h5>
                <a href="{{ url('/api-client/hospitals') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Hospitals
                </a>
            </div>
            <div class="card-body">
                <div id="loadingIndicator" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading hospital data...</p>
                </div>
                
                <form id="editHospitalForm" style="display: none;">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback" id="nameError"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Hospital
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
        
        // Get hospital ID from URL
        const urlParts = window.location.pathname.split('/');
        const hospitalId = urlParts[urlParts.length - 2];
        
        // Handle form submission
        const form = document.getElementById('editHospitalForm');
        form.addEventListener('submit', function(e) {
            handleFormSubmit(e, hospitalId);
        });
        
        // Load hospital data
        loadHospitalData(hospitalId);
    });
    
    async function loadHospitalData(hospitalId) {
        try {
            showLoading();
            const response = await ApiClient.getHospital(hospitalId);
            hideLoading();
            
            if (response && response.data) {
                // Populate form
                document.getElementById('name').value = response.data.name;
                
                // Show form
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('editHospitalForm').style.display = 'block';
            }
        } catch (error) {
            hideLoading();
            
            showToast('Failed to load hospital data', 'danger');
            console.error('Hospital data loading error:', error);
            
            // If unauthorized, redirect to login
            if (error.status === 401) {
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            } else {
                // Redirect back to hospitals list
                setTimeout(() => {
                    window.location.href = '/api-client/hospitals';
                }, 1500);
            }
        }
    }
    
    async function handleFormSubmit(e, hospitalId) {
        e.preventDefault();
        
        // Reset errors
        resetErrors();
        
        // Get form data
        const name = document.getElementById('name').value;
        
        try {
            showLoading();
            const response = await ApiClient.updateHospital(hospitalId, { name });
            hideLoading();
            
            showToast('Hospital updated successfully', 'success');
            
            // Redirect to hospitals list
            setTimeout(() => {
                window.location.href = '/api-client/hospitals';
            }, 1000);
        } catch (error) {
            hideLoading();
            
            if (error.errors) {
                displayValidationErrors(error.errors);
            } else {
                showToast(error.message || 'Failed to update hospital', 'danger');
            }
            
            console.error('Hospital update error:', error);
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