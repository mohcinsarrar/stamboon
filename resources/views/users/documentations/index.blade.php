@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Documentations')

@section('vendor-style')

@endsection

@section('vendor-script')

@endsection

@section('page-script')


@endsection

@section('page-style')

    <style>

        .documents .product-file-type .img-icon {
            width: 46px;
            height: 46px;
            line-height: 46px;
            font-weight: 600;
            border-radius: 50%;
            font-size: 18px;
            text-align: center;
        }

        /* -- Text Color -- */
        .documents .text-white {
            color: #ffffff !important;
        }

        .documents .text-black {
            color: #282828 !important;
        }

        .documents .text-muted {
            color: #8A98AC !important;
        }

        .documents .text-primary {
            color: #6e81dc !important;
        }

        .documents .text-secondary {
            color: #718093 !important;
        }

        .documents .text-success {
            color: #5fc27e !important;
        }

        .documents .text-danger {
            color: #f44455 !important;
        }

        .documents .text-warning {
            color: #fcc100 !important;
        }

        .documents .text-info {
            color: #72d0fb !important;
        }

        .documents .text-light {
            color: #dcdde1 !important;
        }

        .documents .text-dark {
            color: #2d3646 !important;
        }


        .documents .primary-rgba {
            background-color: rgba(110, 129, 220, 0.1);
        }

        .documents .secondary-rgba {
            background-color: rgba(113, 128, 147, 0.1);
        }

        .documents .success-rgba {
            background-color: rgba(95, 194, 126, 0.1);
        }

        .documents .danger-rgba {
            background-color: rgba(244, 68, 85, 0.1);
        }

        .documents .warning-rgba {
            background-color: rgba(252, 193, 0, 0.1);
        }

        .documents .info-rgba {
            background-color: rgba(114, 208, 251, 0.1);
        }

        .documents .light-rgba {
            background-color: rgba(220, 221, 225, 0.1);
        }

        .documents .dark-rgba {
            background-color: rgba(45, 54, 70, 0.1);
        }

        .documents .card-header:first-child {
            border-radius: calc(5px - 1px) calc(5px - 1px) 0 0;
            padding: 15px 20px;
        }

        .documents .card-header:first-child {
            border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
        }

        .documents .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background-color: transparent;
        }

        .documents .card {
            border: none;
            border-radius: 3px;
            background-color: #ffffff;
        }

        .documents .m-b-30 {
            margin-bottom: 30px;
        }


    </style>
@endsection
@section('content')
    <div class="row justify-content-between my-4">
        <div class="col-auto">
            <h4 class="fw-bold mb-0">
                <span class="text-muted fw-light"></span><i class="ti ti-file-description h2 mb-1"></i> Documentations
            </h4>
        </div>
    </div>
    <div class="row documents">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="card-title mb-0">Important Files</h5>
                        </div>
                        <div class="col-4">
                            <ul class="list-inline-group text-right mb-1 pl-0">
                                <li class="list-inline-item mr-0 font-12"><i
                                        class="feather icon-more-vertical- font-20 text-primary"></i></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="product-file-type">
                        @foreach($documents as $document)
                        <div class="row py-4 border-bottom">
                            <div class="col-auto">
                                <span class="mr-3 align-self-center img-icon primary-rgba text-primary d-block">.{{pathinfo($document['file'], PATHINFO_EXTENSION)}}</span>
                            </div>
                            <div class="col">
                                <div class="media-body">
                                                <a href="{{ asset('storage/'.$document['file']) }}" download>
                                                    <h5 class="font-16 mb-1">{{$document['title']}}</h5>
                                                </a>
                                    
                                            @php
                                                if (\Illuminate\Support\Facades\Storage::exists($document['file'])) {
                                                    $fileSizeInBytes = \Illuminate\Support\Facades\Storage::size($document['file']);
                                                    
                                                    if ($fileSizeInBytes < 1024) {
                                                        // Size in Bytes
                                                        $fileSize = $fileSizeInBytes . ' Bytes';
                                                    } elseif ($fileSizeInBytes < 1048576) {
                                                        // Size in KB
                                                        $fileSizeInKb = $fileSizeInBytes / 1024;
                                                        $fileSize = number_format($fileSizeInKb, 2) . ' KB';
                                                    } else {
                                                        // Size in MB
                                                        $fileSizeInMb = $fileSizeInBytes / 1048576;
                                                        $fileSize = number_format($fileSizeInMb, 2) . ' MB';
                                                    }

                                                } else {
                                                    $fileSize == "";
                                                }
                                            @endphp
                                    <p>.{{pathinfo($document['file'], PATHINFO_EXTENSION)}}, {{$fileSize}}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
