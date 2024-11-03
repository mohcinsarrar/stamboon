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
                        class="nav-link active waves-effect waves-light"><span class="d-none d-sm-block"><i
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
                    id="navs-pills-justified-security" role="tabpanel">
                    <div class="p-4 card-action mb-4">
                        <div class="card-header align-items-center">
                            <h5 class="card-action-title mb-0">Two-steps verification</h5>
                            <div class="card-action-element">
                                <form action="{{ route('two-factor.enable') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        @if (auth()->user()->two_factor_secret == null)
                                            <button type="submit"
                                                class="btn btn-primary me-2 waves-effect waves-light">Enable</button>
                                        @else
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger me-2 waves-effect waves-light">Disable</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body mt-4">

                            <div class="row mb-4 mt-5">
                                <span>Keep your account secure with authentication step.</span>
                                <p class="mb-0">Two-factor authentication adds an additional layer of security to your
                                    account by requiring more than just a password to log in.</p>

                            </div>

                            @if (auth()->user()->two_factor_secret != null)
                                <div class="row justify-content-center mb-3">
                                    <div class="col-auto d-flex align-items-end">
                                        {!! auth()->user()->twoFactorQrCodeSvg() !!}
                                    </div>

                                </div>
                            @endif



                        </div>
                    </div>

                    <div class="p-4 mb-6 border-top">
                        <h5 class="card-header mb-4">Recent Devices</h5>
                        <div class="table-responsive table-border-bottom-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-truncate">Browser</th>
                                        <th class="text-truncate">Device</th>
                                        <th class="text-truncate">Location</th>
                                        <th class="text-truncate">Recent Activities</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($devices as $device)
                                        <tr>
                                            <td class="text-truncate">{{ $device['agent']['browser'] }}</td>
                                            <td class="text-truncate">{{ $device['agent']['platform'] }}</td>
                                            <td class="text-truncate">{{ $device['ip'] }}</td>
                                            <td class="text-truncate">{{ $device['lastActive'] }}</td>
                                            <td>
                                                @if ($device['isCurrentDevice'] == false)
                                                    <a href="{{ route('users.profile.session_delete', $device['id']) }}"
                                                        type="button"
                                                        class="btn btn-icon btn-danger waves-effect waves-light text-white"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Editer">
                                                        <span class="ti ti-trash"></span>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
