FilePond.registerPlugin(
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize
    );

const file_input = $('#attachment').filepond({
    acceptedFileTypes: ['image/png', 'image/jpeg'],
    maxFileSize: '2MB',
    maxFiles: '1',
    server: {
        
        process: '/notes/file/upload',
        revert: '/notes/file/revert',
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
    maxFiles: '1',
    server: {
        
        process: '/notes/file/upload',
        revert: '/notes/file/revert',
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }

    }
});