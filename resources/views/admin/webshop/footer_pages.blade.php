@extends('layouts/layoutMaster')

@section('title', 'Footer pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection


@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>

@endsection

@section('page-script')
    <script>
        forUpdate = $('.updatePages')
        formRepeater = $('.form-repeater');
        if (formRepeater.length) {
            var row = 2;
            var col = 1;
            forUpdate.on('submit', function(e) {
                e.preventDefault();
            });
            formRepeater.repeater({
                isFirstItemUndeletable: true,
                show: function() {
                    var fromControl = $(this).find('.form-control, .form-select');
                    var formLabel = $(this).find('.form-label');
                    var full_editor = $(this).find('.full_editor');
                    var full_editor_message = $(this).find('.full_editor_message');
                    var full_editor_hidden = $(this).find('.full_editor_hidden');

                    $(this).find('.page_number').text('Page ' + row)

                    fromControl.each(function(i) {
                        var id = 'form-repeater-' + row + '-' + col;
                        $(fromControl[i]).attr('id', id);
                        $(formLabel[i]).attr('for', id);
                        col++;
                    });

                    $(full_editor).attr('id', 'form-repeater-' + row + '-3');
                    $(full_editor_message).attr('id', 'form-repeater-' + row + '-3-message');
                    $(full_editor_hidden).attr('id', 'form-repeater-' + row + '-3-hidden');
                    $(full_editor).html('');

                    new Quill('#form-repeater-' + row + '-3', {
                        bounds: '#form-repeater-' + row + '-3',
                        placeholder: '',
                        modules: {
                            formula: true,
                            toolbar: fullToolbar
                        },
                        theme: 'snow'
                    });

                    row++;


                    $(this).slideDown();


                },
                hide: function(e) {
                    confirm('Are you sure you want to delete this element?') && $(this).slideUp(e);
                }
            });
        }
        $('#submitButton').on('click', function(event) {

            // add full editor content to hidden inputs
            var full_editor = document.querySelectorAll('.full_editor');
            var error = false
            full_editor.forEach(element => {
                // editor not empty
                if (element.querySelector('div.ql-blank') == null) {
                    var content = element.querySelector('div.ql-editor').innerHTML
                    document.querySelector('input#' + element.id + '-hidden').value = content
                }
                // editor empty
                else {
                    document.querySelector('span#' + element.id + '-message').style.display = "block";
                    error = true
                }
            });

            if (error == true) {
                event.preventDefault();
                return false
            }


            // Validate the form
            const form = document.getElementById('updatePages');
            if (form.checkValidity()) {
                // If the form is valid, submit it

                $('#updatePages').off('submit').submit();


            } else {
                // If the form is invalid, trigger HTML5 validation
                form.reportValidity();
            }
        });
    </script>
    <script>
        const fullToolbar = [
            [{
                    font: []
                },
                {
                    size: []
                }
            ],
            ['bold', 'italic', 'underline', 'strike'],
            [{
                    color: []
                },
                {
                    background: []
                }
            ],
            [
            ],
            [{
                    header: '1'
                },
                {
                    header: '2'
                },
                {
                    header: '3'
                },
                {
                    header: '4'
                },
            ],
            [{
                    list: 'ordered'
                },
                {
                    list: 'bullet'
                },
                {
                    indent: '-1'
                },
                {
                    indent: '+1'
                }
            ],
            [],
            ['link'],
            ['clean']
        ];
        let pages = @json($pages);
        pages.forEach((page, index) => {
            new Quill('#form-repeater-' + (index + 1) + '-3', {
                bounds: '#form-repeater-' + (index + 1) + '-3',
                placeholder: '',
                modules: {
                    formula: true,
                    toolbar: fullToolbar
                },
                theme: 'snow'
            });
        });
    </script>
@endsection

@section('page-style')

@endsection

@section('content')

    <!-- Users List Table -->
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Webshop /</span> Footer pages
    </h4>

    <div id="editorjs">

    </div>

    <form action="{{ route('admin.webshop.footer_pages.update') }}" method="POST" enctype="multipart/form-data"
        class="updatePages" id="updatePages">
        @csrf

        <div class="mb-3 form-repeater">
            <div data-repeater-list="pages">
                @if (count($pages) == 0)
                    <div data-repeater-item>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-2 page_number">Page 1</h5>
                                <div class="row">
                                    <div class="mb-3 col-12 mb-0">
                                        <label class="form-label" for="form-repeater-1-1">Title</label>
                                        <input type="text" id="form-repeater-1-1" class="form-control" name="title"
                                            value="" required>
                                    </div>
                                    <div class="mb-3 col-12 mb-0">
                                        <label class="form-label" for="form-repeater-1-3">Content</label>
                                        <div class="full_editor" id="form-repeater-1-3"></div>
                                        <span class="text-danger full_editor_message" id="form-repeater-1-3-message"
                                            style="display:none;"> Please enter the page's content. </span>
                                        <input class="full_editor_hidden" type="hidden" name="content"
                                            id="form-repeater-1-3-hidden">
                                    </div>

                                    <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                        <button class="btn btn-label-danger mt-4" data-repeater-delete>
                                            <i class="ti ti-x ti-xs me-1"></i>
                                            <span class="align-middle">Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @foreach ($pages as $key => $page)
                    <div data-repeater-item>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="mb-2 page_number">Page {{ $key + 1 }}</h5>
                                <div class="row">
                                    <div class="mb-3 col-12 mb-0">
                                        <label class="form-label" for="form-repeater-{{ $key + 1 }}-1">Title</label>
                                        <input type="text" id="form-repeater-{{ $key + 1 }}-1" class="form-control"
                                            name="title" value="{{ $page['title'] }}" required>
                                    </div>
                                    <div class="mb-3 col-12 mb-0">
                                        <label class="form-label" for="form-repeater-{{ $key + 1 }}-3">Content</label>
                                        <div class="full_editor" id="form-repeater-{{ $key + 1 }}-3">
                                            {!! $page['content'] !!}</div>
                                        <span class="text-danger full_editor_message"
                                            id="form-repeater-{{ $key + 1 }}-3-message" style="display:none;"> Please
                                            enter the page's content. </span>
                                        <input class="full_editor_hidden" type="hidden" name="content"
                                            id="form-repeater-{{ $key + 1 }}-3-hidden">
                                    </div>

                                    <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                        <button class="btn btn-label-danger mt-4" data-repeater-delete>
                                            <i class="ti ti-x ti-xs me-1"></i>
                                            <span class="align-middle">Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-0">
                <button class="btn btn-primary" data-repeater-create>
                    <i class="ti ti-plus me-1"></i>
                    <span class="align-middle">Add Page</span>
                </button>
            </div>

        </div>
        <div class="row justify-content-end">
            <button type="submit" id="submitButton" class="col-auto btn btn-primary waves-effect waves-light">Save</button>

        </div>
    </form>


@endsection
