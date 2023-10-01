@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">
                {{ $pageHeader }}
            </h5>

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
                        <label class="form-label" for=""></label>
                        <input type="text" name="" id="" class="form-control">
                    </div>
                @endslot
            @endcomponent

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        {{-- Table Header  --}}
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @if (!empty($items))
                        @php $i=0; @endphp
                        @foreach ($items as $item)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                {{-- Table Rows  --}}
                                <td class="d-flex justify-content-center gap-1">
                                    @component('components.modal_update', ['index' => $i])
                                        @slot('modal_body_update')
                                            <input type="hidden" name="" value="">
                                            {{-- Modal Body Update  --}}
                                        @endslot
                                    @endcomponent

                                    @component('components.modal_delete', ['index' => $i])
                                        @slot('modal_body_delete')
                                            <input type="hidden" name="" value="">
                                            <p class="text-start mb-0">
                                                Are you sure want to delete
                                                <strong>
                                                    {{-- Item to be deleted --}}
                                                </strong> ?
                                            </p>
                                        @endslot
                                    @endcomponent

                                    @component('components.alert_delete', ['index' => $i])
                                        @slot('deleteBody')
                                            <input type="hidden" name="" value="">
                                        @endslot
                                        @slot('deleteName')
                                            {{-- Delete Name  --}}
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
