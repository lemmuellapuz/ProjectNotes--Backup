@extends('layouts.app')

@section('content')

    <div class="container">

        <form action="{{ route('notes.update', ['note' => $note]) }}" method="POST">

            <div class="row mt-3">

                <h2>Edit Note</h2>
                <a href="{{ url()->previous() }}">Back</a>

                @include('components.alerts')
                
                @csrf
                @method('PUT')

                <div class="col-auto">
                    <input class="form-control" type="text" name="title" placeholder="Title" value="{{ $note->title }}" required>
                </div>

                <div class="col-auto">
                    <textarea name="content" placeholder="Content" class="form-control">{{ $note->content }}</textarea>
                </div>

                @if(!$attachment)
                <div class="col-12">
                    <input type="file" class="my-2" name="attachment" id="attachment">
                </div>
                @endif

                
                @include('components.form_errors')

                <div class="col-auto">
                    <input type="submit" value="Update" class="btn btn-success">
                </div>

            </div>

        </form>

        <h2 class="mt-3">Attachment</h2>
        
        @if($attachment)
        
        <div class="card shadow-sm my-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <img src="{{ route('file.get', ['notefile' => $attachment]) }}" alt="" style="width:10%">
                    <a href="{{ route('file.download', ['notefile' => $attachment]) }}" class="m-3">{{ $attachment->filename }}</a>
                </div>

                <form id="form-delete-attachment" action="{{ route('file.destroy', ['notefile'=>$attachment]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger m-3"><span class="fas fa-times"></span></button>
                </form>
            </div>
        </div>
        @endif

    </div>

@endsection

@section('script')

<script>
    
    $('#form-delete-attachment').on('submit', function(e){
        e.preventDefault();

        Swal.fire({
            title: 'Delete attachment?',
            text: 'Do you want to delete your attachment? You won\'t be able to revert this.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result)=>{

            if(result.isConfirmed) {

                let loading = Swal.fire({
                    title: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: ()=> {
                        Swal.showLoading()
                    }
                })

                $.ajax({
                    url: "{{ route('file.destroy', ['notefile'=> $attachment ?: '#']) }}",
                    method: 'POST',
                    data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}'
                    },
                    success: (response)=> {
                        
                        loading.close()
                        window.location.reload()

                    },
                    error: (response)=> {

                        loading.close()
                        Swal.fire({
                            title: response.responseJSON.status,
                            text: response.responseJSON.message,
                            icon: 'error',
                        })

                    }
                })

            }

        })


    })
</script>

@endsection