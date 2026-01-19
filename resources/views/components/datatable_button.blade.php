@push('style')
<link href="{{ asset('adminfile') }}/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet" />
@endpush
@section('script')
    <script src="{{ asset('adminfile') }}/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('adminfile') }}/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('adminfile') }}/js/table-datatable.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.jshttps://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>


    <script>
        $(document).ready(function() {
            $('#datatableprint').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endsection
