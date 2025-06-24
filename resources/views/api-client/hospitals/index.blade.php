@extends('layouts.api-app')

@section('title', 'Hospitals')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Hospitals</h5>
                <a href="{{ url('/api-client/hospitals/create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Hospital
                </a>
            </div>
            <div class="card-body">
                <!-- Search -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search hospitals...">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th>Name</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="hospitalsTableBody">
                            <tr>
                                <td colspan="3" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted" id="paginationInfo">
                        Showing <span id="currentCount">0</span> of <span id="totalCount">0</span> hospitals
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
                Are you sure you want to delete this hospital? This action cannot be undone.
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
    let hospitals = [];
    let filteredHospitals = [];
    let currentPage = 1;
    let perPage = 10;
    let deleteHospitalId = null;
    let deleteModal = null;
    
    // Filters
    let searchTerm = '';
    
    document.addEventListener('DOMContentLoaded', function() {
        // Check if token exists, redirect to login if not
        const token = localStorage.getItem('api_token');
        if (!token) {
            window.location.href = '/api-client/login';
            return;
        }
        
        // Initialize delete modal
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('confirmDelete').addEventListener('click', confirmDeleteHospital);
        
        // Initialize event listeners
        document.getElementById('searchButton').addEventListener('click', handleSearch);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
        
        // Load hospitals data
        loadHospitals();
    });
    
    async function loadHospitals() {
        try {
            showLoading();
            const response = await ApiClient.getHospitals();
            hideLoading();
            
            hospitals = response.data;
            applyFiltersAndRender();
        } catch (error) {
            hideLoading();
            showToast('Failed to load hospitals', 'danger');
            console.error('Hospitals loading error:', error);
            
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
    
    function applyFiltersAndRender() {
        // Apply filters
        filteredHospitals = hospitals.filter(hospital => {
            // Search term filter
            return searchTerm === '' || hospital.name.toLowerCase().includes(searchTerm);
        });
        
        // Render table and pagination
        renderHospitalsTable();
        renderPagination();
    }
    
    function renderHospitalsTable() {
        const tableBody = document.getElementById('hospitalsTableBody');
        
        if (filteredHospitals.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No hospitals found</td></tr>';
            document.getElementById('currentCount').textContent = '0';
            document.getElementById('totalCount').textContent = '0';
            return;
        }
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * perPage;
        const endIndex = Math.min(startIndex + perPage, filteredHospitals.length);
        const hospitalsToShow = filteredHospitals.slice(startIndex, endIndex);
        
        // Update pagination info
        document.getElementById('currentCount').textContent = hospitalsToShow.length;
        document.getElementById('totalCount').textContent = filteredHospitals.length;
        
        let html = '';
        
        hospitalsToShow.forEach(hospital => {
            html += `
                <tr>
                    <td>${hospital.id}</td>
                    <td>${hospital.name}</td>
                    <td>
                        <a href="{{ url('/api-client/hospitals') }}/${hospital.id}/edit" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteHospital(${hospital.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
    }
    
    function renderPagination() {
        const paginationControls = document.getElementById('paginationControls');
        
        if (filteredHospitals.length <= perPage) {
            paginationControls.innerHTML = '';
            return;
        }
        
        const totalPages = Math.ceil(filteredHospitals.length / perPage);
        
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
        const totalPages = Math.ceil(filteredHospitals.length / perPage);
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        renderHospitalsTable();
        renderPagination();
        
        // Scroll to top of table
        document.querySelector('.card-body').scrollIntoView({ behavior: 'smooth' });
    }
    
    function deleteHospital(id) {
        deleteHospitalId = id;
        deleteModal.show();
    }
    
    async function confirmDeleteHospital() {
        if (!deleteHospitalId) return;
        
        try {
            showLoading();
            const response = await ApiClient.deleteHospital(deleteHospitalId);
            hideLoading();
            
            deleteModal.hide();
            showToast('Hospital deleted successfully', 'success');
            
            // Reload hospitals
            loadHospitals();
        } catch (error) {
            hideLoading();
            deleteModal.hide();
            showToast('Failed to delete hospital', 'danger');
            console.error('Hospital delete error:', error);
        }
        
        deleteHospitalId = null;
    }
</script>
@endsection 