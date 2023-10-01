<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/' . $template . '/css/' . $template . '.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/custom.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <!-- SweetAlert -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- Pace Loader -->
    <link rel="stylesheet" href="{{ asset('plugins/pace/flash.css') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <title>Portal All in One | AIO</title>
</head>

<body class="bg-body-secondary" data-bs-theme="{{ session('darkmode') ? 'dark' : 'light' }}">
    <div id="page-container">
        <main class="row p-3 pt-5 mt-5 mx-3 mx-md-5 border-top border-accent border-3 card-body bg-light-gray bg-body">
            <div class="col-lg-6 text-center my-4">
                <img src="{{ asset('img/logo.png') }}" alt="" class="w-25 mb-3">
                <h3 class="w-100">
                    INTERSYSTEMS TRAKCARE <br>
                    RSDH LIVE
                </h3>
            </div>
            <div class="col-lg-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>


<!-- jQuery -->
<script nonce="{{ $nonce }}" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- MomentJS -->
<script nonce="{{ $nonce }}" src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script nonce="{{ $nonce }}" src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<!-- Select2 -->
<script nonce="{{ $nonce }}" src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert -->
<script nonce="{{ $nonce }}" src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<!-- Pace Loader -->
<script nonce="{{ $nonce }}" src="{{ asset('plugins/pace/pace.min.js') }}"></script>

<script nonce="{{ $nonce }}" src="{{ asset('template/custom/custom.js') }}"></script>

@if (session('success'))
    <script nonce="{{ $nonce }}">
        sweetAlert('success', "@php
            echo session('success');
            session()->forget('success');
        @endphp")
    </script>
@endif
@if (session('error'))
    <script nonce="{{ $nonce }}">
        sweetAlert('error', "@php
            echo session('error');
            session()->forget('error');
        @endphp")
    </script>
@endif
@if (session('warning'))
    <script nonce="{{ $nonce }}">
        sweetAlert('warning', "@php
            echo session('warning');
            session()->forget('warning');
        @endphp")
    </script>
@endif
@if (session('info'))
    <script nonce="{{ $nonce }}">
        sweetAlert('info', "@php
            echo session('info');
            session()->forget('info');
        @endphp")
    </script>
@endif

@yield('script')

</html>
