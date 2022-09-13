@extends('layouts.app')

@section('content')

    <div class="container mt-3">

        <form id="form-import-notes">

            <div class="row mt-3">

                <h2>Import Notes</h2>
                <a href="{{ route('notes.index') }}">Back</a>

                @csrf
                
                @include('components.alerts')

                <div class="col-12">
                    <input type="file" class="my-2" name="attachment" id="excel-attachment">
                </div>
                <small class="fw-bold fst-italic text-danger">Accepted file types: csv</small>

                @include('components.form_errors')

                <div class="col-auto">
                    <input type="submit" value="Add" class="btn btn-success">
                </div>

            </div>

        </form>

    </div>

@endsection

@section('script')

<script>
    $('#form-import-notes').on('submit', function(e){
        e.preventDefault();

        let loading = Swal.fire({
            title: 'Writing data',
            text: 'Please wait. This may take a while depending on the data being written',
            allowOutsideClick: false,
            didOpen: ()=>{
                Swal.showLoading();
            }
        })

        $.ajax({
            url: "{{ route('file.import') }}",
            method: "POST",
            data: $('#form-import-notes').serializeArray(),
            success: function(response) {
                loading.close()
                
                Swal.fire({
                    title: response.title,
                    text: response.message,
                    icon: 'success'
                }).then(function(res){
                    if(res) {
                        location.reload();
                    }
                });
            },
            error: function(response) {
                loading.close()
                
                Swal.fire({
                    title: response.responseJSON.title,
                    text: response.responseJSON.message,
                    icon: 'error'
                })

            }
        })
    })
</script>

@endsection