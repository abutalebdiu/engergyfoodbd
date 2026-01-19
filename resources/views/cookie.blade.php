@extends('web.layouts.frontend', ['title' => 'Cookie Policy'])


@section('content')
    @include('sections.breadcrumb',['title' => 'Cookie Policy'])

    <section class="py-5">
        <div class="container">
            @php
                echo $cookie->data_values->description;
            @endphp
        </div>
    </section>
@endsection



{{-- @extends('web.layouts.frontend',['title'=> 'Cookie Policy'])
@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Cookie Policy')</h5>
                    </div>
                    <div class="card-body">
                        @php
                            echo $cookie->data_values->description
                        @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection --}}
