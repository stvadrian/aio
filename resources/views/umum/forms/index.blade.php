@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>

            @component('components.modal_add')
                @slot('modal_body_add')
                    <div class="form-group mb-3">
                        <label class="form-label" for="form_name">Form Name</label>
                        <input type="text" name="form_name" id="form_name" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="form_description">Form Description</label>
                        <textarea id="form_description" name="form_description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="form_status">Status</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="item_status1" name="form_status" value="0"
                                    role="button">
                                <label class="form-check-label user-select-none" for="item_status1" role="button">Inactive</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="item_status2" name="form_status" value="1"
                                    role="button" checked>
                                <label class="form-check-label user-select-none" for="item_status2" role="button">Active</label>
                            </div>
                        </div>
                    </div>
                @endslot
            @endcomponent

            <table class="table table-border align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Form Name</th>
                        <th>Description</th>
                        <th>Creator</th>
                        <th>Link Form</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($forms) && !empty($forms))
                        @php $i = 0; @endphp
                        @foreach ($forms as $form)
                            <tr>
                                <td rowspan="2">{{ ++$i }}</td>
                                <td rowspan="2">{{ $form->form_name }}</td>
                                <td rowspan="2">{{ $form->description }}</td>
                                <td rowspan="2">{{ $form->nm_user }}</td>
                                <td rowspan="2">
                                    <a href="{{ url('/forms/view/' . $form->form_name_e) }}" target="_blank"
                                        class="text-accent">
                                        Open {{ $form->form_name }}
                                    </a>
                                </td>
                                <td rowspan="2" class="px-3 text-center">
                                    @if ($form->status == '1')
                                        <span
                                            class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success text-white">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger text-white">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                @if ($form->created_by == auth()->user()->id)
                                    <td class="text-center px-3 d-flex align-items-center justify-content-center gap-1">
                                        @if ($form->status == '1')
                                            <a class="btn btn-warning btn-sm"
                                                href="{{ url('/forms/view-qr/' . strtolower(str_replace(' ', '_', $form->form_name))) }}">
                                                <i class="ti ti-qrcode"></i> QR
                                            </a>
                                        @else
                                            <button class="btn btn-warning btn-sm">
                                                <i class="ti ti-qrcode"></i> QR
                                            </button>
                                        @endif
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ url('/forms/list-data/' . strtolower(str_replace(' ', '_', $form->form_name))) }}">
                                            <i class="ti ti-eye"></i> View Data
                                        </a>
                                    </td>
                                @else
                                    <td rowspan="2"
                                        class="text-center px-3 d-flex align-items-center justify-content-center gap-1">
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ url('/forms/list-data/' . strtolower(str_replace(' ', '_', $form->form_name))) }}">
                                            <i class="ti ti-eye"></i> View Data
                                        </a>
                                    </td>
                                @endif
                            </tr>
                            @if ($form->created_by == auth()->user()->id)
                                <tr>
                                    <td class="text-center px-3">
                                        <a class="btn btn-info btn-sm"
                                            href="/forms/edit/{{ strtolower(str_replace(' ', '_', $form->form_name)) }}">
                                            <i class="ti ti-pencil"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal_delete{{ $i }}">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                @include('umum.forms.modals.modal_delete_form')
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

