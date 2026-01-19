@extends('admin.layouts.app', ['title' => __('বিস্তারিত')])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ __('Make Production List') }} - Total Qty: {{ en2bn($makeproductions->sum('qty')) }}
            
            
                <a href="{{ route('admin.makeproduction.show',$date) }}?department_id={{$department->id}}&type=pdf" class="btn btn-primary btn-sm float-end">
                    <i class="fa fa-download"></i> @lang('PDF')
                </a>
                
                <a href="{{ route('admin.makeproduction.create') }}" class="btn btn-outline-primary btn-sm float-end ms-2"> <i
                        class="bi bi-plus"></i> @lang('Add New Make Production')</a>

                <a href="{{ route('admin.makeproduction.index') }}" class="btn btn-outline-primary btn-sm float-end"> <i
                        class="bi bi-list"></i> @lang('Make Production List')</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <p>@lang('Department'): {{ $department->name }}, Date: {{ $date }}</p>
                </div>
            </div>
            <div class="row">
                @php
                    $i = 1;
                @endphp
                @foreach ($makeproductions->chunk(40) as $makeproduction)
                    <div class="col-12 col-md-6">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr class="border-bottom">
                                    <th style="width: 10%">@lang('SL No')</th>
                                    <th style="width: 60%">@lang('Product')</th>
                                    <th style="width: 60%">@lang('QTY')</th>
                                    <th style="width: 60%">@lang('Unit')</th>
                                    <th style="width: 15%">@lang('Weight') (গ্রাম)</th>
                                    <th style="width: 15%">@lang('Weight')  (কেজি)</th>
                                    <th style="width: 15%">@lang('Unit Price') </th>
                                    <th style="width: 15%">@lang('Total Price') </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalweightgram = 0;
                                    $totalweightkg   = 0;
                                    $totamamount     = 0;
                                @endphp
                                @foreach ($makeproduction as $key => $production)
                                    <tr>
                                        <td>{{ en2bn($i++) }} </td>
                                        <td style="text-align: left">  {{ optional($production->item)->name }} </td>
                                        <td>{{ en2bn($production->qty) }}</td>
                                        <td>{{ en2bn($production->item->unit->name) }}</td>
                                        <td>{{ en2bn(optional($production->item)->weight_gram * $production->qty) }}</td>
                                        <td>{{ en2bn((optional($production->item)->weight_gram * $production->qty)/1000) }}</td>
                                        
                                         @php
                                            $totalweightgram += (optional($production->item)->weight_gram * $production->qty);
                                            $totalweightkg   += ((optional($production->item)->weight_gram * $production->qty)/1000);
                                            $totamamount     += optional($production->item)->price  * $production->qty;
                                        @endphp
                                        <td>{{ en2bn(optional($production->item)->price) }}</td>
                                        <td>{{ en2bn(optional($production->item)->price  * $production->qty)   }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">@lang('Total')</th>
                                    <th>{{ en2bn($totalweightgram) }}</th>
                                    <th>{{ en2bn($totalweightkg) }}</th>
                                    <th></th>
                                    <th>{{ en2bn($totamamount) }}</th>
                                    
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection




@include('components.select2')
