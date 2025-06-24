@extends('layouts.api-app')

@section('title', 'Customer Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Customer Details</h5>
                <a href="{{ url('/api-client/customers') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Customers
                </a>
            </div>
            <div class="card-body">
                <div id="loadingIndicator" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading customer data...</p>
                </div>
                
                <div id="customerDetails" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Personal Information</h6>
                            <dl class="row">
                                <dt class="col-sm-4">Contact Person</dt>
                                <dd class="col-sm-8" id="contactPerson">-</dd>
                                
                                <dt class="col-sm-4">Position</dt>
                                <dd class="col-sm-8" id="position">-</dd>
                                
                                <dt class="col-sm-4">Contact No</dt>
                                <dd class="col-sm-8" id="contactNo">-</dd>
                            </dl>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Additional Information</h6>
                            <dl class="row">
                                <dt class="col-sm-4">Area</dt>
                                <dd class="col-sm-8" id="area">-</dd>
                                
                                <dt class="col-sm-4">Hospital</dt>
                                <dd class="col-sm-8" id="hospital">-</dd>
                                
                                <dt class="col-sm-4">PHSS</dt>
                                <dd class="col-sm-8" id="phss">-</dd>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a id="editCustomerLink" href="#" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Customer
                        </a>
                    </div>
                </div>
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
        
        // Get customer ID from URL
        const urlParts = window.location.pathname.split('/');
        const customerId = urlParts[urlParts.length - 1];
        
        // Set edit link
        document.getElementById('editCustomerLink').href = `/api-client/customers/${customerId}/edit`;
        
        // Load customer data
        loadCustomerData(customerId);
    });
    
    async function loadCustomerData(customerId) {
        try {
            showLoading();
            const response = await ApiClient.getCustomer(customerId);
            hideLoading();
            
            if (response && response.data) {
                displayCustomerData(response.data);
                
                // Show customer details
                document.getElementById('loadingIndicator').style.display = 'none';
                document.getElementById('customerDetails').style.display = 'block';
            }
        } catch (error) {
            hideLoading();
            
            showToast('Failed to load customer data', 'danger');
            console.error('Customer data loading error:', error);
            
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
    
    function displayCustomerData(customer) {
        // Set personal information
        document.getElementById('contactPerson').textContent = customer.contact_person;
        document.getElementById('position').textContent = customer.position;
        document.getElementById('contactNo').textContent = customer.contact_no;
        
        // Set relational data if available
        if (customer.area) {
            document.getElementById('area').textContent = customer.area.name;
        }
        
        if (customer.hospital) {
            document.getElementById('hospital').textContent = customer.hospital.name;
        }
        
        if (customer.phss) {
            document.getElementById('phss').textContent = customer.phss.full_name;
        }
    }
</script>
@endsection 