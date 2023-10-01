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
                        <label class="form-label" for="icon_name">Nama Icon</label>
                        <input type="text" name="icon_name" id="icon_name" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="icon_code">Kode Icon</label>
                        <input type="text" name="icon_code" id="icon_code" class="form-control">
                    </div>
                @endslot
            @endcomponent

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <th class="text-center">#</th>
                    <th>Icon Name</th>
                    <th>Icon Code</th>
                    <th>Icon Display</th>
                    <th class="text-center">Actions</th>
                @endslot
                @slot('table_body')
                    @php $i=0; @endphp
                    @foreach ($icons as $item)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $item->icons_name }}</td>
                            <td>{{ $item->icons_code }}</td>
                            <td><i class="{{ $item->icons_code }} fs-4"></i></td>
                            <td class="d-flex justify-content-center gap-1">
                                @component('components.modal_update', ['index' => $i])
                                    @slot('modal_body_update')
                                        <input type="hidden" name="icon_id" value="{{ $item->id }}">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="icon_name">Icon Name</label>
                                            <input type="text" name="icon_name" id="icon_name{{ $i }}" class="form-control"
                                                value="{{ $item->icons_name }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="icon_code">Icon Code</label>
                                            <input type="text" name="icon_code" id="icon_code{{ $i }}" class="form-control"
                                                value="{{ $item->icons_code }}">
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('components.alert_delete', ['index' => $i])
                                    @slot('deleteBody')
                                        <input type="hidden" name="icon_id" value="{{ $item->id }}">
                                    @endslot
                                    @slot('deleteName')
                                        {{ $item->icons_name }}
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
