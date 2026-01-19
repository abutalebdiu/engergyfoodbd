@extends('admin.layouts.app', ['title' => 'Mail Send History list'])
@section('panel')
    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive--md table-responsive">
                    <table class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Group')</th>
                                <th>@lang('Subject')</th>
                                <th>@lang('Body')</th>
                                <th>@lang('Participant Count')</th>
                                <th>@lang('Attachment Count')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Start At')</th>
                                <th>@lang('End At')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mail_histories as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ @$item->category->title }}</td>
                                    <td>{{ $item->subject }}</td>
                                    <td>{!! Str::limit($item->message, '20', '...') !!}</td>
                                    <td>
                                        @if (!empty($item->email))
                                            @php
                                                $decodedEmail = json_decode($item->email, true);
                                                $count = is_array($decodedEmail) ? count($decodedEmail) : 0;
                                            @endphp
                                            <span class="badge bg-warning">[{{ $count }}]</span>
                                        @else
                                            <span class="badge bg-warning">[0]</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($item->attachment))
                                            @php
                                                $decodedEmail = json_decode($item->attachment, true);
                                                $count = is_array($decodedEmail) ? count($decodedEmail) : 0;
                                            @endphp
                                            <span class="badge bg-warning">[{{ $count }}]</span>
                                        @else
                                            <span class="badge bg-warning">[0]</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php echo $item->statusBadge; @endphp
                                    </td>
                                    <td>
                                        {{ $item->created_at->format('d-m-Y H:i:s') }}
                                    </td>
                                    <td>
                                        {{ $item->updated_at->format('d-m-Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($mail_histories->hasPages())
                <div class="card-footer pagination-card-footer">
                    {{ paginateLinks($mail_histories) }}
                </div>
            @endif
        </div>
    </div>
@endsection
