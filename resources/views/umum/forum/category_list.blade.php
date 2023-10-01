@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>

            @component('components.modal_add')
                @slot('modal_body_add')
                    {{-- Modal Body Add  --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="category_name">Category Name</label>
                        <input type="text" name="category_name" id="category_name" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="category_icon">Category Icon</label>
                        <div class="row">
                            @foreach ($icons as $icon)
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="category_icon{{ $icon->id }}"
                                            name="category_icon" value="{{ $icon->icons_code }}" role="button">
                                        <label class="form-check-label user-select-none" for="category_icon{{ $icon->id }}"
                                            role="button">
                                            <h2 class="{{ $icon->icons_code }}"></h2>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="category_description">Category Description</label>
                        <textarea name="category_description" id="category_description" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                @endslot
            @endcomponent

            <table class="table table-borderless align-middle">
                <thead class="border-bottom border-2 border-accent">
                    <tr>
                        <th colspan="2" class="text-uppercase">Thread Category</th>
                        <th class="d-none d-md-table-cell text-center">Threads</th>
                        <th class="d-none d-md-table-cell text-center">Posts</th>
                        <th class="d-none d-md-table-cell">Last Post</th>
                        @if (auth()->user()->hak_akses == 3)
                            <th class="text-center">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if (count($threadCategories) > 0)
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($threadCategories as $category)
                            <tr>
                                <td class="text-center">
                                    <i class="{{ $category->category_icon }} fs-5"></i>
                                </td>
                                <td>
                                    <a class="fw-semibold"
                                        href="{{ url('/forum/' . $category->category_name) }}">{{ $category->category_name }}</a>
                                    <div class="fs-sm text-muted mt-1">{{ $category->category_description }}</div>
                                </td>
                                <td class="d-none d-md-table-cell text-center">
                                    <a class="fw-semibold" href="javascript:void(0)">{{ $category->threads_count }}</a>
                                </td>
                                <td class="d-none d-md-table-cell text-center">
                                    <a class="fw-semibold" href="javascript:void(0)">{{ $category->posts_count }}</a>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if ($category->latestPost)
                                        <span class="fs-sm">by
                                            <span class="fw-medium">{{ $category->latestThread->user->nm_user }}</span>
                                            <br>
                                            on
                                            {{ $category->latestPost->created_at->format('M d, Y') }}
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
                                                    <input type="hidden" name="catid" value="{{ $category->id }}">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="category_name">Category Name</label>
                                                        <input type="text" name="category_name"
                                                            id="category_name{{ $i }}" class="form-control"
                                                            value="{{ $category->category_name }}">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="category_icon">Category Icon</label>
                                                        <div class="row">
                                                            @foreach ($icons as $icon)
                                                                <div class="col-auto">
                                                                    <div class="form-check">
                                                                        <input type="radio" class="form-check-input"
                                                                            id="category_icon{{ $icon->id }}{{ $i }}"
                                                                            name="category_icon" value="{{ $icon->icons_code }}"
                                                                            role="button"
                                                                            {{ $category->category_icon == $icon->icons_code ? 'checked' : '' }}>
                                                                        <label class="form-check-label user-select-none"
                                                                            for="category_icon{{ $icon->id }}{{ $i }}"
                                                                            role="button">
                                                                            <h2 class="{{ $icon->icons_code }}"></h2>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="category_description">Category
                                                            Description</label>
                                                        <textarea name="category_description" id="category_description" cols="30" rows="5" class="form-control">{{ $category->category_description }}</textarea>
                                                    </div>
                                                @endslot
                                            @endcomponent

                                            <form action="" method="post">
                                                @csrf
                                                <input type="hidden" name="catid" value="{{ $category->id }}">
                                                <input type="hidden" name="delete">
                                                <button type="button" name="delete"
                                                    class="btn btn-danger btn-sm btn-delete"
                                                    data-name="{{ $category->category_name }}">
                                                    <i class="ti ti-trash fs-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center"> No Data Available </td>
                        </tr>
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
