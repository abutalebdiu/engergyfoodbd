@extends('admin.layouts.app', ['title' => 'Employee Salary Detail'])
@section('panel')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Employee Salary Detail</h5>
        <div>
            <a href="{{ route('admin.salarygenerate.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> New Process
            </a>
            <a href="{{ route('admin.salarygenerate.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-list"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card-body">
        <form id="salaryForm" action="{{ route('admin.salarygenerate.show.detail') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Month</label>
                    <select name="month_id" class="form-select">
                        <option value="">Select Month</option>
                        @foreach ($months as $month)
                            <option value="{{ $month->id }}"
                                {{ request('month_id') == $month->id ? 'selected' : '' }}>
                                {{ $month->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Year</label>
                    <select name="year_id" class="form-select">
                        <option value="">Select Year</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->id }}"
                                {{ request('year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="button" id="searchBtn" class="btn btn-primary">Search</button>
                    <button type="submit" name="pdf" value="1" class="btn btn-danger">PDF</button>
                </div>
            </div>
        </form>

        <div class="mt-3" id="loading" style="display:none;">
            @include('admin.reports.partials.skeleton')
        </div>

        <div class="mt-3" id="results" style="display:none;"></div>

        <div class="mt-3 text-center text-muted" id="empty" style="display:none;">
            Please select filters and click Search to view salary details.
        </div>
    </div>
</div>

<x-destroy-confirmation-modal />
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const form = document.getElementById('salaryForm');
    const searchBtn = document.getElementById('searchBtn');
    const results = document.getElementById('results');
    const loading = document.getElementById('loading');
    const empty = document.getElementById('empty');
    const url = form.action;

    function fetchData() {
        const formData = new FormData(form);
        const queryString = new URLSearchParams(formData).toString();

        results.style.display = 'none';
        empty.style.display = 'none';
        loading.style.display = 'block';

        fetch(url + '?' + queryString, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            loading.style.display = 'none';
            if (html.trim() === '') {
                empty.style.display = 'block';
            } else {
                results.innerHTML = html;
                results.style.display = 'block';
            }
        })
        .catch(() => {
            loading.style.display = 'none';
            alert('Request failed!');
        });
    }

    searchBtn.addEventListener('click', fetchData);

    // Initial load default data
    fetchData();
});
</script>
@endpush
