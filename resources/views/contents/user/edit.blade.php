@extends('layouts.app')

@section('content')

    <div class="container">

        <form action="{{ route('user.update', ['user' => $user]) }}" method="POST">

            <div class="row mt-3">

                <h2>Edit User</h2>
                <a href="{{ url()->previous() }}">Back</a>

                @include('components.alerts')
                
                @csrf
                @method('PUT')

                @if($user->profile)
                <div class="col-12">
                    <img src="{{ route('user.profile.get', ['user' => $user]) }}" alt="" style="width:25%">
                </div>
                @endif
                <div class="col-3 mb-3">
                    <label for="">Change profile picture:</label>
                    <input type="file" class="my-2" name="profile" id="profile">
                </div>

                <div class="col-12 mb-3">
                    <label for="">Name:</label>
                    <input class="form-control" type="text" name="name" placeholder="Name" value="{{ $user->name }}" required>
                </div>

                <div class="col-12 mb-3">
                    <label for="">Email:</label>
                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{ $user->email }}" required>
                </div>

                <div class="col-12 mb-3">
                    <input type="button" onclick="changePassword()" value="Change password" class="btn btn-secondary">
                </div>
                
                @include('components.form_errors')
                @include('contents.user.modal.update_password')

                <div class="col-auto my-3">
                    <input type="submit" value="Update" class="btn btn-success">
                </div>

            </div>

        </form>

    </div>

@endsection

@section('script')

<script>

    function changePassword() {
        $('#update-pass-form').attr('action', "{{ route('user.update-password', ['user' => $user]) }}")
        $('#update-password-modal').modal('show')
    }

</script>

@endsection