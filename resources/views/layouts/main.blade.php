<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ session('darkmode') ? 'dark' : 'light' }}">

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
    <!-- DataTable -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SimpleBar -->
    <link rel="stylesheet" href="{{ asset('plugins/simplebar/dist/simplebar.min.css') }}">
    <!-- SummerNote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-lite.min.css') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">

    <title>Portal All in One | AIO</title>
</head>


<body class="bg-body-secondary">
    <div class="page-wrapper mini-sidebar" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
        data-sidebartype="mini-sidebar" data-sidebar-position="fixed" data-header-position="fixed">

        @include('components.sidebar')
        <div class="overlay d-none"></div>

        <div class="body-wrapper">
            @include('components.header')

            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
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
<!-- Bootstrap -->
<script nonce="{{ $nonce }}" src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
<!-- DataTable -->
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}">
</script>
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
</script>
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
</script>
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}">
</script>
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}">
</script>
<script nonce={{ $nonce }} src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script nonce={{ $nonce }} src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script nonce={{ $nonce }} src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script nonce={{ $nonce }} src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- SimpleBar -->
<script nonce={{ $nonce }} src="{{ asset('plugins/simplebar/dist/simplebar.min.js') }}"></script>
<!-- Summernote -->
<script nonce={{ $nonce }} src="{{ asset('plugins/summernote/summernote-lite.min.js') }}"></script>
<!-- ChartJS -->
<script nonce={{ $nonce }} src="{{ asset('plugins/chart.js/Chart.bundle.min.js') }}"></script>
{{-- Broadcaster  --}}
<script nonce={{ $nonce }} src="{{ asset('plugins/socketio.js') }}"></script>

<script nonce="{{ $nonce }}" src="{{ asset('template/custom/custom.js') }}"></script>
<script nonce="{{ $nonce }}" src="{{ asset('template/' . $template . '/js/' . $template . '.min.js') }}">
</script>


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

@yield('scripts')

</html>
