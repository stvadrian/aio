<div class="modal" id="modal_add_form_item" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_add_new">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Add New Item</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="form_name" value="{{ $form->form_name_e }}">
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_name">Item Name</label>
                        <input type="text" name="item_name" id="item_name" class="form-control isalpha" maxlength="48"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_type">Item Type</label>
                        <select id="item_type" name="item_type" class="form-control form-select select2bs4"
                            data-parent="modal_add_form_item" required>
                            <option selected disabled value="">--Select One--</option>
                            @foreach ($item_categories as $category)
                                <optgroup label="{{ $category->item_category }}">
                                    @foreach ($category->item_category_child as $type)
                                        <option value="{{ $type->item_type }}">{{ $type->item_name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_order">Order</label>
                        <select id="item_order" name="item_order" class="form-control form-select" required>
                            @if ($items->isEmpty())
                                <option value="1/0" selected>At beginning</option>
                            @else
                                @php $count = 0; @endphp
                                <option selected disabled value="">Select one</option>
                                <option value="1/{{ count($items) }}">At beginning</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->item_order + 1 }}/{{ count($items) }}"
                                        {{ $count + 1 == count($items) ? 'selected' : '' }}>
                                        After {{ $item->item_name }}</option>
                                    {{ $count + 1 . '  ' . count($items) }}
                                    @php $count++; @endphp
                                @endforeach
                            @endif

                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="is_mandatory">Mandatory</label>
                        <select id="is_mandatory" name="is_mandatory" class="form-control form-select">
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="add_form_item">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
