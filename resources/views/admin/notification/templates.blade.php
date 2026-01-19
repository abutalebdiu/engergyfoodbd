@extends('admin.layouts.app',['title'=> 'Notification Templates'])
@section('panel')
<div class="row">
	<div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive--sm table-responsive">
                    <table class="table">
                        <thead class="table-light">
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Subject')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td>{{ __($template->name) }}</td>
                                <td>{{ __($template->subj) }}</td>
                                <td>
                                    <a href="{{ route('admin.setting.notification.template.edit', $template->id) }}"
                                        class="btn btn-sm btn-outline-primary ms-1 editGatewayBtn">
                                        <i class="la la-pencil"></i> @lang('Edit')
                                    </a>
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
        </div>
    </div>
</div>
@endsection
