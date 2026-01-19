@extends('admin.layouts.app', ['title' => 'Assets list'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">@lang('Assets List')
                <a href="{{ route('admin.asset.create') }}" class="btn btn-primary btn-sm float-end"> <i
                        class="fa fa-plus"></i> @lang('Add New Asset')</a>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="productsTable">
                    <thead>
                        <tr>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Purchase Date')</th>
                            <th>@lang('Expired Date')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $item)
                            <tr>
                                <td> {{ en2bn($loop->iteration) }} </td>
                                <td style="text-align: left"> {{ $item->title }} </td>
                                <td> {{ en2bn(number_format($item->price,2,'.',',')) }}</td>
                                <td> {{ en2bn($item->purchase_date) }}</td>
                                <td> {{ en2bn($item->expired_date) }}</td>
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
                                                <a href="{{ route('admin.asset.edit', $item->id) }}">
                                                    <i class="bi bi-pencil"></i> @lang('Edit')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.asset.show', $item->id) }}">
                                                    <i class="bi bi-eye"></i> @lang('Show')
                                                </a>
                                            </li>
                                            <li>
                                                <button class="btn btn-sm confirmationBtn btn-outline--success"
                                                    data-action="{{ route('admin.asset.destroy', $item->id) }}"
                                                    data-question="@lang('Are you sure to Delete this Asset?')" type="button">
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
                                {{  en2bn(number_format($assets->sum('price'),2,'.',',')) }}
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
