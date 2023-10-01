  <div class="modal" id="modal_delete_options_selected" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <form action="" method="post" id="form_delete_item_form">
                  @csrf
                  <div class="modal-header">
                      <h3 class="modal-title">Delete Selected Options</h3>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <input type="hidden" name="item_id" value="{{ $options[0]->id }}">
                      Are your sure want to delete these options below ? <br>
                      <strong>
                          <ul id="selected-list"></ul>
                      </strong>
                      <div class="my-3">
                          <small><em class="text-danger">This action cannot be undone</em></small>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-danger" name="delete_options_selected">Delete</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
