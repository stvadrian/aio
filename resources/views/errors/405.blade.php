<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/' . $template . '/css/' . $template . '.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/error.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/custom.css') }}">

    <!-- Pace Loader -->
    <link rel="stylesheet" href="{{ asset('plugins/pace/flash.css') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <title>Portal All in One | AIO</title>
</head>

<body>
    <div class="d-flex vh-100 align-items-center justify-content-center {{ session('darkmode') ? 'bg-dark' : '' }}">
        <div class="text-center fw-bold">
            <div class="error mx-auto mb-3" data-text="405">405</div>
            <p class="lead text-gray-800 mb-4 fw-bolder">Method Not Allowed</p>
            <a href="{{ url('/') }}" class="btn btn-outline-accent">
                <i class="ti ti-chevron-left"></i>
                Back to Main Page
            </a>
        </div>
    </div>
</body>

<!-- Pace Loader -->
<script nonce="{{ $nonce }}" src="{{ asset('plugins/pace/pace.min.js') }}"></script>

</html>
