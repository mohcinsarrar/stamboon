<!-- modal settings -->
<div class="modal fade" id="settings" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Family tree settings</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <form action="{{ route('users.pedigree.settings') }}" method="POST">
                    @csrf
                    <div class="row ">
                        <div class="divider text-start">
                            <div class="divider-text">
                                <h5>Links colors</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col">
                            <div class="row">
                                <p class="mt-2">Spouse link</p>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-6">
                                    <div id="color-picker-spouse"></div>
                                    <input type="hidden" name="spouse_link_color" id="">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <p class="mt-2">Biological Child link</p>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-6">
                                    <div id="color-picker-bio-child"></div>
                                    <input type="hidden" name="bio_child_link_color">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <p class="mt-2">Adoptive Child link</p>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-6">
                                    <div id="color-picker-adop-child"></div>
                                    <input type="hidden" name="adop_child_link_color">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="divider text-start">
                            <div class="divider-text">
                                <h5>Gender colors</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col">
                            <div class="row">
                                <p class="mt-2">Male color</p>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-6">
                                    <div id="color-picker-male"></div>
                                    <input type="hidden" name="male_color">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <p class="mt-2">Male text color</p>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-6">
                                    <div id="color-picker-text-male"></div>
                                    <input type="hidden" name="male_text_color">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <p class="mt-2">Female color</p>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-6">
                                    <div id="color-picker-female"></div>
                                    <input type="hidden" name="female_color">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="row">
                                <p class="mt-2">Female text color</p>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-6">
                                    <div id="color-picker-text-female"></div>
                                    <input type="hidden" name="female_text_color">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="divider text-start">
                            <div class="divider-text">
                                <h5>Node template</h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md mb-md-0 mb-5">
                            <div
                                class="form-check custom-option custom-option-image custom-option-image-check checked">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="customCheckboxImg1" checked="">
                                <label class="form-check-label custom-option-content" for="customCheckboxImg1">
                                    <span class="custom-option-body">
                                        <img src="https://demos.pixinvent.com/vuexy-html-laravel-admin-template/demo/assets/img/backgrounds/watch.png"
                                            alt="cbImg">
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md mb-md-0 mb-5">
                            <div class="form-check custom-option custom-option-image custom-option-image-check">
                                <input class="form-check-input " type="checkbox" value=""
                                    id="customCheckboxImg2">
                                <label class="form-check-label custom-option-content" for="customCheckboxImg2">
                                    <span class="custom-option-body">
                                        <img src="https://demos.pixinvent.com/vuexy-html-laravel-admin-template/demo/assets/img/backgrounds/phone.png"
                                            alt="cbImg">
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-check custom-option custom-option-image custom-option-image-check">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="customCheckboxImg3">
                                <label class="form-check-label custom-option-content" for="customCheckboxImg3">
                                    <span class="custom-option-body">
                                        <img src="https://demos.pixinvent.com/vuexy-html-laravel-admin-template/demo/assets/img/backgrounds/mac.png"
                                            alt="cbImg">
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md mb-md-0 mb-5">
                            <div
                                class="form-check custom-option custom-option-image custom-option-image-check checked">
                                <input class="form-check-input" type="checkbox" value=""
                                    id="customCheckboxImg1" checked="">
                                <label class="form-check-label custom-option-content" for="customCheckboxImg1">
                                    <span class="custom-option-body">
                                        <img src="https://demos.pixinvent.com/vuexy-html-laravel-admin-template/demo/assets/img/backgrounds/watch.png"
                                            alt="cbImg">
                                    </span>
                                </label>
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