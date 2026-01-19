@extends('admin.layouts.app', ['title' => 'Notification History'])
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead class="table-light">
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Sent')</th>
                                    <th>@lang('Sender')</th>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            @if ($log->user)
                                                <span class="fw-bold">{{ $log->user->fullname }}</span>
                                                <br>
                                                <span class="small">
                                                    <a
                                                        href="{{ route('admin.buyers.detail', $log->user_id) }}"><span>@</span>{{ $log->user->username }}</a>
                                                </span>
                                            @else
                                                <span class="fw-bold">@lang('System')</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ showDateTime($log->created_at) }}
                                            <br>
                                            {{ $log->created_at->diffForHumans() }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ __($log->sender) }}</span>
                                        </td>
                                        <td>{{ __($log->subject) }}</td>
                                        <td>

                                            <div class="btn-group">
                                                <button data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class="notifyDetail"
                                                            data-type="{{ $log->notification_type }}"
                                                            @if ($log->notification_type == 'email') data-message="{{ route('admin.report.email.details', $log->id) }}" @else data-message="{{ $log->message }}" @endif
                                                            data-sent_to="{{ $log->sent_to }}"><i class="las la-desktop"></i>
                                                            @lang('Detail')</button>
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
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($logs->hasPages())
                    <div class="card-footer pagination-card-footer">
                        {{ paginateLinks($logs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>


    <div class="modal fade" id="notifyDetailModal" tabindex="-1" aria-labelledby="notifyDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notifyDetailModalLabel">@lang('Notification Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 class="mb-3 text-center">@lang('To'): <span class="sent_to"></span></h3>
                    <div class="detail"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('breadcrumb-plugins')
    @if (@$user)
        <a href="{{ route('admin.buyers.notification.single', $user->id) }}" class="btn btn-outline--primary btn-sm"><i
                class="las la-paper-plane"></i> @lang('Send Notification')</a>
    @else
        <x-search-form placeholder="Search Username" />
    @endif
@endpush

@push('script')
    <script>
        $('.notifyDetail').click(function() {
            var message = $(this).data('message');
            var sent_to = $(this).data('sent_to');
            var modal = $('#notifyDetailModal');
            if ($(this).data('type') == 'email') {
                var message = `<iframe src="${message}" height="500" width="100%" title="Iframe Example"></iframe>`
            }
            $('.detail').html(message)
            $('.sent_to').text(sent_to)
            modal.modal('show');
        });
    </script>
@endpush
