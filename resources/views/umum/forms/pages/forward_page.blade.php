@extends('layouts.main')

@section('content')
    <a class="btn btn-accent mb-3" href="{{ url('/forms/list-data/' . $form->form_name_e) }}">
        <i class="ti ti-chevron-left"></i> Back to Data List
    </a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">
                {{ $page_header }}
            </h5>

            <form action="" method="post" id="edit-form" class="mb-5">
                @csrf
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-warning mb-3 btn-sm" id="btn-edit">
                        <i class="ti ti-pencil"></i> Edit Data
                    </button>
                    <button type="submit" class="btn btn-success mb-3 btn-sm d-none" id="btn-save" name="edit">
                        <i class="ti ti-check"></i> Save
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered w-100">
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($columns as $column)
                                <tr>
                                    @if ($column->item_type == 'title')
                                        <td colspan="2" class="table-primary">{{ $column->item_name }}</td>
                                    @else
                                        <td>{{ $column->item_name }}</td>
                                        <td>
                                            <input type="text" name="{{ $column->item_name_e }}" class="form-control"
                                                value="{{ $list_data->{$column->item_name_e} }}" disabled>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>Post Fields</th>
                        <th class="text-center">Action</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @if (isset($forwards))
                        @foreach ($forwards as $forward)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                <td class="text-wrap">
                                    @foreach ($columns as $column)
                                        @if ($column->item_type != 'title')
                                            {{ $column->item_name_e }} => {{ $list_data->{$column->item_name_e} }} <br>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <form action="{{ $forward->fw_link }}" method="post" target="_blank">
                                        @csrf
                                        @foreach ($columns as $column)
                                            @if ($column->item_type != 'title')
                                                <input type="hidden" name="{{ $column->item_name_e }}"
                                                    value="{{ $list_data->{$column->item_name_e} }}">
                                            @endif
                                        @endforeach
                                        <button type="submit" class="btn btn-primary">{{ $forward->fw_name }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endslot
            @endcomponent
        </div>
    </div>
@endsection

@section('scripts')
    <script nonce="{{ $nonce }}">
        $('#btn-edit').click(function() {
            $(this).addClass('d-none');
            $('#btn-save').removeClass('d-none');
            $('input').prop('disabled', false);
        })
    </script>
@endsection
