FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview,
    FilePondPluginImageTransform,
    FilePondPluginImageResize
);

const file_input = $('#attachment').filepond({
    acceptedFileTypes: ['image/png', 'image/jpeg'],
    maxFileSize: '2MB',
    allowImageResize: false,
    maxFiles: '1',
    server: {
        
        process: '/notes/file/upload',
        revert: '/notes/file/revert',
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }

    }
});

const profile_file_input = $('#profile').filepond({
    acceptedFileTypes: ['image/png', 'image/jpeg'],
    maxFileSize: '2MB',
    allowImageResize: true,
    maxFiles: '1',
    server: {
        
        process: '/user/file/upload',
        revert: '/user/file/revert',
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }

    }
});

const excel_file_input = $('#excel-attachment').filepond({
    acceptedFileTypes: [
        'text/csv', //CSV
        // 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' //XLSX
    ],
    maxFileSize: '2MB',
    allowImageResize: false,
    maxFiles: '1',
    server: {
        
        process: '/notes/file/upload',
        revert: '/notes/file/revert',
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }

    }
});