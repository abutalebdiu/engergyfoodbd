@extends('admin.layouts.app', ['title' => 'Subscriber Manager'])

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Subscribe At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscribers as $subscriber)
                                    <tr>
                                        <td>{{ $subscriber->email }}</td>
                                        <td>{{ showDateTime($subscriber->created_at) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button data-bs-toggle="dropdown">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button class=" confirmationBtn" data-question="@lang('Are you sure to remove this subscriber?')"
                                                            data-action="{{ route('admin.subscriber.remove', $subscriber->id) }}">
                                                            <i class="las la-trash"></i> @lang('Remove')
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
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($subscribers->hasPages())
                    <div class="card-footer pagination-card-footer">
                        {{ paginateLinks($subscribers) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.subscriber.send.email') }}" class="btn btn-outline-primary"><i
            class="las la-paper-plane me-2"></i>@lang('Send Email')</a>
@endpush
