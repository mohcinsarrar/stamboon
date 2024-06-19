@extends('layouts/layoutMaster')

@section('title', 'User View - Pages')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-user-view.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/modal-edit-user.js') }}"></script>
    <script src="{{ asset('assets/js/app-user-view.js') }}"></script>
    <script src="{{ asset('assets/js/app-user-view-account.js') }}"></script>
@endsection

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light"></span><i class="ti ti-id h2 mb-1"></i> Profile
    </h4>
    <div class="row">
        <!-- User Sidebar -->
        <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
            <!-- User Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class=" d-flex align-items-center flex-column">
                            @if($user->image != null)
                            <img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{ asset('storage/'.$user->image) }}"
                                height="100" width="100" alt="User avatar" />
                            @else
                            <div class="avatar avatar-xl me-2" >
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    {{ mb_substr($user->name, 0, 1)}}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-around flex-wrap mt-3 pt-3 pb-4 border-bottom">
                        <div class="d-flex align-items-start me-4 mt-3 gap-2">
                            <span class="badge bg-label-primary p-2 rounded"><i class='ti ti-chart-arcs-3 ti-sm'></i></span>
                            <div>
                                <p class="mb-0 fw-semibold">1</p>
                                <small>Fanchart</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mt-3 gap-2">
                            <span class="badge bg-label-primary p-2 rounded"><i class='ti ti-chart-dots-3 ti-sm'></i></span>
                            <div>
                                <p class="mb-0 fw-semibold">0</p>
                                <small>Family tree</small>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 small text-uppercase text-muted">Details</p>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <span class="fw-semibold me-1">Username:</span>
                                <span>{{ $user->name }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">Email:</span>
                                <span>{{ $user->email }}</span>
                            </li>
                            <li class="mb-2 pt-1">
                                <span class="fw-semibold me-1">Status:</span>
                                <span class="badge bg-label-success">Active</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /User Card -->
            <!-- Plan Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="badge bg-label-primary">Standard</span>
                        <div class="d-flex justify-content-center">
                            <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary fw-normal">$</sup>
                            <h1 class="fw-semibold mb-0 text-primary">99</h1>
                            <sub class="h6 pricing-duration mt-auto mb-2 text-muted fw-normal">/month</sub>
                        </div>
                    </div>
                    <ul class="ps-3 g-2 my-3">
                        <li class="mb-2">10 Users</li>
                        <li class="mb-2">Up to 10 GB storage</li>
                        <li>Basic Support</li>
                    </ul>
                    <div class="d-flex justify-content-between align-items-center mb-1 fw-semibold text-heading">
                        <span>Days</span>
                        <span>65% Completed</span>
                    </div>
                    <div class="progress mb-1" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="65"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span>4 days remaining</span>
                    <div class="d-grid w-100 mt-4">
                        <a class="btn btn-primary" href="{{ route('users.subscription.index') }}">Upgrade Plan</a>
                    </div>
                </div>
            </div>
            <!-- /Plan Card -->
        </div>
        <!--/ User Sidebar -->


        <!-- User Content -->
        <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
            <!-- User Pills -->
            <ul class="nav nav-pills flex-column flex-md-row mb-4">
                <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                            class="ti ti-id ti-xs me-1"></i>Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('app/user/view/security') }}"><i
                            class="ti ti-lock ti-xs me-1"></i>Security</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('app/user/view/billing') }}"><i
                            class="ti ti-currency-dollar ti-xs me-1"></i>Billing & Plans</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('app/user/view/notifications') }}"><i
                            class="ti ti-bell ti-xs me-1"></i>Notifications</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('app/user/view/connections') }}"><i
                            class="ti ti-link ti-xs me-1"></i>Connections</a></li>
            </ul>
            <!--/ User Pills -->

            <!-- Project table -->
            <div class="card mb-4">
                <h5 class="card-header">Update Profile</h5>
                <div class="card-body">
                    <form id="formAccountSettings" method="POST" action="{{ route('users.profile.edit') }}"
                        class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-12 fv-plugins-icon-container">
                                <label for="image" class="form-label">Photo</label>
                                <input class="form-control" type="file" id="image" name="image">
                            </div>
                            <div class="mb-3 col-md-6 fv-plugins-icon-container">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input class="form-control" type="text" id="fullname" name="fullname"
                                    value="{{$user->name}}" autofocus="">
                            </div>
                            <div class="mb-3 col-md-6 fv-plugins-icon-container">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" id="email"
                                    value="{{$user->email}}">
                            </div>
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password_confirmation" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2 waves-effect waves-light">Save
                                changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Project table -->

            <!-- Activity Timeline -->
            <div class="card mb-4">
                <h5 class="card-header">User Activity Timeline</h5>
                <div class="card-body pb-0">
                    <ul class="timeline mb-0">
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">12 Invoices have been paid</h6>
                                    <small class="text-muted">12 min ago</small>
                                </div>
                                <p class="mb-2">Invoices have been paid to the company</p>
                                <div class="d-flex">
                                    <a href="javascript:void(0)" class="me-3">
                                        <img src="{{ asset('assets/img/icons/misc/pdf.png') }}" alt="PDF image"
                                            width="15" class="me-2">
                                        <span class="fw-semibold text-heading">invoices.pdf</span>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-warning"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Client Meeting</h6>
                                    <small class="text-muted">45 min ago</small>
                                </div>
                                <p class="mb-2">Project meeting with john @10:15am</p>
                                <div class="d-flex flex-wrap">
                                    <div class="avatar me-3">
                                        <img src="{{ asset('assets/img/avatars/3.png') }}" alt="Avatar"
                                            class="rounded-circle" />
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Lester McCarthy (Client)</h6>
                                        <small>CEO of {{ config('variables.creatorName') }}</small>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class=" timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-info"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Create a new project for client</h6>
                                    <small class="text-muted">2 Day Ago</small>
                                </div>
                                <p class="mb-2">5 team members in a project</p>
                                <div class="d-flex align-items-center avatar-group">
                                    <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                        data-bs-placement="top" title="Vinnie Mostowy">
                                        <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </div>
                                    <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                        data-bs-placement="top" title="Marrie Patty">
                                        <img src="{{ asset('assets/img/avatars/12.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </div>
                                    <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                        data-bs-placement="top" title="Jimmy Jackson">
                                        <img src="{{ asset('assets/img/avatars/9.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </div>
                                    <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                        data-bs-placement="top" title="Kristine Gill">
                                        <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </div>
                                    <div class="avatar pull-up" data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                        data-bs-placement="top" title="Nelson Wilson">
                                        <img src="{{ asset('assets/img/avatars/4.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent border-0">
                            <span class="timeline-point timeline-point-success"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Design Review</h6>
                                    <small class="text-muted">5 days Ago</small>
                                </div>
                                <p class="mb-0">Weekly review of freshly prepared design for our new app.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /Activity Timeline -->

            <!-- Invoice table -->
            <div class="card mb-4">
                <div class="table-responsive mb-3">
                    <table class="table datatable-invoice border-top">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th><i class='ti ti-trending-up'></i></th>
                                <th>Total</th>
                                <th>Issued Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- /Invoice table -->
        </div>
        <!--/ User Content -->
    </div>

    <!-- Modal -->

    <!-- /Modal -->
@endsection
