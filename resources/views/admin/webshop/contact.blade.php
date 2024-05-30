@extends('layouts/layoutMaster')

@section('title', 'Contact')

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
        <span class="text-muted fw-light">Webshop /</span> Contact section
    </h4>
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.webshop.contact.update')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="enable"  id="enable" {{ ($data['enable'] == true ? 'checked' : '')}}>
                    <label class="form-check-label" for="enable">Enable section</label>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $data['title'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="subTitle">Sub title</label>
                    <textarea type="text" class="form-control" id="subTitle" name="subTitle"
                        required>{{ $data['subTitle'] }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
            </form>
        </div>
    </div>

@endsection
