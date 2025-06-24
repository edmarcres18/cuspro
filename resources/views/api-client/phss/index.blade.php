@extends('layouts.api-app')

@section('title', 'PHSS')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">PHSS</h5>
                <a href="{{ url('/api-client/phss/create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New PHSS
                </a>
            </div>
            <div class="card-body">
                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search PHSS...">
                            <button class="btn btn-outline-secondary" type="button" id="searchButton">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="areaFilter">
                            <option value="">All Areas</option>
                            <!-- Will be populated dynamically -->
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="50">ID</th>
                                <th>Name</th>
                                <th>Area</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="phssTableBody">
                            <tr>
                                <td colspan="4" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted" id="paginationInfo">
                        Showing <span id="currentCount">0</span> of <span id="totalCount">0</span> PHSS
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
                Are you sure you want to delete this PHSS? This action cannot be undone.
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
    let phssList = [];
    let areas = [];
    let filteredPhss = [];
    let currentPage = 1;
    let perPage = 10;
    let deletePhssId = null;
    let deleteModal = null;
    
    // Filters
    let searchTerm = '';
    let selectedArea = '';
    
    document.addEventListener('DOMContentLoaded', function() {
        // Check if token exists, redirect to login if not
        const token = localStorage.getItem('api_token');
        if (!token) {
            window.location.href = '/api-client/login';
            return;
        }
        
        // Initialize delete modal
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('confirmDelete').addEventListener('click', confirmDeletePhss);
        
        // Initialize event listeners
        document.getElementById('searchButton').addEventListener('click', handleSearch);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
        
        document.getElementById('areaFilter').addEventListener('change', handleFilters);
        
        // Load filter data
        loadAreas();
        
        // Load phss data
        loadPhss();
    });
    
    async function loadAreas() {
        try {
            const response = await ApiClient.getAreas();
            areas = response.data;
            populateFilter('areaFilter', areas);
        } catch (error) {
            console.error('Areas loading error:', error);
            
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
    
    async function loadPhss() {
        try {
            showLoading();
            const response = await ApiClient.getPhssList();
            hideLoading();
            
            phssList = response.data;
            applyFiltersAndRender();
        } catch (error) {
            hideLoading();
            showToast('Failed to load PHSS', 'danger');
            console.error('PHSS loading error:', error);
            
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
        currentPage = 1;
        applyFiltersAndRender();
    }
    
    function applyFiltersAndRender() {
        // Apply filters
        filteredPhss = phssList.filter(phss => {
            // Search term filter
            const matchesSearch = searchTerm === '' || 
                phss.full_name.toLowerCase().includes(searchTerm);
            
            // Area filter
            const matchesArea = selectedArea === '' || 
                (phss.area_id && phss.area_id.toString() === selectedArea);
            
            return matchesSearch && matchesArea;
        });
        
        // Render table and pagination
        renderPhssTable();
        renderPagination();
    }
    
    function renderPhssTable() {
        const tableBody = document.getElementById('phssTableBody');
        
        if (filteredPhss.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No PHSS found</td></tr>';
            document.getElementById('currentCount').textContent = '0';
            document.getElementById('totalCount').textContent = '0';
            return;
        }
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * perPage;
        const endIndex = Math.min(startIndex + perPage, filteredPhss.length);
        const phssToShow = filteredPhss.slice(startIndex, endIndex);
        
        // Update pagination info
        document.getElementById('currentCount').textContent = phssToShow.length;
        document.getElementById('totalCount').textContent = filteredPhss.length;
        
        let html = '';
        
        phssToShow.forEach(phss => {
            // Get area name from relationship if available
            const areaName = phss.area ? phss.area.name : 'Not assigned';
            
            html += `
                <tr>
                    <td>${phss.id}</td>
                    <td>${phss.full_name}</td>
                    <td>${areaName}</td>
                    <td>
                        <a href="{{ url('/api-client/phss') }}/${phss.id}/edit" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deletePhss(${phss.id})">
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
        
        if (filteredPhss.length <= perPage) {
            paginationControls.innerHTML = '';
            return;
        }
        
        const totalPages = Math.ceil(filteredPhss.length / perPage);
        
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
        const totalPages = Math.ceil(filteredPhss.length / perPage);
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        renderPhssTable();
        renderPagination();
        
        // Scroll to top of table
        document.querySelector('.card-body').scrollIntoView({ behavior: 'smooth' });
    }
    
    function deletePhss(id) {
        deletePhssId = id;
        deleteModal.show();
    }
    
    async function confirmDeletePhss() {
        if (!deletePhssId) return;
        
        try {
            showLoading();
            const response = await ApiClient.deletePhss(deletePhssId);
            hideLoading();
            
            deleteModal.hide();
            showToast('PHSS deleted successfully', 'success');
            
            // Reload phss list
            loadPhss();
        } catch (error) {
            hideLoading();
            deleteModal.hide();
            showToast('Failed to delete PHSS', 'danger');
            console.error('PHSS delete error:', error);
        }
        
        deletePhssId = null;
    }
</script>
@endsection 