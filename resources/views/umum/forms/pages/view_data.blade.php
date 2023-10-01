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

            @include('components.form_tgl')
            <div class="table-responsive">
                <table class="table table-bordered table-hover js-dataTable">
                    <thead class="table-primary">
                        <tr>
                            <th class='text-center'>#</th>
                            <th class="text-center">Actions</th>
                            @foreach ($columns as $column)
                                <th>{{ $column->item_name }}</th>
                            @endforeach
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($list_data as $data)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                <td class="text-center">
                                    <a href="{{ url('/forms/list-data/' . $form->form_name_e . '/' . $data->id) }}"
                                        class="btn btn-warning btn-sm">Forward</a>
                                </td>
                                @foreach ($columns as $column)
                                    @php $col = strtolower(str_replace(' ', '_', $column->item_name)); @endphp
                                    @if (str_contains($data->$col, '///'))
                                        <td>{{ substr(str_replace('///', ', ', $data->$col), 0, -2) }}</td>
                                    @else
                                        <td>{{ $data->$col == '' ? '-' : $data->$col }}</td>
                                    @endif
                                @endforeach
                                <td>{{ $data->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
