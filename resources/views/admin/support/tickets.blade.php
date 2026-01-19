@extends('admin.layouts.app', ['title' => @$title])

@section('panel')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h4 class="mb-0">All Support List
                    <a href="{{ route('admin.support.create') }}" class="btn btn-primary btn-sm float-end"> <i class="fa fa-plus"></i> Add New Support</a>
                </h4>

            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive--sm table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('Subject')</th>
                            <th>@lang('Customer Name')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Last Reply')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.support.view', $item->id) }}" class="fw-bold">
                                        [@lang('Support')#{{ $item->ticket }}] {{ strLimit($item->subject, 30) }} </a>
                                </td>

                                <td>
                                    @if ($item->user_id)
                                        <a href=""> {{ @$item->user->name }}</a>
                                    @else
                                        <p class="fw-bold"> {{ $item->name }}</p>
                                    @endif
                                </td>
                                <td>
                                    @php echo $item->statusBadge; @endphp
                                </td>

                                <td>
                                    {{ diffForHumans($item->last_reply) }}
                                </td>

                                <td>
                                    <div class="btn-group">
                                        <button data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('admin.support.view', $item->id) }}">
                                                    <i class="las la-desktop"></i>@lang('Details')
                                                </a>
                                            </li>

                                        </ul>
                                    </div>



                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table><!-- table end -->
            </div>
        </div>
        @if ($items->hasPages())
            <div class="card-footer pagination-card-footer">
                {{ paginateLinks($items) }}
            </div>
        @endif
    </div><!-- card end -->
@endsection
