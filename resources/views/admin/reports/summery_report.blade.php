@extends('admin.layouts.app', ['title' => __('Summery Report')])

@section('panel')
<div 
    x-data="summaryReport()" 
    x-init="loadData()"
    class="card"
>
    <div class="card-header">
        <h6>@lang('Summery Report')</h6>
    </div>

    <div class="card-body">
        <form
            action="{{ route('admin.reports.summery') }}"
            method="get"
            x-ref="reportForm"
        >
            <div class="row mb-3">

                <div class="col-md-3">
                    <input
                        type="date"
                        name="start_date"
                        class="form-control"
                        x-model="start_date"
                        value="{{ $start_date ?? '' }}"
                    >
                </div>

                <div class="col-md-3">
                    <input
                        type="date"
                        name="end_date"
                        class="form-control"
                        x-model="end_date"
                        value="{{ $end_date ?? '' }}"
                    >
                </div>

                <div class="col-md-4 d-flex gap-2">

                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="
                            $refs.reportForm.target = '_self';
                            loadData();
                        "
                    >
                        <i class="bi bi-search"></i> Search
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                        name="pdf"
                        value="1"
                        @click="$refs.reportForm.target = '_blank'"
                    >
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </button>

                </div>
            </div>
        </form>


        {{-- LOADING --}}
        @include('admin.reports.partials.skeleton')

        <div x-show="!loading" x-html="content"></div>

    </div>
</div>
@endsection


@push('script')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
function summaryReport() {
    return {
        start_date: '',
        end_date: '',
        loading: false,
        content: '',

        loadData() {
            this.loading = true;

            fetch(`{{ route('admin.reports.summeryreport') }}?start_date=${this.start_date}&end_date=${this.end_date}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                this.content = html;
                this.loading = false;
            })
            .catch(() => {
                alert('Failed to load data');
                this.loading = false;
            });
        }
    }
}
</script>
@endpush
