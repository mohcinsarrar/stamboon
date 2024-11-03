@extends('layouts/layoutMaster')

@section('title', 'Notifications')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('page-style')

@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
@endsection

@section('page-script')
    <script>
        forUpdate = $('.updateDocumentations')
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

                    fromControl.each(function(i) {
                        var id = 'form-repeater-' + row + '-' + col;
                        $(fromControl[i]).attr('id', id);
                        $(formLabel[i]).attr('for', id);
                        col++;
                    });

                    row++;

                    $(this).slideDown();
                },
                hide: function(e) {
                    confirm('Are you sure you want to delete this element?') && $(this).slideUp(e);
                }
            });
        }
        $('#submitButton').on('click', function() {
            // Validate the form
            const form = document.getElementById('updateDocumentations');
            if (form.checkValidity()) {
                // If the form is valid, submit it
                $('#updateDocumentations').off('submit').submit();
            } else {
                // If the form is invalid, trigger HTML5 validation
                form.reportValidity();
            }
        });
    </script>
    <script>
        function updateDocument(document_id){
            let documents = @json($data['documents']);
            const my_document = documents.find(document => document.id == document_id);

            var canvas = document.getElementById('offcanvasUpdateDocument')
            var bsOffcanvas = new bootstrap.Offcanvas(canvas)
            // reset forms
            document.getElementById('updateDocumentsForm').reset()
            document.querySelector('#updateDocumentsForm #update-document-file-message').innerHTML = '';

            document.querySelector('#updateDocumentsForm #update-document-id').value = my_document.id;
            document.querySelector('#updateDocumentsForm #update-document-title').value = my_document.title;
            
            if(my_document.file != null){
                console.log(my_document.file)
                document.querySelector('#updateDocumentsForm #update-document-file-message').innerHTML = "your current file : " + my_document.file;
            }
            bsOffcanvas.show()
        }
    </script>
@endsection

@section('content')
    <div class="row justify-content-between my-4">
        <div class="col-auto">
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light"></span><i class="ti ti-file-description h2 mb-1"></i> Documentations
            </h4>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle='offcanvas' data-bs-target= '#offcanvasAddDocument'>
                <i class="ti ti-plus me-1"></i>
                <span class="align-middle">Add document</span>
            </button>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddDocument"
        aria-labelledby="offcanvasAddDocumentLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasAddDocumentLabel" class="offcanvas-title">Add Document</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <form class="add-new-documents pt-0" id="addNewDocumentsForm" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.documentations.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="add-document-title">Title</label>
                    <input type="text" id="add-document-title" class="form-control" name="title" value=""
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="add-document-file">File</label>
                    <input type="file" id="add-document-file" class="form-control" name="file" required>
                </div>


                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>

            </form>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpdateDocument"
        aria-labelledby="offcanvasUpdateDocumentLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasUpdateDocumentLabel" class="offcanvas-title">Update Document</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
            <form class="update-documents pt-0" id="updateDocumentsForm" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.documentations.update') }}">
                @csrf
                <input type="hidden" name="id" id="update-document-id" value="">
                <div class="mb-3">
                    <label class="form-label" for="update-document-title">Title</label>
                    <input type="text" id="update-document-title" class="form-control" name="title" value=""
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="update-document-file">File</label>
                    <input type="file" id="update-document-file" class="form-control mb-2" name="file">
                    <span id="update-document-file-message" class="mt-2"></span>
                </div>


                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </div>

            </form>
        </div>
    </div>


    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row border-bottom my-3 py-3">
                    <div class="col">
                        <h6>Title</h6>
                    </div>
                    <div class="col">
                        <h6>File</h6>
                    </div>
                    <div class="col">
                        <h6>Actions</h6>
                    </div>
                </div>
                @foreach ($data['documents'] as $key => $document)
                <div class="row border-bottom my-3 py-3">
                    <div class="col">
                        <h5>{{$document['title']}}</h5>
                    </div>
                    <div class="col">
                        {{$document['file']}}
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <a href="javascript:;" onclick="updateDocument({{$document['id']}})" class="text-body"><i class="ti ti-edit ti-sm me-2"></i></a>
                            <a href="javascript:;" class="text-body delete-record" onclick="if(confirm('Are you sure ?')) document.getElementById('delete-{{ $document['id'] }}').submit()"><i class="ti ti-trash ti-sm mx-2"></i></a>
                            <form id="delete-{{ $document['id'] }}" action="{{ route('admin.documentations.destory', $document['id']) }}" method="POST">
                                @method('DELETE')
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>


@endsection
