@extends('layouts.app')

@section('content')

    <div class="container text-center">
        <div class="card shadow" style="width:50%; left:25%;">
            <div class="card-body">
                
                <div class="d-flex justify-content-between align-items-center">
                    <p>Role name:</p>
                    <p>{{$role->name}}</p>
                </div>
            
            </div>
            <div class="card-footer">
                <a href="{{ route('role.edit', ['role'=> $role]) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('role.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

@endsection