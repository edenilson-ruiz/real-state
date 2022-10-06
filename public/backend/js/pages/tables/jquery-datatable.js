$(function () {
    $('.js-basic-example').DataTable({
        responsive: true
    });

    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        retrieve: true,
        processing: true,
        serverSide: true,
        ajax: "{{ route('properties.index') }}",
        paging: true,
        searching: true,
        data: {
            "_token": "{{ csrf_token() }}",  
        },            
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});