<div class="d-flex align-items-center">
    <a data-toggle="tooltip" data-placement="top" title="open Fanchart" href="{{ route('users.fantree.index', $id) }}"
        class="text-body px-2"><i class="ti ti-chart-arcs-3 ti-sm me-2"></i></a>
    <a data-toggle="tooltip" data-placement="top" title="Edit Fanchart" href="javascript:;"
        onclick="updateFantree({{ $model }})" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>
    <a data-toggle="tooltip" data-placement="top" title="delete Fanchart" href="javascript:;"
        class="text-body delete-record"
        onclick="if(confirm('Are you sure ?')) document.getElementById('delete-{{ $id }}').submit()"><i
            class="ti ti-trash ti-sm mx-2"></i></a>
    <form id="delete-{{ $id }}" action="{{ route('users.fantree.destroy', $id) }}" method="POST">
        @method('DELETE')
        @csrf
    </form>
</div>
