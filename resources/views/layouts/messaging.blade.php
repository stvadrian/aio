@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-md-5 col-xl-3">
            <div class="card">
                <div class="card-header">
                    <div class="block-title fs-5">
                        {{ $pageHeader }}
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills flex-column push">
                        <li class="nav-item my-1">
                            <a class="nav-link d-flex justify-content-between align-items-center {{ request()->is('message') ? 'active' : 'link-dark' }}"
                                href="{{ url('/message') }}">
                                <span class="fs-sm">
                                    <i class="ti ti-inbox me-1 opacity-50"></i> Inbox
                                </span>
                                <span class="badge rounded-pill bg-dark-subtle">{{ $receivedMessagesCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item my-1">
                            <a class="nav-link d-flex justify-content-between align-items-center {{ request()->is('message/sent') ? 'active' : 'link-dark' }}"
                                href="{{ url('/message/sent') }}">
                                <span class="fs-sm">
                                    <i class="ti ti-send me-1 opacity-50"></i> Sent
                                </span>
                                <span class="badge rounded-pill bg-dark-subtle">{{ $sentMessagesCount }}</span>
                            </a>
                        </li>
                        <li class="nav-item my-1">
                            <a class="nav-link d-flex justify-content-between align-items-center  {{ request()->is('message/starred') ? 'active' : 'link-dark' }}"
                                href="{{ url('/message/starred') }}">
                                <span class="fs-sm">
                                    <i class="ti ti-star me-1 opacity-50"></i> Starred
                                </span>
                                <span class="badge rounded-pill bg-dark-subtle">{{ $starredMessagesCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-7 col-xl-9">
            <div class="card">
                <div class="card-body">
                    @yield('message_page')
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    @yield('scripts')
@endsection
