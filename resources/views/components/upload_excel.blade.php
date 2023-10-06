{{-- MODAL UPLOAD EXCEL --}}
<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modal_upload_excel">
    <i class="ti ti-circle-plus me-1"></i>
    {{ isset($button_title) ? $button_title : 'Upload Excel' }}
</button>
<div class="modal" id="modal_upload_excel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ isset($modal_header_add) ? $modal_header_add : 'Upload Your Excel File' }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="file-upload" class="form-label">Only Support .xlsx or .xls</label>
                    <input type="file" name="file" id="file-upload" class="form-control" accept=".xlsx, .xls">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="upload_excel">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
