@extends('layouts.main')

@section('content')
    <a class="btn btn-accent mb-3" href="{{ url('/forms') }}">
        <i class="ti ti-chevron-left"></i> Back to Form List
    </a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">
                {{ $form->form_name }} QR Code
            </h5>

            <div class="mb-5">
                <div class="row mb-3">
                    <div class="col-lg-12 text-center">
                        <img src="{{ $qr_img }}" class="img-fluid border border-3 p-3 border-accent rounded mb-3" />
                        <br>
                        <i class="text-muted">{{ $form->link_form }}</i>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-center gap-3">
                            <a id="downloadButton" download="{{ $form->form_name }}.png" href="{{ $qr_img }}"
                                class="btn btn-accent">
                                <i class="ti ti-download"></i> Download QR
                            </a>
                            <button class="btn btn-primary btn-copy" data-copy="{{ $form->link_form }}">
                                <i class="ti ti-copy"></i> Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

