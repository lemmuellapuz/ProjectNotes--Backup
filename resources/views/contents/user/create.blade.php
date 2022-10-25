@extends('layouts.app')

@section('content')

    <div class="container">

        <form action="{{ route('user.store') }}" method="POST">

            <div class="row mt-3">

                <h2>Add User</h2>
                <a href="{{ url()->previous() }}">Back</a>

                @csrf
                
                @include('components.alerts')

                <div class="col-12">
                    <input type="file" class="my-2" name="profile" id="profile">
                </div>

                <div class="col-auto">
                    <input class="form-control" type="text" placeholder="Name" name="name" required>
                </div>

                <div class="col-auto">
                    <input type="email" name="email" placeholder="Email" class="form-control" required>
                </div>

                <div class="col-auto">
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                </div>

                <div class="col-auto">
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" required>
                </div>

                @include('components.form_errors')

                <div class="col-auto">
                    <input type="submit" value="Add" class="btn btn-success">
                </div>

            </div>

        </form>

    </div>

@endsection