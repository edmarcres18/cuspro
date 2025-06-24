@extends('layouts.api-app')

@section('title', 'Users')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users</h5>
                <a href="{{ url('/api-client/users/create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>
            <div class="card-body">
                <!-- Search -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search users...">
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
                                <th>Email</th>
                                <th>Role</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr>
                                <td colspan="5" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted" id="paginationInfo">
                        Showing <span id="currentCount">0</span> of <span id="totalCount">0</span> users
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
                Are you sure you want to delete this user? This action cannot be undone.
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
    let users = [];
    let filteredUsers = [];
    let currentPage = 1;
    let perPage = 10;
    let deleteUserId = null;
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
        
        // Check if user is admin
        const user = JSON.parse(localStorage.getItem('user'));
        if (!user || user.role !== 'Admin') {
            showToast('Access denied. Admin privileges required.', 'danger');
            setTimeout(() => {
                window.location.href = '/api-client';
            }, 1500);
            return;
        }
        
        // Initialize delete modal
        deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('confirmDelete').addEventListener('click', confirmDeleteUser);
        
        // Initialize event listeners
        document.getElementById('searchButton').addEventListener('click', handleSearch);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
        
        // Load users data
        loadUsers();
    });
    
    async function loadUsers() {
        try {
            showLoading();
            const response = await ApiClient.getUsers();
            hideLoading();
            
            users = response.data;
            applyFiltersAndRender();
        } catch (error) {
            hideLoading();
            showToast('Failed to load users', 'danger');
            console.error('Users loading error:', error);
            
            // Handle different error cases
            if (error.status === 401) {
                // If unauthorized, redirect to login
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            } else if (error.status === 403) {
                // If forbidden, redirect to dashboard
                showToast('Access denied. Admin privileges required.', 'danger');
                setTimeout(() => {
                    window.location.href = '/api-client';
                }, 1500);
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
        filteredUsers = users.filter(user => {
            // Search term filter
            return searchTerm === '' || 
                user.name.toLowerCase().includes(searchTerm) || 
                user.email.toLowerCase().includes(searchTerm);
        });
        
        // Render table and pagination
        renderUsersTable();
        renderPagination();
    }
    
    function renderUsersTable() {
        const tableBody = document.getElementById('usersTableBody');
        
        if (filteredUsers.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No users found</td></tr>';
            document.getElementById('currentCount').textContent = '0';
            document.getElementById('totalCount').textContent = '0';
            return;
        }
        
        // Calculate pagination
        const startIndex = (currentPage - 1) * perPage;
        const endIndex = Math.min(startIndex + perPage, filteredUsers.length);
        const usersToShow = filteredUsers.slice(startIndex, endIndex);
        
        // Update pagination info
        document.getElementById('currentCount').textContent = usersToShow.length;
        document.getElementById('totalCount').textContent = filteredUsers.length;
        
        let html = '';
        
        usersToShow.forEach(user => {
            html += `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.role || 'User'}</td>
                    <td>
                        <a href="{{ url('/api-client/users') }}/${user.id}/edit" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})">
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
        
        if (filteredUsers.length <= perPage) {
            paginationControls.innerHTML = '';
            return;
        }
        
        const totalPages = Math.ceil(filteredUsers.length / perPage);
        
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
        const totalPages = Math.ceil(filteredUsers.length / perPage);
        if (page < 1 || page > totalPages) return;
        
        currentPage = page;
        renderUsersTable();
        renderPagination();
        
        // Scroll to top of table
        document.querySelector('.card-body').scrollIntoView({ behavior: 'smooth' });
    }
    
    function deleteUser(id) {
        // Don't allow deleting your own account
        const currentUser = JSON.parse(localStorage.getItem('user'));
        if (currentUser && currentUser.id === id) {
            showToast('You cannot delete your own account', 'warning');
            return;
        }
        
        deleteUserId = id;
        deleteModal.show();
    }
    
    async function confirmDeleteUser() {
        if (!deleteUserId) return;
        
        try {
            showLoading();
            const response = await ApiClient.deleteUser(deleteUserId);
            hideLoading();
            
            deleteModal.hide();
            showToast('User deleted successfully', 'success');
            
            // Reload users
            loadUsers();
        } catch (error) {
            hideLoading();
            deleteModal.hide();
            
            if (error.status === 403) {
                showToast('Access denied. Admin privileges required.', 'danger');
            } else {
                showToast('Failed to delete user', 'danger');
            }
            
            console.error('User delete error:', error);
        }
        
        deleteUserId = null;
    }
</script>
@endsection 