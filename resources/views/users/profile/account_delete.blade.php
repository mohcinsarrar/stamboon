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
    <script src="{{ asset('auth/edit_profile.js') }}"></script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-2">
        <span class="text-muted fw-light"></span><i class="ti ti-id h2 mb-1"></i> Profile
    </h4>
    <div class="row">
        <div class="nav-align-top mb-6">
            <ul class="nav nav-pills mb-4" role="tablist">
                <li class="nav-item mb-1 mb-sm-0" role="presentation">
                    <a type="button" href="{{ route('users.profile.index') }}"
                        class="nav-link waves-effect waves-light"><span class="d-none d-sm-block"><i
                                class="tf-icons ti ti-user ti-sm me-1_5 align-text-bottom"></i>
                            Account information
                        </span><i class="ti ti-home ti-sm d-sm-none"></i></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a type="button" href="{{ route('users.profile.security') }}"
                        class="nav-link  waves-effect waves-light"><span class="d-none d-sm-block"><i
                                class="tf-icons ti ti-lock ti-sm me-1_5 align-text-bottom"></i>
                            Security</span><i class="ti ti-lock ti-sm d-sm-none"></i></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a type="button" href="{{ route('users.profile.notifications') }}"
                        class="nav-link waves-effect waves-light"><span class="d-none d-sm-block"><i
                                class="tf-icons ti ti-bell ti-sm me-1_5 align-text-bottom"></i>
                            Notifications<span
                                class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger ms-1_5 pt-50 ms-2"
                                id="notifications-count"></span></span><i
                            class="ti ti-message-dots ti-sm d-sm-none"></i></a>
                </li>
                <li class="nav-item mb-1 mb-sm-0" role="presentation">
                    <a type="button" href="{{ route('users.profile.account_delete') }}"
                        class="nav-link active waves-effect waves-light"><span class="d-none d-sm-block"><i
                                class="tf-icons ti ti-circle-minus ti-sm me-1_5 align-text-bottom"></i> Delete
                            Account</span><i class="ti ti-user ti-sm d-sm-none"></i></a>
                </li>
            </ul>


            {{-- Access specific parameter --}}

            <div class="tab-content">

                <div class="tab-pane fade show active "
                    id="navs-pills-justified-profile" role="tabpanel">
                    <h5 class="card-header mb-2">Delete Acount</h5>
                    <div class="card-body mt-4 border-top pt-4">
                        <form id="formDeleteAccount" method="POST" action="{{ route('users.profile.delete') }}"
                            class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mx-0 mt-3 mb-5">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="confirm" name="confirm"
                                        id="defaultCheck3" required>
                                    <label class="form-check-label" for="defaultCheck3">
                                        I understand that my account will be deleted
                                    </label>
                                </div>
                            </div>
                            <div class="mt-4 mx-0 row justify-content-start">
                                <button type="submit" class="col-auto btn btn-primary me-2 waves-effect waves-light">Delete
                                    account! This is
                                    irreversible</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
