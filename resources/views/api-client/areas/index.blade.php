@extends('layouts.api-app')

@section('title', 'Areas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Areas</h5>
                <a href="{{ url('/api-client/areas/create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Area
                </a>
            </div>
            <div class="card-body">
                <!-- Search -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search areas...">
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
                        <tbody id="areasTableBody">
                            <tr>
                                <td colspan="3" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted" id="paginationInfo">
                        Showing <span id="currentCount">0</span> of <span id="totalCount">0</span> areas
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
                Are you sure you want to delete this area? This action cannot be undone.
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
    let areas = [];
    let filteredAreas = [];
    let currentPage = 1;
    let perPage = 10;
    let deleteAreaId = null;
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
        document.getElementById('confirmDelete').addEventListener('click', confirmDeleteArea);
        
        // Initialize event listeners
        document.getElementById('searchButton').addEventListener('click', handleSearch);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
        
        // Load areas data
        loadAreas();
    });
    
    async function loadAreas() {
        try {
            showLoading();
            const response = await ApiClient.getAreas();
            hideLoading();
            
            areas = response.data;
            applyFiltersAndRender();
        } catch (error) {
            hideLoading();
            showToast('Failed to load areas', 'danger');
            console.error('Areas loading error:', error);
            
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
        filteredAreas = areas.filter(area => {
            // Search term filter
            return searchTerm === '' || area.name.toLowerCase().includes(searchTerm);
        });
        
        // Render table and pagination
        renderAreasTable();
        renderPagination();
    }
    
    function renderAreasTable() {
        const tableBody = document.getElementById('areasTableBody');
        
        if (filteredAreas.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="3" class="text-center">No areas found</td></tr>';
            document.getElementById('currentCount').textContent = '0';
            document.getElementById('totalCount').textContent = '0';
            return;
        }
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * perPage;
        const endIndex = Math.min(startIndex + perPage, filteredAreas.length);
        const areasToShow = filteredAreas.slice(startIndex, endIndex);
        
        // Update pagination info
        document.getElementById('currentCount').textContent = areasToShow.length;
        document.getElementById('totalCount').textContent = filteredAreas.length;
        
        let html = '';
        
        areasToShow.forEach(area => {
            html += `
                <tr>
                    <td>${area.id}</td>
                    <td>${area.name}</td>
                    <td>
                        <a href="{{ url('/api-client/areas') }}/${area.id}/edit" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteArea(${area.id})">
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
        
        if (filteredAreas.length <= perPage) {
            paginationControls.innerHTML = '';
            return;
        }
        
        const totalPages = Math.ceil(filteredAreas.length / perPage);
        
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
        const totalPages = Math.ceil(filteredAreas.length / perPage);
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        renderAreasTable();
        renderPagination();
        
        // Scroll to top of table
        document.querySelector('.card-body').scrollIntoView({ behavior: 'smooth' });
    }
    
    function deleteArea(id) {
        deleteAreaId = id;
        deleteModal.show();
    }
    
    async function confirmDeleteArea() {
        if (!deleteAreaId) return;
        
        try {
            showLoading();
            const response = await ApiClient.deleteArea(deleteAreaId);
            hideLoading();
            
            deleteModal.hide();
            showToast('Area deleted successfully', 'success');
            
            // Reload areas
            loadAreas();
        } catch (error) {
            hideLoading();
            deleteModal.hide();
            showToast('Failed to delete area', 'danger');
            console.error('Area delete error:', error);
        }
        
        deleteAreaId = null;
    }
</script>
@endsection 