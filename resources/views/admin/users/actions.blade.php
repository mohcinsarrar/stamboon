<div class="d-flex align-items-center">
    <a data-toggle="tooltip" data-placement="top" title="Delete" href="javascript:;" class="text-body delete-record"
        onclick="if(confirm('Are you sure ?')) document.getElementById('delete-{{ $id }}').submit()"><i
            class="ti ti-trash ti-sm mx-2"></i></a>
    <form id="delete-{{ $id }}" action="{{ route('admin.users.destroy', $id) }}" method="POST">
        @method('DELETE')
        @csrf
    </form>
    @if ($active == 0)
        <a onclick="if(confirm('Are you sure ?')) document.getElementById('toggle-active-{{ $id }}').submit()"
            href="javascript:;" class="text-body" data-toggle="tooltip" data-placement="top" title="Activate"><i
                class="ti ti-circle-check ti-sm mx-2"></i></a>
    @else
        <a onclick="if(confirm('Are you sure ?')) document.getElementById('toggle-active-{{ $id }}').submit()"
            href="javascript:;" class="text-body" data-toggle="tooltip" data-placement="top" title="Deactivate"><i
                class="ti ti-circle-x  ti-sm mx-2"></i></a>
    @endif
    <form id="toggle-active-{{ $id }}" action="{{ route('admin.users.toggle_active', $id) }}" method="POST">
        @csrf
    </form>
    <a onclick="var myOffcanvas = document.getElementById('offcanvasToogleRole-{{ $id }}');
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
    bsOffcanvas.show();"
        href="javascript:;" class="text-body" data-toggle="tooltip" data-placement="top" title="change role"><i
            class="ti ti-user ti-sm mx-2"></i></a>

</div>



<!-- Offcanvas to change user role -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasToogleRole-{{ $id }}"
    aria-labelledby="offcanvasToogleRoleLabel" data-bs-backdrop="false">
    <div class="offcanvas-header">
        <h5 id="offcanvasToogleRoleLabel" class="offcanvas-title">Edit Role</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
        <h6 class="offcanvas-subtitle"></h6>
        <form class="add-new-user pt-0" id="formToogleRole" method="POST"
            action="{{ route('admin.users.toggle_role', $id) }}">
            @csrf

            <div class="mb-3">
                <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input user" type="radio" id="user" name="role" value="user"
                        {{ $model->hasRole('user') && !$model->hasRole('superuser') && !$model->hasRole('superadmin') ? 'checked' : '' }}>
                    <label class="form-check-label" for="user"> User</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input superuser" type="radio" id="superuser" name="role"
                        value="superuser"
                        {{ $model->hasRole('user') && $model->hasRole('superuser') && !$model->hasRole('superadmin') ? 'checked' : '' }}>
                    <label class="form-check-label" for="superuser"> SuperUser</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input superadmin" type="radio" id="superadmin" name="role"
                        value="superadmin"
                        {{ $model->hasRole('user') && !$model->hasRole('superuser') && $model->hasRole('superadmin') ? 'checked' : '' }}>
                    <label class="form-check-label" for="superadmin"> superAdmin</label>
                </div>

            </div>

            <div class="row mx-0 my-5 justify-content-center">
                <button type="submit" class="col-auto btn btn-warning me-sm-3 me-1 data-submit col-md-4">
                    <span class="ti-xs ti ti-pencil me-1"></span>
                    Edit
                </button>
            </div>
        </form>
    </div>
</div>
