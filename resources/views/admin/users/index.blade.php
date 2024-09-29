@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Users')

@section('vendor-style')
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/af-2.7.0/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/cr-2.0.0/date-1.5.2/fc-5.0.0/fh-4.0.1/kt-2.12.0/r-3.0.0/rg-1.5.0/rr-1.5.0/sc-2.4.1/sb-1.7.0/sp-2.3.0/sl-2.0.0/sr-1.4.0/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />
@endsection

@section('vendor-script')
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.0.3/af-2.7.0/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/cr-2.0.0/date-1.5.2/fc-5.0.0/fh-4.0.1/kt-2.12.0/r-3.0.0/rg-1.5.0/rr-1.5.0/sc-2.4.1/sb-1.7.0/sp-2.3.0/sl-2.0.0/sr-1.4.0/datatables.min.js"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection

@section('page-script')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}

<script>
    var myOffcanvas = document.getElementById('offcanvasAddUser')
    myOffcanvas.addEventListener('show.bs.offcanvas', function () {
            document.getElementById('formAuthentication').reset()
            document.querySelectorAll('#formAuthentication div.fv-plugins-message-container').forEach(box => {
                    box.innerHTML = "";
            });
            document.querySelectorAll("#formAuthentication input").forEach(box => {
                    box.classList.remove('is-invalid');
            });
            document.querySelectorAll("#formAuthentication select").forEach(box => {
                    box.classList.remove('is-invalid');
            });
    })

</script>
    <script>
        function updateUSer($user){

            var canvas = document.getElementById('offcanvaseditUser')
            var bsOffcanvas = new bootstrap.Offcanvas(canvas)
            // reset forms
            document.getElementById('formUpdateUser').reset()
            document.querySelectorAll('#formUpdateUser div.fv-plugins-message-container').forEach(box => {
                    box.innerHTML = "";
            });
            document.querySelectorAll("#formUpdateUser input").forEach(box => {
                    box.classList.remove('is-invalid');
            });
            document.querySelectorAll("#formUpdateUser select").forEach(box => {
                    box.classList.remove('is-invalid');
            });
            // fill out the form
            document.getElementById('formUpdateUser').action = "/users/" + $user.id
            document.querySelector('#formUpdateUser #firstname_update').value = $user.firstname;
            document.querySelector('#formUpdateUser #lastname_update').value = $user.lastname;
            if($user.sex != null){
                document.querySelector('#formUpdateUser #sex_update').value = $user.sex
            }
            document.querySelector('#formUpdateUser #email_update').value = $user.email;
            if($user.tree.status != null){
                document.querySelector('#formUpdateUser #status_update').value = $user.tree.status.toLowerCase()
            }
            bsOffcanvas.show()
        }
    </script>

    <script type="text/javascript">
        /**
         *  Pages Authentication
         */

        'use strict';
        const formAuthentication = document.querySelector('#formAuthentication');

        document.addEventListener('DOMContentLoaded', function(e) {
            (function() {
                // Form validation for Add new record
                if (formAuthentication) {
                    const fv = FormValidation.formValidation(formAuthentication, {
                        fields: {
                            firstname: {
                                validators: {
                                    notEmpty: {
                                        message: 'Please enter the first name'
                                    }
                                }
                            },
                            lastname: {
                                validators: {
                                    notEmpty: {
                                        message: 'Please enter the last name '
                                    }
                                }
                            },
                            sex: {
                                validators: {
                                    callback: {
                                        message: 'Please select the sex',
                                        callback: function ($input){
                                                if($input.value == "Select Sex"){
                                                    return {
                                                        valid: false,       // or true
                                                        message: 'Please select the sex'
                                                    };
                                                }
                                                return true
                                            }
                                    }
                                }
                            },
                            email: {
                                validators: {
                                    notEmpty: {
                                        message: 'Please enter your email'
                                    },
                                    emailAddress: {
                                        message: 'Please enter valid email address'
                                    }
                                }
                            },

                        },
                        plugins: {
                            trigger: new FormValidation.plugins.Trigger(),
                            bootstrap5: new FormValidation.plugins.Bootstrap5({
                                eleValidClass: '',
                                rowSelector: '.mb-3'
                            }),
                            submitButton: new FormValidation.plugins.SubmitButton(),

                            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                            autoFocus: new FormValidation.plugins.AutoFocus()
                        },
                        init: instance => {
                            instance.on('plugins.message.placed', function(e) {
                                if (e.element.parentElement.classList.contains(
                                        'input-group')) {
                                    e.element.parentElement.insertAdjacentElement(
                                        'afterend', e.messageElement);
                                }
                            });
                        }
                    });
                }

                //  Two Steps Verification
                const numeralMask = document.querySelectorAll('.numeral-mask');

                // Verification masking
                if (numeralMask.length) {
                    numeralMask.forEach(e => {
                        new Cleave(e, {
                            numeral: true
                        });
                    });
                }
            })();
        });
    </script>

<script type="text/javascript">
    /**
     *  Pages Authentication
     */

    'use strict';
    const formUpdateUser = document.querySelector('#formUpdateUser');

    document.addEventListener('DOMContentLoaded', function(e) {
        (function() {
            // Form validation for Add new record
            if (formUpdateUser) {
                const fvUpdateUser = FormValidation.formValidation(formUpdateUser, {
                    fields: {
                        firstname: {
                            validators: {
                                notEmpty: {
                                    message: 'Please enter the first name'
                                }
                            }
                        },
                        lastname: {
                            validators: {
                                notEmpty: {
                                    message: 'Please enter the last name '
                                }
                            }
                        },
                        sex: {
                            validators: {
                                    callback: {
                                        message: 'Please select the sex',
                                        callback: function ($input){
                                                if($input.value == "Select Sex"){
                                                    return {
                                                        valid: false,       // or true
                                                        message: 'Please select the sex'
                                                    };
                                                }
                                                return true;
                                            }
                                    }
                            }
                        },
                        email: {
                            validators: {
                                notEmpty: {
                                    message: 'Please enter your email'
                                },
                                emailAddress: {
                                    message: 'Please enter valid email address'
                                }
                            }
                        },

                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            eleValidClass: '',
                            rowSelector: '.mb-3'
                        }),
                        submitButton: new FormValidation.plugins.SubmitButton(),

                        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                        autoFocus: new FormValidation.plugins.AutoFocus()
                    },
                    init: instance => {
                        instance.on('plugins.message.placed', function(e) {
                            if (e.element.parentElement.classList.contains(
                                    'input-group')) {
                                e.element.parentElement.insertAdjacentElement(
                                    'afterend', e.messageElement);
                            }
                        });
                    }
                });
            }

            //  Two Steps Verification
            const numeralMask = document.querySelectorAll('.numeral-mask');

            // Verification masking
            if (numeralMask.length) {
                numeralMask.forEach(e => {
                    new Cleave(e, {
                        numeral: true
                    });
                });
            }
        })();
    });
</script>

    <script>
        function open_edit_user_modal(){
            const bsOffcanvas = new bootstrap.Offcanvas('#offcanvaseditUser');
            $("#offcanvaseditUser ")
            bsOffcanvas.show();
        }
    </script>
@endsection

@section('content')

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{$total_users}}</h4>
                            </div>
                            <span>Total Users</span>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="ti ti-user ti-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{$new_users}}</h4>
                            </div>
                            <span>New Users</span>
                        </div>
                        <span class="badge bg-label-info rounded p-2">
                            <i class="ti ti-user-plus ti-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{$active_users}}</h4>
                            </div>
                            <span>Active Users</span>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                              <i class="ti ti-user-check ti-sm"></i>
                            </span>
                          </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <div class="d-flex align-items-center my-1">
                                <h4 class="mb-0 me-2">{{$expired_users}}</h4>
                            </div>
                            <span>Expired Users</span>
                        </div>
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="ti ti-user-x ti-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Users List Table -->
    <div class="card">
        <div class="card-datatable table-responsive p-3">
            {{ $dataTable->table(['class' => 'table-striped table nowrap']) }}
        </div>
        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                <form class="add-new-user pt-0" id="formAuthentication" action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" for="firstname">First Name <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" id="firstname" placeholder="John Doe" name="firstname"
                            aria-label="John" autofocus required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="lastname">Last Name <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" id="lastname" placeholder="John Doe" name="lastname"
                            aria-label="Doe" required/>
                    </div>
                    <div class="mb-3">
                        <label for="sex" class="form-label">Sex <span class="text-danger">*</span> </label>
                        <select class="form-select hidden-placeholder" id="sex" name="sex" autocomplete="off">
                            <option hidden>Select Sex</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email">Email</label>
                        <input type="text" id="email" class="form-control" placeholder="john.doe@example.com"
                            aria-label="john.doe@example.com" name="email" />
                    </div>
                    
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
        <!-- Offcanvas to edit user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvaseditUser" aria-labelledby="offcanvaseditUserLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvaseditUserLabel" class="offcanvas-title">Edit User</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                <form class="edit-new-user pt-0" id="formUpdateUser" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" for="firstname_update">First Name</label>
                        <input type="text" class="form-control" id="firstname_update" placeholder="John Doe" name="firstname"
                            aria-label="John" autofocus />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="lastname_update">Last Name</label>
                        <input type="text" class="form-control" id="lastname_update" placeholder="John Doe" name="lastname"
                            aria-label="Doe" />
                    </div>
                    <div class="mb-3">
                        <label for="sex_update" class="form-label">Sex <span class="text-danger">*</span> </label>
                        <select class="form-select hidden-placeholder" id="sex_update" name="sex" autocomplete="off">
                            <option hidden>Select Sex</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="email_update">Email</label>
                        <input type="text" id="email_update" class="form-control" placeholder="john.doe@example.com"
                            aria-label="john.doe@example.com" name="email" />
                    </div>
                    <div class="mb-3">
                        <label for="status_update" class="form-label">Status <span class="text-danger">*</span> </label>
                        <select class="form-select hidden-placeholder" id="status_update" name="status" autocomplete="off">
                            <option value="pending">Pending</option>
                            <option value="waiting">Waiting</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection
