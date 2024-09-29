@extends('layouts/layoutMaster')

@section('title', 'User View - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-email.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-user-view.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('admin/js/admin_notifications.js') }}"></script>
    <script src="{{ asset('auth/edit_profile.js') }}"></script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-2">
        <span class="text-muted fw-light"></span><i class="ti ti-bell h2 mb-1"></i> Notifications
    </h4>
    <div class="row">
        <div class="card">
            <div class="app-email">
                <div class="row g-0">
                    <!-- Emails List -->
                    <div class="col app-emails-list">
                        <div class="shadow-none border-0">
                            <div class="emails-list-header p-3 py-lg-3 py-2">

                                <hr class="mx-n3 emails-list-header-hr">
                                <!-- Email List: Actions -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check mb-0 me-2">
                                            <input class="form-check-input" type="checkbox" id="email-select-all">
                                            <label class="form-check-label" for="email-select-all"></label>
                                        </div>
                                        <i id="markasread-all" class="ti ti-mail-opened email-list-read cursor-pointer me-2"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Mark as read"></i>
                                        <i id="delete-all" class="ti ti-trash email-list-delete cursor-pointer me-2"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></i>
                                    </div>
                                    <div
                                        class="email-pagination d-sm-flex d-none align-items-center flex-wrap justify-content-between justify-sm-content-end">
                                    </div>
                                </div>
                            </div>
                            <hr class="container-m-nx m-0">
                            <!-- Email List: Items -->
                            <div class="email-list pt-0">
                                <ul class="list-unstyled m-0">

                                </ul>
                            </div>
                        </div>
                        <div class="app-overlay"></div>
                    </div>
                    <!-- /Emails List -->

                </div>


            </div>
        </div>

    </div>


@endsection
