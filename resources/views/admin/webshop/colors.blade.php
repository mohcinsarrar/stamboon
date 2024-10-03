@extends('layouts/layoutMaster')

@section('title', 'Colors & Logo')

@section('vendor-style')
@endsection


@section('vendor-script')
@endsection

@section('page-script')

@endsection

@section('page-style')

@endsection

@section('content')

    <!-- Users List Table -->
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Webshop /</span> Colors & Logo
    </h4>
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.webshop.colors.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
            </form>
        </div>
    </div>

@endsection
