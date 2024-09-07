<!-- modal export -->
<div class="modal fade" id="addNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Add note</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="mb-4">
                    <textarea class="form-control" id="note" rows="3"></textarea>
                </div>
                <div class="row mt-4 justify-content-end">
                    <div class="col-auto">
                        <button type="button" id="addNoteBtn" class="btn btn-primary" disabled>
                            <span class="ti-xs ti ti-plus me-1"></span>
                            Add
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal export -->
<div class="modal fade" id="editNoteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Edit note</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="mb-4">
                    <textarea class="form-control" id="note" rows="3"></textarea>
                </div>
                <div class="row mt-4 justify-content-end">
                    <div class="col-auto">
                        <button type="button" id="editNoteBtn" class="btn btn-warning" disabled>
                            <span class="ti-xs ti ti-pencil me-1"></span>
                            Edit
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
