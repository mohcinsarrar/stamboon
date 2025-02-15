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
    @if ($model->hasRole('superuser') == true)
        <a onclick="if(confirm('Are you sure ?')) document.getElementById('toggle-role-{{ $id }}').submit()"
            href="javascript:;" class="text-body" data-toggle="tooltip" data-placement="top" title="make it user"><i
                class="ti ti-user-minus ti-sm mx-2"></i></a>
    @else
        <a onclick="if(confirm('Are you sure ?')) document.getElementById('toggle-role-{{ $id }}').submit()"
            href="javascript:;" class="text-body" data-toggle="tooltip" data-placement="top"
            title="make it superuser"><i class="ti ti-user-plus  ti-sm mx-2"></i></a>
    @endif
    <form id="toggle-role-{{ $id }}" action="{{ route('admin.users.toggle_role', $id) }}" method="POST">
        @csrf
    </form>
</div>
