@extends('layouts.messaging')

@section('message_page')
    <div class="table-responsive">
        <table class="table table-hover table-vcenter table-striped align-middle js-dataTable w-100" data-dom="frt">
            <thead>
                <tr>
                    <td class="text-center">#</td>
                    <td>Recipient</td>
                    <td>Message</td>
                    <td class="text-end">Time Sent</td>
                </tr>
            </thead>
            <tbody>
                @if (count($sentMessages) > 0)
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($sentMessages as $message)
                        <tr>
                            <td class="text-center">
                                {{ ++$i }}
                            </td>
                            <td>
                                <a href="{{ url('/message/' . $message->token) }}"
                                    class="link-dark">{{ $message->recipient->nm_user }}</a>
                            </td>
                            <td>
                                <a class="link-dark" href="{{ url('/message/' . $message->token) }}">
                                    <p class="fw-semibold mb-1">{{ $message->subject }}</p>
                                    {{ $message->spoiler }}
                                </a>
                            </td>
                            <td class="text-end">
                                <span>{{ date('d M Y (H:i)', strtotime($message->created_at)) }}</span>
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
        {{ $sentMessages->links('components.pagination') }}
    </div>
@endsection

@section('scripts')
@endsection
