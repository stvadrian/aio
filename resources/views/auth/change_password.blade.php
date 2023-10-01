@extends('layouts.auth')

@section('content')
    <form action="" method="post" class="js-validate-form">
        @csrf
        @include('components.honeypot')

        <p class="text-danger fw-bold">For security concerns, please update your password matching the specify condition below.</p>
        <div class="input-group mt-4">
            <input type="password" name="password" id="password"
                class="form-control enhanced @error('password') is-invalid @enderror" placeholder="Password">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        @include('components.password_requirements')

        <div class="input-group mb-3 mt-3">
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control enhanced @error('password') is-invalid @enderror" placeholder="Confirm Password">
        </div>

        <div class="d-flex mt-4 justify-content-between">
            <input type="hidden" name="change_pass">
            <button type="submit" class="btn btn-accent ms-auto d-block">
                Change password
            </button>
        </div>
    </form>
@endsection


@section('script')
@endsection
