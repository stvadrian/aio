<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/' . $template . '/css/' . $template . '.min.css') }}">
    <title>{{ ucwords($qr->qr_name) }}</title>
</head>

<body class="bg-dark">
    <div class="my-5 px-2">
        <div class="col-xs-12 col-sm-10 col-md-9 col-lg-8 mx-auto">
            <div class="card bg-body">
                <div class="card-header d-flex justify-content-between">
                    <strong class="fs-4">{{ ucwords($qr->qr_name) }} QR Code</strong>
                    <img src="{{ asset('img/logo.png') }}" alt="logo" width="25" class="img-fluid">
                </div>
                <div class="card-body text-center">
                    <img src="{{ $qr->qr_img }}" alt="QR" class="img-fluid p-4 border border-4 border-dark rounded my-4">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
