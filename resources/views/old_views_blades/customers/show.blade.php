@extends('adminlte::page')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Customer Details</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Customer Name:</strong> {{ $customer->contact_person }}</p>
                            <p><strong>Position:</strong> {{ $customer->position }}</p>
                            <p><strong>Contact No:</strong> {{ $customer->contact_no ?: 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Area:</strong> {{ $customer->area->name }}</p>
                            <p><strong>Hospital:</strong> {{ $customer->hospital->name }}</p>
                            <p><strong>PHSS Name:</strong> {{ $customer->phss->full_name }}</p>
                        </div>
                    </div>
 
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('customers.index') }}" class="btn btn-info">Back</a>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection
