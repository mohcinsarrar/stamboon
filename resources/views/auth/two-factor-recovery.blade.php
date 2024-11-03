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


                const twoStepsForm = document.querySelector('#twoStepsForm');

                // Form validation for Add new record
                if (twoStepsForm) {
                    const fv = FormValidation.formValidation(twoStepsForm, {
                        fields: {
                            recovery_code: {
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
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1 pt-2">Two Step Verification 💬</h4>
                    <p class="mb-0 fw-semibold">Tape your recovery code</p>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="twoStepsForm" method="POST" action="{{ url('/two-factor-challenge') }}">
                      @csrf
                      <div class="row justify-content-center mt-4">
                        <div class="mb-2 col">
                          <!-- Create a hidden field which is combined by 3 fields above -->
                          <input type="text" name="recovery_code" class="form-control" id="defaultFormControlInput" aria-describedby="defaultFormControlHelp">
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
