@extends('layouts.auth')

@section('content')
    <form action="{{ url('/login') }}" method="POST" id="form-login" class="me-4 js-validate-form">
        @csrf
        @include('components.honeypot')

        <div class="mb-4">
            @if (session('login-failed'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ session('login-failed') }}
                </div>
            @endif
            @if (session('recover-password'))
                <div class="alert alert-primary alert-dismissible" role="alert">
                    {{ session('recover-password') }}
                </div>
            @endif
            @if (session('honeypot'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('honeypot') }}
                </div>
            @endif
        </div>

        <div class="row mb-3 align-items-center justify-content-end">
            <div class="col-md-4">
                <label for="username" class="form-label">Username</label>
            </div>
            <div class="col-md-8">
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username') }}">
            </div>
        </div>
        <div class="row mb-3 align-items-center justify-content-end">
            <div class="col-md-4">
                <label for="username" class="form-label">Password</label>
            </div>
            <div class="col-md-8">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password">
            </div>
        </div>
        <div class="row mb-5 align-items-center">
            <div class="col-md-10">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input optional" id="remember" name="remember" value="1"
                        role="button">
                    <label class="form-check-label user-select-none fw-bold" for="remember" role="button">Remember
                        Me</label>
                </div>
            </div>
        </div>
        <div class="row mb-3 justify-content-end align-items-center">
            <div class="col-md-12 text-end">
                <a href="{{ url('/reset-password') }}" class="text-accent fs-sm fw-medium me-3">
                    Reset Password
                </a>
                <button type="submit" class="btn btn-accent">
                    Logon
                </button>
            </div>
        </div>
    </form>
@endsection

@section('script')
@endsection
