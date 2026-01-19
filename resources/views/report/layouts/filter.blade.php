
<div class="modal fade" id="filterByDate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter By Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="ul-list">
                    <li data-filter="today" class="{{ request()->filter == 'today' ? 'active' : '' }}">Today</li>
                    <li data-filter="yesterday" class="{{ request()->filter == 'yesterday' ? 'active' : '' }}">Yesterday</li>
                    <li data-filter="last_7_days" class="{{ request()->filter == 'last_7_days' ? 'active' : '' }}">Last 7 Days</li>
                    <li data-filter="this_month" class="{{ request()->filter == 'this_month' ? 'active' : '' }}">This Month</li>
                    <li data-filter="last_month" class="{{ request()->filter == 'last_month' ? 'active' : '' }}">Last Month</li>
                    <li data-filter="custom_range" class="{{ request()->filter == 'custom_range' ? 'active' : '' }}">Custom Range</li>
                    <li data-filter="clear" class="text-danger">Clear</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('report.layouts.custom_range_modal', ['url' => $url])


@push('script')

<script>
    $(document).ready(function() {

        $('.ul-list li').on('click', function() {
            $('.ul-list li').removeClass('active');
            $(this).addClass('active');
            var filter = $(this).data('filter');

            if(filter == 'custom_range'){
                $('#filterByDate').modal('hide');
                $('#customRangeModal').modal('show');

            }else if(filter == 'clear'){
                $('#filterByDate').modal('hide');
                $('#customRangeModal').modal('hide');
                filterByDate(filter);
            }else{
                $('#filterByDate').modal('hide');
                $('#customRangeModal').modal('hide');
                filterByDate(filter);
            }
        });

        function filterByDate(filter, range = '') {
            let url = '{{ route($url) }}';
            let filterParam = filter;
            let rangeValue = range;


            if (filterParam === 'clear') {
                window.location.href = url;
            } else if (filterParam) {
                window.location.href = url + '?filter=' + filterParam;
            } else if (rangeValue) {
                window.location.href = url + '?range=' + rangeValue;
            } else {
                window.location.href = url;
            }
        }

    });
</script>

@endpush

@push('style')
<style>
    .ul-list{
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ul-list li{
        padding: 5px 10px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;

    }

    .ul-list li:hover{
        background: #043599;
        color: #fff;
    }

    .ul-list li.active{
        background: #043599;
        color: #fff;
    }
</style>
@endpush
