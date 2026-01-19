
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="min-width: 500px !important">
        <form action="{{ route($url) }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Report Print</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @if(request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif

                    @if(request('filter') === 'custom_range')

                        <input type="hidden" class="form-control" id="range" class="form-control" name="range"
                            @if(request('filter') == 'custom_range')
                                value="{{ explode(',', request('range'))[0] . ',' . explode(',', request('range'))[1] }}"
                            @endif
                        >
                    @endif

                    <div class="row">
                        <div class="col">
                            <h6>
                                Select Export type
                            </h6>
                        </div>
                    </div>
                    <div class="mb-4 row">
                        <div class="col">
                            <div class="form-group">
                                <input type="radio" class="custom-form-input" name="print_type" value="pdf" checked>
                                <label class="custom-form-label" for="print_type">Export to PDF</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <input type="radio" class="custom-form-input" name="print_type" value="excel">
                                <label class="custom-form-label" for="print_type">Export to Excel</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <input type="radio" class="custom-form-input" name="print_type" value="csv">
                                <label class="custom-form-label" for="print_type">Export to CSV</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <h6>
                                Select Export Language
                            </h6>
                        </div>
                    </div>

                    <div class="mb-4 row">
                        <div class="col">
                            <div class="form-group">
                                <input type="radio" class="custom-form-input" name="lang" value="bn" checked>
                                <label class="custom-form-label" for="lang">Bangla</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <input type="radio" class="custom-form-input" name="lang" value="en">
                                <label class="custom-form-label" for="lang">English</label>
                            </div>
                        </div>
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

</script>

@endpush

@push('style')

@endpush
