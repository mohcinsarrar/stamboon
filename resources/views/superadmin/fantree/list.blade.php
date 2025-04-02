@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Fantree')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables/datatables.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

@endsection

@section('page-style')

@endsection


@section('title', 'Pedigree')

@section('vendor-script')

    <script src="{{ asset('assets/vendor/libs/jqueryui/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jspdf/jspdf.umd.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/datatables/datatables.min.js') }}"></script>



@endsection

@section('page-script')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        window.Helpers.initCustomOptionCheck();
    </script>
@endsection


@section('content')


    <div class="card">
        <h4 class="m-4 ">All Fancharts</h4>
        <div class="card-datatable table-responsive p-3">
            {{ $dataTable->table(['class' => 'table-striped table nowrap']) }}
        </div>
    </div>

@endsection
