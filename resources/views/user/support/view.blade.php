@extends('web.layouts.frontend', ['title' => 'Supports'])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="card custom-card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <div>
                            @php echo $myTicket->statusBadge; @endphp
                        </div>
                    <h5 class="mt-0">
                        {{ $myTicket->subject }}
                    </h5>
                    </div>
                    @if ($myTicket->status != Status::SUPPORT_CLOSE && $myTicket->user)
                        <button class="btn btn-danger close-button btn-sm confirmationBtn" type="button"
                            data-question="@lang('Are you sure to close this support?')" data-action="{{ route('support.close', $myTicket->id) }}"><i
                                class="fa fa-lg fa-times-circle"></i>
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('support.reply', $myTicket->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex align-items-end gap-4">
                            <div class="flex-grow-1">
                                <textarea name="message" class="form-control" rows="4" placeholder="@lang('Write your message')">{{ old('message') }}</textarea>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-base btn-sm"> <i class="bi bi-reply-all"></i>
                                    @lang('Reply')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card custom-card mt-4">
                <div class="card-body">
                    @foreach ($messages as $message)
                        @if ($message->admin_id == 0)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <span>{{ $message->ticket->name }}</span>
                                    |
                                    <span class="text-muted fw-bold">
                                        <small>{{ showDateTime($message->created_at, 'd M Y') }}</small>
                                        @
                                        <small>{{ showDateTime($message->created_at, 'H:i A') }}</small>
                                    </span>
                                </div>
                                <div class="card-body">
                                    <p>{{ $message->message }}</p>
                                </div>
                            </div>
                        @else
                            <div class="card mt-3">
                                <div class="card-header" style="background:#f1f1f1">
                                    <span>{{ $message->admin->name }}</span>
                                    |
                                    <span class="text-muted fw-bold">
                                        <small>{{ showDateTime($message->created_at, 'd M Y') }}</small>
                                        @
                                        <small>{{ showDateTime($message->created_at, 'H:i A') }}</small>
                                    </span>

                                </div>
                                <div class="card-body">
                                    <p>{{ $message->message }}</p>

                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a href="{{ route('support.download', encrypt($image->id)) }}"
                                                    class="me-3"><i class="fa fa-file"></i> @lang('Attachment')
                                                    {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <x-confirmation-modal />
@endsection
