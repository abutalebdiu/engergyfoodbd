<form action="" id="search-form">
    <div class="row mb-2">
        <div class="col-12 col-md-4">
            <div class="py-2 form-group">
                <label for="" class="form-label">@lang('Start Date')</label>
                <input type="date" value="{{ isset($start_date) ? $start_date : date('Y-m-d') }}" name="start_date"
                    class="form-control" id="start_date">
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="py-2 form-group">
                <label for="" class="form-label">@lang('End Date')</label>
                <input type="date" value="{{ isset($end_date) ? $end_date : date('Y-m-d') }}" name="end_date"
                    class="form-control" id="end_date">
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="py-2 form-group" style="margin-top: 28px;">
               <button type="submit" class="btn btn-primary">@lang('Submit')</button>
            </div>
        </div>
    </div>
</form>