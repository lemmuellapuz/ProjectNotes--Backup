@extends('layouts.app')

@section('content')

    <div class="container">

        <form action="{{ route('notes.store') }}" method="POST">

            <div class="row mt-3">

                <h2>Add Note</h2>
                <a href="{{ url()->previous() }}">Back</a>

                @csrf
                
                @include('components.alerts')

                <div class="col-auto">
                    <input class="form-control" type="text" placeholder="Title" name="title" required>
                </div>

                <div class="col-auto">
                    <textarea name="content" placeholder="Note" class="form-control" required></textarea>
                </div>

                <div class="col-12">
                    <input type="file" class="my-2" name="attachment" id="attachment">
                </div>

                @include('components.form_errors')

                <div class="col-auto">
                    <input type="submit" value="Add" class="btn btn-success">
                </div>

            </div>

        </form>

    </div>

@endsection