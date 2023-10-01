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
                        <label class="form-label" for="item_name"> Item Name </label>
                        <input type="text" name="item_name" id="item_name" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_type"> Item Type </label>
                        <input type="text" name="item_type" id="item_type" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_category">Item Category</label>
                        <select name="item_category" id="item_category" class="form-select">
                            @foreach ($categories as $category)
                                <option value="{{ $category->item_category }}">
                                    {{ $category->item_category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endslot
            @endcomponent

            @component('components.table_data', ['dataTableType' => 'js-dataTable'])
                @slot('table_header')
                    <tr>
                        <th class="text-center">#</th>
                        <th>Item Name</th>
                        <th>Item Type</th>
                        <th>Item Category</th>
                        <th class="text-center">Actions</th>
                    </tr>
                @endslot
                @slot('table_body')
                    @php $i=0; @endphp
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->item_type }}</td>
                            <td>{{ $item->item_category }}</td>
                            <td class="d-flex justify-content-center gap-1">
                                @component('components.modal_update', ['index' => $i])
                                    @slot('modal_body_update')
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="item_name{{ $i }}">
                                                Item Name
                                            </label>
                                            <input type="text" name="item_name" id="item_name{{ $i }}" class="form-control"
                                                required value="{{ $item->item_name }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="item_type{{ $i }}"> Item Type
                                            </label>
                                            <input type="text" name="item_type" id="item_type{{ $i }}" class="form-control"
                                                value="{{ $item->item_type }}">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="item_category{{ $i }}">Item
                                                Category</label>
                                            <select name="item_category" id="item_category{{ $i }}" class="form-select">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->item_category }}"
                                                        {{ $category->item_category == $item->item_category ? 'selected' : '' }}>
                                                        {{ $category->item_category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('components.alert_delete', ['index' => $i])
                                    @slot('deleteBody')
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    @endslot
                                    @slot('deleteName')
                                        {{ $item->item_name }}
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
