@extends('admin.layouts.app', ['title' => 'Show Assets Detail'])
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
                <table class="table table-bordered table-hover table-striped">
                    <tbody>
                        <tr>
                            <th>@lang('Name')</th>
                            <td style="text-align: left"> {{ $asset->title }} </td>
                        </tr>
                        <tr>
                            <th>@lang('Price')</th>
                            <td style="text-align: left"> {{ en2bn(number_format($asset->price, 2, '.', ',')) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Purchase Date')</th>
                            <td style="text-align: left"> {{ en2bn($asset->purchase_date) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Expired Date')</th>
                            <td style="text-align: left"> {{ en2bn($asset->expired_date) }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Description')</th>
                            <td style="text-align: left"> {{ $asset->description }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Status')</th>
                            <td style="text-align: left">
                                <span class="btn btn-{{ statusButton($asset->status) }} btn-sm">{{ $asset->status }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Action')</th>
                            <td style="text-align: left">
                                <div class="btn-group">
                                    <button data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="{{ route('admin.asset.edit', $asset->id) }}">
                                                <i class="bi bi-pencil"></i> @lang('Edit')
                                            </a>
                                        </li>

                                        <li>
                                            <button class="btn btn-sm confirmationBtn btn-outline--success"
                                                data-action="{{ route('admin.asset.destroy', $asset->id) }}"
                                                data-question="@lang('Are you sure to Delete this Asset?')" type="button">
                                                <i class="fa fa-trash"></i>@lang('Delete')
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
    </div>

    <x-destroy-confirmation-modal />
@endsection
