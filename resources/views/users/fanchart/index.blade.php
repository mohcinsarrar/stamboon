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

    <link rel="stylesheet" href="{{ asset('js/webtree/css/fan-chart.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('js/webtree/css/svg.css') }}">
    <link rel="stylesheet" href="{{ asset('js/webtree/css/tree.css') }}?v={{ time() }}">
@endsection


@section('title', 'Page 2')

@section('vendor-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="https://scaleflex.cloudimg.io/v7/plugins/filerobot-image-editor/latest/filerobot-image-editor.min.js">
    </script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/webtree/fan-chart.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>


@endsection

@section('page-script')

    @if (isset($status) && $status == 'waiting')
        <script>
            $('.blocking-card').block({
                message: '',
                timeout: 0,
                css: {
                    backgroundColor: 'transparent',
                    border: '0',
                    cursor: 'default'
                },
                overlayCSS: {
                    opacity: 0.5,
                    cursor: 'default'
                }
            });
        </script>
    @endif
    <script>
        // Check selected custom option
        window.Helpers.initCustomOptionCheck();
    </script>
    <script src="{{ asset('js/webtree/js/init-fan-chart.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/webtree/js/image-editor.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/webtree/js/settings.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/webtree/js/update-person.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/webtree/js/add-person.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/webtree/js/delete-person.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/webtree/js/edit-image.js') }}?v={{ time() }}"></script>
    <script>
        // init settings
        document.getElementById("show_empty_node").checked == true
    </script>
    <script>
        // load configuration from controller and create a fanchart
        let fanchartconfig = {!! $chartParams !!}
        fanchartconfig.cssFiles = {!! json_encode($exportStylesheets) !!};
        fanChart = init_chart(fanchartconfig)

        // change fanchar configuration
        //// change show empty node
        if (document.getElementById("show_empty_node").checked) {
            fanChart.configuration.hideEmptySegments = false;
        } else {
            fanChart.configuration.hideEmptySegments = true;
        }
        //// change show gradient colors
        if ($('#template option:selected').val() == "gradient") {
            fanChart.configuration.showColorGradients = true;
        } else {
            fanChart.configuration.showColorGradients = false;
        }
        // load initial fanchart
        generation = parseInt($('#generations option:selected').val())
        draw_chart(generation, fanChart)
    </script>

    <script>
        $(document).on("click", "input.customimagescheckbox:checkbox", function() {
            var $box = $(this);
            if ($box.is(":checked")) {
                var group = "input:checkbox[name='" + $box.attr("name") + "']";
                $(group).prop("checked", false);
                $box.prop("checked", true);

            } else {
                $box.prop("checked", false);

            }
        });
    </script>
    <script>
        $(document).on("click", "#import-excel", function() {
            const file = $('#excel').prop('files')[0];
            if (!file) {
                alert_msg('error', 'No file selected');
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
                url: "/fanchart/importexcel",
                type: 'POST',
                data: formData,
                encode: true,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.error == false) {
                        document.location.href = data.redirect_url
                    } else {
                        alert_msg('error', data.msg)
                    }

                },
                error: function(xhr, status, error) {
                    if ('responseJSON' in xhr) {
                        alert_msg('error', xhr.responseJSON.message)
                    } else {
                        alert_msg('error', error)
                    }

                    return null;
                }
            });

        });
    </script>

    <script>
        function send_tree() {
            var template = document.querySelector('#sendTree input[name="template_send"]:checked').value;
            var generation = document.querySelector('#generations').value;
            Swal.fire({
                title: 'Are you sure?',
                text: 'you cannot change your family tree after send it',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, send it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "/fanchart/send",
                        type: 'POST',
                        encode: true,
                        dataType: 'json',
                        data: {
                            'template': template,
                            'generation': generation
                        },
                        success: function(data) {
                            if (data.error == false) {
                                alert_msg('success', data.msg)
                                document.location.href = '/fanchart'
                                generation = parseInt($('#generations option:selected')
                                    .val());
                                draw_chart(generation, fanChart)
                            } else {
                                alert_msg('error', data.msg)
                            }
                        },
                        error: function(xhr, status, error) {
                            alert_msg('error', error)
                            return null;
                        }
                    });

                }
            });


        }
    </script>
    <script>
        $(document).on("click", "#export", function() {

            var type = document.querySelector('#exportModal #type').value;
            if (type == "pdf") {
                document.querySelector('#exportModal #formatPdfContainer').style.display = "block";
                document.querySelector('#exportModal #orientationContainer').style.display = "block";
                document.querySelector('#exportModal #formatContainer').style.display = "none";
            } else {
                if (type == "png") {
                    document.querySelector('#exportModal #formatPdfContainer').style.display = "none";
                    document.querySelector('#exportModal #orientationContainer').style.display = "none";
                    document.querySelector('#exportModal #formatContainer').style.display = "block";
                }
            }

            var myModal = new bootstrap.Modal(document.getElementById('exportModal'))
            myModal.show()

        });

        $(document).on("change", "#exportModal #type", function() {
            var type = document.querySelector('#exportModal #type').value;
            if (type == "pdf") {
                document.querySelector('#exportModal #formatPdfContainer').style.display = "block";
                document.querySelector('#exportModal #orientationContainer').style.display = "block";
                document.querySelector('#exportModal #formatContainer').style.display = "none";
            } else {
                if (type == "png") {
                    document.querySelector('#exportModal #formatPdfContainer').style.display = "none";
                    document.querySelector('#exportModal #orientationContainer').style.display = "none";
                    document.querySelector('#exportModal #formatContainer').style.display = "block";
                }
            }


        });

        $(document).on("click", "#exportBtn", function() {
            var type = document.querySelector('#exportModal #type').value;
            const modal = document.getElementById('exportModal')
            const bsmodal = bootstrap.Modal.getInstance(modal)
            bsmodal.hide()

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/fanchart/print",
                type: 'POST',
                encode: true,
                dataType: 'json',
                success: function(data) {
                    if (data.error == false) {
                        if (type == "png") {
                            var format = document.querySelector('#exportModal #format').value;
                            exportGraph(format)
                        } else {
                            if (type == "pdf") {
                                var format = document.querySelector('#exportModal #formatPdf').value;
                                var orientation = document.querySelector('#exportModal #orientation')
                                    .value;
                                downloadPdf(format, orientation)
                            }
                        }

                    } else {
                        show_toast('danger', 'error', "can't print, print limit reached!")
                    }

                },
                error: function(xhr, status, error) {
                    show_toast('danger', 'error', "can't print, please try again !")
                    return null;
                }
            });



        });

        function exportGraph(format) {

            var max_output_png = []
            max_output_png[1] = '1344 x 839 px';
            max_output_png[2] = '2688 x 1678 px';
            max_output_png[3] = '4032 x 2517 px';
            max_output_png[4] = '5376 x 3356 px';
            max_output_png[5] = '6720 x 4195 px';


            output_png = max_output_png[format];
            output_png = output_png.replace(' px', '');
            var format_array = output_png.split(" x ");
            var x = parseInt(format_array[0]);
            var y = parseInt(format_array[1]);

            var format_array = [x, y]

            fanChart.exportPNG(format_array)

        }

        function downloadPdf(format, orientation) {

            fanChart._chart.svg.export('png').svgToPDF(fanChart._chart.svg, "fan-chart.pdf", orientation, format);
        }
    </script>
@endsection


@section('content')
    @include('users.pedigree.export')
    <!-- send tree modal-->
    <div class="modal fade" id="sendTree" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Choose your template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md mb-md-0 mb-2">
                            <div class="form-check custom-option custom-option-image custom-option-image-radio">
                                <label class="form-check-label custom-option-content" for="customRadioImg1">
                                    <span class="custom-option-body">
                                        <img src="{{ asset('storage/templates/template1.png') }}" alt="radioImg" />
                                    </span>
                                </label>
                                <input name="template_send" class="form-check-input" type="radio" value="template1"
                                    id="customRadioImg1" checked />
                            </div>
                        </div>
                        <div class="col-md mb-md-0 mb-2">
                            <div class="form-check custom-option custom-option-image custom-option-image-radio">
                                <label class="form-check-label custom-option-content" for="customRadioImg2">
                                    <span class="custom-option-body">
                                        <img src="{{ asset('storage/templates/template2.png') }}" alt="radioImg" />
                                    </span>
                                </label>
                                <input name="template_send" class="form-check-input" type="radio" value="template2"
                                    id="customRadioImg2" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="send_tree()">Send</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Offcanvas to add new user -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdatePerson"
        aria-labelledby="offcanvasUpdatePersonLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasUpdatePersonLabel" class="offcanvas-title">Update Person</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">

            <form class="add-new-user pt-0" id="formUpdatePerson" method="POST">
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
                    <label class="form-label" for="name">First name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="firstname" placeholder="First name" name="firstname"
                        aria-label="John" autocomplete="off" />
                    <span class="text-danger d-none" id="firstname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="name">Last name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" id="lastname" placeholder="Last name" name="lastname"
                        aria-label="Doe" autocomplete="off" />
                    <span class="text-danger d-none" id="lastname_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="birth_date">Birth</label>
                    <div class="input-group">
                        <input type="text" id="birth_date" class="form-control date-mask" placeholder="YYYY" />
                    </div>
                    <span class="text-danger d-none" id="birth_date_feedback"></span>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="death_date">Death</label>
                    <div class="input-group">
                        <input type="text" id="death_date" class="form-control date-mask" placeholder="YYYY" />
                    </div>
                    <span class="text-danger d-none" id="death_date_feedback"></span>
                </div>

                <div class="mb-3">
                    <label for="sex" class="form-label">Sex <span class="text-danger">*</span> </label>
                    <select class="form-select" id="sex" autocomplete="off">
                        <option hidden>Select Sex</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                    <span class="text-danger d-none" id="sex_feedback"></span>
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
    <!-- preview -->
    <div class="modal fade" id="previewImage" tabindex="-1" aria-hidden="true" style="z-index:10000;">>
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewImageTitle">Preview image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="previewImageContainer" class="rounded-circle d-block mx-auto" src=""
                        style="height: 255px;object-fit: cover;" />
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Import image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- choose images -->
                    <div id="img_placeholder_container" class="">
                        <div class="row justify-content-between mb-4 mx-0">
                            <h5 class="col-auto">Choose a placeholder image</h5>
                            <button type="button" class="btn btn-primary waves-effect waves-light col-auto"
                                id="save_img_placeholder">Save</button>
                        </div>
                        <div class="row" id="portrait_check_container">

                        </div>
                    </div>

                    <!-- import images -->
                    <img src="" class="d-none" id="img_placeholder">
                    <div class="row border-top mt-4">
                        <h5 class="mb-4 pt-4">Or import and edit an image</h5>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <input class="form-control" type="file" id="upload_image" autocomplete="off">
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary waves-effect waves-light col-auto"
                                id="preview_image">preview</button>
                        </div>
                    </div>
                    <div id="editor_container" style="height: 800px; width:100%;"></div>

                </div>
            </div>
        </div>
    </div>
    @if (isset($status) && $status == 'waiting')
        <div class="row mx-0">
            <div class="alert alert-primary alert-dismissible" role="alert">
                <h5 class="alert-heading mb-2">Your familly tree — waiting!</h5>
                <p class="mb-0">Your family tree has been sent, you will receive in your email your final product</p>
                <p class="mb-0">Your can't modify your tree until complete, </p>
                <p class="mb-0">If you want to abort the operation and continue editing your tree contact the
                    administration</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        </div>
    @endif
    @if (isset($status) && $status == 'completed')
        <div class="row mx-0">
            <div class="alert alert-success alert-dismissible" role="alert">
                <h5 class="alert-heading mb-2">Your familly tree — Completed!</h5>
                <p class="mb-0">Your family tree completed, you will find your final product in your email</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        </div>
    @endif

    <!-- settings -->
    <div class="card mb-5 mt-4 blocking-card">
        <div class="card-header d-flex justify-content-between border-bottom">
            <h5 class="card-title m-0 me-2">Settings</h5>
            <div>
                <button type="button" id="export" class="btn btn-primary waves-effect waves-light"><i
                        class="ti ti-printer me-2"></i>Print</button>
            </div>
        </div>
        <div class="card-body mt-3">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="template" class="form-label">Import your tree as excel file</label>
                    <div class="input-group">
                        <input type="file" name="excel" id="excel" class="form-control" id="inputGroupFile04"
                            aria-describedby="import-excel" aria-label="Upload" autocomplete="off">
                        <button class="btn btn-outline-primary waves-effect" type="button"
                            id="import-excel">Import</button>
                    </div>
                    <span><a href="{{ asset('docs/template.xlsx') }}" download="">Download excel file
                            template</a></span>
                </div>
                <div class="col-md-3">
                    <label for="generations" class="form-label">Generations</label>
                    <select class="form-select" id="generations">
                        @if ($max_generation >= 2)
                            <option value="2" {{ $generation == '2' ? 'selected' : '' }}>2</option>
                        @endif
                        @if ($max_generation >= 3)
                            <option value="3" {{ $generation == '3' ? 'selected' : '' }}>3</option>
                        @endif
                        @if ($max_generation >= 4)
                            <option value="4" {{ $generation == '4' ? 'selected' : '' }}>4</option>
                        @endif
                        @if ($max_generation >= 5)
                            <option value="5" {{ $generation == '5' ? 'selected' : '' }}>5</option>
                        @endif
                        @if ($max_generation >= 6)
                            <option value="6" {{ $generation == '6' ? 'selected' : '' }}>6</option>
                        @endif
                        @if ($max_generation >= 7)
                            <option value="7" {{ $generation == '7' ? 'selected' : '' }}>7</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="template" class="form-label">Template</label>
                    <select class="form-select" id="template">
                        <option value="basic" {{ $template == 'basic' ? 'selected' : '' }}>Basic</option>
                        <option value="gradient" {{ $template == 'gradient' ? 'selected' : '' }}>Gradient</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="template" class="form-label mb-2">Show empty Nodes</label>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="show_empty_node" checked="">
                        <label class="form-check-label" for="show_empty_node" value="show"></label>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <div class="card blocking-card">
        <div class="card-body">
            <div id="webtrees-fan-chart-container"
                class="webtrees-fan-chart-container wt-ajax-load wt-page-content wt-chart wt-fan-chart"></div>
        </div>
    </div>

@endsection
