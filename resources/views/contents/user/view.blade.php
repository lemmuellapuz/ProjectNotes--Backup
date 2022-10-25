@extends('layouts.app')

@section('content')

    <div class="container text-center">
        <div class="card shadow" style="width:50%; left:25%;">
            <div class="card-body">
                
                <div class="">
                    <img src="{{ route('user.profile.get', ['user' => $user]) }}" alt="" width="20%">
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p>Name:</p>
                    <p>{{$user->name}}</p>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p>Email:</p>
                    <p>{{$user->email}}</p>
                </div>
            
            </div>
            <div class="card-footer">
                <a href="{{ route('user.edit', ['user'=> $user]) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('user.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

@endsection