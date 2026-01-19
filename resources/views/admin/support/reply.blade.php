@extends('admin.layouts.app',['title' => 'Reply Ticket'])

@section('panel')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body ">

                    <h6 class="mb-4 card-title">
                        <div class="row">
                            <div class="col-sm-8 col-md-6">
                                @php echo $ticket->statusBadge; @endphp
                                [@lang('Support#'){{ $ticket->ticket }}] {{ $ticket->subject }}
                            </div>
                            <div class="mt-3 col-sm-4 col-md-6 text-sm-end mt-sm-0">
                                @if($ticket->status != Status::SUPPORT_CLOSE)
                                <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#DelModal">
                                    <i class="fa fa-lg fa-times-circle"></i> @lang('Close Support')
                                </button>
                                @endif
                            </div>
                        </div>
                    </h6>


                    <form action="{{ route('admin.support.reply', $ticket->id) }}" enctype="multipart/form-data" method="post" class="form-horizontal">
                        @csrf



                            <div class="d-flex align-items-end gap-3">
                                <div class="flex-grow-1">
                                    <textarea class="form-control" name="message" rows="5" required id="inputMessage"></textarea>
                                </div>
                               <div>
                                <button class="mt-4 btn btn-primary w-100" type="submit" name="replayTicket" value="1"><i class="la la-fw la-lg la-reply"></i> @lang('Submit')
                                </button>
                               </div>


                            </div>




                    </form>


                    @foreach($messages as $message)
                        @if($message->admin_id == 0)

                            <div class="mx-2 my-3 border row border--primary border-radius-3">

                                <div class="col-md-3 border-end text-md-end text-start">
                                    <h5 class="my-3">{{ $ticket->name }}</h5>
                                    @if($ticket->user_id != null)
                                        <p><a href="{{route('admin.users.detail', $ticket->user_id)}}" >&#64;{{ $ticket->name }}</a></p>
                                    @else
                                        <p>@<span>{{$ticket->name}}</span></p>
                                    @endif
                                    <button class="my-3 btn btn-danger btn-sm confirmationBtn"
                                    data-question="@lang('Are you sure to delete this message?')"
                                    data-action="{{ route('admin.support.delete',$message->id)}}"
                                    ><i class="la la-trash"></i> @lang('Delete')</button>
                                </div>

                                <div class="col-md-9">
                                    <p class="my-3 text-muted fw-bold">
                                        @lang('Posted on') {{ showDateTime($message->created_at, 'l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                </div>
                            </div>
                        @else
                            <div class="mx-2 my-3 border row border-warning border-radius-3 admin-bg-reply">

                                <div class="col-md-3 border-end text-md-end text-start">
                                    <h5 class="my-3">{{ @$message->admin->name }}</h5>
                                    <p class="lead text-muted">@lang('Staff')</p>
                                    <button class="my-3 btn btn-danger btn-sm confirmationBtn"
                                    data-question="@lang('Are you sure to delete this message?')"
                                    data-action="{{ route('admin.support.delete',$message->id)}}"
                                    ><i class="la la-trash"></i> @lang('Delete')</button>
                                </div>

                                <div class="col-md-9">
                                    <p class="my-3 text-muted fw-bold">
                                        @lang('Posted on') {{showDateTime($message->created_at,'l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                </div>

                            </div>

                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Close Support Ticket!')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>@lang('Are you want to close this support ticket?')</p>
                </div>
                <div class="modal-footer">
                    <form method="post" action="{{ route('admin.support.close', $ticket->id) }}">
                        @csrf
                        <input type="hidden" name="replayTicket" value="2">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal"> @lang('No') </button>
                        <button type="submit" class="btn btn-primary"> @lang('Yes') </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection




@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.support.index') }}" />
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            })
            var fileAdded = 0;
            $('.extraTicketAttachment').on('click',function(){
                if (fileAdded >= 4) {
                    notify('error',__('You\'ve added maximum number of file'));
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="row">
                        <div class="mb-3 col-9">
                            <div class="file-upload-wrapper" data-text="@lang('Select your file!')"><input type="file" class="form-control" name="attachments[]" id="inputAttachments" class="file-upload-field"/></div>
                        </div>
                        <div class="col-3">
                            <button type="button" class="btn btn-danger extraTicketAttachmentDelete"><i class="la la-times ms-0"></i></button>
                        </div>
                    </div>
                `)
            });

            $(document).on('click','.extraTicketAttachmentDelete',function(){
                fileAdded--;
                $(this).closest('.row').remove();
            });
        })(jQuery);
    </script>
@endpush
