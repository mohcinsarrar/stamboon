@extends('layouts/layoutMaster')

@section('title', 'Products')

@section('vendor-style')
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/af-2.7.0/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/cr-2.0.0/date-1.5.2/fc-5.0.0/fh-4.0.1/kt-2.12.0/r-3.0.0/rg-1.5.0/rr-1.5.0/sc-2.4.1/sb-1.7.0/sp-2.3.0/sl-2.0.0/sr-1.4.0/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection


@section('vendor-script')
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/af-2.7.0/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/cr-2.0.0/date-1.5.2/fc-5.0.0/fh-4.0.1/kt-2.12.0/r-3.0.0/rg-1.5.0/rr-1.5.0/sc-2.4.1/sb-1.7.0/sp-2.3.0/sl-2.0.0/sr-1.4.0/datatables.min.js"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('admin/js/products_index.js')}}"></script>
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}

@endsection

@section('page-style')

@endsection

@section('content')

<!-- Users List Table -->
<h4 class="py-3 mb-4">
   Products management
</h4>
<div class="card">
  <div class="card-datatable table-responsive p-3">
    {{ $dataTable->table(['class' => 'table-striped table nowrap']) }}
  </div>
</div>
<!-- Offcanvas to add new product -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddProduct" aria-labelledby="offcanvasAddProductLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddProductLabel" class="offcanvas-title">Add Product</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
      <form class="add-new-product pt-0" id="addNewProductForm" method="POST" action="{{route('admin.webshop.products.store')}}">
        @csrf
        <div class="mb-3">
          <label class="form-label" for="add-product-name">Name<span class="text-danger ps-1">*</span></label>
          <input type="text" class="form-control" id="add-product-name" name="name" required />
        </div>
        <div class="mb-3">
            <label class="form-label" for="add-product-amount">Amount<span class="text-danger ps-1">*</span></label>
            <input type="number" step='1' min="0" value='0' class="form-control" id="add-product-amount" name="amount" required />
        </div>
        <div class="mb-3">
            <label class="form-label" for="add-product-description">Description<span class="text-danger ps-1">*</span></label>
            <textarea type="text" class="form-control" id="add-product-description" name="description" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label" for="add-product-features">Features<span class="text-danger ps-1">*</span></label>
            <select class="form-control select2" id="add-product-features" name="features[]" data-allow-clear="true" multiple="multiple" required></select>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
  <!-- Offcanvas to update product -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateProduct" aria-labelledby="offcanvasUpdateProductLabel">
  <div class="offcanvas-header">
    <h5 id="offcanvasUpdateProductLabel" class="offcanvas-title">Update Product</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
    <form class="add-new-product pt-0" id="updateProductForm" method="POST" action="">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label class="form-label" for="add-product-name">Name<span class="text-danger ps-1">*</span></label>
        <input type="text" class="form-control" id="update-product-name" name="name" required />
      </div>
      <div class="mb-3">
          <label class="form-label" for="add-product-amount">Amount<span class="text-danger ps-1">*</span></label>
          <input type="number" step='1' min="0" value='0' class="form-control" id="update-product-amount" name="amount" required />
      </div>
      <div class="mb-3">
          <label class="form-label" for="add-product-description">Description<span class="text-danger ps-1">*</span></label>
          <textarea type="text" class="form-control" id="update-product-description" name="description" required></textarea>
      </div>
      <div class="mb-3">
          <label class="form-label" for="add-product-features">Features<span class="text-danger ps-1">*</span></label>
          <select class="form-control select2" id="update-product-features" name="features[]" data-allow-clear="true" multiple="multiple"></select>
      </div>

      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
      <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
    </form>
  </div>
</div>
@endsection
