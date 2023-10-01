@extends('layouts.messaging')

@section('message_page')
    <div class="row mb-3 mt-3">
        <div class="col-md-12">
            <a href="{{ url('/message') }}" class="btn btn-accent mb-4">
                <i class="ti ti-chevron-left fs-2"></i> Back to Inbox
            </a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="d-flex">
                <img src="{{ $message->sender_profile }}" alt="profile" class="me-3" width="45" height="45"
                    class="img-fluid rounded-circle">
                <div>
                    <strong>{{ $message->sender->nm_user }}</strong> <br>
                    <p class="text-muted fs-2 mt-1"> To : {{ $message->recipient->nm_user }} </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex align-items-center justify-content-end">
                {{ date('M, d Y, H:i', strtotime($message->created_at)) }}
                <form action="" method="post" id="form-delete">
                    @csrf
                    <button type="button" class="ms-3 btn btn-light-gray btn-sm" id="btn-delete-message">
                        <i class="ti ti-trash fs-4"></i> Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>

    <hr>

    <div class="d-block mb-3 fs-4 fw-semibold">
        {{ $message->subject }}
    </div>

    <hr>

    <div class="row mb-3">
        <div class="col-md-12 p-3">
            @php echo $message->content @endphp
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex gap-3">
                <div class="modal" id="modal_reply" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="" method="post" id="form_add" class="js-validate-form">
                                @csrf
                                <div class="modal-header">
                                    <h3 class="modal-title"> Reply Message</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="reply_to_id" value="{{ $message->sender_id }}">
                                    <div class="form-group mb-3">
                                        <input type="text" name="reply_to" id="reply_to"
                                            value="{{ $message->sender->nm_user }}" class="form-control" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input name="subject" type="text" class="form-control"
                                            value="Reply: {{ $message->subject }}" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <textarea name="content" id="reply-content" class="form-control summernote" data-placeholder="Message">
                                            {{ $message->content }}
                                            <hr> <br>
                                        </textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="reply">
                                    <button type="submit" class="btn btn-success" name="reply">Reply</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-accent rounded-pill" data-bs-toggle="modal" data-bs-target="#modal_reply">
                    <i class="ti ti-send fs-2 me-2"></i> Reply
                </button>

                <div class="modal" id="modal_forward" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="" method="post" id="form_forward" class="js-validate-form">
                                @csrf
                                <div class="modal-header">
                                    <h3 class="modal-title">Forward Message</h3>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <select name="forward_to[]" id="forward_to" class="form-control select2multiple"
                                            data-placeholder="Recipient" data-parent="modal_forward" multiple
                                            role="button">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->nm_user }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <input name="subject" type="text" class="form-control"
                                            value="Forward: {{ $message->subject }}" readonly>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="hidden" name="forward">
                                    <button type="submit" class="btn btn-success" name="forward">Forward</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-accent rounded-pill" data-bs-toggle="modal"
                    data-bs-target="#modal_forward">
                    <i class="ti ti-arrow-forward fs-2 me-2"></i> Forward
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script nonce="{{ $nonce }}">
        $('#btn-delete-message').click(function() {
            alertMessage(
                'Are you sure want to delete this message?',
                'Yes', 'No',
                () => $('#form-delete').append('<input type="hidden" name="delete"/>').submit())
        });
    </script>
@endsection
