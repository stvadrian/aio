<div class="modal" id="modal_delete{{ $i }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_add_new">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Delete Form</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="form_id" value="{{ $form->id }}">
                    Are your sure want to delete <strong>{{ $form->form_name }}</strong> ?
                    <div class="my-3">
                        <small><em class="text-danger">This action cannot be undone</em></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger" name="delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
