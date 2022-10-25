@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">
            @include('components.alerts')

            @can('create-user')
            <div class="col-auto">
                <a class="btn btn-primary" href="{{ route('user.create') }}">Add User</a>
            </div>
            @endcan

            <div class="col-12">
                <h4 class="mt-3">Users</h4>

                <table id="users-table" class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>

                </table>

            </div>

        </div>

    </div>
    
@endsection

@section('script')

<!-- DATATABLE -->
<script>
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("user.table") }}',
        columns: [
            {data: 'name'},
            {data: 'email'},
            {data: 'actions'}
        ],
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>
@endsection