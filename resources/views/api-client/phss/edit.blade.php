@extends('layouts.api-app')

@section('title', 'Edit PHSS')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit PHSS</h5>
                <a href="{{ url('/api-client/phss') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to PHSS
                </a>
            </div>
            <div class="card-body">
                <div id="loadingIndicator" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading PHSS data...</p>
                </div>
                
                <form id="editPhssForm" style="display: none;">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">PHSS Name</label>
                        <input type="text" class="form-control" id="full_name" required>
                        <div class="invalid-feedback" id="fullNameError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="area_id" class="form-label">Area</label>
                        <select class="form-select" id="area_id" required>
                            <option value="">Select Area</option>
                            <!-- Areas will be populated dynamically -->
                        </select>
                        <div class="invalid-feedback" id="areaIdError"></div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update PHSS
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
        
        // Get PHSS ID from URL
        const urlParts = window.location.pathname.split('/');
        const phssId = urlParts[urlParts.length - 2];
        
        // Handle form submission
        const form = document.getElementById('editPhssForm');
        form.addEventListener('submit', function(e) {
            handleFormSubmit(e, phssId);
        });
        
        // Load areas and PHSS data
        loadAreasAndPhssData(phssId);
    });
    
    async function loadAreasAndPhssData(phssId) {
        try {
            showLoading();
            
            // Load areas first
            const areasResponse = await ApiClient.getAreas();
            populateAreasDropdown(areasResponse.data);
            
            // Then load PHSS data
            const phssResponse = await ApiClient.getPhss(phssId);
            populateForm(phssResponse.data);
            
            hideLoading();
            
            // Show form
            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('editPhssForm').style.display = 'block';
        } catch (error) {
            hideLoading();
            
            showToast('Failed to load data', 'danger');
            console.error('Data loading error:', error);
            
            // If unauthorized, redirect to login
            if (error.status === 401) {
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            } else {
                // Redirect back to PHSS list
                setTimeout(() => {
                    window.location.href = '/api-client/phss';
                }, 1500);
            }
        }
    }
    
    function populateAreasDropdown(areas) {
        const dropdown = document.getElementById('area_id');
        
        if (!areas || areas.length === 0) {
            dropdown.innerHTML = '<option value="">No areas available</option>';
            return;
        }
        
        let options = '<option value="">Select Area</option>';
        
        areas.forEach(area => {
            options += `<option value="${area.id}">${area.name}</option>`;
        });
        
        dropdown.innerHTML = options;
    }
    
    function populateForm(phss) {
        if (!phss) return;
        
        document.getElementById('full_name').value = phss.full_name;
        
        // Set selected area
        if (phss.area_id) {
            document.getElementById('area_id').value = phss.area_id;
        }
    }
    
    async function handleFormSubmit(e, phssId) {
        e.preventDefault();
        
        // Reset errors
        resetErrors();
        
        // Get form data
        const full_name = document.getElementById('full_name').value;
        const area_id = document.getElementById('area_id').value;
        
        try {
            showLoading();
            const response = await ApiClient.updatePhss(phssId, { full_name, area_id });
            hideLoading();
            
            showToast('PHSS updated successfully', 'success');
            
            // Redirect to PHSS list
            setTimeout(() => {
                window.location.href = '/api-client/phss';
            }, 1000);
        } catch (error) {
            hideLoading();
            
            if (error.errors) {
                displayValidationErrors(error.errors);
            } else {
                showToast(error.message || 'Failed to update PHSS', 'danger');
            }
            
            console.error('PHSS update error:', error);
        }
    }
    
    function resetErrors() {
        document.getElementById('full_name').classList.remove('is-invalid');
        document.getElementById('area_id').classList.remove('is-invalid');
        document.getElementById('fullNameError').textContent = '';
        document.getElementById('areaIdError').textContent = '';
    }
    
    function displayValidationErrors(errors) {
        if (errors.full_name) {
            document.getElementById('full_name').classList.add('is-invalid');
            document.getElementById('fullNameError').textContent = errors.full_name[0];
        }
        
        if (errors.area_id) {
            document.getElementById('area_id').classList.add('is-invalid');
            document.getElementById('areaIdError').textContent = errors.area_id[0];
        }
    }
</script>
@endsection 