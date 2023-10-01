@extends('layouts.main')

@section('content')
    <a class="btn btn-accent mb-3" href="{{ url('/forms') }}">
        <i class="ti ti-chevron-left"></i> Back to Form List
    </a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">
                {{ $page_header }}
            </h5>

            <div class="mb-5">
                <form action="" class="row justify-content-center" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-4 text-center">
                        <div class="my-3">
                            Current Background: <br><br>
                            @if (!$form_bg)
                                <strong>None</strong>
                            @endif
                            <img src="{{ $form_bg }}" class="mb-3 img-fluid" />
                        </div>
                        <input type="file" name="bg_form" id="bg_form" class="upload-file form-control"
                            accept="image/*">
                    </div>
                </form>
            </div>


            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                data-bs-target="#modal_add_forwarding">
                <i class="ti ti-circle-plus"></i>
                Add Forwarding
            </button>
            @include('umum.forms.modals.modal_add_forwarding')
            <table class="table mb-5">
                <caption class="caption-top">
                    Setting Forward
                </caption>
                <thead class="table-primary">
                    <tr>
                        <th class='text-center'>#</th>
                        <th>Forward Name</th>
                        <th>Forward Link</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($forwards) && !empty($forwards))
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($forwards as $forward)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $forward->fw_name }}</td>
                                <td>{{ $forward->fw_link }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modal_update_forwarding{{ $i }}">
                                        <i class="ti ti-pencil fs-4"></i>
                                    </button>
                                    @include('umum.forms.modals.modal_update_forwarding')

                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modal_delete_forwarding{{ $i }}">
                                        <i class="ti ti-trash fs-4"></i>
                                    </button>
                                    @include('umum.forms.modals.modal_delete_forwarding')
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>


            <button type="button" class="btn btn-success btn-sm mt-5" data-bs-toggle="modal"
                data-bs-target="#modal_add_form_item">
                <i class="ti ti-circle-plus"></i>
                Add Item
            </button>
            @include('umum.forms.modals.modal_add_item_form')
            <table class="table w-100 mt-2">
                <caption class="caption-top">
                    <p class="mb-1">Item List</p>
                    <span class="text-danger">
                        Pastikan menambah <strong>item title</strong> untuk setiap kategori agar muncul di form
                    </span>
                </caption>
                <thead class="table-primary">
                    <tr>
                        <th class='text-center'>Order #</th>
                        <th>Item Name</th>
                        <th>Item Type</th>
                        <th>Mandatory</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($items) && !empty($items))
                        @php $i=0; @endphp
                        @foreach ($items as $item)
                            <tr @if ($item->item_type == 'title') class="bg-gray-light" @endif>
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->item_type }}</td>
                                <td>{{ $item->item_mandatory == 1 ? 'Y' : 'N' }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modal_update_item_form{{ $i }}">
                                        <i class="ti ti-pencil fs-4"></i>
                                    </button>
                                    @include('umum.forms.modals.modal_update_item_form')

                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modal_delete_item_form{{ $i }}">
                                        <i class="ti ti-trash fs-4"></i>
                                    </button>
                                    @include('umum.forms.modals.modal_delete_item_form')

                                    @if (
                                        $item->item_type == 'checkbox' ||
                                            $item->item_type == 'select' ||
                                            $item->item_type == 'radio' ||
                                            $item->item_type == 'select2' ||
                                            $item->item_type == 'select2multiple')
                                        <a href="{{ url('/forms/edit/' . $form->form_name_e . '/' . $item->item_name) . '/options' }}"
                                            class="btn btn-secondary btn-sm">
                                            <i class="ti ti-list fs-4"></i>
                                        </a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@endsection
