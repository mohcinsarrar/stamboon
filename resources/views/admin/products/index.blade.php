@extends('layouts/layoutMaster')

@section('title', 'Products')

@section('vendor-style')
    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/af-2.7.0/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/cr-2.0.0/date-1.5.2/fc-5.0.0/fh-4.0.1/kt-2.12.0/r-3.0.0/rg-1.5.0/rr-1.5.0/sc-2.4.1/sb-1.7.0/sp-2.3.0/sl-2.0.0/sr-1.4.0/datatables.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection


@section('vendor-script')
    <script
        src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/af-2.7.0/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/cr-2.0.0/date-1.5.2/fc-5.0.0/fh-4.0.1/kt-2.12.0/r-3.0.0/rg-1.5.0/rr-1.5.0/sc-2.4.1/sb-1.7.0/sp-2.3.0/sl-2.0.0/sr-1.4.0/datatables.min.js">
    </script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('admin/js/products_index.js') }}?{{ time() }}"></script>
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
            <form class="add-new-product pt-0" id="addNewProductForm" method="POST"  enctype="multipart/form-data" 
                action="{{ route('admin.webshop.products.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="add-product-name">Name<span class="text-danger ps-1">*</span></label>
                    <input type="text" class="form-control" id="add-product-name" name="name" required />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="add-product-price">Price ($)<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='0.01' min="0" value='0' class="form-control"
                        id="add-product-price" name="price" required />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="add-product-image">Image<span
                            class="text-danger ps-1">*</span></label>
                    <input type="file" class="form-control" id="add-product-image" name="image" required />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="add-product-description">Description<span
                            class="text-danger ps-1">*</span></label>
                    <textarea type="text" class="form-control" id="add-product-description" name="description" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="add-product-duration">Duration (month)<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="add-product-duration" name="duration" required />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="add-product-print_number">Max print<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="add-product-print_number" name="print_number" required />
                </div>

                <div class="mb-3">
                    <label class="fw-medium d-block">Chart type</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="checkbox" name="chart_type[]" id="add-product-chart_type1"
                            value="fanchart">
                        <label class="form-check-label" for="add-product-chart_type1">Fanchart</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="chart_type[]" id="add-product-chart_type2"
                            value="pedigree">
                        <label class="form-check-label" for="add-product-chart_type2">Pedigree</label>
                    </div>
                </div>

                <div class="mb-3">
                    <h6>Fan Chart Features :</h6>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="add-product-fanchart_max_generation">Max generations<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="add-product-fanchart_max_generation" name="fanchart_max_generation" required />
                </div>
                <div class="mb-3">
                    <label class="fw-medium d-block">Print type</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="checkbox" name="fanchart_print_type[]"
                            id="add-product-fanchart_print_type1" value="png">
                        <label class="form-check-label" for="add-product-fanchart_print_type1">PNG</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="fanchart_print_type[]"
                            id="add-product-fanchart_print_type2" value="pdf">
                        <label class="form-check-label" for="add-product-fanchart_print_type2">PDF</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="add-product-fanchart_max_output_png" class="form-label">Max png sizes</label>
                    <select id="add-product-fanchart_max_output_png" class="form-select" name="fanchart_max_output_png"
                        required>
                        <option value="1">1344 x 839 px</option>
                        <option value="2">2688 x 1678 px</option>
                        <option value="3">4032 x 2517 px</option>
                        <option value="4">5376 x 3356 px</option>
                        <option value="5">6720 x 4195 px</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="add-product-fanchart_max_output_pdf" class="form-label">Max pdf sizes</label>
                    <select id="add-product-fanchart_max_output_pdf" class="form-select" name="fanchart_max_output_pdf"
                        required>
                        <option value="a4">A4</option>
                        <option value="a3">A3</option>
                        <option value="a2">A2</option>
                        <option value="a1">A1</option>
                        <option value="a0">A0</option>
                    </select>
                </div>

                <div class="mb-3">
                    <h6>Pedigree Features :</h6>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="add-product-pedigree_max_generation">Max generations<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="add-product-pedigree_max_generation" name="pedigree_max_generation" required />
                </div>


                <div class="mb-3">
                    <label class="form-label" for="add-product-max_nodes">Max nodes<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="add-product-max_nodes" name="max_nodes" required />
                </div>

                <div class="mb-3">
                    <label class="fw-medium d-block">Print type</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="checkbox" name="pedigree_print_type[]"
                            id="add-product-pedigree_print_type1" value="png">
                        <label class="form-check-label" for="add-product-pedigree_print_type1">PNG</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="pedigree_print_type[]"
                            id="add-product-pedigree_print_type2" value="pdf">
                        <label class="form-check-label" for="add-product-pedigree_print_type2">PDF</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="add-product-pedigree_max_output_png" class="form-label">Max png sizes</label>
                    <select id="add-product-pedigree_max_output_png" class="form-select" name="pedigree_max_output_png"
                        required>
                        <option value="1">1344 x 839 px</option>
                        <option value="2">2688 x 1678 px</option>
                        <option value="3">4032 x 2517 px</option>
                        <option value="4">5376 x 3356 px</option>
                        <option value="5">6720 x 4195 px</option>
                    </select>
                </div>


                <div class="mb-3">
                    <label for="add-product-pedigree_max_output_pdf" class="form-label">Max pdf sizes</label>
                    <select id="add-product-pedigree_max_output_pdf" class="form-select" name="pedigree_max_output_pdf"
                        required>
                        <option value="a4">A4</option>
                        <option value="a3">A3</option>
                        <option value="a2">A2</option>
                        <option value="a1">A1</option>
                        <option value="a0">A0</option>
                    </select>
                </div>


                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>

            </form>
        </div>
    </div>
    <!-- Offcanvas to update product -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateProduct"
        aria-labelledby="offcanvasUpdateProductLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasUpdateProductLabel" class="offcanvas-title">Update Product</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <form class="add-new-product pt-0" id="updateProductForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="update-product-name">Name<span
                            class="text-danger ps-1">*</span></label>
                    <input type="text" class="form-control" id="update-product-name" name="name" required />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="update-product-price">Price ($)<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='0.01' min="0" value='0' class="form-control"
                        id="update-product-price" name="price" required />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="update-product-image">Image<span class="text-danger ps-1">*</span></label>
                    <div>
                        <img id="update-product-image-preview" src="" class="img-fluid">
                    </div>
                    <input type="file" class="form-control" id="update-product-image" name="image" />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="update-product-description">Description<span
                            class="text-danger ps-1">*</span></label>
                    <textarea type="text" class="form-control" id="update-product-description" name="description" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="update-product-duration">Duration (month)<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="update-product-duration" name="duration" required />
                </div>
                <div class="mb-3">
                    <label class="form-label" for="update-product-print_number">Max print<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="update-product-print_number" name="print_number" required />
                </div>
                <div class="mb-3">
                    <label class="fw-medium d-block">Chart type</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="checkbox" name="chart_type[]"
                            id="update-product-chart_type1" value="fanchart">
                        <label class="form-check-label" for="update-product-chart_type1">Fanchart</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="chart_type[]"
                            id="update-product-chart_type2" value="pedigree">
                        <label class="form-check-label" for="update-product-chart_type2">Pedigree</label>
                    </div>
                </div>



                <div class="mb-3">
                    <h6>Fan Chart Features :</h6>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="update-product-fanchart_max_generation">Max generations<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="update-product-fanchart_max_generation" name="fanchart_max_generation" required />
                </div>
                <div class="mb-3">
                    <label class="fw-medium d-block">Print type</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="checkbox" name="fanchart_print_type[]"
                            id="update-product-fanchart_print_type1" value="png">
                        <label class="form-check-label" for="update-product-fanchart_print_type1">PNG</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="fanchart_print_type[]"
                            id="update-product-fanchart_print_type2" value="pdf">
                        <label class="form-check-label" for="update-product-fanchart_print_type2">PDF</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="update-product-fanchart_max_output_png" class="form-label">Max png sizes</label>
                    <select id="update-product-fanchart_max_output_png" class="form-select" name="fanchart_max_output_png"
                        required>
                        <option value="1">1344 x 839 px</option>
                        <option value="2">2688 x 1678 px</option>
                        <option value="3">4032 x 2517 px</option>
                        <option value="4">5376 x 3356 px</option>
                        <option value="5">6720 x 4195 px</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="update-product-fanchart_max_output_pdf" class="form-label">Max pdf sizes</label>
                    <select id="update-product-fanchart_max_output_pdf" class="form-select" name="fanchart_max_output_pdf"
                        required>
                        <option value="a4">A4</option>
                        <option value="a3">A3</option>
                        <option value="a2">A2</option>
                        <option value="a1">A1</option>
                        <option value="a0">A0</option>
                    </select>
                </div>

                <div class="mb-3">
                    <h6>Pedigree Features :</h6>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="update-product-pedigree_max_generation">Max generations<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="update-product-pedigree_max_generation" name="pedigree_max_generation" required />
                </div>


                <div class="mb-3">
                    <label class="form-label" for="update-product-max_nodes">Max nodes<span
                            class="text-danger ps-1">*</span></label>
                    <input type="number" step='1' min="0" value='0' class="form-control"
                        id="update-product-max_nodes" name="max_nodes" required />
                </div>

                <div class="mb-3">
                    <label class="fw-medium d-block">Print type</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="checkbox" name="pedigree_print_type[]"
                            id="update-product-pedigree_print_type1" value="png">
                        <label class="form-check-label" for="update-product-pedigree_print_type1">PNG</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="pedigree_print_type[]"
                            id="update-product-pedigree_print_type2" value="pdf">
                        <label class="form-check-label" for="update-product-pedigree_print_type2">PDF</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="update-product-pedigree_max_output_png" class="form-label">Max png sizes</label>
                    <select id="update-product-pedigree_max_output_png" class="form-select" name="pedigree_max_output_png"
                        required>
                        <option value="1">1344 x 839 px</option>
                        <option value="2">2688 x 1678 px</option>
                        <option value="3">4032 x 2517 px</option>
                        <option value="4">5376 x 3356 px</option>
                        <option value="5">6720 x 4195 px</option>
                    </select>
                </div>


                <div class="mb-3">
                    <label for="update-product-pedigree_max_output_pdf" class="form-label">Max pdf sizes</label>
                    <select id="update-product-pedigree_max_output_pdf" class="form-select" name="pedigree_max_output_pdf"
                        required>
                        <option value="a4">A4</option>
                        <option value="a3">A3</option>
                        <option value="a2">A2</option>
                        <option value="a1">A1</option>
                        <option value="a0">A0</option>
                    </select>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>

            </form>
        </div>
    </div>
@endsection
