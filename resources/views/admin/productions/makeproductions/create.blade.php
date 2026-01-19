@extends('admin.layouts.app', ['title' => __('Add Production Expense')])
@section('panel')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.makeproduction.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            @lang('Add Production Expense')
                            <a href="{{ route('admin.makeproduction.index') }}" class="btn btn-primary btn-sm float-end"><i class="fa fa-list"></i> @lang('Daily Production Expense List')</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                              
                             
                            <div class="pb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label text-capitalize">@lang('Department')</label>
                                    <select name="department_id" id="department_id" class="form-control select2 department_id">
                                        <option value="">@lang('Select')</option>
                                        @foreach ($departments as $department)
                                            <option @if(Session::get('department_id')) {{ Session::get('department_id') == $department->id ? "selected" : "" }}  @endif value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pb-3 col-md-3">
                                <div class="form-group">
                                    <label class="form-label text-capitalize">@lang('Date')</label>
                                    <input class="form-control" type="date" name="date" required
                                        value="{{ old('date') ? old('date') : Date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-6">
                            <table>
                            <thead>
                                <tr class="border-bottom">
                                    <th style="width: 10%">@lang('SL No')</th>
                                    <th style="width: 70%">@lang('Product')</th>
                                    <th>@lang('Quantity')</th>
                                </tr>
                            </thead>
                            <tbody>
                             @php $i=1; @endphp
                                @foreach($itemsgroupes as $items)
                                   @php
                                        $categoryName = optional($items->first()->category)->name;
                                    @endphp
                                    
                                       <tr class="bg-secondary text-white">
                                            <td colspan="3" class="font-weight-bold  text-start p-1">
                                                {{ $categoryName ?: 'No Category' }}
                                            </td> 
                                        </tr>
                                        
                                    @forelse($items as $key => $item)
                                        <tr> 
                                           <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                                            <td>{{ en2bn($i++) }} - </td>
                                            <td style="text-align: left">
                                                {{ $item->name }}
                                            </td>
                                            <td><input type="text" name="item_qty[]"
                                                    id="item_qty_{{ $key }}" value=""
                                                    class="border form-control qty"></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center text-muted" colspan="100%">No Data Found</td>
                                        </tr>
                                    @endforelse
                            @endforeach
                             </tbody>
                        </table>
                              </div>  
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <a href="{{ route('admin.makeproduction.index') }}"
                                    class="btn btn-outline-info mt-4 float-start">Back</a>
                                <button type="submit" class="btn btn-primary mt-4 float-end">@lang('Submit')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .form-control:focus {
            border-color: red !important;
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the quantity inputs
            const qtyInputs = document.querySelectorAll('input[name="item_qty[]"]');

            // Add event listeners to each input
            qtyInputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    // Prevent form submission on Enter key press and move to the next input
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextInput = qtyInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }

                    // Navigate to next quantity input on Down arrow key press
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        const nextInput = qtyInputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }

                    // Navigate to previous quantity input on Up arrow key press
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        const prevInput = qtyInputs[index - 1];
                        if (prevInput) {
                            prevInput.focus();
                        }
                    }
                });
            });
        });
    </script>
@endpush

@include('components.select2')