@extends('layouts.main')

@section('content')
    <a class="btn btn-accent mb-3" href="{{ url('/forum/' . request()->route('thread_category')) }}">
        <i class="ti ti-chevron-left"></i> Back to Thread List
    </a>
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>
            <table class="table table-borderless">
                <tbody>
                    <tr class="border-bottom border-2">
                        <td class="d-none d-sm-table-cell text-center">
                            <p>
                                <img class="img-avatar" width="50" src="{{ $thread->user->preview_profile }}">
                            </p>
                        </td>
                        <td class="w-100">
                            <div class="d-flex justify-content-between">
                                <p>
                                    <span class="fw-semibold text-accent">
                                        {{ $thread->user->nm_user }}
                                        {{ $thread->user->nm_user == auth()->user()->nm_user ? '(You)' : '' }}
                                    </span>
                                    on {{ date('M d, Y H:i', strtotime($thread->created_at)) }}
                                    @if ($thread->created_at != $thread->updated_at)
                                        - <i class="fs-2">Edited at
                                            {{ date('M d, Y H:i', strtotime($thread->updated_at)) }}</i>
                                    @endif
                                </p>
                                @if (auth()->user()->id == $thread->user->id)
                                    <div class="d-flex gap-1">
                                        <a class="link-primary px-2" role="button" data-bs-toggle="modal"
                                            data-bs-target="#modal_update_thread">
                                            <i class="ti ti-edit fs-5"></i>
                                        </a>
                                    </div>
                                    <div class="modal" id="modal_update_thread" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="" method="post" id="form_update_thread"
                                                    class="js-validate-form">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h3 class="modal-title">Update Thread</h3>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="content">Thread Content</label>
                                                            <textarea name="content" id="content" cols="30" rows="10" class="form-control summernote"
                                                                data-placeholder="Write Thread Content">{{ $thread->content }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="update_thread">
                                                        <button type="submit" class="btn btn-primary"
                                                            name="update">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @php echo $thread->content @endphp
                        </td>
                    </tr>
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($thread->posts as $post)
                        <tr class="border-bottom border-2">
                            <td class="d-none d-sm-table-cell text-center">
                                <p>
                                    <img class="img-avatar" width="50" src="{{ $post->user->preview_profile }}">
                                </p>
                            </td>
                            <td class="w-100">
                                <div class="d-flex justify-content-between">
                                    <p>
                                        <span class="fw-semibold text-accent">
                                            {{ $post->user->nm_user }}
                                            {{ $post->user->nm_user == auth()->user()->nm_user ? '(You)' : '' }}
                                        </span>
                                        on {{ date('M d, Y H:i', strtotime($post->created_at)) }}
                                        @if ($post->created_at != $post->updated_at)
                                            - <i class="fs-2">Edited at
                                                {{ date('M d, Y H:i', strtotime($post->updated_at)) }}</i>
                                        @endif
                                    </p>
                                    @if (auth()->user()->id == $post->user->id)
                                        <div class="d-flex gap-1">
                                            <a class="link-primary px-2" role="button" data-bs-toggle="modal"
                                                data-bs-target="#modal_update_post{{ $i }}">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                            <div class="modal" id="modal_update_post{{ $i }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <form action="" method="post"
                                                            id="form_update_post{{ $i }}"
                                                            class="js-validate-form">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h3 class="modal-title">Update Post</h3>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="post_id"
                                                                    value="{{ $post->id }}">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label" for="content">
                                                                        Reply Content
                                                                    </label>
                                                                    <textarea name="content" id="content{{ $i }}" cols="30" rows="10" class="form-control summernote"
                                                                        data-placeholder="Reply Here">{{ $post->content }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <input type="hidden" name="update_post">
                                                                <button type="submit" class="btn btn-primary"
                                                                    name="update">Update</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <form action="" method="post">
                                                @csrf
                                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                <input type="hidden" name="delete_post">
                                                <a class="link-primary btn-delete" role="button">
                                                    <i class="ti ti-trash fs-4"></i>
                                                </a>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                @php echo $post->content @endphp
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach

                    <tr>
                        <td class="d-none d-sm-table-cell text-center">
                            <p>
                                <img class="img-fluid" width="50" src="{{ auth()->user()->preview_profile }}">
                            </p>
                        </td>
                        <td class="w-100">
                            <form action="" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="reply_content" class="form-label text-accent">You</label>
                                    <textarea name="reply_content" id="reply_content" cols="30" rows="5" class="form-control summernote"
                                        data-placeholder="Reply Here"></textarea>
                                </div>
                                <div class="mb-3 d-block text-end">
                                    <button type="submit" class="btn btn-accent" name="reply_post">
                                        <i class="ti ti-arrow-forward me-1 fs-4"></i> Reply
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
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
                    alertMessage(
                        'Are you sure want to delete your post?',
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
