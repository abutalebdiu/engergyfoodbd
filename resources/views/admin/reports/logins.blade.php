@extends('admin.layouts.app',['title'=>@$title])

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table">
                            <thead class="table-light">
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Login at')</th>
                                <th>@lang('IP')</th>
                                <th>@lang('Location')</th>
                                <th>@lang('Browser | OS')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($loginLogs as $log)
                                <tr>

                                <td>
                                    <span class="fw-bold">{{ @$log->user->name }}</span>
                                    <br>
                                    <span> <a href="{{ route('admin.buyers.detail', $log->user_id) }}"><span>@</span>{{ @$log->user->username }}</a> </span>
                                </td>

                                    <td>
                                        {{showDateTime($log->created_at) }} <br> {{diffForHumans($log->created_at) }}
                                    </td>



                                    <td>
                                        <span class="fw-bold">
                                        <a href="{{route('admin.report.login.ipHistory',[$log->user_ip])}}">{{ $log->user_ip }}</a>
                                        </span>
                                    </td>

                                    <td>{{ __($log->city) }} <br> {{ __($log->country) }}</td>
                                    <td>
                                        {{ __($log->browser) }} <br> {{ __($log->os) }}
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
                @if ($loginLogs->hasPages())
                <div class="card-footer pagination-card-footer">
                    {{ paginateLinks($loginLogs) }}
                </div>
                @endif
            </div><!-- card end -->
        </div>


    </div>
@endsection

@push('breadcrumb-plugins')
    @if(request()->routeIs('admin.report.login.history'))
        <x-search-form placeholder="Enter Username" />
    @endif
@endpush
@if(request()->routeIs('admin.report.login.ipHistory'))
    @push('breadcrumb-plugins')
        <a href="https://www.ip2location.com/{{ $ip }}" target="_blank" class="btn btn-primary">@lang('Lookup IP') {{ $ip }}</a>
    @endpush
@endif
