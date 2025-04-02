@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pedigree')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables/datatables.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

@endsection

@section('page-style')

@endsection



@section('vendor-script')

    <script src="{{ asset('assets/vendor/libs/jqueryui/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jspdf/jspdf.umd.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/datatables/datatables.min.js') }}"></script>



@endsection

@section('page-script')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        window.Helpers.initCustomOptionCheck();
    </script>
    <script>
        function updatePedigree($pedigree) {
            var canvas = document.getElementById('offcanvasEditPedigree')
            var bsOffcanvas = new bootstrap.Offcanvas(canvas)
            // reset forms
            document.getElementById('editNewPedigreeForm').reset()
            document.getElementById('editNewPedigreeForm').action = "/pedigree/edit/" + $pedigree.id

            document.querySelector('#editNewPedigreeForm #edit-pedigree-name').value = $pedigree.name;

            bsOffcanvas.show()
        }
    </script>
@endsection


@section('content')


    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditPedigree" aria-labelledby="offcanvasEditPedigreeLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasEditPedigreeLabel" class="offcanvas-title">Edit Pedigree</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <form class="edit-new-pedigree pt-0" id="editNewPedigreeForm" method="POST" enctype="multipart/form-data"
                action="">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="edit-pedigree-name">Name<span class="text-danger ps-1">*</span></label>
                    <input type="text" class="form-control" id="edit-pedigree-name" name="name" required />
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddPedigree"
        aria-labelledby="offcanvasAddPedigreeLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddPedigreeLabel" class="offcanvas-title">Add Pedigree</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <form class="add-new-pedigree pt-0" id="addNewPedigreeForm" method="POST" enctype="multipart/form-data"
                action="{{ route('users.pedigree.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="add-pedigree-name">Name<span class="text-danger ps-1">*</span></label>
                    <input type="text" class="form-control" id="add-pedigree-name" name="name" required />
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <h4 class="m-4 ">Pedigrees</h4>
        <div class="card-datatable table-responsive p-3">
            {{ $dataTable->table(['class' => 'table-striped table nowrap']) }}
        </div>
    </div>

@endsection
