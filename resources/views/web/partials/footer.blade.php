

<section class="bg-white py-3">
    <div class="container">
        <div class="text-center">
            <p>@lang('Copyright') &copy; {{ date('Y') }}. @lang('All Rights Reserved')
            </p>
        </div>
    </div>
</section>


@push('script')
    <script>
        $(document).on('submit', '.subscribe-form', function(e) {
            e.preventDefault();
            var email = $('.email-input').val();
            if (!email) {
                notify('error', 'Email field is required');
            } else {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    url: "{{ route('subscribe') }}",
                    method: "POST",
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.success) {
                            $('input[name="email"]').val('');
                            notify('success', response.message);
                        } else {
                            notify('error', response.error);
                        }

                    }
                });
            }
        });
    </script>
@endpush
