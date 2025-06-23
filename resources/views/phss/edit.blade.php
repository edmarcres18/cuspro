@extends('adminlte::page')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Phss</h3>
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

                    <form action="{{ route('phss.update', $phss->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">

                        <div class="col-md-6">
                        <div class="form-group">
                                     <label for="area_id">Area</label>
                                     <select id="area_id" name="area_id" class="form-control"required>
                                     <option value="">Select Area</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ $area->id == $phss->area_id ? 'selected' : '' }} >{{ $area->name }}</option>
                                @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name">PHSS Name:</label>
                                    <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', $phss->full_name) }}" placeholder="Enter phss name">
                                </div>
                            </div>


                            <!-- Add more fields as needed -->
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="btn-group" role="group" aria-label="Button group">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>&nbsp;&nbsp;
                                    <a href="{{ route('phss.index') }}" class="btn btn-info">Back</a>
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
