@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">
            @include('components.alerts')

            @can('create-note')
            <div class="col-auto">
                <a class="btn btn-primary" href="{{ route('notes.create') }}">Add Note</a>
            </div>
            @endcan
            <div class="col-auto">
                <a class="btn btn-primary" onclick="startCamera()">Search via QR</a>
            </div>
            <div class="col-auto">
                <a class="btn btn-success" href="{{ route('file.index') }}">Import notes</a>
            </div>

            <div class="col-12">
                <h4 class="mt-3">Notes</h4>

                <table id="notes-table" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>

                </table>

            </div>

        </div>

    </div>

   @include('components.qr_scanner_modal')
   @include('components.qr_generate_modal')
    
@endsection

@section('script')

<!-- DATATABLE -->
<script>
    $('#notes-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("notes.table") }}',
        columns: [
            {data: 'id'},
            {data: 'title'},
            {data: 'content'},
            {data: 'actions'}
        ],
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>

<!-- QR -->
<script>
    const html5QrCode = new Html5Qrcode("reader");
    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        
        html5QrCode.stop().then(() => {
            $('#qr-scanner-modal').modal('hide');
            
            let loading = Swal.fire({
                title: 'Searching data',
                text: 'Please wait.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            })

            $.ajax({
                url: "{{ route('notes.show.via-qr') }}",
                method: "POST",
                data: {
                    '_token':  "{{ csrf_token() }}",
                    'qr': decodedText
                },
                success: function(response) {
                    
                    window.location.replace("/notes/"+ response.data)
                    
                },
                error: function(response) {
                    loading.close();
                    
                    Swal.fire({
                        title: response.responseJSON.status,
                        text: response.responseJSON.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            })

        })

    };

    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    function startCamera() {
        //OPEN MODAL
        $('#qr-scanner-modal').modal('show');
        
        //START CAMERA
        html5QrCode.start({ facingMode: { exact: "environment"} }, config, qrCodeSuccessCallback).catch(function(){
            html5QrCode.start({ facingMode: { exact: "user"} }, config, qrCodeSuccessCallback).catch(function(){
                alert('No camera found');
            });
        });
    }

    $('#qr-scanner-modal').on('hidden.bs.modal', function (e) {
        try{
            html5QrCode.stop();
        }
        catch(err) {

        }
    })

    $('#qr-generate-modal').on('hidden.bs.modal', function() {
        $('#qrcode').empty();
    })

    function openQrModal(code) {
        $('#qr-generate-modal').modal('show');
        makeQr(code);
    }

    function makeQr(code) {
        new QRCode(document.getElementById('qrcode'), {
            text: code,
            width: 256,
            height: 256,
            correctLevel : QRCode.CorrectLevel.H,
            logo: '/images/logo.png',
            logoWidth: 50,
            logoHeigh: 50,
            onRenderingEnd: function(qrCodeOptions, dataURL) {
                $('#qr-download-btn').attr('href', dataURL);
            }
        });
    }
</script>
@endsection