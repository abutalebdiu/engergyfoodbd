<div class="card">
    <div class="">
        <ul class="nav nav-tabs">
            @php
                $tabs = [
                    'v-pills-itempurchase-tab' => 'Item Purchase History',
                    'v-pills-transaction-tab' => 'Transaction History',
                    'v-pills-payment-tab' => 'Payment History',
                    'v-pills-duepayment-tab' => 'Due Payment History',
                ];
            @endphp

            @foreach ($tabs as $key => $value)
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link {{ $key }} v-pills-tabLink" id="{{ $key }}"
                        data-tab="{{ $key }}">{{ __($value) }}</a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="card-body">
        <div>
            @include('admin.users.partials.__form')

            <div id="loadingIndicator" style="display: none;">
                <div class="spinner-border text-primary text-center align-items-center justify-content-center m-auto"
                    role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="table-responsive" id="order_list">

            </div>
        </div>
    </div>
</div>


@push('script')
    <script>
        var supplier_id = "{{ $supplier->id }}";

        $('#v-pills-itempurchase-tab').on('click', function() {
            var selectedTab = $(this).attr('data-tab');
            var selectedStartDate = $('#start_date').val() || '';
            var selectedEndDate = $('#end_date').val() || '';
            loadList(selectedTab, selectedStartDate, selectedEndDate);
        });


        $('#v-pills-transaction-tab').on('click', function() {
            var selectedTab = $(this).attr('data-tab');
            var selectedStartDate = $('#start_date').val() || '';
            var selectedEndDate = $('#end_date').val() || '';
            loadList(selectedTab, selectedStartDate, selectedEndDate);
        });

        $('#v-pills-payment-tab').on('click', function() {
            var selectedTab = $(this).attr('data-tab');
            var selectedStartDate = $('#start_date').val() || '';
            var selectedEndDate = $('#end_date').val() || '';
            loadList(selectedTab, selectedStartDate, selectedEndDate);
        });

        $('#v-pills-duepayment-tab').on('click', function() {
            var selectedTab = $(this).attr('data-tab');
            var selectedStartDate = $('#start_date').val() || '';
            var selectedEndDate = $('#end_date').val() || '';
            loadList(selectedTab, selectedStartDate, selectedEndDate);
        });

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var selectedTab = $('.v-pills-tabLink.active').attr('data-tab');
            var selectedStartDate = $(this).find('#start_date').val();
            var selectedEndDate = $(this).find('#end_date').val();
            loadList(selectedTab, selectedStartDate, selectedEndDate);
        });



        function loadList(selectedTab = 'v-pills-itempurchase-tab', selectedStartDate = '', selectedEndDate = '') {

            var selectedTab = selectedTab || 'v-pills-itempurchase-tab';
            var selectedStartDate = selectedStartDate || '';
            var selectedEndDate = selectedEndDate || '';

            var url = "{{ route('admin.suppliers.statement', ':id') }}";
            url = url.replace(':id', supplier_id);

            $('#loadingIndicator').show();
            $('#order_list').empty();

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    tab: selectedTab,
                    start_date: selectedStartDate,
                    end_date: selectedEndDate
                },
                success: function(data) {
                    if (selectedTab == 'v-pills-itempurchase-tab') {
                        $('.v-pills-itempurchase-tab').addClass('active');
                        $('.v-pills-transaction-tab').removeClass('active');
                        $('.v-pills-payment-tab').removeClass('active');
                        $('.v-pills-duepayment-tab').removeClass('active');
                    } else if (selectedTab == 'v-pills-transaction-tab') {
                        $('.v-pills-transaction-tab').addClass('active');
                        $('.v-pills-itempurchase-tab').removeClass('active');
                        $('.v-pills-payment-tab').removeClass('active');
                        $('.v-pills-duepayment-tab').removeClass('active');
                    } else if (selectedTab == 'v-pills-payment-tab') {
                        $('.v-pills-payment-tab').addClass('active');
                        $('.v-pills-transaction-tab').removeClass('active');
                        $('.v-pills-itempurchase-tab').removeClass('active');
                        $('.v-pills-duepayment-tab').removeClass('active');
                    } else if (selectedTab == 'v-pills-duepayment-tab') {
                        $('.v-pills-duepayment-tab').addClass('active');
                        $('.v-pills-itempurchase-tab').removeClass('active');
                        $('.v-pills-transaction-tab').removeClass('active');
                        $('.v-pills-payment-tab').removeClass('active');
                    }

                    if (data.success) {
                        $('#order_list').html(data.data);
                    }

                    $('#loadingIndicator').hide();
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        loadList();
    </script>
@endpush
