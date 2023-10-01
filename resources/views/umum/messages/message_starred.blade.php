@extends('layouts.messaging')

@section('message_page')
    <div class="table-responsive">
        <form action="" method="post" id="form-inbox">
            @csrf
            <table class="table table-hover table-vcenter table-striped align-middle js-dataTable w-100" data-dom="frt">
                <thead>
                    <tr>
                        <td class="text-center">
                            <div class="form-check d-inline-block">
                                <input class="form-check-input border-accent" type="checkbox" name="select-all"
                                    id="select-all" role="button">
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-accent" type="submit" id="btn-star-message"
                                        name="star">
                                        <i class="ti ti-star-off fs-2"></i>
                                        <span class="d-none d-sm-inline ms-1">Unstar</span>
                                    </button>
                                    <button class="btn btn-sm btn-accent" type="button" id="btn-delete-message"
                                        name="delete">
                                        <i class="ti ti-trash fs-2"></i>
                                        <span class="d-none d-sm-inline ms-1">Delete</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($starredMessages) > 0)
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($starredMessages as $message)
                            <tr>
                                <td class="text-center">
                                    <div class="form-check d-inline-block">
                                        <input type="checkbox" name="checked-message[]" id="message-{{ $i }}"
                                            class="form-check-input border-accent" value="{{ $message->token }}"
                                            role="button">
                                    </div>
                                </td>
                                <td>
                                    @if ($message->is_read == 0)
                                        <small class="badge bg-accent">
                                            New
                                        </small>
                                    @endif
                                    <a href="{{ url('/message/' . $message->token) }}"
                                        class="link-dark {{ $message->is_read == 0 ? 'fw-semibold' : '' }}">{{ $message->sender->nm_user }}</a>
                                </td>
                                <td>
                                    <a class="link-dark {{ $message->is_read == 0 ? 'fw-semibold' : '' }}"
                                        href="{{ url('/message/' . $message->token) }}">
                                        <p class="fw-semibold mb-1">
                                            @if ($message->is_starred == 1)
                                                <i class="ti ti-star text-warning me-2"></i>
                                            @endif
                                            {{ $message->subject }}
                                        </p>
                                        {{ $message->spoiler }}
                                    </a>
                                </td>
                                <td class="text-end">
                                    <span
                                        class="{{ $message->is_read == 0 ? 'fw-semibold' : '' }}">{{ date('d M Y (H:i)', strtotime($message->created_at)) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="4">No Data Available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{ $starredMessages->links('components.pagination') }}
        </form>
    </div>
@endsection

@section('scripts')
    <script nonce="{{ $nonce }}">
        $(document).ready(function() {
            $('#btn-delete-message').click(function() {
                const checkboxes = $('input[name="checked-message[]"]:checked');
                if (checkboxes.length == 0) {
                    return false;
                }
                alertMessage(
                    'Are you sure want to delete this message?',
                    'Yes', 'No',
                    () => $('#form-inbox').append('<input type="hidden" name="delete"/>').submit());
            });
            $('#btn-star-message').click(function() {
                const checkboxes = $('input[name="checked-message[]"]:checked');
                if (checkboxes.length == 0) {
                    return false;
                }
            });
            $('#select-all').change(function() {
                const checkboxes = $('input[name="checked-message[]"]');
                if ($(this).is(':checked')) {
                    checkboxes.prop('checked', true);
                } else {
                    checkboxes.prop('checked', false);
                }
            })
        })
    </script>
@endsection
