<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar avatar-md me-2">
        <span class="avatar-initial rounded-circle bg-label-primary">
            {{ mb_substr($name, 0, 1) .' '.mb_substr($name, 0, 1)}}
        </span>
    </div>
    <div class="d-flex flex-column">
        <a href="{{route('admin.users.show',$id)}}"><span class="emp_name text-truncate">{{ $name }}</span></a>
        <small class="emp_post text-truncate text-muted"></small>
    </div>
</div>

