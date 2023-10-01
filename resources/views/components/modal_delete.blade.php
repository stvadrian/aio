{{-- MODAL DELETE  --}}
<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal_delete{{ $index }}">
    <i class="ti ti-trash fs-4"></i>
</button>
<div class="modal" id="modal_delete{{ $index }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_delete">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">
                        Delete Item
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $modal_body_delete }}
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="delete">
                    <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
