@extends('admin.layouts.app', ['title' => 'Liabilitie list'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Liabilitie List')
                <a href="{{ route('admin.liabilitie.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> @lang('Add New Liabilitie')</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="productsTable">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Note')</th>                            
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($liabilities as $item)
                            <tr>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td style="text-align: left"> {{ $item->name }} </td>
                                <td> {{ en2bn(number_format($item->amount,2,'.',',')) }}</td>
                                <td> {{ en2bn($item->note) }}</td>                                 
                                <td>
                                    <span
                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.liabilitie.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.liabilitie.show', $item->id) }}">
                                                    <i class="bi bi-eye"></i> @lang('Show')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm confirmationBtn btn-outline--success"
                                                    data-action="{{ route('admin.liabilitie.destroy', $item->id) }}"
                                                    data-question="@lang('Are you sure to Delete this Liabilitie?')" type="button">
                                                    <i class="fa fa-trash"></i>@lang('Delete')
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan=""></th>
                            <th>@lang('Total')</th>
                            <td>
                                {{  en2bn(number_format($liabilities->sum('amount'),2,'.',',')) }}
                            </td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection
