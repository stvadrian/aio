<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/' . $template . '/css/' . $template . '.min.css') }}">
    <title>{{ ucwords($form->form_name) }}</title>
</head>

<body class="form-background {{ $form_background == '' ? 'bg-dark' : '' }}" data-background="{{ $form_background }}">

    <div class="overlay"></div>
    <div class="my-5 px-2">
        <div class="col-xs-12 col-sm-10 col-md-9 col-lg-8 mx-auto">
            <div class="card bg-body">
                <div class="card-header d-flex justify-content-between">
                    <strong class="fs-4">{{ ucwords($form->form_name) }}</strong>
                    <img src="{{ asset('img/logo.png') }}" alt="logo" width="25" class="img-fluid">
                </div>
                <div class="card-body text-center">
                    <h1>Thank You!</h1>
                    <p class="lead fw-bolder my-5 px-3">Your {{ $form->form_name }} <br> Has Been Successfully
                        Submitted!</p>

                    <a href="{{ url('/forms/view/' . $form->form_name_e) }}" class="btn btn-outline-primary">
                        Submit Another Response
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- jQuery -->
<script nonce="{{ $nonce }}" src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script nonce="{{ $nonce }}">
    $(document).ready(function() {
        $('.form-background').each(function() {
            let bg = $(this).data('background');
            $(this).css('background-image', 'url(' + bg + ')');
        })
    });
</script>

</html>
