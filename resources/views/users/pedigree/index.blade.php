@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pedigree')

@section('vendor-style')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
    <!-- 'classic' theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css" />
    <!-- 'monolith' theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css" />
    <!-- 'nano' theme -->


@endsection

@section('page-style')

    <style>
        .pcr-app {
            width: 40em !important;
            height: 20em !important;
        }

        .pcr-app .pcr-color-palette {
            height: 15em !important;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('admin/pedigree/css/graph.css') }}?{{ time() }}">


@endsection


@section('title', 'Pedigree')

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/jqueryui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jspdf/jspdf.umd.min.js') }}"></script>
    <script>
        $(document).ready(function() {



        });
    </script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/filerobot-image-editor/filerobot-image-editor.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/libs/read-gedcom/read-gedcom.min.js') }}"></script>

    <!-- d3-org-chart -->
    <script src="{{ asset('assets/vendor/libs/d3js/d3.v7.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/d3js/d3-org-chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/d3js/d3-flextree.js') }}"></script>


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
    <script src="{{ asset('admin/pedigree/js/order_spouse.js') }}?{{ time() }}"></script>



    <script src="{{ asset('admin/pedigree/js/draw_graph.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/load_gedcom.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/settings.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/export.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/save.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/add_note.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/add_weapon.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/dates.js') }}?{{ time() }}"></script>

    <script src="{{ asset('admin/pedigree/js/download.js') }}?{{ time() }}"></script>


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

    @include('users.pedigree.download')

    @include('users.pedigree.export')

    @include('users.pedigree.add_note')

    @include('users.pedigree.add_weapon')

    @include('users.pedigree.edit_image')

    @include('users.pedigree.add_person')

    @include('users.pedigree.order_spouse')

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
    @php
        $end = false;
    @endphp
    <!-- main graph -->
    @if (Auth::user()->has_payment() == false)
        @if (Auth::user()->last_payment() != false)
            @php
                $end = true;
            @endphp
            <a href="{{ route('users.subscription.index') }}">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <span class="alert-icon text-secondary me-2">
                        <i class="ti ti-alert-triangle ti-xs"></i>
                    </span>
                    You subscription expired, Order new to extend your account !!
                </div>
            </a>
        @else
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <span class="alert-icon text-secondary me-2">
                    <i class="ti ti-alert-triangle ti-xs"></i>
                </span>
                You don't have any subscription plan, Order one now !!
            </div>
        @endif
    @endif
    <div class="card" id="main_graph">
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

            <div class="border position-absolute p-2 bg-gray rounded {{ $end == true ? 'end' : '' }}"
                style="top:10px;left:10px" id="tools-bar">
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Upload your Gedcom file">
                    <button id="uploadGedcomBtn" {{ $has_payment == false ? 'disabled' : '' }} data-bs-toggle="modal"
                        data-bs-target="#uploadFile" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-upload fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Download your pedigree as Gedcom">
                    <button type="button" id="downloadButton"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-download fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Add Family weapon">
                    <button id="addWeapon" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-shield-chevron fs-4"></i></button>
                </div>

                <div class="row  mb-2 justify-content-center">
                    <button id="addNote" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-note fs-4"></i></button>
                </div>

                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Print your pedigree">
                    <button {{ $has_payment == false ? 'disabled' : '' }} id="export" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-printer fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Save state">
                    <button {{ $has_payment == false ? 'disabled' : '' }} id="save" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-device-floppy fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Settings">
                    <button {{ $has_payment == false ? 'disabled' : '' }} id="open_settings" type="button"
                        class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-settings fs-4"></i></button>
                </div>
                <hr class="border-1 text-white my-2 ">
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
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
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Center your familytree">
                    <button id="fit" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-arrows-minimize fs-4"></i></button>
                </div>
                <div class="row  mb-2 justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Zoom In">
                    <button id="zoomIn" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-zoom-in fs-4"></i></button>
                </div>
                <div class="row  justify-content-center" data-bs-toggle="tooltip" data-bs-placement="left"
                    title="Zoom Out">
                    <button id="zoomOut" type="button" class="btn text-white border-0 p-2 col-auto rounded-circle"><i
                            class="ti ti-zoom-out fs-4"></i></button>
                </div>
            </div>
            <div id="graph" class="h-100"></div>
        </div>
    </div>

@endsection
