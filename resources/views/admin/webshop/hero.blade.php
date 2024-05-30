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
        <span class="text-muted fw-light">Webshop /</span> Hero section
    </h4>
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.webshop.hero.update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="enable"  id="enable" {{ ($data['enable'] == true ? 'checked' : '')}}>
                    <label class="form-check-label" for="enable">Enable section</label>
                </div>
                @if ($data['image'] != '')
                    <div class="mb-3">
                        <img src="{{ asset('storage/'.$data['image']) }}" alt="" width="700" class="d-block mx-auto">
                    </div>
                @endif
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input class="form-control" type="file" id="image" name="image">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $data['title'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="subTitle">Sub title</label>
                    <input type="text" class="form-control" id="subTitle" name="subTitle"
                        value="{{ $data['subTitle'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="buttonTitle">Button title</label>
                    <input type="text" class="form-control" id="buttonTitle" name="buttonTitle"
                        value="{{ $data['buttonTitle'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="videoTitle">Video title</label>
                    <input type="text" class="form-control" id="videoTitle" name="videoTitle"
                        value="{{ $data['videoTitle'] }}" required>
                </div>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
            </form>
        </div>
    </div>

@endsection
