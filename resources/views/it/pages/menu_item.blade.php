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
                        <label class="form-label" for="item_name">Menu Item Name</label>
                        <input type="text" name="item_name" id="item_name" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="access_link">Access Link</label>
                        <input type="text" name="access_link" id="access_link" class="form-control nospaces">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="menu_icon">Menu Icon</label>
                        <div class="row">
                            @foreach ($icons as $icon)
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" id="menu_icon{{ $icon->id }}"
                                            name="menu_icon" value="{{ $icon->icons_code }}" role="button">
                                        <label class="form-check-label user-select-none" for="menu_icon{{ $icon->id }}"
                                            role="button">
                                            <h2 class="{{ $icon->icons_code }}"></h2>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="file_name">File Name</label>
                        <input type="text" name="file_name" id="file_name" class="form-control nospaces">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="function_name">Function Name</label>
                        <input type="text" name="function_name" id="function_name" class="form-control nospaces">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="master_header">Master Header</label>
                        <div class="checkbox-set">
                            @foreach ($headers as $header)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="master_header{{ $header->id }}"
                                        name="master_header[]" value="{{ $header->id }}" role="button">
                                    <label class="form-check-label user-select-none" for="master_header{{ $header->id }}"
                                        role="button">{{ $header->menu_header_name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="show_departemen">Departemen</label>
                        <div class="checkbox-set">
                            @foreach ($departemens as $departemen)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="departemen{{ $departemen->id }}"
                                        name="show_departemen[]" value="{{ $departemen->modul }}" role="button">
                                    <label class="form-check-label user-select-none" for="departemen{{ $departemen->id }}"
                                        role="button">{{ $departemen->nm_departemen }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_status">Status</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="item_status1" name="item_status" value="0"
                                    role="button">
                                <label class="form-check-label user-select-none" for="item_status1" role="button">Inactive</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="item_status2" name="item_status" value="1"
                                    role="button" checked>
                                <label class="form-check-label user-select-none" for="item_status2" role="button">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="hak_akses">Hak Akses</label>
                        <select id="hak_akses" name="hak_akses" class="form-control form-select">
                            <option selected disabled value="">Select One</option>
                            @foreach ($hakAkses as $akses)
                                <option value="{{ $akses->kd_hak_akses }}">{{ $akses->nm_hak_akses }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="urutan">Urutan</label>
                        <input type="number" name="urutan" id="urutan" class="form-control" value="9" required>
                    </div>
                @endslot
            @endcomponent

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>Menu Header</th>
                        <th>Menu Item</th>
                        <th>File Name</th>
                        <th>Owner</th>
                        <th class="text-center">Status</th>
                        <th>Hak Akses</th>
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @php $i=0; @endphp
                    @foreach ($items as $item)
                        <tr>
                            <td class="text-center">{{ ++$i }}</td>
                            <td>{{ $item->menuHeader->menu_header_name }}</td>
                            <td>{{ $item->menu_item_name }}</td>
                            <td>{{ $item->menu_item_file }}</td>
                            <td>{{ $item->modul_departemen }}</td>
                            <td class="text-center">
                                @if ($item->menu_item_status == 1)
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
                                            danger
                                        @endslot
                                        @slot('text')
                                            Inactive
                                        @endslot
                                    @endcomponent
                                @endif
                            </td>
                            <td>{{ $item->hak_akses->nm_hak_akses }}</td>
                            <td class="d-flex justify-content-center gap-1">
                                @component('components.modal_update', ['index' => $i])
                                    @slot('modal_body_update')
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="item_name{{ $i }}">Menu Item
                                                Name</label>
                                            <input type="text" name="item_name" id="item_name{{ $i }}"
                                                class="form-control" value="{{ $item->menu_item_name }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="access_link{{ $i }}">Access
                                                Link</label>
                                            <input type="text" name="access_link" id="access_link{{ $i }}"
                                                class="form-control nospaces"- value="{{ $item->menu_item_link }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="menu_icon">Menu Icon</label>
                                            <div class="row">
                                                @foreach ($icons as $icon)
                                                    <div class="col-auto">
                                                        <div class="form-check">
                                                            <input type="radio" class="form-check-input"
                                                                id="menu_icon_update{{ $icon->id }}{{ $i }}"
                                                                name="menu_icon_update" value="{{ $icon->icons_code }}" role="button"
                                                                {{ $icon->icons_code == $item->menu_icon ? 'checked' : '' }}>
                                                            <label class="form-check-label user-select-none"
                                                                for="menu_icon_update{{ $icon->id }}{{ $i }}"
                                                                role="button">
                                                                <h2 class="{{ $icon->icons_code }}"></h2>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="file_name{{ $i }}">File
                                                Name</label>
                                            <input type="text" name="file_name" id="file_name{{ $i }}"
                                                class="form-control nospaces" value="{{ $item->menu_item_file }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="function_name{{ $i }}">Function
                                                Name</label>
                                            <input type="text" name="function_name" id="function_name{{ $i }}"
                                                class="form-control nospaces" value="{{ $item->menu_function }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="master_header">Master Header</label>
                                            <div class="checkbox-set">
                                                @foreach ($headers as $header)
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input"
                                                            id="master_header{{ $header->id }}{{ $i }}"
                                                            name="master_header" value="{{ $header->id }}"
                                                            {{ $header->id == $item->master_header ? 'checked' : '' }} role="button">
                                                        <label class="form-check-label user-select-none"
                                                            for="master_header{{ $header->id }}{{ $i }}"
                                                            role="button">{{ $header->menu_header_name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="item_status">Status</label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="item_status1{{ $i }}"
                                                        name="item_status" value="0" role="button"
                                                        {{ $item->menu_item_status == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label user-select-none"
                                                        for="item_status1{{ $i }}" role="button">Inactive</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" id="item_status2{{ $i }}"
                                                        name="item_status" value="1" role="button"
                                                        {{ $item->menu_item_status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label user-select-none"
                                                        for="item_status2{{ $i }}" role="button">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="hak_akses">Hak Akses</label>
                                            <select id="hak_akses{{ $i }}" name="hak_akses" class="form-control form-select"
                                                required>
                                                <option selected disabled value="">Select One</option>
                                                @foreach ($hakAkses as $akses)
                                                    <option value="{{ $akses->kd_hak_akses }}"
                                                        {{ $akses->kd_hak_akses == $item->hak_akses->kd_hak_akses ? 'selected' : '' }}>
                                                        {{ $akses->nm_hak_akses }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="urutan">Urutan</label>
                                            <input type="number" name="urutan" id="urutan{{ $i }}" class="form-control"
                                                value="{{ $item->urutan }}" required>
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('components.alert_delete', ['index' => $i])
                                    @slot('deleteBody')
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    @endslot
                                    @slot('deleteName')
                                        {{ $item->menu_item_name }}
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
