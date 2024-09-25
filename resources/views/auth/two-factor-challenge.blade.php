@php
    $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Two Steps Verifications Basic - Pages')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection


@section('vendor-script')
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('page-script')
    <script>
        /**
         *  Page auth two steps
         */

        'use strict';

        document.addEventListener('DOMContentLoaded', function(e) {
            (function() {

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

                let maskWrapper = document.querySelector('.numeral-mask-wrapper');

                for (let pin of maskWrapper.children) {
                    pin.onkeyup = function(e) {
                        // While entering value, go to next
                        if (pin.nextElementSibling) {
                            if (this.value.length === parseInt(this.attributes['maxlength'].value)) {
                                pin.nextElementSibling.focus();
                            }
                        }

                        // While deleting entered value, go to previous
                        // Delete using backspace and delete
                        if (pin.previousElementSibling) {
                            if (e.keyCode === 8 || e.keyCode === 46) {
                                pin.previousElementSibling.focus();
                            }
                        }
                    };
                }

                const twoStepsForm = document.querySelector('#twoStepsForm');

                // Form validation for Add new record
                if (twoStepsForm) {
                    const fv = FormValidation.formValidation(twoStepsForm, {
                        fields: {
                            code: {
                                validators: {
                                    notEmpty: {
                                        message: 'Veuillez entrer votre code'
                                    }
                                }
                            }
                        },
                        plugins: {
                            bootstrap: new FormValidation.plugins.Bootstrap5(),
                            trigger: new FormValidation.plugins.Trigger(),
                            submitButton: new FormValidation.plugins.SubmitButton(),
                            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                        }
                    });

                    const numeralMaskList = twoStepsForm.querySelectorAll('.numeral-mask');
                    const keyupHandler = function() {
                        let codeFlag = true,
                            codeVal = '';
                        numeralMaskList.forEach(numeralMaskEl => {
                            if (numeralMaskEl.value === '') {
                                codeFlag = false;
                                twoStepsForm.querySelector('[name="code"]').value = '';
                            }
                            codeVal = codeVal + numeralMaskEl.value;
                        });
                        if (codeFlag) {
                            twoStepsForm.querySelector('[name="code"]').value = codeVal;
                        }
                    };
                    numeralMaskList.forEach(numeralMaskEle => {
                        numeralMaskEle.addEventListener('keyup', keyupHandler);
                    });
                }
            })();
        });
    </script>
@endsection

@section('content')
    <div class="authentication-wrapper authentication-basic px-4">
        <div class="authentication-inner py-4">
            <!--  Two Steps Verification -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-4 mt-2">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros', ['height' => 20, 'withbg' => 'fill: #fff;'])</span>
                            <span
                                class="app-brand-text demo text-body fw-bold ms-1">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1 pt-2">Two Step Verification 💬</h4>
                    <p class="text-start mb-4">
                        We sent a verification code to your mobile. Enter the code from the mobile in the field below.
                    </p>
                    <p class="mb-0 fw-semibold">Type your 6 digit security code</p>

                    <form id="twoStepsForm" method="POST" action="{{ url('/two-factor-challenge') }}">
                      @csrf
                      <div class="row justify-content-center">
                        <div class="mb-2 col-auto">
                          <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                            <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1" autofocus required>
                            <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                            <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask text-center h-px-50 mx-1 my-2" maxlength="1">
                          </div>
                          <!-- Create a hidden field which is combined by 3 fields above -->
                          <input type="hidden" name="code" />
                        </div>
                      </div>
                      <div class="row  justify-content-center mt-4">
                          <div class="col-auto">
                              <button class="btn btn-primary btn-embossed">Verify</button>
                          </div>
                      </div>

            
                  </form>
                </div>
            </div>
            <!-- / Two Steps Verification -->
        </div>
    </div>
@endsection
