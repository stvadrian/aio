<div class="modal" id="modal_add_forwarding" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_add_new">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Add New Forwarding</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="form_id" value="{{ $form->id }}">
                    <div class="form-group mb-3">
                        <label class="form-label" for="forward_name">Forward Name</label>
                        <input type="text" name="forward_name" id="forward_name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="forward_link">Forward Link</label>
                        <input type="text" name="forward_link" id="forward_link" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="add_forwarding">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
