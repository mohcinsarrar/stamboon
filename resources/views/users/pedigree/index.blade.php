@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('vendor-style')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />

@endsection

@section('page-style')

    <link rel="stylesheet" href="{{ asset('admin/pedigree/css/graph.css') }}?{{ time() }}">
    <style>
        .custom-modal {
            display: none;
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 1000;
            min-width: 350px;
        }

        .custom-modal-body {
            position: relative
        }

        .custom-modal-close {
            position: absolute;
            right: 0;
            top: 0;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
@endsection


@section('title', 'Pedigree')

@section('vendor-script')
    <script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script>
        $(document).ready(function() {
            new Cleave('.death_date', {
                delimiters: ['-', '-'],
                blocks: [4, 2, 2],
                numericOnly: true,
                onValueChanged: function(e) {
                    let value = e.target.value;
                    if (value.length === 4) {
                        e.target.setRawValue(value); // remove delimiters if only the year is entered
                    }
                }
            });
            new Cleave('.birth_date', {
                delimiters: ['-', '-'],
                blocks: [4, 2, 2],
                numericOnly: true,
                onValueChanged: function(e) {
                    let value = e.target.value;
                    if (value.length === 4) {
                        e.target.setRawValue(value); // remove delimiters if only the year is entered
                    }
                }
            });
            new Cleave('.death_date_add_spouse', {
                delimiters: ['-', '-'],
                blocks: [4, 2, 2],
                numericOnly: true,
                onValueChanged: function(e) {
                    let value = e.target.value;
                    if (value.length === 4) {
                        e.target.setRawValue(value); // remove delimiters if only the year is entered
                    }
                }
            });
            new Cleave('.birth_date_add_spouse', {
                delimiters: ['-', '-'],
                blocks: [4, 2, 2],
                numericOnly: true,
                onValueChanged: function(e) {
                    let value = e.target.value;
                    if (value.length === 4) {
                        e.target.setRawValue(value); // remove delimiters if only the year is entered
                    }
                }
            });
            new Cleave('.death_date_add_child', {
                delimiters: ['-', '-'],
                blocks: [4, 2, 2],
                numericOnly: true,
                onValueChanged: function(e) {
                    let value = e.target.value;
                    if (value.length === 4) {
                        e.target.setRawValue(value); // remove delimiters if only the year is entered
                    }
                }
            });
            new Cleave('.birth_date_add_child', {
                delimiters: ['-', '-'],
                blocks: [4, 2, 2],
                numericOnly: true,
                onValueChanged: function(e) {
                    let value = e.target.value;
                    if (value.length === 4) {
                        e.target.setRawValue(value); // remove delimiters if only the year is entered
                    }
                }
            });
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
    <script src="{{ asset('admin/pedigree/js/draw_graph.js') }}?{{ time() }}"></script>
    <script src="{{ asset('admin/pedigree/js/load_gedcom.js') }}?{{ time() }}"></script>
    <script>
        draw_tree()
    </script>

    <script>
        $(document).on("click", "#import-gedcom", function() {
            const file = $('#gedcom').prop('files')[0];
            var modalElement = document.getElementById('uploadFile');
            var modal = bootstrap.Modal.getInstance(modalElement);


            if (!file) {
                show_toast('error', 'error', 'No file selected')
                return;
            }

            const formData = new FormData();
            formData.append('file', file);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/pedigree/importgedcom",
                type: 'POST',
                data: formData,
                encode: true,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    modal.hide();
                    if (data.error == false) {
                        show_toast('success', 'upload file', data.msg)
                        draw_tree()
                    } else {
                        show_toast('error', 'error', data.error)
                    }

                },
                error: function(xhr, status, error) {
                    modal.hide();
                    if ('responseJSON' in xhr) {
                        show_toast('error', 'error', xhr.responseJSON.message)
                    } else {
                        show_toast('error', 'error', error)
                    }

                    return null;
                }
            });

        });
    </script>


@endsection


@section('content')
    <!-- Offcanvas to add child -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddChild" aria-labelledby="offcanvasAddChildLabel"
        data-bs-backdrop="false">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddChildLabel" class="offcanvas-title">Add Child</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <h6 class="offcanvas-subtitle"></h6>
            <form class="add-new-user pt-0" id="formAddChild" method="POST"
                action="{{ route('users.pedigree.addchild') }}">
                @csrf
                <input type="hidden" name="person_id" class="person_id">
                <input type="hidden" name="person_type" class="person_type">
                <div class="mb-3 parents">
                    <label class="form-label" for="parents">Parents <span class="text-danger">*</span>
                    </label>
                    <div class="parents_container">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">First and middle name <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control firstname" placeholder="First name" name="firstname"
                        aria-label="John" autocomplete="off" />
                    <span class="text-danger d-none" id="firstname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">Last name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control lastname" placeholder="Last name" name="lastname"
                        aria-label="Doe" autocomplete="off" />
                    <span class="text-danger d-none" id="lastname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="d-block form-label">Sex</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input male" type="radio" id="male" name="sex" value="M">
                        <label class="form-check-label" for="male"> Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input female" type="radio" id="female" name="sex" value="F">
                        <label class="form-check-label" for="female"> Female</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="d-block form-label">Status</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input living" type="radio" id="livingAddChild" name="status" value="living">
                        <label class="form-check-label" for="livingAddChild"> Living</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input deceased" type="radio" id="deceasedAddChild" name="status" value="deceased">
                        <label class="form-check-label" for="deceasedAddChild"> Deceased</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="text" name="birth_date" class="form-control date-input birth_date birth_date_add_child"
                        placeholder="YYYY-MM-DD or YYYY">
                </div>
                <!--
                    <div class="mb-2">
                        <label class="form-label" for="birth_place">Place of Birth</label>
                        <input type="text" class="form-control" id="birth_place" name="birth_place">
                    </div>
                    -->

                <div class="death-container">
                    <div class="mb-3">
                        <label class="form-label">Date of Death</label>
                        <input type="text" name="death_date"
                            class="form-control date-input death_date death_date_add_child"
                            placeholder="YYYY-MM-DD or YYYY">
                    </div>
                    <!--
                        <div class="mb-3">
                            <label class="form-label" for="death_place">Place of Death</label>
                            <input type="text" class="form-control" id="death_place" name="death_place">
                        </div>
                        -->
                </div>


                <div class="row mx-0 my-5 justify-content-center">
                    <button type="submit" class="col-auto btn btn-warning me-sm-3 me-1 data-submit col-md-4">
                        <span class="ti-xs ti ti-edit me-1"></span>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Offcanvas to add spouse -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddSpouse"
        aria-labelledby="offcanvasAddSpouseLabel" data-bs-backdrop="false">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddSpouseLabel" class="offcanvas-title">Add Spouse</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <h6 class="offcanvas-subtitle"></h6>
            <form class="add-new-user pt-0" id="formAddSpouse" method="POST"
                action="{{ route('users.pedigree.addspouse') }}">
                @csrf
                <input type="hidden" name="person_id" class="person_id">
                <div class="mb-3">
                    <label class="form-label" for="name">First and middle name <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control firstname" placeholder="First name" name="firstname"
                        aria-label="John" autocomplete="off" />
                    <span class="text-danger d-none" id="firstname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">Last name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control lastname" placeholder="Last name" name="lastname"
                        aria-label="Doe" autocomplete="off" />
                    <span class="text-danger d-none" id="lastname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="d-block form-label">Status</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input living" type="radio" id="livingAddSpouse" name="status" value="living">
                        <label class="form-check-label" for="livingAddSpouse"> Living</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input deceased" type="radio" id="deceasedAddSpouse" name="status" value="deceased">
                        <label class="form-check-label" for="deceasedAddSpouse"> Deceased</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="text" name="birth_date"
                        class="form-control date-input birth_date birth_date_add_spouse" placeholder="YYYY-MM-DD or YYYY">
                </div>
                <!--
                            <div class="mb-2">
                                <label class="form-label" for="birth_place">Place of Birth</label>
                                <input type="text" class="form-control" id="birth_place" name="birth_place">
                            </div>
                            -->

                <div class="death-container">
                    <div class="mb-3">
                        <label class="form-label">Date of Death</label>
                        <input type="text" name="death_date"
                            class="form-control date-input death_date death_date_add_spouse"
                            placeholder="YYYY-MM-DD or YYYY">
                    </div>
                    <!--
                                <div class="mb-3">
                                    <label class="form-label" for="death_place">Place of Death</label>
                                    <input type="text" class="form-control" id="death_place" name="death_place">
                                </div>
                                -->
                </div>


                <div class="row mx-0 my-5 justify-content-center">
                    <button type="submit" class="col-auto btn btn-warning me-sm-3 me-1 data-submit col-md-4">
                        <span class="ti-xs ti ti-edit me-1"></span>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Custom Modal -->
    <div class="custom-modal p-2" id="nodeModal">
        <div class="modal-body custom-modal-body" id="nodeModalBody">
            <span class="custom-modal-close" onclick="close_custom_modal()">&times;</span>
            <div class="row mx-0">
                <div class="card mb-0 border-0 shadow-none p-2">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="..." class="card-img card-img-left personImage rounded-0" alt="...">
                        </div>
                        <div class="col-8">
                            <div class="card-body p-0 ms-3">
                                <h5 class="card-title name mb-2">Card title</h5>
                                <p class="card-text mb-1">Birth : <small class="text-muted birth">Last updated 3 mins
                                        ago</small></p>
                                <p class="card-text mb-1">Death : <small class="text-muted death">Last updated 3 mins
                                        ago</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mx-0">
                <div class="btn-group px-1" role="group" aria-label="Basic example">
                    <button type="button" id="nodeEdit"
                        class="btn btn-secondary waves-effect waves-light">Edit</button>
                    <button type="button" id="nodeDelete"
                        class="btn btn-secondary waves-effect waves-light">Delete</button>
                    <form id="formDeletePerson" action="{{ route('users.pedigree.delete') }}" method="POST">
                        @csrf
                        <input type="hidden" name="person_id" class="person_id">
                    </form>
                    <button type="button" id="addSpouse" class="btn btn-secondary waves-effect waves-light">Add
                        Spouse</button>
                    <button type="button" id="addChild" class="btn btn-secondary waves-effect waves-light">Add
                        child</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Offcanvas to edit person -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdatePerson"
        aria-labelledby="offcanvasUpdatePersonLabel" data-bs-backdrop="false">
        <div class="offcanvas-header">
            <h5 id="offcanvasUpdatePersonLabel" class="offcanvas-title">Edit Person</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">

            <form class="add-new-user pt-0" id="formUpdatePerson" method="POST"
                action="{{ route('users.pedigree.update') }}">
                @csrf
                <input type="hidden" name="person_id" class="person_id">
                <div class="mb-3">
                    <label class="form-label" for="name">First and middle name <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control firstname" placeholder="First name" name="firstname"
                        aria-label="John" autocomplete="off" />
                    <span class="text-danger d-none" id="firstname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">Last name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control lastname" placeholder="Last name" name="lastname"
                        aria-label="Doe" autocomplete="off" />
                    <span class="text-danger d-none" id="lastname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="d-block form-label">Status</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input living" type="radio" id="livingUpdatePerson" name="status" value="living">
                        <label class="form-check-label" for="livingUpdatePerson"> Living</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input deceased" type="radio" id="deceasedUpdatePerson" name="status" value="deceased">
                        <label class="form-check-label" for="deceasedUpdatePerson"> Deceased</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="text" name="birth_date" class="form-control date-input birth_date"
                        placeholder="YYYY-MM-DD or YYYY">
                </div>
                <!--
                            <div class="mb-2">
                                <label class="form-label" for="birth_place">Place of Birth</label>
                                <input type="text" class="form-control" id="birth_place" name="birth_place">
                            </div>
                            -->

                <div class="death-container">
                    <div class="mb-3">
                        <label class="form-label">Date of Death</label>
                        <input type="text" name="death_date" class="form-control date-input death_date"
                            placeholder="YYYY-MM-DD or YYYY">
                    </div>
                    <!--
                                <div class="mb-3">date-input
                                    <label class="form-label" for="death_place">Place of Death</label>
                                    <input type="text" class="form-control" id="death_place" name="death_place">
                                </div>
                                -->
                </div>


                <div class="row mx-0 my-5 justify-content-center">
                    <button type="submit" class="col-auto btn btn-warning me-sm-3 me-1 data-submit col-md-4">
                        <span class="ti-xs ti ti-edit me-1"></span>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
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
        <div class="card-body position-relative">
            <div class="border position-absolute p-3 bg-secondary rounded">
                <div class="row mx-0 mb-2">
                    <button data-bs-toggle="modal" data-bs-target="#uploadFile" type="button"
                        class="btn btn-outline-dark waves-effect text-white border-0 px-1"><i
                            class="ti ti-upload fs-3"></i></button>
                </div>
                <div class="row mx-0 mb-2">
                    <a id="downloadFile" type="button" href="{{ asset('storage/' . $gedcom_file) }}"
                        class="btn btn-outline-dark waves-effect text-white border-0 px-1" download=""><i
                            class="ti ti-download fs-3"></i></a>
                </div>
                <div class="row mx-0 mb-2">
                    <button id="compactView" data-compact="false" type="button"
                        class="btn btn-outline-dark waves-effect text-white border-0 px-1">Compact</button>
                </div>
                <div class="row mx-0 mb-2">
                    <button id="expandView" data-expand="false" type="button"
                        class="btn btn-outline-dark waves-effect text-white border-0 px-1">Expand</button>
                </div>
                <div class="row mx-0 mb-2">
                    <button id="collpaseView" data-collpase="false" type="button"
                        class="btn btn-outline-dark waves-effect text-white border-0 px-1">Collpase</button>
                </div>
                <div class="row mx-0 mb-2">
                    <div class="btn-group dropend">
                        <button type="button"
                            class="btn btn-outline-dark dropdown-toggle waves-effect hide-arrow  text-white border-0 px-1"
                            data-bs-toggle="dropdown" data-trigger="hover" aria-expanded="false"><i
                                class="ti ti-growth  fs-3"></i></button>
                        <ul class="dropdown-menu">
                            <li>
                                <h6 class="dropdown-header text-uppercase">View</h6>
                            </li>
                            <li><a class="dropdown-item waves-effect" href="javascript:void(0);"
                                    id="viewVertical">Vertical</a></li>
                            <li><a class="dropdown-item waves-effect" href="javascript:void(0);"
                                    id="viewHorizontal">Horizontal</a></li>
                        </ul>
                    </div>
                </div>
                <hr class="border-1 text-white my-2">
                <div class="row mx-0 mb-2">
                    <button id="zoomIn" type="button"
                        class="btn btn-outline-dark waves-effect text-white border-0 px-1"><i
                            class="ti ti-zoom-in fs-3"></i></button>
                </div>
                <div class="row mx-0">
                    <button id="zoomOut" type="button"
                        class="btn btn-outline-dark waves-effect text-white border-0 px-1"><i
                            class="ti ti-zoom-out fs-3"></i></button>
                </div>
            </div>
            <div id="graph"></div>
        </div>
    </div>

@endsection
