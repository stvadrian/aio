<div class="modal" id="modal_update_item_form{{ $i }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_update_forwarding">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Update Forwarding</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="form_name_e" value="{{ $form->form_name_e }}">
                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                    <input type="hidden" name="current_order" value="{{ $item->item_order }}">
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_name">Item Name</label>
                        <input type="text" name="item_name" id="item_name{{ $i }}" class="form-control isalpha"
                            value="{{ $item->item_name }}" maxlength="48" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_type">Item Type</label>
                        <select id="item_type{{ $i }}" name="item_type"
                            class="form-control form-select"
                            data-parent="modal_update_item_form_{{ $i }}" required>
                            <option selected disabled value="">Select one</option>
                            @foreach ($item_categories as $category)
                                <optgroup label="{{ $category->item_category }}">
                                    @foreach ($category->item_category_child as $type)
                                        <option value="{{ $type->item_type }}"
                                            {{ $type->item_type == $item->item_type ? 'selected' : '' }}>
                                            {{ $type->item_name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="item_order">Item Order</label>
                        <select id="item_order{{ $i }}" name="item_order" class="form-control form-select"
                            required>
                            @if (empty($items))
                                <option value="1/0">At beginning</option>
                            @else
                                <option value="1/{{ count($items) }}">
                                    At beginning
                                </option>
                                @foreach ($items as $option)
                                    @if ($option->item_order == $item->item_order)
                                        @continue
                                    @elseif ($item->item_order != 1)
                                        <option value="{{ $option->item_order + 1 }}/{{ count($items) }}"
                                            {{ $option->item_order == $item->item_order - 1 ? 'selected' : '' }}>
                                            {{ $option->item_order == $item->item_order - 1 ? 'Keep Current Order' : 'After ' . $option->item_name }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="is_mandatory">Apakah Mandatory</label>
                        <select id="is_mandatory{{ $i }}" name="is_mandatory"
                            class="form-control form-select">
                            <option value="1" {{ $item->item_mandatory == '1' ? 'selected' : '' }}>
                                Ya
                            </option>
                            <option value="0" {{ $item->item_mandatory == '0' ? 'selected' : '' }}>
                                Tidak
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="update_item_form">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
