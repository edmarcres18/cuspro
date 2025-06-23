@extends('adminlte::page')

@section('content')
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hospital List</h3>
                    <div class="card-tools">
                            <a href="{{ route('hospitals.create') }}" class="btn btn-primary btn-sm">Create New Hospital</a>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                @if ($message = Session::get('success'))
    <div id="success-alert" class="alert alert-success">{{ $message }}</div>
@endif
                    <table id="hospital-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="30px">ID</th>
                                <th>Hospital</th>
                                <th width="300px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hospitals as $hospital)
                                <tr>
                                    <td>{{ $hospital->id }}</td>
                                    <td>{{ $hospital->name }}</td>
                                    <td>
                                        <div class="btn-group">
                                                <a class="btn btn-primary btn-sm" href="{{ route('hospitals.edit',$hospital->id) }}"><i class="fas fa-edit"></i>&nbsp;Edit</a>&nbsp;
                                                <form action="{{ route('hospitals.destroy', $hospital->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this hospital?')"><i class="fas fa-trash"></i>&nbsp;Delete</button>
                                                </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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

@section('js')
<script>
    $(document).ready(function () {
        $('#hospital-table').DataTable();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Select the success alert
        var successAlert = document.getElementById('success-alert');

        // If the alert exists, set a timeout to hide it after 3 seconds
        if (successAlert) {
            setTimeout(function() {
                // Apply the fade-out effect
                successAlert.style.transition = "opacity 1s ease-out";
                successAlert.style.opacity = 0;

                // After the fade-out effect, set display to none
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 1000); // Match this time to the transition duration
            }, 3000); // 3 seconds before starting the fade-out
        }
    });
</script>
@endsection
