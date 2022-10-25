@extends('layouts.app')

@section('content')

    <div class="container">

        <form action="{{ route('role.store') }}" method="POST">

            <div class="row mt-3">

                <h2>Add Role</h2>
                <a href="{{ url()->previous() }}">Back</a>

                @csrf
                
                @include('components.alerts')

                <div class="col-auto">
                    <input class="form-control" type="text" placeholder="Role name" name="name" required>
                </div>
                
                @include('components.form_errors')

                <div class="col-auto">
                    <input type="submit" value="Add" class="btn btn-success">
                </div>

            </div>

        </form>

    </div>

@endsection