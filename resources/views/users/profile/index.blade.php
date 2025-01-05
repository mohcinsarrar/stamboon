@extends('layouts/layoutMaster')

@section('title', 'Profile')

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
    <script src="{{ asset('auth/edit_profile.js') }}?{{ time() }}"></script>
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
                        class="nav-link active waves-effect waves-light"><span class="d-none d-sm-block"><i
                                class="tf-icons ti ti-user ti-sm me-1_5 align-text-bottom"></i>
                            Account information
                        </span><i class="ti ti-home ti-sm d-sm-none"></i></a>
                </li>
                <li class="nav-item" role="presentation">
                    <a type="button" href="{{ route('users.profile.security') }}"
                        class="nav-link waves-effect waves-light"><span class="d-none d-sm-block"><i
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
                        class="nav-link waves-effect waves-light"><span class="d-none d-sm-block"><i
                                class="tf-icons ti ti-circle-minus ti-sm me-1_5 align-text-bottom"></i> Delete
                            Account</span><i class="ti ti-user ti-sm d-sm-none"></i></a>
                </li>
            </ul>


            {{-- Access specific parameter --}}

            <div class="tab-content">
                <div class="tab-pane fade show active"
                    id="navs-pills-justified-home" role="tabpanel">
                    <h5 class="card-header mb-2">Update Acount Information</h5>
                    <span class="text-light">Update your personal details here.</span>
                    <div class="card-body mt-4 border-top pt-4">
                        <form id="formAccountSettings" method="POST" action="{{ route('users.profile.edit') }}"
                            class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="row mb-5">
                                    <label class="col-sm-2 col-form-label" for="firstname">First Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="firstname" name="firstname"
                                            value="{{ $user->firstname }}">
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label class="col-sm-2 col-form-label" for="lastname">Last Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="lastname" name="lastname"
                                            value="{{ $user->lastname }}">
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label class="col-sm-2  col-form-label" for="country">Country</label>
                                    <div class="col-sm-10">
                                      <select type="text" class="form-control" id="country" name="country" required>
                                        <option></option>
                                        @foreach ($countries as $country)
                                          <option value="{{$country->id}}" {{$country->id == $user->country ? "selected" : ""}}>{{$country->name}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                  </div>
                                <div class="row mb-5">
                                    <label class="col-sm-2 col-form-label" for="city">City</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="city" name="city"
                                            value="{{ $user->city }}">
                                    </div>
                                </div>
                                
                                <div class="row mb-5">
                                    <label class="col-sm-2 col-form-label" for="address">Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="{{ $user->address }}">
                                    </div>
                                </div>
                                <div class="row mb-5">
                                    <label class="col-sm-2 col-form-label" for="email">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $user->email }}">
                                    </div>
                                </div>
                                <div class="row mb-5 form-password-toggle">
                                    <label class="col-sm-2 col-form-label" for="password">New Password</label>
                                    <div class="col-sm-10">
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" name="password" class="form-control"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password2" value="" autocomplete="off" />
                                            <span class="input-group-text cursor-pointer" id="password2"><i
                                                    class="ti ti-eye-off"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-5 form-password-toggle">
                                    <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm
                                        Password</label>
                                    <div class="col-sm-10">
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password_confirmation"
                                                name="password_confirmation" class="form-control"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                aria-describedby="password_confirmation2" value=""
                                                autocomplete="off" />
                                            <span class="input-group-text cursor-pointer" id="password_confirmation2"><i
                                                    class="ti ti-eye-off"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 row justify-content-end">
                                <button type="submit" class="col-auto btn btn-primary me-2 waves-effect waves-light">Save
                                    changes</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
