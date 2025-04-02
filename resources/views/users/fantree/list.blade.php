@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Fantree')

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
        function updateFantree($fantree) {
            var canvas = document.getElementById('offcanvasEditFantree')
            var bsOffcanvas = new bootstrap.Offcanvas(canvas)
            // reset forms
            document.getElementById('editNewFantreeForm').reset()
            document.getElementById('editNewFantreeForm').action = "/fantree/edit/" + $fantree.id

            document.querySelector('#editNewFantreeForm #edit-fantree-name').value = $fantree.name;

            bsOffcanvas.show()
        }
    </script>
@endsection


@section('content')


    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditFantree" aria-labelledby="offcanvasEditFantreeLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasEditFantreeLabel" class="offcanvas-title">Edit Fanchart</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <form class="edit-new-fantree pt-0" id="editNewFantreeForm" method="POST" enctype="multipart/form-data"
                action="">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="edit-fantree-name">Name<span class="text-danger ps-1">*</span></label>
                    <input type="text" class="form-control" id="edit-fantree-name" name="name" required />
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddFantree" aria-labelledby="offcanvasAddFantreeLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddFantreeLabel" class="offcanvas-title">Add Fanchart</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <form class="add-new-fantree pt-0" id="addNewFantreeForm" method="POST" enctype="multipart/form-data"
                action="{{ route('users.fantree.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="add-fantree-name">Name<span class="text-danger ps-1">*</span></label>
                    <input type="text" class="form-control" id="add-fantree-name" name="name" required />
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <h4 class="m-4 ">Fancharts</h4>
        <div class="card-datatable table-responsive p-3">
            {{ $dataTable->table(['class' => 'table-striped table nowrap']) }}
        </div>
    </div>

@endsection
