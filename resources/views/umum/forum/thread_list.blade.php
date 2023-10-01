@extends('layouts.main')

@section('content')
    <a class="btn btn-accent mb-3" href="{{ url('/forum') }}">
        <i class="ti ti-chevron-left"></i> Back to Forum List
    </a>
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>

            @component('components.modal_add')
                @slot('button_title')
                    Create New Thread
                @endslot
                @slot('modal_header_add')
                    Create New Thread
                @endslot
                @slot('modal_body_add')
                    {{-- Modal Body Add  --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="title">Thread Title</label>
                        <input type="text" name="title" id="title" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="content">Thread Content</label>
                        <textarea name="content" id="content" cols="30" rows="10" class="form-control summernote"
                            data-placeholder="Write Your Content Here"></textarea>
                    </div>
                @endslot
            @endcomponent

            <table class="table table-borderless align-middle">
                <thead class="border-bottom">
                    <tr>
                        <th>{{ $category->category_name }} Threads List</th>
                        <th class="d-none d-md-table-cell text-center">Posts</th>
                        <th class="d-none d-md-table-cell">Last Post</th>
                        @if (auth()->user()->hak_akses == 3)
                            <th class="text-center">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($threads->isEmpty())
                        <tr>
                            <td class="text-center" colspan="4">No Thread is Available</td>
                        </tr>
                    @else
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($threads as $thread)
                            <tr>
                                <td>
                                    <a class="fw-semibold"
                                        href="{{ url('/forum/' . $category->category_name . '/' . $thread->title) }}">{{ $thread->title }}</a>
                                    <div class="fs-sm text-muted mt-1">
                                        <span class="fw-semibold">{{ $thread->user->nm_user }}</span>
                                        {{ date('M d, Y', strtotime($thread->created_at)) }}
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell text-center">
                                    <a class="fw-semibold" href="#">{{ $thread->posts_count }}</a>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if ($thread->posts->isNotEmpty())
                                        <span class="fs-sm">
                                            by <span class="fw-medium"> {{ $thread->posts->last()->user->nm_user }}</span>
                                            <br>
                                            on {{ $thread->posts->last()->created_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                @if (auth()->user()->hak_akses == 3)
                                    <td class="text-start">
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            @component('components.modal_update', ['index' => $i])
                                                @slot('modal_body_update')
                                                    <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="title">Thread Title</label>
                                                        <input type="text" name="title" id="title{{ $i }}"
                                                            class="form-control" value="{{ $thread->title }}">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="content">Thread Content</label>
                                                        <textarea name="content" id="content{{ $i }}" cols="30" rows="10" class="form-control summernote"
                                                            data-placeholder="Write Your Content Here">{{ $thread->content }}</textarea>
                                                    </div>
                                                @endslot
                                            @endcomponent

                                            <form action="" method="post">
                                                @csrf
                                                <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                                                <input type="hidden" name="delete">
                                                <button type="button" name="delete"
                                                    class="btn btn-danger btn-sm btn-delete"
                                                    data-name="{{ $thread->title }}">
                                                    <i class="ti ti-trash fs-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection


@section('scripts')
    <script nonce="{{ $nonce }}">
        $(document).ready(function() {
            $('.btn-delete').each(function() {
                $(this).click(function(e) {
                    let item = $(this).data('name');
                    alertMessage(
                        'Are you sure want to delete <b>' + item + '</b>?',
                        'Yes',
                        'No',
                        () => {
                            $(this).parent().submit();
                        },
                    )
                })
            })
        })
    </script>
@endsection
