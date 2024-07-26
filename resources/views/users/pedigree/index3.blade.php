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
