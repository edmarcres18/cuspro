@extends('adminlte::page')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Profile</h3>
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

                        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <p>Customer Information</p>
        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_person">Customer Name<span class="text-danger">*</span></label>
                                        <input type="text" id="contact_person" name="contact_person" class="form-control" placeholder="Enter customer customer name"required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="position">Position<span class="text-danger">*</span></label>
                                        <input type="text" id="position" name="position" class="form-control" placeholder="Enter customer position" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_no">Contact No.<span class="text-danger">*</span></label>
                                        <input type="number" id="contact_no" name="contact_no" class="form-control" placeholder="Enter customer contact number"required>
                                    </div>
                                </div>
                                
                                <!-- Add more fields as needed -->
                            </div>
<!-- Start Of Home Address-->
<br>
<br>
                    <p>Area, Hospital, and PHSS</p>
                            <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                     <label for="area_id">Area<span class="text-danger">*</span></label>
                                     <select id="area_id" name="area_id" class="form-control"required>
                                     <option value="">Select Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                                    </select>
                                </div>
                                </div>

                                <div class="col-md-6">
                                <div class="form-group">
                                     <label for="hospital_id">Hospital<span class="text-danger">*</span></label>
                                     <select id="hospital_id" name="hospital_id" class="form-control"required>
                                     <option value="">Select hospital</option>
                                @foreach($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">{{ $hospital->name }}</option>
                                @endforeach
                                    </select>
                                </div>
                                </div>
                               
                                <div class="col-md-6">
                                <div class="form-group">
                                     <label for="phss_id">PHSS<span class="text-danger">*</span></label>
                                     <select id="phss_id" name="phss_id" class="form-control"required>
                                     <option value="">Select PHSS</option>
                                @foreach($phss as $phss)
                                    <option value="{{ $phss->id }}">{{ $phss->full_name }}</option>
                                @endforeach
                                    </select>
                                </div>
                                </div>
</div>
<!-- End Of Home Address-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group" role="group" aria-label="Button group">
                                <button type="submit" class="btn btn-primary">Create</button>&nbsp;&nbsp;
                                <a href="{{ route('customers.index') }}" class="btn btn-info">Back</a>
                                    </div>
                                </div>
                            </div>
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