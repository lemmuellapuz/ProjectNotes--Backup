@extends('layouts.app')

@section('content')

    <div class="container text-center">
        <div class="card shadow">
            <div class="card-body">
                <h3>{{$note->title}}</h3>
                <p>{{$note->content}}</p>
            </div>
            <div class="card-footer">
                @can('edit-note')
                    <a href="{{ route('notes.edit', ['note'=> $note]) }}" class="btn btn-primary">Edit</a>
                @endcan
                <a href="{{ route('notes.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>

@endsection