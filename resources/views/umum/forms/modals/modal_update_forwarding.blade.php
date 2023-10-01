<div class="modal" id="modal_update_forwarding{{ $i }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_update_forwarding">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Update Forwarding</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="fw_id" value="{{ $forward->id }}">
                    <div class="form-group mb-3">
                        <label class="form-label" for="forward_name">Forward Name</label>
                        <input type="text" name="forward_name" id="forward_name{{ $i }}"
                            class="form-control" value="{{ $forward->fw_name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="forward_link">Forward Link</label>
                        <input type="text" name="forward_link" id="forward_link{{ $i }}"
                            class="form-control" value="{{ $forward->fw_link }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="update_forwarding">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
