@push('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
@endpush

@push('script')
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
  
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                "aLengthMenu": [100, 150, 200, 300, 400, 500]
            });
        });
    </script>
@endpush
