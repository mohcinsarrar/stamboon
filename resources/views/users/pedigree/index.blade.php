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
@endsection


@section('title', 'Pedigree')

@section('vendor-script')
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
    <!-- Offcanvas to edit person -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdatePerson"
        aria-labelledby="offcanvasUpdatePersonLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasUpdatePersonLabel" class="offcanvas-title">Update Person</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">

            <form class="add-new-user pt-0" id="formUpdatePerson" method="POST" action="{{route('users.pedigree.update')}}">
                @csrf
                <input type="hidden" name="person_id" id="person_id">
                <div class="mb-3">
                    <div id="portrait_container">

                    </div>
                    <button id="importimagebtn" type="button" class="btn btn-primary mt-4" data-id="" data-sex=""
                        data-image="">
                        <span class="ti-xs ti ti-edit me-1"></span>
                        Edit image
                    </button>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">First and middle name <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="firstname" placeholder="First name" name="firstname"
                        aria-label="John" autocomplete="off"/>
                    <span class="text-danger d-none" id="firstname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">Last name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="lastname" placeholder="Last name" name="lastname"
                        aria-label="Doe" autocomplete="off"/>
                    <span class="text-danger d-none" id="lastname_feedback"></span>
                </div>
                <div class="mb-0">
                    <label class="d-block form-label">Status</label>
                    <div class="form-check form-check-inline mt-2">
                        <input class="form-check-input" type="radio" name="status" id="living"
                            value="living">
                        <label class="form-check-label" for="living"> Living</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="deceased"
                            value="deceased">
                        <label class="form-check-label" for="deceased"> Deceased</label>
                    </div>
                </div>
                <div class="divider my-0"><hr></div>
                <div class="mb-3" id="date_birth">
                    <label class="form-label">Date of Birth <span class="text-muted">(DD MM YYYY)</span></label>
                    <div class="input-group">
                        <input type="text" name="birth_day" aria-label="day" class="form-control" placeholder="Day">
                        <input type="text" name="birth_month" aria-label="month" class="form-control" placeholder="Month">
                        <input type="text" name="birth_year" aria-label="year" class="form-control" placeholder="Year">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label" for="birth_place">Place of Birth</label>
                    <input type="text" class="form-control" id="birth_place" name="birth_place">
                </div>
                <div class="divider my-0"><hr></div>
                <div id="death-container">
                    <div class="mb-3" id="date_death">
                        <label class="form-label">Date of Death <span class="text-muted">(DD MM YYYY)</span></label>
                        <div class="input-group">
                            <input type="text" name="death_day" aria-label="day" class="form-control" placeholder="Day">
                            <input type="text" name="death_month" aria-label="month" class="form-control" placeholder="Month">
                            <input type="text" name="death_year" aria-label="year" class="form-control" placeholder="Year">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="death_place">Place of Death</label>
                        <input type="text" class="form-control" id="death_place" name="death_place">
                    </div>
                </div>
                

                <div class="row mx-0 mb-3">
                    <button type="submit" class="btn btn-warning me-sm-3 me-1 data-submit col-md-4">
                        <span class="ti-xs ti ti-edit me-1"></span>
                        Update
                    </button>
                    <button type="button" id="deletePerson" class="btn btn-danger col-md-4">
                        <span class="ti-xs ti ti-trash me-1"></span>
                        Delete
                    </button>
                </div>

                <div class="row mx-0 mb-3">
                    <button type="button" id="addFather" class="btn btn-primary me-sm-3 me-1 col-md-4">
                        <span class="ti-xs ti ti-layout-grid-add me-1"></span>
                        Add Father
                    </button>
                    <button type="button" id="addMother" class="btn btn-primary col-md-4">
                        <span class="ti-xs ti ti-layout-grid-add me-1"></span>
                        Add Mother
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
    <div class="card blocking-card">
        <div class="card-body position-relative">
            <div class="border position-absolute p-3 bg-secondary rounded">
                <div class="row mx-0 mb-2">
                    <button data-bs-toggle="modal" data-bs-target="#uploadFile" type="button"
                        class="btn btn-outline-dark waves-effect text-white border-0 px-1"><i
                            class="ti ti-upload fs-3"></i></button>
                </div>
                <div class="row mx-0 mb-2">
                    <a id="downloadFile" type="button" href="{{asset('storage/'.$gedcom_file)}}"
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
