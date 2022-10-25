@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">
            @include('components.alerts')

            @can('create-role')
            <div class="col-auto">
                <a class="btn btn-primary" href="{{ route('role.create') }}">Add Role</a>
            </div>
            @endcan
            
            <div class="col-12">
                <h4 class="mt-3">Roles</h4>

                <table id="roles-table" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role name</th>
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
    $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("role.table") }}',
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'actions'}
        ],
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>
@endsection