{{-- MODAL ADD --}}
<button type="button" class="btn btn-accent mb-3" data-bs-toggle="modal" data-bs-target="#modal_add">
    <i class="ti ti-circle-plus me-1"></i>
    {{ isset($button_title) ? $button_title : 'Add New Item' }}
</button>
<div class="modal" id="modal_add" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_add" class="js-validate-form">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">{{ isset($modal_header_add) ? $modal_header_add : 'Add New Item' }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $modal_body_add }}
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="add">
                    <button type="submit" class="btn btn-success" name="add">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
