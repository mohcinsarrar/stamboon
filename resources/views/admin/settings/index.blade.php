@extends('layouts/layoutMaster')

@section('title', 'Hero')

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
        <span class="text-muted fw-light">Webshop /</span> Settings
    </h4>
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.settings.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="can_register"  id="can_register" {{ ($data['can_register'] == true ? 'checked' : '')}}>
                    <label class="form-check-label" for="can_register">Can register</label>
                </div>

                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
            </form>
        </div>
    </div>

@endsection
