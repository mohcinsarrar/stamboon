@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

@endsection

@section('page-style')

    <link rel="stylesheet" href="{{ asset('admin/pedigree/css/graph.css') }}?{{ time() }}">

@endsection


@section('title', 'Pedigree')

@section('vendor-script')
    <script src="https://scaleflex.cloudimg.io/v7/plugins/filerobot-image-editor/latest/filerobot-image-editor.min.js">
    </script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script>
        $(document).ready(function() {
            
            
            
        });
    </script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="https://scaleflex.cloudimg.io/v7/plugins/filerobot-image-editor/latest/filerobot-image-editor.min.js">
    </script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/read-gedcom/dist/read-gedcom.min.js"></script>
    <!-- apex dTree -->
    <!-- required for dTree -->
    <!-- load dTree -->
    <!-- load treant -->
    <!-- d3-org-chart -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-org-chart@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.1.2/build/d3-flextree.js"></script>
    <!-- elgrapho -->


@endsection

@section('page-script')
    <script src="{{ asset('admin/pedigree/js/global_variable.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/utils.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/import_gedcom.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/edit_person.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/add_person.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/add_child.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/add_spouse.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/delete_person.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/edit_image.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/settings.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/draw_graph.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/load_gedcom.js') }}?{{ time() }}"></script>
    <script>
        draw_tree()
    </script>
    <script>
        window.Helpers.initCustomOptionCheck();
    </script>
@endsection


@section('content')

    @include('users.pedigree.add_child')

    @include('users.pedigree.add_spouse')

    @include('users.pedigree.show_person')

    @include('users.pedigree.edit_person')

    @include('users.pedigree.settings')

    @include('users.pedigree.edit_image')

    @include('users.pedigree.add_person')

    <!-- modal to upload gedcom file -->
    <div class="modal fade" id="uploadFile" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Uplod your family tree</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="template" class="form-label">Import your Gedcom file</label>
                            <div class="input-group">
                                <input type="file" name="gedcom" id="gedcom" class="form-control"
                                    id="inputGroupFile04" aria-describedby="import-gedcom" aria-label="Upload"
                                    autocomplete="off">
                                <button class="btn btn-outline-primary waves-effect" type="button"
                                    id="import-gedcom">Import</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- min graph -->
    <div class="card blocking-card">
        <div class="row justify-content-center mt-4 d-none" id="add-first-person-container">
            <div class="col-auto">
                <button data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddPerson" type="button" class="btn btn-primary waves-effect waves-light">
                    <span class="ti-xs ti ti-plus me-1"></span>
                    Add person
                </button>
            </div>
        </div>
        <div class="card-body position-relative tools" style="min-height: 600px;">
            
            <div class="border position-absolute p-2 bg-gray rounded">
                <div class="row  mb-2 justify-content-center">
                    <button data-bs-toggle="modal" data-bs-target="#uploadFile" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-upload fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center">
                    <a id="downloadFile" type="button" href="{{ asset('storage/' . $gedcom_file) }}"
                        class="btn text-white border-0 p-2 col-auto rounded-circle" download=""><i
                            class="ti ti-download fs-4"></i></a>
                </div>
                <div class="row  mb-2 justify-content-center">
                    <div class="btn-group dropend col-auto">
                        <button type="button" class="btn text-white border-0 p-2  rounded-circle" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" style="">
                            <li><a id="compactView" class="dropdown-item" href="javascript:void(0);">Compact</a></li>
                            <li><a id="expandView" class="dropdown-item" href="javascript:void(0);">Expand All</a></li>
                            <li><a id="collpaseView" class="dropdown-item" href="javascript:void(0);">Collpase All</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row  mb-2 justify-content-center">
                    <button data-bs-toggle="modal" data-bs-target="#settings" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-settings fs-4"></i></button>
                </div>
                <hr class="border-1 text-white my-2 ">
                <div class="row  mb-2 justify-content-center">
                    <button id="fit" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-arrows-minimize fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center">
                    <button id="zoomIn" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-zoom-in fs-4"></i></button>
                </div>
                <div class="row  justify-content-center">
                    <button id="zoomOut" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-zoom-out fs-4"></i></button>
                </div>
            </div>
            <div id="graph"></div>
        </div>
    </div>

@endsection
