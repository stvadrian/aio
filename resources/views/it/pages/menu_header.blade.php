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
                        <label class="form-label" for="header_name">Menu Header Name</label>
                        <input type="text" name="header_name" id="header_name" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="header_status">Status</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="status2" name="status" value="0"
                                    role="button">
                                <label class="form-check-label user-select-none" for="status2" role="button">Inactive</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="status1" name="status" value="1"
                                    role="button" checked>
                                <label class="form-check-label user-select-none" for="status1" role="button">Active</label>
                            </div>
                        </div>
                    </div>
                @endslot
            @endcomponent

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>Menu Header</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @php $i=0; @endphp
                    @foreach ($headers as $item)
                        <tr>
                            <td class="text-center">{{ ++$i }}</td>
                            <td>{{ $item->menu_header_name }}</td>
                            <td class="text-center">
                                @if ($item->menu_header_status == 1)
                                    @component('components.badge')
                                        @slot('color')
                                            success
                                        @endslot
                                        @slot('text')
                                            Active
                                        @endslot
                                    @endcomponent
                                @else
                                    @component('components.badge')
                                        @slot('color')
                                            success
                                        @endslot
                                        @slot('text')
                                            Inactive
                                        @endslot
                                    @endcomponent
                                @endif
                            </td>
                            <td class="d-flex justify-content-center gap-1">
                                @component('components.modal_update', ['index' => $i])
                                    @slot('modal_body_update')
                                        <input type="hidden" name="header_id" value="{{ $item->id }}">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="header_name{{ $i }}">Menu Header Name</label>
                                            <input type="text" name="header_name" id="header_name{{ $i }}"
                                                class="form-control" value="{{ $item->menu_header_name }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="header_status{{ $i }}">Status</label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="status2{{ $i }}"
                                                        name="status" value="0" role="button"
                                                        {{ $item->menu_header_status == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label user-select-none" for="status2{{ $i }}"
                                                        role="button">Inactive</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="status1{{ $i }}"
                                                        name="status" value="1" role="button"
                                                        {{ $item->menu_header_status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label user-select-none" for="status1{{ $i }}"
                                                        role="button">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('components.alert_delete', ['index' => $i])
                                    @slot('deleteBody')
                                        <input type="hidden" name="header_id" value="{{ $item->id }}">
                                    @endslot
                                    @slot('deleteName')
                                        {{ $item->menu_header_name }}
                                    @endslot
                                @endcomponent
                            </td>
                        </tr>
                    @endforeach
                @endslot
            @endcomponent
        </div>
    </div>
@endsection


@section('scripts')
@endsection
