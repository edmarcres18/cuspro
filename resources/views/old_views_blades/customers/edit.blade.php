@extends('adminlte::page')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Profile</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <p>Customer Information</p>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_person">Customer Name:</label>
                                    <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ $customer->contact_person }}" placeholder="Enter customer name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position">Position:</label>
                                    <input type="text" id="position" name="position" class="form-control" value="{{ $customer->position }}" placeholder="Enter customer middle name (optional)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_no">Last Name:</label>
                                    <input type="number" id="contact_no" name="contact_no" class="form-control" value="{{ $customer->contact_no }}" placeholder="Enter customer last name" required>
                                </div>
                            </div>
                        </div>
                        <!-- Add more fields as needed -->
                       
<!-- Start Of Home Address-->
                    <p>Area, Hospital, and PHSS</p>
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label for="area_id">Area</label>
                                     <select id="area_id" name="area_id" class="form-control"required>
                                     <option value="">Select Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ $area->id == $customer->area_id ? 'selected' : '' }} >{{ $area->name }}</option>
                                @endforeach
                                    </select>
                                </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                     <label for="hospital_id">Hospital</label>
                                     <select id="hospital_id" name="hospital_id" class="form-control"required>
                                     <option value="">Select Hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}" {{ $hospital->id == $customer->hospital_id ? 'selected' : '' }} >{{ $hospital->name }}</option>
                                @endforeach
                                    </select>
                                </div>
                                </div>
                               
                                <div class="col-md-6">
                                <div class="form-group">
                                     <label for="phss_id">PHSS</label>
                                     <select id="phss_id" name="phss_id" class="form-control"required>
                                     <option value="">Select phss</option>
                                @foreach($phss as $phss)
                                    <option value="{{ $phss->id }}" {{ $phss->id == $customer->phss_id ? 'selected' : '' }} >{{ $phss->full_name }}</option>
                                @endforeach
                                    </select>
                                </div>
                                </div>
                                
</div>
<!-- End Of Home Address-->
                           <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-info">Back</a>
                    </form>
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
