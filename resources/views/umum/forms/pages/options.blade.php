@extends('layouts.main')

@section('content')
    <a class="btn btn-accent mb-3" href="{{ url('/forms/edit/' . $form->form_name_e) }}">
        <i class="ti ti-chevron-left"></i> Back to Edit Form
    </a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">
                Manage Options For {{ $options[0]->item_name }}
            </h5>
            <form action="" method="post" id="add_option_form">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <input type="hidden" name="item_id" value="{{ $options[0]->id }}">
                        <input type="text" name="add_item_option" id="add_item_option" class="form-control"
                            placeholder="Type Your Option Here" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-success" name="add_options">Add Option</button>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#modal_add_options_from_api">Use API</button>
                    </div>
                </div>
            </form>

            @include('umum.forms.modals.modal_add_options_from_api')
            <table class="table table-hover w-100 mt-3">
                <caption class="caption-top">
                    <div class="d-flex justify-content-between align-items-center">
                        Inserted Options
                        <div class="d-flex gap-2">
                            <button class="btn btn-light-primary" id="select-all">Select/Deselect All</button>
                            <button class="btn btn-light-danger d-none" id="delete-selected">Delete Selected</button>
                        </div>
                        @include('umum.forms.modals.modal_delete_options_selected')
                    </div>
                </caption>
                <thead class="table-primary">
                    <tr>
                        <th class="text-center">#</th>
                        <th>Option Value</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($options as $option)
                        @if ($option->item_options == '')
                            <tr class="text-center">
                                <td colspan="100">
                                    No Options Yet
                                </td>
                            </tr>
                        @else
                            @php
                                $i = 0;
                                $options_arr = explode('///', $option->item_options);
                            @endphp
                            @foreach ($options_arr as $item_option)
                                @if ($item_option != '')
                                    <tr>
                                        <td class='text-center'>
                                            <input type="checkbox" name="option[]" class="form-check-input" role="button"
                                                value="{{ $item_option }}">
                                        </td>
                                        <td>{{ $item_option }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal_delete_options_form{{ $i }}">
                                                <i class="fas fa-times"></i> Delete
                                            </button>
                                            @include('umum.forms.modals.modal_delete_options_form')
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('scripts')
    <script nonce="{{ $nonce }}">
        $(document).ready(function() {
            $('#add_item_option').keydown(function(e) {
                let regex = /^[^\/]*$/;
                let input = e.key;
                console.log(input);
                if (!regex.test(input)) {
                    $(this)[0].setCustomValidity('Sorry, Options cannot contain slashes');
                    $(this)[0].reportValidity();
                    e.preventDefault();
                } else {
                    $(this)[0].setCustomValidity('');
                }
            })

            $("input[name='option[]']").change(function() {
                if ($("input[name='option[]']:checked").length > 0) {
                    $("#delete-selected").removeClass('d-none');
                } else {
                    $("#delete-selected").addClass('d-none');
                }
            });

            $("#delete-selected").click(function() {
                var selectedValues = [];

                $("input[name='option[]']:checked").each(function() {
                    selectedValues.push($(this).val());
                });
                var listItems = selectedValues.map(function(value) {
                    return "<li>" + value +
                        "</li><input type='hidden' name='selected_option[]' value='" + value +
                        "'/>";
                });

                $("#selected-list").html(listItems.join(""));
                $('#modal_delete_options_selected').modal('show');
            });

            var isAllChecked = false;
            $("#select-all").click(function() {
                isAllChecked = !isAllChecked;
                $("input[name='option[]']").prop('checked', isAllChecked).change();
            });
        });
    </script>
@endsection
