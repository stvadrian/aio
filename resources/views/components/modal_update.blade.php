{{-- MODAL UPDATE --}}
<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modal_update{{ $index }}">
    <i class="ti ti-pencil fs-4"></i>
</button>
<div class="modal" id="modal_update{{ $index }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_update" class="js-validate-form">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Update Item</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $modal_body_update }}
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="update">
                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
