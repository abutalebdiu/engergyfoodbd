@extends('web.layouts.master')
@section('content')
    <div class="table-responsive--md table-responsive">
        <div class="text-end pb-3">
            <a href="{{ route('support.open') }}" class="btn btn-sm btn-base mb-2"> <i class="fa fa-plus"></i>
                @lang('New Support')</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>@lang('Subject')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Last Reply')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supports as $support)
                        <tr>
                            <td> <span> [#{{ $support->ticket }}]
                                    {{ __($support->subject) }} </span></td>
                            <td>
                                @php echo $support->statusBadge; @endphp
                            </td>
                            <td>{{ diffForHumans($support->last_reply) }} </td>

                            <td>
                                
                                <a href="{{ route('support.view', $support->ticket) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>
                                   @lang('Details')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $supports->links() }}

    </div>
@endsection

@push('title')
    <h5>@lang('Supports')</h5>
@endpush
