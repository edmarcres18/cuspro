@extends('layouts.api-app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Dashboard</h5>
            </div>
            <div class="card-body">
                <div class="row" id="statsContainer">
                    <div class="col-md-12 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Distribution</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="distributionChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group" id="activityList">
                                    <li class="list-group-item text-center">No recent activity</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if token exists, redirect to login if not
        const token = localStorage.getItem('api_token');
        if (!token) {
            window.location.href = '/api-client/login';
            return;
        }
        
        // Load dashboard data
        loadDashboardData();
    });
    
    async function loadDashboardData() {
        try {
            showLoading();
            const response = await ApiClient.getDashboard();
            hideLoading();
            
            if (response && response.data) {
                renderStats(response.data);
                renderChart(response.data);
            }
        } catch (error) {
            hideLoading();
            showToast('Failed to load dashboard data', 'danger');
            console.error('Dashboard data error:', error);
            
            // If unauthorized, redirect to login
            if (error.status === 401) {
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
                window.location.href = '/api-client/login';
            }
        }
    }
    
    function renderStats(data) {
        const statsContainer = document.getElementById('statsContainer');
        
        // Clear loading spinner
        statsContainer.innerHTML = '';
        
        // Create stat cards
        const stats = [
            { name: 'Areas', count: data.area_count, icon: 'map-marker-alt', color: 'warning' },
            { name: 'Hospitals', count: data.hospital_count, icon: 'hospital', color: 'danger' },
            { name: 'Customers', count: data.customer_count, icon: 'user-tie', color: 'success' },
            { name: 'Users', count: data.user_count, icon: 'users', color: 'info' }
        ];
        
        stats.forEach(stat => {
            const statCol = document.createElement('div');
            statCol.className = 'col-md-3 col-sm-6 mb-4';
            
            const statCard = `
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-0">${stat.name}</h6>
                                <h3 class="mb-0">${stat.count}</h3>
                            </div>
                            <div class="rounded-circle p-3 bg-${stat.color} bg-opacity-10">
                                <i class="fas fa-${stat.icon} fa-2x text-${stat.color}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            statCol.innerHTML = statCard;
            statsContainer.appendChild(statCol);
        });
    }
    
    function renderChart(data) {
        const ctx = document.getElementById('distributionChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Areas', 'Hospitals', 'Customers', 'Users'],
                datasets: [{
                    data: [
                        data.area_count, 
                        data.hospital_count, 
                        data.customer_count, 
                        data.user_count
                    ],
                    backgroundColor: [
                        '#ffc107',  // warning
                        '#dc3545',  // danger
                        '#28a745',  // success
                        '#17a2b8'   // info
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>
@endsection 