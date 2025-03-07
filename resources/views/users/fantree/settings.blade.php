<!-- modal settings -->
<div class="modal fade" id="settings" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Family tree settings</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">

                <form action="{{ route('users.fantree.settings') }}" method="POST">
                    @csrf
                    <div class="nav-align-top  mb-6 mt-4">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link active waves-effect" role="tab"
                                    data-bs-toggle="tab" data-bs-target="#general" aria-controls="general"
                                    aria-selected="true">General</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#nameboxes_colors" aria-controls="nameboxes_colors"
                                    aria-selected="true">Nameboxes colors</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#links_colors" aria-controls="links_colors" aria-selected="false"
                                    tabindex="-1">Links & text colors</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#tree_background" aria-controls="tree_background"
                                    aria-selected="false" tabindex="-1">Tree Background</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#default_portrait" aria-controls="default_portrait"
                                    aria-selected="false" tabindex="-1">Default Portrait</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#notes_tab" aria-controls="notes_tab" aria-selected="false"
                                    tabindex="-1">Notes</button>
                            </li>


                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <div class="row">
                                    <div class="col-auto mb-4">
                                        <label for="default_date" class="form-label">Default date format</label>
                                        <select id="default_date" name="default_date" class="form-select">
                                            <option value="MM-DD-YYYY">MM-DD-YYYY</option>
                                            <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                                            <option value="DD-MM-YYYY">DD-MM-YYYY</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-6 col-12 mb-4">
                                        <h6>Photos type</h6>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="photos_type"
                                                id="photos_type2" value="oval">
                                            <label class="form-check-label" for="photos_type2">Oval</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="photos_type"
                                                id="photos_type1" value="round">
                                            <label class="form-check-label" for="photos_type1">Round</label>
                                        </div>

                                    </div>
                                    <div class="col-md-6 col-12 mb-4">
                                        <h6>Photos direction</h6>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="photos_direction"
                                                id="photos_direction2" value="radial">
                                            <label class="form-check-label" for="photos_direction2">Radial</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="photos_direction"
                                                id="photos_direction1" value="vertical">
                                            <label class="form-check-label" for="photos_direction1">Vertical</label>
                                        </div>

                                    </div>

                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6 col-12 mb-4">
                                        <h6>Default photos filter</h6>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="default_filter"
                                                id="default_filter1" value="none">
                                            <label class="form-check-label" for="default_filter1">None</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="default_filter"
                                                id="default_filter2" value="grayscale">
                                            <label class="form-check-label" for="default_filter2">black and
                                                white</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="default_filter"
                                                id="default_filter3" value="invert">
                                            <label class="form-check-label" for="default_filter3">Invert</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="default_filter"
                                                id="default_filter4" value="sepia">
                                            <label class="form-check-label" for="default_filter4">Sepia</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nameboxes_colors" role="tabpanel">
                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-7">
                                                <div class="row">
                                                    <p class="mt-2">Male</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col justify-content-start">
                                                        <div id="color-picker-male"></div>
                                                        <input type="hidden" name="male_color">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-7">
                                                <div class="row">
                                                    <p class="mt-2">Female</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col justify-content-start">
                                                        <div id="color-picker-female"></div>
                                                        <input type="hidden" name="female_color">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="links_colors" role="tabpanel">
                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="row justify-content-start">
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <div class="row">
                                                    <p class="mt-2">Father link</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col justify-content-start">
                                                        <div id="color-picker-father"></div>
                                                        <input type="hidden" name="father_link_color"
                                                            id="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <div class="row">
                                                    <p class="mt-2">Mother link</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col justify-content-start">
                                                        <div id="color-picker-mother"></div>
                                                        <input type="hidden" name="mother_link_color">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <div class="row">
                                                    <p class="mt-2">Text color</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col justify-content-start">
                                                        <div id="color-picker-text"></div>
                                                        <input type="hidden" name="text_color">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <div class="row">
                                                    <p class="mt-2">Band color</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col justify-content-start">
                                                        <div id="color-picker-band"></div>
                                                        <input type="hidden" name="band_color">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tree_background" role="tabpanel">
                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-12 mb-2">
                                                <div
                                                    class="form-check custom-option custom-option-image custom-option-image-check">
                                                    <input class="form-check-input customimagescheckboxbg"
                                                        type="checkbox" name="bg_template" value="0"
                                                        id="customCheckboxImgbg0">
                                                    <label
                                                        class="form-check-label custom-option-content  d-flex justify-content-center align-items-center"
                                                        for="customCheckboxImgbg0" style="height: 120px;">
                                                        <span class="custom-option-body">
                                                            <h5>No background</h5>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-12 mb-2">
                                                <div
                                                    class="form-check custom-option custom-option-image custom-option-image-check">
                                                    <input class="form-check-input customimagescheckboxbg"
                                                        type="checkbox" name="bg_template" value="1"
                                                        id="customCheckboxImgbg1">
                                                    <label class="form-check-label custom-option-content"
                                                        for="customCheckboxImgbg1" style="height: 120px;">
                                                        <span class="custom-option-body">
                                                            <img src="{{ asset('admin/images/Parchment_small1.png') }}"
                                                                alt="cbImg" style="object-fit: scale-down;">
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-12 mb-2">
                                                <div
                                                    class="form-check custom-option custom-option-image custom-option-image-check">
                                                    <input class="form-check-input customimagescheckboxbg "
                                                        type="checkbox" name="bg_template" value="2"
                                                        id="customCheckboxImgbg2">
                                                    <label class="form-check-label custom-option-content"
                                                        for="customCheckboxImgbg2" style="height: 120px;">
                                                        <span class="custom-option-body">
                                                            <img src="{{ asset('admin/images/Parchment_small2.png') }}"
                                                                alt="cbImg" style="object-fit: scale-down;">
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-12  mb-2">
                                                <div
                                                    class="form-check custom-option custom-option-image custom-option-image-check">
                                                    <input class="form-check-input customimagescheckboxbg"
                                                        type="checkbox" name="bg_template" value="3"
                                                        id="customCheckboxImgbg3">
                                                    <label class="form-check-label custom-option-content"
                                                        for="customCheckboxImgbg3" style="height: 120px;">
                                                        <span class="custom-option-body">
                                                            <img src="{{ asset('admin/images/Parchment_small3.png') }}"
                                                                alt="cbImg" style="object-fit: scale-down;">
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-12  mb-2">
                                                <div
                                                    class="form-check custom-option custom-option-image custom-option-image-check">
                                                    <input class="form-check-input customimagescheckboxbg"
                                                        type="checkbox" name="bg_template" value="4"
                                                        id="customCheckboxImgbg4">
                                                    <label class="form-check-label custom-option-content"
                                                        for="customCheckboxImgbg4" style="height: 120px;">
                                                        <span class="custom-option-body">
                                                            <img src="{{ asset('admin/images/Parchment_small4.png') }}"
                                                                alt="cbImg" style="object-fit: scale-down;">
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="default_portrait" role="tabpanel">
                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="row">
                                            <h5 class="mt-3">Female Portrait</h5>
                                            @for ($i = 1; $i <= 6; $i++)
                                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                                    <div
                                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                                        <input class="form-check-input" name="default_female_image"
                                                            type="checkbox"
                                                            id="default_female_image{{ $i }}"
                                                            value="female{{ $i }}">
                                                        <label class="form-check-label custom-option-content"
                                                            for="default_female_image{{ $i }}">
                                                            <span class="custom-option-body">
                                                                <img src="{{ asset('storage/placeholder_portraits/female' . $i . '.jpg') }}"
                                                                    alt="cbImg"
                                                                    style="height: 190px;object-fit: cover;">
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        <div class="row">
                                            <h5 class="mt-3">Male Portrait</h5>
                                            @for ($i = 1; $i <= 6; $i++)
                                                <div class="col-lg-2 col-md-4 col-6 mb-2">
                                                    <div
                                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                                        <input class="form-check-input" name="default_male_image"
                                                            type="checkbox" id="default_male_image{{ $i + 7 }}"
                                                            value="man{{ $i }}">
                                                        <label class="form-check-label custom-option-content"
                                                            for="default_male_image{{ $i + 7 }}">
                                                            <span class="custom-option-body">
                                                                <img src="{{ asset('storage/placeholder_portraits/man' . $i . '.jpg') }}"
                                                                    alt="cbImg"
                                                                    style="height: 190px;object-fit: cover;">
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="notes_tab" role="tabpanel">
                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="row justify-content-center">
                                            <h5 class="mt-3 mb-3">Note background type</h5>
                                            @for ($i = 1; $i <= 3; $i++)
                                                <div class="col-lg-3 col-md-3 col-12 mb-2">
                                                    <div
                                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                                        <input class="form-check-input" name="note_type"
                                                            type="checkbox" id="note_type{{ $i }}"
                                                            value="{{ $i }}">
                                                        <label class="form-check-label custom-option-content"
                                                            for="note_type{{ $i }}">
                                                            <span class="custom-option-body">
                                                                <img src="{{ asset('assets/img/notesbg/note' . $i . '.png') }}"
                                                                    alt="cbImg"
                                                                    style="height: 190px;object-fit: cover;">
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endfor
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-6 col-12">
                                                <div class="row">
                                                    <p class="mt-2">Note text color</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col justify-content-start">
                                                        <div id="color-picker-note"></div>
                                                        <input type="hidden" name="note_text_color" id="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 justify-content-end">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <span class="ti-xs ti ti-device-floppy me-1"></span>
                                Save
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
