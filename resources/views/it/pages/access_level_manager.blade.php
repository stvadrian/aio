@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>

            @component('components.modal_add')
                @slot('button_title')
                    Add New Item
                @endslot
                @slot('modal_header_add')
                    Add New Item
                @endslot
                @slot('modal_body_add')
                    {{-- Modal Body Add  --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="kd_hak_akses">Kode Hak Akses</label>
                        <input type="text" name="kd_hak_akses" id="kd_hak_akses" class="form-control"
                            value="{{ count($items) + 1 }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="nm_hak_akses">Nama Hak Akses</label>
                        <input type="text" name="nm_hak_akses" id="nm_hak_akses" class="form-control">
                    </div>
                @endslot
            @endcomponent

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>Kode Hak Akses</th>
                        <th>Nama Hak Akses</th>
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @if (!empty($items))
                        @php $i=0; @endphp
                        @foreach ($items as $item)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $item->kd_hak_akses }}</td>
                                <td>{{ $item->nm_hak_akses }}</td>
                                <td class="d-flex justify-content-center gap-1">
                                    @component('components.modal_update', ['index' => $i])
                                        @slot('modal_body_update')
                                            <input type="hidden" name="akses_id" value="{{ $item->id }}">
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="kd_hak_akses{{ $i }}">Kode Hak Akses</label>
                                                <input type="text" name="kd_hak_akses" id="kd_hak_akses{{ $i }}"
                                                    class="form-control" value="{{ $item->kd_hak_akses }}" disabled>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="nm_hak_akses{{ $i }}">Nama Hak Akses</label>
                                                <input type="text" name="nm_hak_akses" id="nm_hak_akses{{ $i }}"
                                                    class="form-control" value="{{ $item->nm_hak_akses }}">
                                            </div>
                                        @endslot
                                    @endcomponent

                                    @component('components.alert_delete', ['index' => $i])
                                        @slot('deleteBody')
                                            <input type="hidden" name="akses_id" value="{{ $item->id }}">
                                        @endslot
                                        @slot('deleteName')
                                            {{ $item->nm_hak_akses }}
                                        @endslot
                                    @endcomponent
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
@endsection
