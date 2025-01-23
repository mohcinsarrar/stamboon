<!-- Custom Modal -->
<div class="custom-modal p-2" id="nodeModal">
    <div class="modal-body custom-modal-body" id="nodeModalBody">
        <button type="button" class="btn-close custom-modal-close" onclick="close_custom_modal()"></button>
        <div class="row mx-0">
            <div class="card mb-0 border-0 shadow-none p-2">
                <div class="row g-0">
                    <div class="col-4">
                        <img src="" class="card-img card-img-left personImage rounded-0" alt="..." style="width: 80px;">
                    </div>
                    <div class="col-8">
                        <div class="card-body p-0 ms-3">
                            <h6 class="card-title name mb-2"></h6>
                            <p class="card-text mb-1">Birth : <small class="text-muted birth"></small></p>
                            <p class="card-text mb-1">Death : <small class="text-muted death"></small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-group dropend">
            <button {{($has_payment == false ? 'disabled' : '')}} type="button" class="btn btn-primary waves-effect p-2 border-0 rounded"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ti ti-pencil"></i>
            </button>
            <ul class="dropdown-menu" style="">
                <li><a id="nodeEdit" class="dropdown-item waves-effect" href="javascript:void(0);"><i class="ti ti-pencil me-2"></i> Edit info</a></li>
                <li><a id="nodeEditPhoto" class="dropdown-item waves-effect" href="javascript:void(0);"><i class="ti ti-camera me-2"></i> Edit photo</a></li>
                <li id="deletePhotoContainer"><a id="nodeDeletePhoto" class="dropdown-item waves-effect" href="javascript:void(0);"><i class="ti ti-camera-off me-2"></i> Delete photo</a></li>
                <li><a id="nodeDelete" class="dropdown-item waves-effect" href="javascript:void(0);"><i class="ti ti-trash me-2"></i> Delete</a></li>
                <form id="formDeletePerson" action="{{ route('users.fantree.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="person_id" class="person_id">
                </form>
                <li id="addParentsContainer"><a id="addParents" class="dropdown-item waves-effect" href="javascript:void(0);"><i class="ti ti-user-plus me-2"></i> Add parents</a>
                </li>
            </ul>
        </div>

    </div>
</div>
