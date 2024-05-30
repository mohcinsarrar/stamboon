<div class="d-flex align-items-center">
    <a href="javascript:;" onclick="updateProduct({{$model}})" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>
    <a href="javascript:;" class="text-body delete-record" onclick="if(confirm('Are you sure ?')) document.getElementById('delete-{{ $id }}').submit()"><i class="ti ti-trash ti-sm mx-2"></i></a>
    <form id="delete-{{ $id }}" action="{{ route('admin.webshop.products.destroy', $id) }}" method="POST">
        @method('DELETE')
        @csrf
    </form>
</div>
