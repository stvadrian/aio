@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="lead fw-semibold mb-4">
                {{ $pageHeader }}
            </div>

            <form action="" method="post" id="form-add">
                @csrf
                <div class="form-group mb-3">
                    <label for="link_qr" class="form-label mb-1">Type your QR content below</label>
                    <input type="text" name="link_qr" id="link_qr" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="qr_name" class="form-label mb-1">QR Name</label>
                    <input type="text" name="qr_name" id="qr_name" class="form-control isalpha spaces">
                </div>
                <button type="submit" class="btn btn-primary">Generate QR</button>
            </form>


            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>QR Name</th>
                        <th>QR Content</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @if (!empty($qrs))
                        @php $i=0; @endphp
                        @foreach ($qrs as $item)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $item->qr_name }}</td>
                                <td>{{ $item->qr_content }}</td>
                                <td>{{ $item->created_by }}</td>
                                <td>{{ date('d M Y H:i', strtotime($item->created_at)) }}</td>
                                <td class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-info btn-sm btn-popup"
                                        data-link="{{ url('/qr-generator/view/' . $item->qr_name) }}"
                                        data-title="{{ $item->qr_name }}">
                                        <i class="ti ti-eye fs-4"></i>
                                    </button>

                                    @component('components.alert_delete', ['index' => $i])
                                        @slot('deleteBody')
                                            <input type="hidden" name="qrid" value="{{ $item->id }}">
                                        @endslot
                                        @slot('deleteName')
                                            {{ $item->qr_name }}
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
