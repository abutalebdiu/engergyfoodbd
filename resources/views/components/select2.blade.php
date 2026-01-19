@push('style')
    <link href="{{ asset('assets') }}/admin/css/select2.min.css" rel="stylesheet" />
@endpush


@push('script')
    <script src="{{ asset('assets') }}/admin/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
