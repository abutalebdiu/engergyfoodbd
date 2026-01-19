@extends('web.layouts.frontend')
@section('content')

    @include('sections.breadcrumb')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5">
                        @php
                            echo $policy->lang('details');
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
