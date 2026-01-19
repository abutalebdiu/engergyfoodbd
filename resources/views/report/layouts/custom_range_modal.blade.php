
<div class="modal fade" id="customRangeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="min-width: 500px !important">
        <form class="form" action="{{ route($url) }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Custom Date Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 form-group">
                        <label for="start_date">Start Date</label>
                        <input type="text" class="form-control" id="start_date" class="form-control" name="start_date" required
                            @if(request('filter') == 'custom_range')
                                value="{{ explode(',', request('range'))[0] }}"
                            @endif
                        >
                        <p class="error_start_date text-danger">Example: 2022-01-01</p>
                    </div>

                    <div class="mb-3 form-group">
                        <label for="end_date">End Date</label>
                        <input type="text" class="form-control" id="end_date" name="end_date" required
                            @if(request('filter') == 'custom_range')
                                value="{{ explode(',', request('range'))[1] }}"
                            @endif
                        >
                        <p class="error_end_date text-danger">Example: 2022-01-01</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>


@push('script')

<script>
    $(document).ready(function() {
        $('#start_date').flatpickr({});
        $('#end_date').flatpickr({});
    });
</script>

<script>
    $(document).ready(function() {
        $('.form').on('submit', function(e) {
            e.preventDefault();
            $url = $(this).attr('action');

            $start_date = $('#start_date').val();
            $end_date = $('#end_date').val();

            if (!$start_date) {
                $('p.error_start_date').html('Please select start date');
                return;
            }

            if (!$end_date) {
                $('p.error_end_date').html('Please select end date');
                return;
            }

            $range = $start_date + ',' + $end_date;

            filterByDate('custom_range', $range);
        });

        function filterByDate(filter, $range = '') {
            let $url = '{{ route($url) }}';
            let $filter = filter;
            let $rangeValue = $range;


            window.location.href = $url + '?filter=' + $filter + '&range=' + $rangeValue;

            $('#customRangeModal').modal('hide');
        }
    });
</script>

@endpush

@push('style')

@endpush
