@extends('admin.layouts.app', ['title' => 'All Suppliers'])
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Supplier List
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-outline-primary btn-sm float-end"><i
                        class="fa fa-plus"></i> Add New
                    Supplier</a>
            </h5>
        </div>
        <div class="card-body">
            <form action="" method="get">
                <div class="row mb-3 border-bottom pb-3">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="@lang('Search')" id="search"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <button class="btn btn-primary" name="pdf">@lang('PDF')</button>
                            <button class="btn btn-info" name="excel">@lang('Excel')</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>@lang('Action')</th>
                            <th>@lang('SL')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Company Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Mobile')</th>
                            <th>@lang('Address')</th>
                            <th>@lang('Opending Payable')</th>
                            <th>@lang('Total Payable')</th>
                            <th>@lang('Entry By')</th>

                        </tr>
                    </thead>
                    <tbody id="UserTable">
                        @php $totalpayable = 0;  @endphp
                        @forelse($users as $user)
                            @php $totalpayable += $user->payable($user->id);  @endphp
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.suppliers.detail', $user->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Details')
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.suppliers.statement', $user->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Statement')
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('admin.referenceCommision', $user->id) }}">
                                                    <i class="las la-desktop"></i> @lang('Setup Ref. Commission')
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>{{ en2bn($loop->iteration) }}</td>
                                <td style="text-align: left"><a
                                        href="{{ route('admin.suppliers.statement', $user->id) }}">{{ $user->name }}</a>
                                </td>
                                <td style="text-align: left">{{ $user->company_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile }}</td>
                                <td style="text-align: left">{{ $user->address }}</td>
                                <td>{{ en2bn(number_format($user->opening_due, 2, '.', ',')) }}</td>
                                <td>{{ en2bn(number_format($user->payable($user->id), 2, '.', ',')) }}</td>
                                <td>
                                    {!! entry_info($user) !!}
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
                            <td colspan="8">@lang('Total') </td>
                            <td>{{ en2bn(number_format($totalpayable, 2, '.', ',')) }}</td>
                        </tr>
                    </tfoot>
                </table><!-- table end -->
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#UserTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
@endpush
