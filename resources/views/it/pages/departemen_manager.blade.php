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
                        <label class="form-label" for="kd_departemen">Kode Departemen</label>
                        <input type="text" name="kd_departemen" id="kd_departemen" class="form-control"
                            value="{{ count($departemens) + 1 }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="nm_departemen">Nama Departemen</label>
                        <input type="text" name="nm_departemen" id="nm_departemen" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="modul">Modul</label>
                        <input type="text" name="modul" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="controller">Controller</label>
                        <input type="text" name="controller" class="form-control" required>
                    </div>
                @endslot
            @endcomponent

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>Nama Departemen</th>
                        <th>Modul</th>
                        <th>Controller</th>
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @php $i=0; @endphp
                    @foreach ($departemens as $item)
                        <tr>
                            <td class="text-center">{{ ++$i }}</td>
                            <td>{{ $item->nm_departemen }}</td>
                            <td>{{ $item->modul }}</td>
                            <td>{{ $item->controller }}</td>
                            <td class="d-flex justify-content-center gap-1">
                                @component('components.modal_update', ['index' => $i])
                                    @slot('modal_body_update')
                                        <input type="hidden" name="departemen_id" value="{{ $item->id }}">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="kd_departemen">Kode Departemen</label>
                                            <input type="text" class="form-control" value="{{ $item->kd_departemen }}" disabled>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="nm_departemen">Nama Departemen</label>
                                            <input type="text" name="nm_departemen" id="nm_departemen{{ $i }}"
                                                class="form-control" value="{{ $item->nm_departemen }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="modul">Modul</label>
                                            <input type="text" name="modul" id="modul{{ $i }}" class="form-control"
                                                value="{{ $item->modul }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="controller">Controller</label>
                                            <input type="text" name="controller" id="controller{{ $i }}" class="form-control"
                                                value="{{ $item->controller }}">
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('components.alert_delete', ['index' => $i])
                                    @slot('deleteBody')
                                        <input type="hidden" name="departemen_id" value="{{ $item->id }}">
                                    @endslot
                                    @slot('deleteName')
                                        {{ $item->nm_departemen }}
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
