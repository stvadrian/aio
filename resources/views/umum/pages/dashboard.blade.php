@extends('layouts.main')

@section('content')
    <div class="col-md-10 mb-4 fw-bolder">
        <p class="text-accent mb-1">{{ auth()->user()->departemen->nm_departemen }}</p>
        <div class="fs-6">Welcome, {{ auth()->user()->nm_user }}</div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header fw-semibold">
                    Live Chat
                </div>
                <div class="card-body">
                    <div class="rounded-top bg-light p-3 livechat" id="livechat-box">
                        @if (count($liveChats) == 0)
                            <em>It's quite here... Let's talk to others!</em>
                        @endif
                        @foreach ($liveChats as $liveChat)
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mb-2">
                                    <strong>{{ $liveChat->user->nm_user }}:</strong>
                                    {{ $liveChat->chat }}
                                </div>
                                <small>{{ date('H:i', strtotime($liveChat->created_at)) }}</small>
                            </div>
                        @endforeach
                    </div>
                    <form action="" method="post" id="livechat">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control rounded-top-0" name="flash-message" id="flash-message"
                                placeholder="Type here...">
                            <button type="submit" name="add_flash_message" class="btn btn-accent rounded-top-0">
                                <i class="ti ti-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header fw-semibold">
                    Task List
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-9">
                                <input type="text" class="form-control mb-3" name="add-task-list" id="add-task-list"
                                    placeholder="Add More Task..">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="add_task" class="btn btn-block btn-accent">Add</button>
                            </div>
                        </div>
                    </form>
                    <ul class="list-group">
                        @foreach ($tasks as $task)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $task->task_description }}

                                @component('components.alert_delete', ['index' => $task->id])
                                    @slot('classBtn')
                                        btn-outline-danger
                                    @endslot
                                    @slot('deleteBody')
                                        <input type="hidden" name="taskid" value="{{ $task->id }}">
                                    @endslot
                                    @slot('deleteName')
                                        Task {{ $task->task_description }}
                                    @endslot
                                @endcomponent
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header fw-semibold">
            Dashboard
        </div>
        <div class="card-body">
            <p class="mb-0">This is a sample pagee </p>

            <div class="row">
                <div class="col-md-5">
                    @component('components.chart')
                        @slot('chartId')
                            sampleChart
                        @endslot
                    @endcomponent
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    @component('components.chart')
                        @slot('chartId')
                            sampleChart2
                        @endslot
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script nonce="{{ $nonce }}">
        async function pollForUpdates() {
            try {
                const response = await fetch('/ajax/liveChatPoll', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                // Handle the received data
                console.log(data);
            } catch (error) {
                // Handle errors
                console.error(error);
            } finally {
                // Continue polling for updates
                // pollForUpdates();
            }
        }

        // Start the polling process
        pollForUpdates();


        $(document).ready(function() {
            generateChart('#sampleChart', @json($barChartData))
            generateChart('#sampleChart2', @json($pieChartData))

            $('#livechat').submit(function(e) {
                e.preventDefault();

                if ($('#flash-message').val().trim() != '') {
                    var dataToSend = {
                        chat: $('#flash-message').val(),
                    };

                    $.ajax({
                        url: "{{ route('ajax.sendLiveChat') }}",
                        type: 'POST',
                        data: dataToSend,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#flash-message').val('');
                        },
                        error: function(error) {
                            $('#flash-message').val(
                                'Some Error Occured. Try to refresh the page');
                        }
                    });
                }
            });

        });
    </script>
@endsection
