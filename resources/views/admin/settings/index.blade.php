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
    <!--
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="my-3">Add country</h4>
            <form action="{{route('admin.settings.add_country')}}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label class="form-label" for="countries_list">Countries List</label>
                        <select type="text" class="form-control" id="countries_list">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="add-country-name">Name<span class="text-danger ps-1">*</span></label>
                        <input type="text" class="form-control" id="add-country-name" name="name" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="add-country-file">Upload countries<span
                                class="text-danger ps-1">*</span></label>
                        <input type="file" class="form-control" id="add-country-file" name="file" />
                    </div>

                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
            </form>
        </div>
    </div>
-->
@endsection
