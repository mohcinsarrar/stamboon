<!-- Offcanvas to add child -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddChild" aria-labelledby="offcanvasAddChildLabel"
data-bs-backdrop="false">
<div class="offcanvas-header">
    <h5 id="offcanvasAddChildLabel" class="offcanvas-title">Add Child</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>

<div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
    <h6 class="offcanvas-subtitle"></h6>
    <form class="add-new-user pt-0" id="formAddChild" method="POST"
        action="{{ route('users.pedigree.addchild') }}">
        @csrf
        <input type="hidden" name="person_id" class="person_id">
        <input type="hidden" name="person_type" class="person_type">
        <div class="mb-3 parents">
            <label class="form-label" for="parents">Parents <span class="text-danger">*</span>
            </label>
            <div class="parents_container">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="relationship">Relationship <span class="text-danger">*</span>
            </label>
            <select id="relationship" name="relationship" class="form-select">
                <option value="bio">Biological</option>
                <option value="adopt">Adoptive</option>
                <!--<option value="foster">Foster</option>-->
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="name">First and middle name <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control firstname" placeholder="First name" name="firstname"
                aria-label="John" autocomplete="off" />
            <span class="text-danger d-none" id="firstname_feedback"></span>
        </div>
        <div class="mb-3">
            <label class="form-label" for="name">Last name <span class="text-danger">*</span> </label>
            <input type="text" class="form-control lastname" placeholder="Last name" name="lastname"
                aria-label="Doe" autocomplete="off" />
            <span class="text-danger d-none" id="lastname_feedback"></span>
        </div>
        <div class="mb-3">
            <label class="d-block form-label">Sex</label>
            <div class="form-check form-check-inline mt-2">
                <input class="form-check-input male" type="radio" id="male" name="sex" value="M">
                <label class="form-check-label" for="male"> Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input female" type="radio" id="female" name="sex" value="F">
                <label class="form-check-label" for="female"> Female</label>
            </div>
        </div>
        <div class="mb-3">
            <label class="d-block form-label">Status</label>
            <div class="form-check form-check-inline mt-2">
                <input class="form-check-input living" type="radio" id="livingAddChild" name="status"
                    value="living">
                <label class="form-check-label" for="livingAddChild"> Living</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input deceased" type="radio" id="deceasedAddChild" name="status"
                    value="deceased">
                <label class="form-check-label" for="deceasedAddChild"> Deceased</label>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Date of Birth</label>
            <input type="text" name="birth_date"
                class="form-control date-input birth_date_add_child" placeholder="YYYY-MM-DD or YYYY">
        </div>

        <div class="death-container">
            <div class="mb-3">
                <label class="form-label">Date of Death</label>
                <input type="text" name="death_date"
                    class="form-control date-input death_date_add_child"
                    placeholder="YYYY-MM-DD or YYYY">
            </div>

        </div>


        <div class="row mx-0 my-5 justify-content-center">
            <button type="submit" class="col-auto btn btn-primary me-sm-3 me-1 data-submit col-md-4">
                <span class="ti-xs ti ti-plus me-1"></span>
                Add
            </button>
        </div>
    </form>
</div>
</div>