<div class="modal" id="modal_add_options_from_api" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="post" id="form_add_new">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">Add New Forwarding</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="item_id" value="{{ $options[0]->id }}">
                    <div class="form-group mb-3">
                        <label for="api_link" class="form-label">Insert API Link</label>
                        <input type="url" name="api_link" id="api_link" class="form-control"
                            placeholder="Ex: https://linkforapi.com/api/somename" value="{{ old('api_link') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="key" class="form-label">Key to Get</label>
                        <input type="text" name="key" id="key" class="form-control"
                            value="{{ old('key') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="add_option_from_api">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
