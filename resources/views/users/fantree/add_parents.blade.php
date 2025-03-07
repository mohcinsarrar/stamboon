<!-- Offcanvas to add parents -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddParents" aria-labelledby="offcanvasAddParentsLabel"
    data-bs-backdrop="false">
    <div class="offcanvas-header">
        <h5 id="offcanvasAddParentsLabel" class="offcanvas-title">Add Parents</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
        <h6 class="offcanvas-subtitle"></h6>
        <form class="add-new-user pt-0" id="formAddParents" method="POST"
            action="{{ route('users.fantree.addparents') }}">
            @csrf
            <input type="hidden" name="person_id" class="person_id">

            <div class="mb-3" id="parent_type_container">
                <label class="form-label" for="parent_type">Parents</label>
                <div class="form-check" id="father_container">
                    <input name="parent_type" class="form-check-input" type="radio" value="1" id="parent_type_1"
                        checked="">
                    <label class="form-check-label" for="parent_type_1">
                        Father
                    </label>
                </div>
                <div class="form-check mt-2" id="mother_container">
                    <input name="parent_type" class="form-check-input" type="radio" value="2" id="parent_type_2">
                    <label class="form-check-label" for="parent_type_2">
                        Mother
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="name">First and middle name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control firstname" placeholder="First name" name="firstname"
                    aria-label="John" autocomplete="off" />
                <span class="text-danger" id="firstname_feedback"></span>
            </div>
            <div class="mb-3">
                <label class="form-label" for="name">Last name <span class="text-danger">*</span> </label>
                <input type="text" class="form-control lastname" placeholder="Last name" name="lastname"
                    aria-label="Doe" autocomplete="off" />
                <span class="text-danger" id="lastname_feedback"></span>
            </div>

            <div class="mb-3">
                <label class="d-block form-label">Status</label>
                <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input living" type="radio" id="livingAddParents" name="status"
                        value="living">
                    <label class="form-check-label" for="livingAddParents"> Living</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input deceased" type="radio" id="deceasedAddParents" name="status"
                        value="deceased">
                    <label class="form-check-label" for="deceasedAddParents"> Deceased</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="text" name="birth_date" class="form-control date-input birth_date_add_parents">
            </div>

            <div class="death-container">
                <div class="mb-3">
                    <label class="form-label">Date of Death</label>
                    <input type="text" name="death_date" class="form-control date-input death_date_add_parents">
                </div>

            </div>

            <div class="mt-4" id="date-msg"><span></span></div>


            <div class="row mx-0 my-5 justify-content-center">
                <button type="submit" class="col-auto btn btn-primary me-sm-3 me-1 data-submit col-md-4">
                    <span class="ti-xs ti ti-plus me-1"></span>
                    Add
                </button>
            </div>
        </form>
    </div>
</div>
