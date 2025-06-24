@extends('adminlte::page')

@section('content')
<br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Area</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    @if (count($errors) > 0)
    <div id="alert-message" class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                        <form action="{{ route('areas.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter area name" required>
                                    </div>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                         
                            <div class="row">
    <div class="col-md-6">
        <div class="btn-group" role="group" aria-label="Button group">
            <button type="submit" class="btn btn-primary">Create</button>&nbsp;&nbsp;
            <a href="{{ route('areas.index') }}" class="btn btn-info">Back</a>
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

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Check if the alert message exists
        const alertMessage = document.getElementById('alert-message');
        if (alertMessage) {
            // Set a timeout to hide the alert after 3 seconds (3000 milliseconds)
            setTimeout(() => {
                alertMessage.style.display = 'none';
            }, 3000);
        }
    });
</script>