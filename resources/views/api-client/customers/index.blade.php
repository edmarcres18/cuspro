@extends('layouts.api-app')

@section('title', 'Customers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Customer Profiles</h5>
                <a href="{{ url('/api-client/customers/create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Customer
                </a>
            </div>
            <div class="card-body">
                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search customers...">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-select" id="areaFilter">
                                    <option value="">All Areas</option>
                                    <!-- Will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="hospitalFilter">
                                    <option value="">All Hospitals</option>
                                    <!-- Will be populated dynamically -->
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="phssFilter">
                                    <option value="">All PHSS</option>
                                    <!-- Will be populated dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Contact Person</th>
                                <th>Position</th>
                                <th>Contact No</th>
                                <th>Hospital</th>
                                <th>Area</th>
                                <th>PHSS</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customersTableBody">
                            <tr>
                                <td colspan="7" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted" id="paginationInfo">
                        Showing <span id="currentCount">0</span> of <span id="totalCount">0</span> customers
                    </div>
                    <div id="paginationControls">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this customer? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // State
    let customers = [];
    let areas = [];
    let hospitals = [];
    let phssList = [];
    let filteredCustomers = [];
    let currentPage = 1;
    let perPage = 10;
    let deleteCustomerId = null;
    let deleteModal = null;
    
    // Filters
    let searchTerm = '';
    let selectedArea = '';
    let selectedHospital = '';
    let selectedPhss = '';
    
    document.addEventListener('DOMContentLoaded', function() {
        // Check if token exists, redirect to login if not
        const token = localStorage.getItem('api_token');
        if (!token) {
            window.location.href = '/api-client/login';
            return;
        }
        
        // Initialize delete modal
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('confirmDelete').addEventListener('click', confirmDeleteCustomer);
        
        // Initialize event listeners
        document.getElementById('searchButton').addEventListener('click', handleSearch);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
        
        document.getElementById('areaFilter').addEventListener('change', handleFilters);
        document.getElementById('hospitalFilter').addEventListener('change', handleFilters);
        document.getElementById('phssFilter').addEventListener('change', handleFilters);
        
        // Load initial data
        loadFilterData();
        loadCustomers();
    });
    
    async function loadFilterData() {
        try {
            // Load areas
            const areasResponse = await ApiClient.getAreas();
            areas = areasResponse.data;
            populateFilter('areaFilter', areas);
            
            // Load hospitals
            const hospitalsResponse = await ApiClient.getHospitals();
            hospitals = hospitalsResponse.data;
            populateFilter('hospitalFilter', hospitals);
            
            // Load PHSS
            const phssResponse = await ApiClient.getPhssList();
            phssList = phssResponse.data;
            populateFilter('phssFilter', phssList, 'full_name');
        } catch (error) {
            console.error('Filter data loading error:', error);
            
            // If unauthorized, redirect to login
            if (error.status === 401) {
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            }
        }
    }
    
    function populateFilter(filterId, items, nameField = 'name') {
        const filter = document.getElementById(filterId);
        
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item[nameField];
            filter.appendChild(option);
        });
    }
    
    async function loadCustomers() {
        try {
            showLoading();
            const response = await ApiClient.getCustomers();
            hideLoading();
            
            customers = response.data;
            applyFiltersAndRender();
        } catch (error) {
            hideLoading();
            showToast('Failed to load customers', 'danger');
            console.error('Customers loading error:', error);
            
            // If unauthorized, redirect to login
            if (error.status === 401) {
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            }
        }
    }
    
    function handleSearch() {
        searchTerm = document.getElementById('searchInput').value.toLowerCase();
        currentPage = 1;
        applyFiltersAndRender();
    }
    
    function handleFilters() {
        selectedArea = document.getElementById('areaFilter').value;
        selectedHospital = document.getElementById('hospitalFilter').value;
        selectedPhss = document.getElementById('phssFilter').value;
        currentPage = 1;
        applyFiltersAndRender();
    }
    
    function applyFiltersAndRender() {
        // Apply filters
        filteredCustomers = customers.filter(customer => {
            // Search term filter
            const matchesSearch = searchTerm === '' || 
                customer.contact_person.toLowerCase().includes(searchTerm) ||
                customer.position.toLowerCase().includes(searchTerm) ||
                customer.contact_no.toLowerCase().includes(searchTerm);
            
            // Area filter
            const matchesArea = selectedArea === '' || customer.area_id.toString() === selectedArea;
            
            // Hospital filter
            const matchesHospital = selectedHospital === '' || customer.hospital_id.toString() === selectedHospital;
            
            // PHSS filter
            const matchesPhss = selectedPhss === '' || customer.phss_id.toString() === selectedPhss;
            
            return matchesSearch && matchesArea && matchesHospital && matchesPhss;
        });
        
        // Render table and pagination
        renderCustomersTable();
        renderPagination();
    }
    
    function renderCustomersTable() {
        const tableBody = document.getElementById('customersTableBody');
        
        if (filteredCustomers.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No customers found</td></tr>';
            document.getElementById('currentCount').textContent = '0';
            document.getElementById('totalCount').textContent = '0';
            return;
        }
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * perPage;
        const endIndex = Math.min(startIndex + perPage, filteredCustomers.length);
        const customersToShow = filteredCustomers.slice(startIndex, endIndex);
        
        // Update pagination info
        document.getElementById('currentCount').textContent = customersToShow.length;
        document.getElementById('totalCount').textContent = filteredCustomers.length;
        
        let html = '';
        
        customersToShow.forEach(customer => {
            // Find related data
            const area = areas.find(a => a.id === customer.area_id) || { name: 'Unknown' };
            const hospital = hospitals.find(h => h.id === customer.hospital_id) || { name: 'Unknown' };
            const phss = phssList.find(p => p.id === customer.phss_id) || { full_name: 'Unknown' };
            
            html += `
                <tr>
                    <td>${customer.contact_person}</td>
                    <td>${customer.position}</td>
                    <td>${customer.contact_no}</td>
                    <td>${hospital.name}</td>
                    <td>${area.name}</td>
                    <td>${phss.full_name}</td>
                    <td>
                        <a href="{{ url('/api-client/customers') }}/${customer.id}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ url('/api-client/customers') }}/${customer.id}/edit" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteCustomer(${customer.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }
    
    function renderPagination() {
        const paginationControls = document.getElementById('paginationControls');
        
        if (filteredCustomers.length <= perPage) {
            paginationControls.innerHTML = '';
            return;
        }
        
        const totalPages = Math.ceil(filteredCustomers.length / perPage);
        
        let html = '<nav><ul class="pagination">';
        
        // Previous button
        html += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
        `;
        
        // Page numbers
        const maxPagesToShow = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
        
        if (endPage - startPage + 1 < maxPagesToShow) {
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            html += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>
                </li>
            `;
        }
        
        // Next button
        html += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
        `;
        
        html += '</ul></nav>';
        
        paginationControls.innerHTML = html;
    }
    
    function changePage(page) {
        const totalPages = Math.ceil(filteredCustomers.length / perPage);
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        renderCustomersTable();
        renderPagination();
        
        // Scroll to top of table
        document.querySelector('.card-body').scrollIntoView({ behavior: 'smooth' });
    }
    
    function deleteCustomer(id) {
        deleteCustomerId = id;
        deleteModal.show();
    }
    
    async function confirmDeleteCustomer() {
        if (!deleteCustomerId) return;
        
        try {
            showLoading();
            const response = await ApiClient.deleteCustomer(deleteCustomerId);
            hideLoading();
            
            deleteModal.hide();
            showToast('Customer deleted successfully', 'success');
            
            // Reload customers
            loadCustomers();
        } catch (error) {
            hideLoading();
            deleteModal.hide();
            showToast('Failed to delete customer', 'danger');
            console.error('Customer delete error:', error);
        }
        
        deleteCustomerId = null;
    }
</script>
@endsection 