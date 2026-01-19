@extends('admin.layouts.app', ['title' => 'Product Size'])
@section('panel')
    <div class="card">
        <div class="card-header py-3">
            <h6 class="mb-0">Product Size </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-4 d-flex">
                    <div class="card border shadow-none w-100">
                        <div class="card-body">
                            <form class="row g-3" action="{{ route('admin.productsize.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input class="form-control" type="text" name="name" placeholder="Size name"
                                        required value="{{ old('name') }}">
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Add Size</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8 d-flex">
                    <div class="card border shadow-none w-100">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>@lang('SL')</th>
                                            <th>@lang('Name')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($productsizes as $item)
                                            <tr>
                                                <td> {{ $loop->iteration }} </td>
                                                <td> {{ $item->name }} </td>
                                                <td> <span
                                                        class="btn btn-{{ statusButton($item->status) }} btn-sm">{{ $item->status }}</span>
                                                </td>
                                                <td>
                                                    <div class="gap-1 fs-6">
                                                        <a href="javascript:;" class="text-primary d-none"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                            title="" data-bs-original-title="View detail"
                                                            aria-label="Views"><i class="fa fa-eye"></i></a>

                                                        <a href="{{ route('admin.productsize.edit', $item->id) }}"
                                                            class="btn btn-sm btn-outline-primary data-bs-toggle="tooltip"
                                                            data-bs-placement="bottom" title=""
                                                            data-bs-original-title="Edit info" aria-label="Edit"><i
                                                                class="fa fa-edit"></i> Edit</a>
                                                        <button class="btn btn-sm btn-outline-danger confirmationBtn"
                                                            data-question="@lang('Are you sure to remove this data from this list?')"
                                                            data-action="{{ route('admin.productsize.destroy', $item->id) }}">
                                                            <i class="fa fa-trash-alt"></i> @lang('Remove')
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center text-muted" colspan="100%">{{ __($emptyMessage) }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div><!--end row-->
        </div>
    </div>
    <x-destroy-confirmation-modal />
@endsection
