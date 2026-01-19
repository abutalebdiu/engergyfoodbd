@push('script')
    <script src="{{ asset('assets/admin/js/sms_counter.min.js') }}"></script>
    <script>
        $('#message').countSms('#sms-counter');
    </script>
@endpush
