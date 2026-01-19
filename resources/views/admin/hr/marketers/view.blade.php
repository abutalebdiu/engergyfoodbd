@extends('admin.layouts.app', ['title' => 'Marketers List'])
@section('panel')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-flex align-items-center mb-2 border-bottom pb-2">
        <div>
            <h6 class="m-0">Marketers List</h6>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.marketer.create') }}" type="button" class="btn btn-primary btn-sm"> <i
                    class="bi bi-plus-circle"></i> @lang('Add New')</a>
        </div>
    </div>
    <!--breadcrumb-->

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('SL No')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Commission')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Address')</th>
                            <th>@lang('Status')</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($marketers as $item)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('admin.marketer.edit', $item->id) }}"
                                                    class="dropdown-item">
                                                    <i class="bi bi-pencil-fill"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;" data-id="{{ $item->id }}"
                                                    data-question="@lang('Are you sure you want to delete this item?')"
                                                    data-action="{{ route('admin.marketer.destroy', $item->id) }}"
                                                    class="dropdown-item confirmationBtn">
                                                    <i class="bi bi-trash"></i> @lang('Delete')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td> {{ $loop->iteration }} </td>
                                <td class="text-start"> {{ $item->name }} </td>

                                <td> {{ en2bn($item->amount) }}%</td>
                                <td> {{ $item->mobile }}</td>
                                <td> {{ $item->address }}</td>
                                <td>
                                    @if ($item->status == 'Active')
                                        <a href="{{ route('admin.marketer.status.change', $item->id) }}"
                                            onclick="return confirm('@lang('Are you sure! Inactive this Marketer')')"
                                            class="btn btn-success btn-sm">@lang('Active')</a>
                                    @else
                                        <a href="{{ route('admin.marketer.status.change', $item->id) }}"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('@lang('Are you sure! Active this Marketer')')">@lang('Inactive')</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <x-destroy-confirmation-modal />
@endsection
