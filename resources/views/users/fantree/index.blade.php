@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Fantree')

@section('vendor-style')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css"/> <!-- 'classic' theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css"/> <!-- 'monolith' theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css"/> <!-- 'nano' theme -->


@endsection

@section('page-style')
    <style>
        .person-text {
            padding-top: 1px;
            background-color: gray;
            color: black;
            --c: 80;
            width: 100px;
            height: 50px;
            aspect-ratio: 3/2;
            -webkit-mask: radial-gradient(
                calc(80*1%) 100% at 50% calc(90% + 100%*cos(asin(50/80))), #0000 calc(100% - 5px), #000
                );
            mask: radial-gradient(calc(var(--c)*1%) 100% at 50% calc(100% + 100%*cos(asin(50/var(--c)))), #0000 calc(100% - 1px), #000);
            clip-path: ellipse(calc(var(--c)*1%) 100% at bottom);
        }
    </style>

@endsection


@section('title', 'Pedigree')

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/jqueryui/jquery-ui.min.js') }}"></script>

    <!-- d3-js -->
    <script src="{{ asset('assets/vendor/libs/d3js/d3.v7.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/read-gedcom/read-gedcom.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.min.js') }}"></script>

@endsection

@section('page-script')
    <script src="{{ asset('admin/fantree/js/global_variable.js') }}?{{ time() }}"></script>
    
    <script src="{{ asset('admin/fantree/js/import_gedcom.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/fantree/js/load_gedcom.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/fantree/js/settings.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/fantree/js/index.js') }}?{{ time() }}"></script>
    <script>
        draw_tree()
    </script>
    <script>
        window.Helpers.initCustomOptionCheck();
    </script>
@endsection


@section('content')

    @include('users.pedigree.settings')


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


    <div class="row mb-4" style="display: none;" id="max-node-alert">
        <div class="col">
            <div class="alert alert-warning" role="alert">
            </div>
        </div>
    </div>

    <div class="row mb-4" style="display: none;" id="max-generations-alert">
        <div class="col">
            <div class="alert alert-warning" role="alert">
            </div>
        </div>
    </div>

    <div class="row mb-4 mx-0" id="spinner" style="display: none;">
        <div class="progress col px-0" style="height: 16px;">
            <div class="progress-bar" id="progress-percentage" role="progressbar" style="width: 0%;" aria-valuenow="0"
                aria-valuemin="0" aria-valuemax="100">0%</div>
        </div>
    </div>
    <!-- main graph -->
    <div class="card h-100" id="main_graph">
        <div class="row justify-content-center mt-4 d-none" id="add-first-person-container">
            <div class="col-auto">
                <button {{ $has_payment == false ? 'disabled' : '' }} data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasAddPerson" type="button" class="btn btn-primary waves-effect waves-light">
                    <span class="ti-xs ti ti-plus me-1"></span>
                    Add person
                </button>
            </div>
        </div>
        <div class="card-body position-relative tools p-2" style="min-height: 600px;">

            <div class="border position-absolute p-2 bg-gray rounded" style="top:10px;left:10px" id="tools-bar">
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Upload your Gedcom file">
                    <button {{ $has_payment == false ? 'disabled' : '' }} data-bs-toggle="modal"
                        data-bs-target="#uploadFile" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-upload fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Download your pedigree as Gedcom">
                    <button type="button" id="downloadButton"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-download fs-4"></i></button>
                </div>
                <!--
                        <div class="row  mb-2 justify-content-center">
                            <button id="addNote" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                                    class="ti ti-note fs-4"></i></button>
                        </div>
                    -->
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Print your pedigree">
                    <button {{ $has_payment == false ? 'disabled' : '' }} id="export" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-printer fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Settings">
                    <button {{ $has_payment == false ? 'disabled' : '' }} id="open_settings" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-settings fs-4"></i></button>
                </div>
                <hr class="border-1 text-white my-2 ">
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Project asset">
                    <div class="btn-group dropend col-auto">
                        <button type="button" class="btn text-white border-0 p-2  rounded-circle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Center your familytree">
                    <button id="fit" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-arrows-minimize fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Zoom In">
                    <button id="zoomIn" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-zoom-in fs-4"></i></button>
                </div>
                <div class="row  justify-content-center" data-bs-toggle="tooltip" data-bs-placement="right"
                    title="Zoom Out">
                    <button id="zoomOut" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-zoom-out fs-4"></i></button>
                </div>
            </div>
            <div id="graph" class="h-100"></div>
        </div>
    </div>

@endsection
