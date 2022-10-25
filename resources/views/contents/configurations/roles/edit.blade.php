@extends('layouts.app')

@section('content')

    <div class="container">

        <form action="{{ route('role.update', ['role' => $role]) }}" method="POST">

            <div class="row mt-3">

                <div class="card p-5">
                    <h2>Edit Role</h2>
                    <a href="{{ url()->previous() }}">Back</a>

                    @include('components.alerts')
                    
                    @csrf
                    @method('PUT')

                    <div class="col-12 mt-3 mb-0">
                        <label for="">Role Name:</label>
                        <input class="form-control" type="text" name="name" placeholder="Role Name" value="{{ $role->name }}" required>
                    </div>
                    
                    @include('components.form_errors')

                    <div class="col-auto">
                        <input type="submit" value="Update role name" class="btn btn-success">
                    </div>
                </div>

            </div>
        </form>

        <div class="row mt-3">
            <hr>

            <div class="card p-5">

                <h2>Permissions</h2>

                <div class="row">
                    
                    <div class="col-3">
                    
                        <nav class="nav flex-column">
                            <a class="btn" onclick="showRolePermissions('{{$role->id}}', 'roles')" style="text-align:left !important;"><i class="fas fa-user-shield fa-fw"></i><span class="mx-1">Roles</span></a>
                            <a class="btn" onclick="showRolePermissions('{{$role->id}}', 'users')" style="text-align:left !important;"><i class="fas fa-user fa-fw"></i><span class="mx-1">User Accounts</span></a>
                            <a class="btn" onclick="showRolePermissions('{{$role->id}}', 'notes')" style="text-align:left !important;"><i class="fas fa-sticky-note fa-fw"></i><span class="mx-1">Notes</span></a>
                        </nav>
                    
                    </div>

                    <div class="col-9" style="border-left: 3px solid #dee2e6;">
                        <form id="role-permission-form">
                            @csrf
                            @method('PUT')
                            <h4 id="group-name"></h4>
                            <table id="permission-table" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:80%">Permission</th>
                                        <th>Grant</th>
                                        <th>Deny</th>
                                    </tr>
                                    <tr>
                                        <th style="width:80%"></th>
                                        <th><input class="radio-default" value="grant" name="all" id="radio-grant-all" type="radio"></th>
                                        <th><input class="radio-default" value="deny" name="all"  id="radio-deny-all" type="radio"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td class="text-center">Please select a permission group.</td>
                                </tbody>
                                <tfoot>
                                    <th style="width:80%">Permission</th>
                                    <th>Grant</th>
                                    <th>Deny</th>
                                </tfoot>
                            </table>

                            <div class="d-flex justify-content-end">
                                <input type="button" onclick="savePermission('{{ $role->id }}')" class="btn btn-success" value="Save">
                            </div>

                        </form>
                    </div>

                </div>

            </div>

        </div>

        

    </div>

@endsection

@section('script')
<script>

    showRolePermissions('{{$role->id}}', 'roles')

    var hasChanges = false
    var selectedGroup;

    function showRolePermissions(role, group) {

        if(hasChanges) {
            Swal.fire({
                icon: 'question',
                title: 'Before anything else',
                text: 'It looks like you\'ve made changes. This action will discard all the changes. Do you still want to continue?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result)=>{
                if(result.isConfirmed) {
                    hasChanges = false
                    selectedGroup = group
                    clearRadioDefault()
                    fillTable(role, group)
                }
            })
        }
        else {
            selectedGroup = group
            clearRadioDefault()
            fillTable(role,group)
        }
    }

    function fillTable(role, group) {

        $('#group-name').text(group[0].toUpperCase() + group.substring(1))

        $('#permission-table').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ordering: false,
            paging: false,
            searching: false,
            ajax: '/role/permission/table/'+role+'/'+group,
            columns: [
                {data: 'name'},
                {data: 'grant'},
                {data: 'deny'}
            ],
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ]
        });
    }

    function clearRadioDefault() {
        const permit = document.querySelectorAll('input[name=all]');

        for(let i = 0; i < permit.length; i++)
        {
            permit[i].checked = false;
        }
    }

    function radioOptionClicked() {
        
        clearRadioDefault()
        hasChanges = true
    }

    function savePermission(role) {
        
        if(hasChanges) {

            var loading = Swal.fire({
                title: 'Please wait',
                text: 'Saving permissions',
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: '/role/permission/update/'+role+'/'+selectedGroup,
                type: 'POST',
                data: $('#role-permission-form').serializeArray(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Permission saved',
                    })
                    hasChanges = false
                    loading.close()
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.responseJSON.message,
                    })
                    loading.close()
                }
            })

        }
    }

    $('#radio-grant-all').on('click', function(){
        $("#radio-grant-all").prop('checked', 'true')
        $(":radio[value=grant]").prop('checked', 'true')
        hasChanges = true
    })

    $('#radio-deny-all').on('click', function(){
        $("#radio-deny-all").prop('checked', 'true')
        $(":radio[value=deny]").prop('checked', 'true')
        hasChanges = true
    })
    
</script>
@endsection