@extends('layouts.auth')

@section('content')
    @if (session('sended'))
        <div class="mb-0 pb-0 mt-5 text-center">
            <p class="fw-bolder fs-4 w-50 mx-auto">
                {{ session('sended') }}
            </p>
            <a href="{{ url('/') }}" class="btn btn-dark mt-3">
                <em class="ti ti-chevron-left me-1"></em> Back to Login
            </a>
        </div>
    @else
        <form action="" method="post" id="form-forgot-password" class="mt-5 js-validate-form">
            @csrf
            @include('components.honeypot')

            @if (session('mobile-not-found'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ session('mobile-not-found') }}
                </div>
            @endif

            <div class="input-group mb-3">
                <input type="tel" name="mobile" class="form-control isnumber @error('mobile') is-invalid @enderror"
                    placeholder="WhatsApp Number" value="{{ old('mobile') }}">
            </div>
            <div class="row mt-4 mb-3 justify-content-between">
                <div class="col-3">
                    <a href="{{ url('/') }}" class="btn btn-dark">
                        <em class="ti ti-chevron-left me-1"></em>
                    </a>
                </div>
                <div class="col-9 text-end">
                    <button type="submit" class="btn btn-accent">
                        Reset Password <i class="ti ti-chevron-right"></i>
                    </button>
                </div>
            </div>
        </form>
    @endif
@endsection


@section('script')
@endsection
