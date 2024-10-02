<!-- modal settings -->
<div class="modal fade" id="settings" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Family tree settings</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <form action="{{ route('users.pedigree.settings') }}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        <div class="col">
                            <div class="row">
                                <div class="divider text-start mb-0">
                                    <div class="divider-text">
                                        <h5>Nameboxes colors</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <p class="mt-2">Box colors</p>
                                    </div>
                                    <div class="row">
                                        <div class="col justify-content-start">
                                            <select id="boxColor" class="form-select"  name="box_color">
                                                <option value="gender">By gender</option>
                                                <option value="blood">By blood</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
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
                                <div class="col">
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
                                <div class="col">
                                    <div class="row">
                                        <p class="mt-2">Blood relative</p>
                                    </div>
                                    <div class="row">
                                        <div class="col justify-content-start">
                                            <div id="color-picker-blood"></div>
                                            <input type="hidden" name="blood_color">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <p class="mt-2">Not Blood relative</p>
                                    </div>
                                    <div class="row">
                                        <div class="col justify-content-start">
                                            <div id="color-picker-notblood"></div>
                                            <input type="hidden" name="notblood_color">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="row">
                                <div class="divider text-start mb-0">
                                    <div class="divider-text">
                                        <h5>Links colors</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-3">
                                    <div class="row">
                                        <p class="mt-2">Spouse link</p>
                                    </div>
                                    <div class="row">
                                        <div class="col justify-content-start">
                                            <div id="color-picker-spouse"></div>
                                            <input type="hidden" name="spouse_link_color" id="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <p class="mt-2">Biological Child link</p>
                                    </div>
                                    <div class="row">
                                        <div class="col justify-content-start">
                                            <div id="color-picker-bio-child"></div>
                                            <input type="hidden" name="bio_child_link_color">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <p class="mt-2">Adoptive Child link</p>
                                    </div>
                                    <div class="row">
                                        <div class="col justify-content-start">
                                            <div id="color-picker-adop-child"></div>
                                            <input type="hidden" name="adop_child_link_color">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="row">
                                <div class="divider text-start mb-0">
                                    <div class="divider-text">
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-3">
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
                                <div class="col-3">
                                    <div class="row">
                                        <p class="mt-2">Portrait band color</p>
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
                    
                    <div class="row mb-4">
                        <div class="col">
                            <div class="row">
                                <div class="divider text-start mb-0">
                                    <div class="divider-text">
                                        <h5>Node template</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check checked">
                                        <input class="form-check-input customimagescheckbox" type="checkbox"
                                            name="node_template" value="4" id="customCheckboxImg4">
                                        <label class="form-check-label custom-option-content" for="customCheckboxImg4"
                                            style="height: 140px;">
                                            <span class="custom-option-body">
                                                <img src="{{ asset('admin/images/template4.png') }}" alt="cbImg"
                                                    style="object-fit: scale-down;">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md mb-md-0 mb-5">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                        <input class="form-check-input customimagescheckbox" type="checkbox"
                                            name="node_template" value="1" id="customCheckboxImg1">
                                        <label class="form-check-label custom-option-content" for="customCheckboxImg1"
                                            style="height: 140px;">
                                            <span class="custom-option-body">
                                                <img src="{{ asset('admin/images/template1.png') }}" alt="cbImg"
                                                    style="object-fit: scale-down;">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md mb-md-0 mb-5">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                        <input class="form-check-input customimagescheckbox " type="checkbox"
                                            name="node_template" value="2" id="customCheckboxImg2">
                                        <label class="form-check-label custom-option-content" for="customCheckboxImg2"
                                            style="height: 140px;">
                                            <span class="custom-option-body">
                                                <img src="{{ asset('admin/images/template2.png') }}" alt="cbImg"
                                                    style="object-fit: scale-down;">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                        <input class="form-check-input customimagescheckbox" type="checkbox"
                                            name="node_template" value="3" id="customCheckboxImg3">
                                        <label class="form-check-label custom-option-content" for="customCheckboxImg3"
                                            style="height: 140px;">
                                            <span class="custom-option-body">
                                                <img src="{{ asset('admin/images/template3.png') }}" alt="cbImg"
                                                    style="object-fit: scale-down;">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col">
                            <div class="row">
                                <div class="divider text-start mb-0">
                                    <div class="divider-text">
                                        <h5>Tree Background</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-md-0 mb-5">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check checked">
                                        <input class="form-check-input customimagescheckboxbg" type="checkbox"
                                            name="bg_template" value="1" id="customCheckboxImgbg1">
                                        <label class="form-check-label custom-option-content"
                                            for="customCheckboxImgbg1" style="height: 120px;">
                                            <span class="custom-option-body">
                                                <img src="{{ asset('admin/images/Parchment_small1.png') }}"
                                                    alt="cbImg" style="object-fit: scale-down;">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md mb-md-0 mb-5">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                        <input class="form-check-input customimagescheckboxbg " type="checkbox"
                                            name="bg_template" value="2" id="customCheckboxImgbg2">
                                        <label class="form-check-label custom-option-content"
                                            for="customCheckboxImgbg2" style="height: 120px;">
                                            <span class="custom-option-body">
                                                <img src="{{ asset('admin/images/Parchment_small2.png') }}"
                                                    alt="cbImg" style="object-fit: scale-down;">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                        <input class="form-check-input customimagescheckboxbg" type="checkbox"
                                            name="bg_template" value="3" id="customCheckboxImgbg3">
                                        <label class="form-check-label custom-option-content"
                                            for="customCheckboxImgbg3" style="height: 120px;">
                                            <span class="custom-option-body">
                                                <img src="{{ asset('admin/images/Parchment_small3.png') }}"
                                                    alt="cbImg" style="object-fit: scale-down;">
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div
                                        class="form-check custom-option custom-option-image custom-option-image-check">
                                        <input class="form-check-input customimagescheckboxbg" type="checkbox"
                                            name="bg_template" value="4" id="customCheckboxImgbg4">
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
                    <div class="row mb-4">
                        <div class="col">
                            <div class="row">
                                <div class="divider text-start mb-0">
                                    <div class="divider-text">
                                        <h5>Default Portrait</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row">
                                    @for ($i = 1; $i <= 6; $i++)
                                        <div class="col-md-2 mb-md-4 mb-5">
                                            <div
                                                class="form-check custom-option custom-option-image custom-option-image-check">
                                                <input class="form-check-input" name="placeholder_images_female" type="checkbox"
                                                    id="placeholder_images_female{{ $i }}"
                                                    value="female{{ $i }}">
                                                <label class="form-check-label custom-option-content"
                                                    for="placeholder_images_female{{ $i }}">
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
                                            <input class="form-check-input" name="placeholder_images_male" type="checkbox"
                                                id="placeholder_images_male{{ $i + 7}}"
                                                value="man{{ $i }}">
                                            <label class="form-check-label custom-option-content"
                                                for="placeholder_images_male{{ $i + 7}}">
                                                <span class="custom-option-body">
                                                    <img src="{{ asset('storage/placeholder_portraits/man' . $i . '.jpg') }}"
                                                        alt="cbImg" style="height: 190px;object-fit: cover;">
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    @endfor
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
