@extends('layouts.api-app')

@section('title', 'Create Customer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create New Customer</h5>
                <a href="{{ url('/api-client/customers') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Customers
                </a>
            </div>
            <div class="card-body">
                <div id="loadingIndicator" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading reference data...</p>
                </div>
                
                <form id="createCustomerForm" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_person" class="form-label">Contact Person</label>
                                <input type="text" class="form-control" id="contact_person" required>
                                <div class="invalid-feedback" id="contactPersonError"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <input type="text" class="form-control" id="position" required>
                                <div class="invalid-feedback" id="positionError"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact_no" class="form-label">Contact No</label>
                                <input type="text" class="form-control" id="contact_no" required>
                                <div class="invalid-feedback" id="contactNoError"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="area_id" class="form-label">Area</label>
                                <select class="form-select" id="area_id" required>
                                    <option value="">Select Area</option>
                                    <!-- Areas will be populated dynamically -->
                                </select>
                                <div class="invalid-feedback" id="areaIdError"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hospital_id" class="form-label">Hospital</label>
                                <select class="form-select" id="hospital_id" required>
                                    <option value="">Select Hospital</option>
                                    <!-- Hospitals will be populated dynamically -->
                                </select>
                                <div class="invalid-feedback" id="hospitalIdError"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phss_id" class="form-label">PHSS</label>
                                <select class="form-select" id="phss_id" required>
                                    <option value="">Select PHSS</option>
                                    <!-- PHSS will be populated dynamically -->
                                </select>
                                <div class="invalid-feedback" id="phssIdError"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Customer
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
        const form = document.getElementById('createCustomerForm');
        form.addEventListener('submit', handleFormSubmit);
        
        // Load reference data
        loadReferenceData();
    });
    
    async function loadReferenceData() {
        try {
            showLoading();
            
            // Load all reference data in parallel
            const [areasResponse, hospitalsResponse, phssResponse] = await Promise.all([
                ApiClient.getAreas(),
                ApiClient.getHospitals(),
                ApiClient.getPhssList()
            ]);
            
            // Populate dropdowns
            populateDropdown('area_id', areasResponse.data, 'name');
            populateDropdown('hospital_id', hospitalsResponse.data, 'name');
            populateDropdown('phss_id', phssResponse.data, 'full_name');
            
            hideLoading();
            
            // Show form
            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('createCustomerForm').style.display = 'block';
        } catch (error) {
            hideLoading();
            
            showToast('Failed to load reference data', 'danger');
            console.error('Reference data loading error:', error);
            
            // If unauthorized, redirect to login
            if (error.status === 401) {
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            } else {
                // Redirect back to customers list
                setTimeout(() => {
                    window.location.href = '/api-client/customers';
                }, 1500);
            }
        }
    }
    
    function populateDropdown(elementId, items, labelField) {
        const dropdown = document.getElementById(elementId);
        
        if (!items || items.length === 0) {
            dropdown.innerHTML = `<option value="">No ${elementId.replace('_id', '')} available</option>`;
            return;
        }
        
        let options = `<option value="">Select ${elementId.replace('_id', '')}</option>`;
        
        items.forEach(item => {
            options += `<option value="${item.id}">${item[labelField]}</option>`;
        });
        
        dropdown.innerHTML = options;
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        // Reset errors
        resetErrors();
        
        // Get form data
        const contact_person = document.getElementById('contact_person').value;
        const position = document.getElementById('position').value;
        const contact_no = document.getElementById('contact_no').value;
        const area_id = document.getElementById('area_id').value;
        const hospital_id = document.getElementById('hospital_id').value;
        const phss_id = document.getElementById('phss_id').value;
        
        try {
            showLoading();
            const response = await ApiClient.createCustomer({
                contact_person,
                position,
                contact_no,
                area_id,
                hospital_id,
                phss_id
            });
            hideLoading();
            
            showToast('Customer created successfully', 'success');
            
            // Redirect to customers list
            setTimeout(() => {
                window.location.href = '/api-client/customers';
            }, 1000);
        } catch (error) {
            hideLoading();
            
            if (error.errors) {
                displayValidationErrors(error.errors);
            } else {
                showToast(error.message || 'Failed to create customer', 'danger');
            }
            
            console.error('Customer creation error:', error);
        }
    }
    
    function resetErrors() {
        const elements = [
            'contact_person', 
            'position', 
            'contact_no', 
            'area_id', 
            'hospital_id', 
            'phss_id'
        ];
        
        elements.forEach(id => {
            document.getElementById(id).classList.remove('is-invalid');
            const errorElement = document.getElementById(id + 'Error');
            if (errorElement) {
                errorElement.textContent = '';
            }
        });
    }
    
    function displayValidationErrors(errors) {
        // Map backend field names to frontend IDs
        const fieldMap = {
            'contact_person': 'contactPerson',
            'position': 'position',
            'contact_no': 'contactNo',
            'area_id': 'areaId',
            'hospital_id': 'hospitalId',
            'phss_id': 'phssId'
        };
        
        for (const field in errors) {
            const errorId = fieldMap[field] + 'Error';
            const inputId = field;
            
            document.getElementById(inputId).classList.add('is-invalid');
            document.getElementById(errorId).textContent = errors[field][0];
        }
    }
</script>
@endsection 