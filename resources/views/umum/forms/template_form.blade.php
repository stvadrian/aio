<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/' . $template . '/css/' . $template . '.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <!-- SweetAlert -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- Pace Loader -->
    <link rel="stylesheet" href="{{ asset('plugins/pace/flash.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <title>{{ ucwords($form->form_name) }}</title>
</head>

<body class="form-background {{ $form_background == '' ? 'bg-dark' : '' }}"
    data-background="{{ $form_background }}">

    <div class="overlay"></div>
    <div class="my-5 px-2">
        <div class="col-xs-12 col-sm-10 col-md-9 col-lg-8 mx-auto">
            <form action="{{ url('/forms/view/' . $form->form_name_e . '/post') }}" method="post" id="automate_form"
                enctype="multipart/form-data">
                @csrf
                @include('components.honeypot')

                <div class="card bg-body">
                    <div class="card-header d-flex justify-content-between">
                        <strong class="fs-4">{{ ucwords($form->form_name) }}</strong>
                        <img src="{{ asset('img/logo.png') }}" alt="logo" width="25" class="img-fluid">
                    </div>
                    <div class="card-body">
                        <div class="bs-stepper">
                            <div class="bs-stepper-header mb-3" role="tablist">
                                <!-- steps goes here -->
                                @php
                                    $no = 1;
                                    $title_counter = 0;
                                @endphp
                                @foreach ($form_items as $item)
                                    @if ($item->item_type == 'title')
                                        @php $title_counter++; @endphp
                                    @endif
                                @endforeach
                                @foreach ($form_items as $title)
                                    @if ($title->item_type == 'title')
                                        <div class="step" data-target="#stepper{{ $no }}">
                                            <button type="button" class="step-trigger" role="tab"
                                                aria-controls="stepper{{ $no }}"
                                                id="stepper{{ $no }}-trigger"
                                                data-stepper="{{ $no }}">
                                                <span class="bs-stepper-circle">{{ $no }}</span>
                                                <span class="bs-stepper-label">{{ $title->item_name }}</span>
                                            </button>
                                        </div>
                                        @if ($no != $title_counter)
                                            <div class="line"></div>
                                        @endif
                                        @php $no++; @endphp
                                    @endif
                                @endforeach
                            </div>
                            <div class="bs-stepper-content">
                                <!-- contents goes here -->
                                @php
                                    $no = 1;
                                    $counter = 0;
                                    $counter_checks = 0;
                                    $counter_radios = 0;
                                @endphp
                                @foreach ($titles as $title)
                                    <div id="stepper{{ $no }}" class="content" role="tabpanel"
                                        data-stepper="{{ $no }}"
                                        aria-labelledby="stepper{{ $no }}-trigger">
                                        @foreach ($title as $title2)
                                            @foreach ($title2 as $item)
                                                @php
                                                    $item_for_id = strtolower(str_replace(' ', '_', $item[0]) . $no);
                                                    $item_for_name = strtolower(str_replace(' ', '_', $item[0]));
                                                @endphp
                                                <div class="form-group mb-3">
                                                    @if ($item[2] == 0)
                                                        <label class="form-label"
                                                            for="{{ $item[0] }}">{{ $item[0] }}
                                                            <span class="text-muted">(Optional)</span></label>
                                                    @else
                                                        <label class="form-label"
                                                            for="{{ $item[0] }}">{{ $item[0] }}</label>
                                                    @endif

                                                    @if ($item[1] == 'select')
                                                        <select name="{{ $item_for_name }}"
                                                            class="form-control form-select {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            id="{{ $item_for_id }}">
                                                            <option selected disabled value="">
                                                                -- Select One --
                                                            </option>
                                                            @php
                                                                $options_select = explode('///', $item[3]);
                                                            @endphp
                                                            @foreach ($options_select as $select)
                                                                @if ($select != '')
                                                                    <option value="{{ $select }}"
                                                                        {{ old($item_for_name) == $select ? 'selected' : '' }}>
                                                                        {{ $select }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @elseif ($item[1] == 'select2')
                                                        <select name="{{ $item_for_name }}"
                                                            class="form-control form-select select2 {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            id="{{ $item_for_id }}" data-parent="automate_form">
                                                            <option selected disabled value="">
                                                                -- Select One --
                                                            </option>
                                                            @php
                                                                $options_select2 = explode('///', $item[3]);
                                                            @endphp
                                                            @foreach ($options_select2 as $select2)
                                                                @if ($select2 != '')
                                                                    <option value="{{ $select2 }}"
                                                                        {{ old($item_for_name) == $select2 ? 'selected' : '' }}>
                                                                        {{ $select2 }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @elseif ($item[1] == 'select2multiple')
                                                        <select name="{{ $item_for_name }}[]"
                                                            class="form-control form-select select2multiple {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            id="{{ $item_for_id }}" data-parent="automate_form"
                                                            multiple>
                                                            @php
                                                                $options_select2 = explode('///', $item[3]);
                                                                $old_val = '';
                                                                if (old($item_for_name) != '') {
                                                                    $old_val = implode('///', old($item_for_name));
                                                                }
                                                            @endphp
                                                            @foreach ($options_select2 as $select2)
                                                                @if ($select2 != '')
                                                                    <option value="{{ $select2 }}"
                                                                        {{ str_contains($old_val, $select2) ? 'selected' : '' }}>
                                                                        {{ $select2 }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @elseif ($item[1] == 'datetime')
                                                        <input type="datetime-local" name="{{ $item_for_name }}"
                                                            class="form-control {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            id="{{ $item_for_id }}"
                                                            value="{{ old($item_for_name) }}">
                                                        <input type="hidden" name="{{ $item_for_name }}"
                                                            id="{{ $item_for_id }}-hidden">
                                                    @elseif ($item[1] == 'time')
                                                        <input type="time" name="{{ $item_for_name }}"
                                                            class="form-control {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            id="{{ $item_for_id }}"
                                                            value="{{ old($item_for_name) }}">
                                                    @elseif ($item[1] == 'textarea')
                                                        <textarea name="{{ $item_for_name }}" id="{{ $item_for_id }}" cols="30" rows="5"
                                                            class="form-control {{ $item[2] == 0 ? 'optional' : '' }}">{{ old($item_for_name) }}</textarea>
                                                    @elseif ($item[1] == 'textAlpha')
                                                        <input type="text" name="{{ $item_for_name }}"
                                                            class="form-control isalpha spaces {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            id="{{ $item_for_id }}"
                                                            value="{{ old($item_for_name) }}">
                                                    @elseif ($item[1] == 'numberOnly')
                                                        <input type="text" name="{{ $item_for_name }}"
                                                            class="form-control isnumber {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            id="{{ $item_for_id }}"
                                                            value="{{ old($item_for_name) }}">
                                                    @elseif ($item[1] == 'checkbox')
                                                        @php
                                                            $counter_checks++;
                                                            $counter_checks_opt = 0;
                                                            $options_checks = explode('///', $item[3]);
                                                            $old_val_checks = '';
                                                            if (old($item_for_name) != '') {
                                                                $old_val_checks = implode('///', old($item_for_name));
                                                            }
                                                        @endphp
                                                        @if ($options_checks[0] == '')
                                                            <div class="form-check check-0">
                                                                <input type="checkbox" class="form-check-input"
                                                                    id="null-checks" name="null-checks"
                                                                    value="null" role="button" disabled>
                                                                <label
                                                                    class="form-check-label user-select-none fw-bold"
                                                                    for="null-checks" role="button">NULL</label>
                                                            </div>
                                                        @endif
                                                        @foreach ($options_checks as $check)
                                                            @if ($check != '')
                                                                @php $counter_checks_opt++; @endphp
                                                                <div
                                                                    class="form-check checkbox {{ $counter_checks }} {{ $item[2] == 0 ? 'optional' : '' }}">
                                                                    <input type="checkbox"
                                                                        name="{{ $item_for_name }}[]"
                                                                        class="form-check-input"
                                                                        id="{{ $item_for_id . $counter_checks_opt }}"
                                                                        value="{{ $check }}" role="button"
                                                                        {{ str_contains($old_val_checks, $check) ? 'checked' : '' }}>
                                                                    <label
                                                                        class="form-check-label user-select-none fw-bold"
                                                                        for="{{ $item_for_id . $counter_checks_opt }}"
                                                                        role="button">
                                                                        {{ $check }}
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @elseif ($item[1] == 'radio')
                                                        @php
                                                            $counter_radios++;
                                                            $counter_radios_opt = 0;
                                                            $options_radios = explode('///', $item[3]);
                                                        @endphp
                                                        @if ($options_radios[0] == '')
                                                            <div class="form-check check-0">
                                                                <input type="radio" class="form-check-input"
                                                                    id="null-checks1" name="null-checks"
                                                                    value="null" role="button" readonly
                                                                    value="">
                                                                <label
                                                                    class="form-check-label user-select-none fw-bold"
                                                                    for="null-checks" role="button">NULL</label>
                                                            </div>
                                                        @endif
                                                        @foreach ($options_radios as $radio)
                                                            @if ($radio != '')
                                                                @php $counter_radios_opt++; @endphp
                                                                <div class="form-check radio-{{ $counter_radios }}">
                                                                    <input type="radio"
                                                                        class="form-check-input {{ $item[2] == 0 ? 'optional' : '' }}"
                                                                        id="{{ $item_for_id . $counter_radios_opt }}"
                                                                        name="{{ $item_for_name }}"
                                                                        value="{{ $radio }}" role="button"
                                                                        {{ old($item_for_name) == $radio ? 'checked' : '' }}>
                                                                    <label
                                                                        class="form-check-label user-select-none fw-bold"
                                                                        for="{{ $item_for_id . $counter_radios_opt }}"
                                                                        role="button">{{ $radio }}</label>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @elseif ($item[1] == 'customDate')
                                                        <input type="text" name="{{ $item_for_name }}"
                                                            class="custom-date form-control {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            id="{{ $item_for_id }}" placeholder="dd / mm / yyyy"
                                                            value="{{ old($item_for_name) }}">
                                                    @elseif ($item[1] == 'file')
                                                        <input
                                                            class="form-control {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            name="{{ $item_for_name }}" type="file"
                                                            id="{{ $item_for_id }}">
                                                    @else
                                                        <input type="{{ $item[1] }}"
                                                            name="{{ $item_for_name }}"
                                                            class="form-control {{ $item[2] == 0 ? 'optional' : '' }}"
                                                            value="{{ old($item_for_name) }}"
                                                            id="{{ $item_for_id }}">
                                                    @endif
                                                    @php
                                                        $counter++;
                                                    @endphp
                                                </div>
                                            @endforeach
                                        @endforeach

                                        <div class="d-flex mt-4" id="whatsApp-notif">
                                            @if ($no == 1)
                                                @if ($no == sizeof($titles))
                                                    <div class="form-check check-0">
                                                        <input type="checkbox" class="form-check-input optional"
                                                            id="preserve_sendWA" name="preserve_sendWA"
                                                            value="1" role="button">
                                                        <label class="form-check-label user-select-none fw-bold"
                                                            for="preserve_sendWA" role="button"> I would like to
                                                            receive
                                                            WhatsApp related to my answer.</label>
                                                    </div>
                                                @endif
                                            @else
                                                @if ($no == sizeof($titles))
                                                    <div class="form-check check-0">
                                                        <input type="checkbox" class="form-check-input optional"
                                                            id="preserve_sendWA" name="preserve_sendWA"
                                                            value="1" role="button">
                                                        <label class="form-check-label user-select-none fw-bold"
                                                            for="preserve_sendWA" role="button"> I would like to
                                                            receive
                                                            WhatsApp related to my answer.</label>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="my-4 row">
                                            @if ($no == 1)
                                                @if ($no == sizeof($titles))
                                                    <div class="col-12">
                                                        <button type="button"
                                                            class="btn btn-primary d-block ms-auto step-next">Submit</button>
                                                    </div>
                                                @else
                                                    <div class="col-12">
                                                        <button type="button"
                                                            class="btn btn-primary d-block ms-auto step-next">Next</button>
                                                    </div>
                                                @endif
                                            @else
                                                @if ($no == sizeof($titles))
                                                    <div class="col-6">
                                                        <button type="button"
                                                            class="btn btn-secondary step-previous">Previous</button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="button"
                                                            class="btn btn-primary d-block ms-auto step-next">Submit</button>
                                                    </div>
                                                @else
                                                    <div class="col-6">
                                                        <button type="button"
                                                            class="btn btn-secondary step-previous">Previous</button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="button"
                                                            class="btn btn-primary d-block ms-auto step-next">Next</button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                    </div>
                                    @php $no++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


</body>


<script nonce="{{ $nonce }}" src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
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

<script nonce="{{ $nonce }}">
    var stepper;
    $(document).ready(function() {
        stepper = new Stepper($('#automate_form')[0], {
            animation: true,
            linear: false
        })

        $(".step-next").click(function() {
            validateBSStepper(stepper);
        })
        $(".step-previous").click(function() {
            stepper.previous();
        });

        $('.form-background').each(function() {
            let bg = $(this).data('background');
            $(this).css('background-image', 'url(' + bg + ')');
        });
    });
</script>

<script nonce="{{ $nonce }}" src="{{ asset('template/custom/custom.js') }}"></script>

@yield('scripts')


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


</html>
