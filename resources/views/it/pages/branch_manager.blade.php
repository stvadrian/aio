@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>

            @component('components.modal_add')
                @slot('button_title')
                    Compose Message
                @endslot
                @slot('modal_header_add')
                    Compose New Message
                @endslot
                @slot('modal_body_add')
                    {{-- Modal Body Add  --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="kd_cabang">Kode Cabang</label>
                        <input type="text" name="kd_cabang" id="kd_cabang" class="form-control" value="{{ count($cabangs) + 1 }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="nm_cabang">Nama Cabang</label>
                        <input type="text" name="nm_cabang" id="nm_cabang" class="form-control">
                    </div>
                @endslot
            @endcomponent

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>Kode Cabang</th>
                        <th>Nama Cabang</th>
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @if (!empty($cabangs))
                        @php $i=0; @endphp
                        @foreach ($cabangs as $cabang)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $cabang->kd_cabang }}</td>
                                <td>{{ $cabang->nm_cabang }}</td>
                                <td class="d-flex justify-content-center gap-1">
                                    @component('components.modal_update', ['index' => $i])
                                        @slot('modal_body_update')
                                            <input type="hidden" name="cabang_id" value="{{ $cabang->id }}">
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="kd_cabang">Kode Cabang</label>
                                                <input type="text" name="kd_cabang" id="kd_cabang" class="form-control"
                                                    value="{{ $cabang->kd_cabang }}" disabled>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="nm_cabang">Nama Cabang</label>
                                                <input type="text" name="nm_cabang" id="nm_cabang" class="form-control"
                                                    value="{{ $cabang->nm_cabang }}">
                                            </div>
                                        @endslot
                                    @endcomponent

                                    @component('components.alert_delete', ['index' => $i])
                                        @slot('deleteBody')
                                            <input type="hidden" name="cabang_id" value="{{ $cabang->id }}">
                                        @endslot
                                        @slot('deleteName')
                                            {{ $cabang->nm_cabang }}
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
