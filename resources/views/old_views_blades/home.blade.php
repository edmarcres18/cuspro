@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 text-dark">Dashboard</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Quick Stats Section -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info elevation-3">
                <div class="inner">
                    <h3>{{number_format($userCount)}}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{route('users.index')}}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning elevation-3">
                <div class="inner">
                    <h3>{{number_format($areaCount)}}</h3>
                    <p>Active Areas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <a href="{{route('areas.index')}}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-danger elevation-3">
                <div class="inner">
                    <h3>{{number_format($hospitalCount)}}</h3>
                    <p>Registered Hospitals</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <a href="{{route('hospitals.index')}}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success elevation-3">
                <div class="inner">
                    <h3>{{number_format($customerCount)}}</h3>
                    <p>Total Customers</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <a href="{{route('customers.index')}}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card elevation-3">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Growth Analytics</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="mainChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card elevation-3">
                <div class="card-header border-0">
                    <h3 class="card-title">Distribution</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .small-box {
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .small-box:hover {
        transform: translateY(-5px);
    }
    .small-box .icon {
        transition: all 0.3s ease;
    }
    .small-box:hover .icon {
        transform: scale(1.1);
    }
    .ribbon-wrapper {
        height: 70px;
        overflow: hidden;
        position: absolute;
        right: -2px;
        top: -2px;
        width: 70px;
    }
    .ribbon {
        font-size: 0.8rem;
        padding: 2px 0;
        position: relative;
        right: -20px;
        text-align: center;
        top: 13px;
        transform: rotate(45deg);
        width: 90px;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Main Chart
    new Chart(document.getElementById('mainChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Users',
                data: [65, 59, 80, 81, 56, 55],
                borderColor: '#17a2b8',
                tension: 0.4,
                fill: true
            }, {
                label: 'Customers',
                data: [28, 48, 40, 19, 86, 27],
                borderColor: '#28a745',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Users', 'Areas', 'Hospitals', 'Customers'],
            datasets: [{
                data: [
                    {{$userCount}},
                    {{$areaCount}},
                    {{$hospitalCount}},
                    {{$customerCount}}
                ],
                backgroundColor: ['#17a2b8', '#ffc107', '#dc3545', '#28a745']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // PHSS Chart
    new Chart(document.getElementById('phssChart'), {
        type: 'bar',
        data: {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            datasets: [{
                label: 'PHSS Growth',
                data: [12, 19, 3, 5],
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Time Range Selector
    document.getElementById('timeRange').addEventListener('change', function() {
        // Add your time range filter logic here
        console.log('Time range changed to:', this.value);
    });
});
</script>
@endsection
