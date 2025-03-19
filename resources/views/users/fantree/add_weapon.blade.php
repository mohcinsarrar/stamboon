<div class="modal fade" id="addWeaponModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Upload your Family Weapon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4 position-relative d-none" id="show-weapon">
                    <button class="position-absolute top-0 end-0 btn btn-primary waves-effect p-2" type="button"
                        id="delete-weapon" style="width: fit-content;"><i class="ti ti-trash fs-4"></i></button>
                    <img width="500" src="" class="img-fluid" alt="">
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <div class="input-group">
                            <input type="file" name="weapon" id="weapon" class="form-control"
                                id="inputGroupFile04" aria-describedby="import-weapon" aria-label="Upload"
                                autocomplete="off">
                            <button class="btn btn-outline-primary waves-effect" type="button"
                                id="import-weapon">Import</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
