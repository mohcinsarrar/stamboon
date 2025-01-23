<div class="modal fade" id="previewImage" tabindex="-1" aria-hidden="true" style="z-index:10000;">>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewImageTitle">Preview image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="previewImageContainer" class="rounded-circle d-block mx-auto" src=""
                    style="width:110px;height: 145px;object-fit: cover;" />
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalEditImage" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content h-100">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditImageTitle">Import image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="nav-align-top mb-6">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link waves-effect active" role="tab"
                                data-bs-toggle="tab" data-bs-target="#navs-top-upload" aria-controls="navs-top-upload"
                                aria-selected="false" tabindex="-1">Upload from your computer</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-top-choose" aria-controls="navs-top-choose" aria-selected="false"
                                tabindex="-1">Choose from our photos</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="navs-top-upload" role="tabpanel">
                            <div class="row">
                                <div class="col">
                                    <h5>Upload and edit your photo</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <input class="form-control" type="file" id="upload_image" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary waves-effect waves-light col-auto"
                                    id="preview_image">preview</button>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary waves-effect waves-light col-auto"
                                        id="save_image" disabled>Save</button>
                                </div>
                            </div>
                            <div id="photo_editor_container" style="height: 500px; width:100%;"></div>
                        </div>
                        <div class="tab-pane fade" id="navs-top-choose" role="tabpanel">
                            <div class="row">
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="col-md-2 mb-md-4 mb-5">
                                        <div
                                            class="form-check custom-option custom-option-image custom-option-image-check">
                                            <input class="form-check-input" name="placeholder_images" type="checkbox"
                                                id="placeholder_images{{ $i }}"
                                                value="female{{ $i }}">
                                            <label class="form-check-label custom-option-content"
                                                for="placeholder_images{{ $i }}">
                                                <span class="custom-option-body">
                                                    <img src="{{ asset('storage/placeholder_portraits/female' . $i . '.jpg') }}"
                                                        alt="cbImg" style="height: 190px;object-fit: cover;">
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endfor

                                @for ($i = 1; $i <= 6; $i++)
                                <div class="col-md-2 mb-md-4 mb-5">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                        <input class="form-check-input" name="placeholder_images" type="checkbox"
                                            id="placeholder_images{{ $i + 7}}"
                                            value="man{{ $i }}">
                                        <label class="form-check-label custom-option-content"
                                            for="placeholder_images{{ $i + 7}}">
                                            <span class="custom-option-body">
                                                <img src="{{ asset('storage/placeholder_portraits/man' . $i . '.jpg') }}"
                                                    alt="cbImg" style="height: 190px;object-fit: cover;">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                @endfor
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary waves-effect waves-light col-auto"
                                        id="save_image_placeholder">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
