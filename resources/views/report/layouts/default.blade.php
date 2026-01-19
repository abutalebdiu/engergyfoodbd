
<section>
    <div class="py-3 d-flex justify-content-between">
        <h5 class="mb-0">{{ @$title }}</h5>
        <div class="d-flex">
            <a>
                <a href="javascript:void(0)" class="btn btn-outline-primary btn-sm float-end ms-3" data-bs-toggle="modal" data-bs-target="#printModal"> <i class="fa fa-print"></i> Report Print</a>
            </a>
            <a
                href="javascript:void(0)"
                class="btn btn-outline-primary btn-sm float-end ms-3" data-bs-toggle="modal" data-bs-target="#filterByDate"> <i class="fa fa-filter"></i>

                @if($range_date == 'to')
                    Filter By Date
                @else
                    {{ $range_date }}
                @endif
            </a>
        </div>
    </div>
</section>

@include('report.layouts.print', ['url' => $url])

@include('report.layouts.filter', ['url' => $url])


