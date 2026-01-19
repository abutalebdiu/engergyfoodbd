<div class="card">
    <div class="">
        <ul class="nav nav-tabs">
            @php
                $tabs = [
                    'v-pills-order-tab' => 'Order List',
                    'v-pills-home-tab' => 'Transaction History',
                    'v-pills-profile-tab' => 'Payment History',
                    'v-pills-customerduepayment-tab' => 'Due Payment History',
                ];
            @endphp

            @foreach ($tabs as $key => $value)
            <li class="nav-item">
                <a href="javascript:;" class="nav-link {{ $key }} v-pills-tabLink" id="{{ $key }}" data-tab="{{ $key }}">{{ __($value) }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="card-body">
        <div>
            @include('admin.customers.partials.__form')

            <div id="loadingIndicator" style="display: none;">
                <div class="spinner-border text-primary text-center align-items-center justify-content-center m-auto" role="status">
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
        var customer_id = "{{ $customer->id }}";

        $('#v-pills-order-tab').on('click', function() {
            var selectedTab = $(this).attr('data-tab');
            var selectedStartDate = $('#start_date').val() || '';
            var selectedEndDate = $('#end_date').val() || '';
            loadList(selectedTab, selectedStartDate, selectedEndDate);
        });

        $('#v-pills-home-tab').on('click', function() {
            var selectedTab = $(this).attr('data-tab');
            var selectedStartDate = $('#start_date').val() || '';
            var selectedEndDate = $('#end_date').val() || '';
            loadList(selectedTab, selectedStartDate, selectedEndDate);
        });

        $('#v-pills-profile-tab').on('click', function() {
            var selectedTab = $(this).attr('data-tab');
            var selectedStartDate = $('#start_date').val() || '';
            var selectedEndDate = $('#end_date').val() || '';
            loadList(selectedTab, selectedStartDate, selectedEndDate);
        });

        $('#v-pills-customerduepayment-tab').on('click', function() {
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

       

        function loadList(selectedTab = 'v-pills-order-tab', selectedStartDate = '', selectedEndDate = '') {

            var selectedTab = selectedTab || 'v-pills-order-tab';
            var selectedStartDate = selectedStartDate || '';
            var selectedEndDate = selectedEndDate || '';

            var url = "{{ route('admin.customers.statement', ':id') }}";
            url = url.replace(':id', customer_id);

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
                    if(selectedTab == 'v-pills-order-tab'){
                        $('.v-pills-order-tab').addClass('active');
                        $('.v-pills-home-tab').removeClass('active');
                        $('.v-pills-profile-tab').removeClass('active');
                        $('.v-pills-customerduepayment-tab').removeClass('active');
                    }else if(selectedTab == 'v-pills-home-tab'){
                        $('.v-pills-home-tab').addClass('active');
                        $('.v-pills-order-tab').removeClass('active');
                        $('.v-pills-profile-tab').removeClass('active');
                        $('.v-pills-customerduepayment-tab').removeClass('active');
                    }else if(selectedTab == 'v-pills-profile-tab'){
                        $('.v-pills-profile-tab').addClass('active');
                        $('.v-pills-order-tab').removeClass('active');
                        $('.v-pills-home-tab').removeClass('active');
                        $('.v-pills-customerduepayment-tab').removeClass('active');
                    }else if(selectedTab == 'v-pills-customerduepayment-tab'){
                        $('.v-pills-customerduepayment-tab').addClass('active');
                        $('.v-pills-order-tab').removeClass('active');
                        $('.v-pills-home-tab').removeClass('active');
                        $('.v-pills-profile-tab').removeClass('active');
                    }

                    $('#order_list').html(data);
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
