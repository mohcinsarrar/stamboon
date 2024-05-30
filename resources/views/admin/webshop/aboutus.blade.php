@extends('layouts/layoutMaster')

@section('title', 'About Us')

@section('vendor-style')
@endsection


@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
@endsection

@section('page-script')
<script>
    forUpdate = $('.updateUboutus')
    formRepeater = $('.form-repeater');
    if (formRepeater.length) {
    var row = 2;
    var col = 1;
    forUpdate.on('submit', function (e) {
      e.preventDefault();
    });
    formRepeater.repeater({
        isFirstItemUndeletable: true,
      show: function () {
        var fromControl = $(this).find('.form-control, .form-select');
        var formLabel = $(this).find('.form-label');

        fromControl.each(function (i) {
          var id = 'form-repeater-' + row + '-' + col;
          $(fromControl[i]).attr('id', id);
          $(formLabel[i]).attr('for', id);
          col++;
        });

        row++;

        $(this).slideDown();
      },
      hide: function (e) {
        confirm('Are you sure you want to delete this element?') && $(this).slideUp(e);
      }
    });
  }
  $('#submitButton').on('click', function() {
                // Validate the form
                const form = document.getElementById('updateUboutus');
                if (form.checkValidity()) {
                    // If the form is valid, submit it
                    $('#updateUboutus').off('submit').submit();
                } else {
                    // If the form is invalid, trigger HTML5 validation
                    form.reportValidity();
                }
            });
</script>
@endsection

@section('page-style')

@endsection

@section('content')

    <!-- Users List Table -->
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Webshop /</span> About us section
    </h4>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.webshop.aboutus.update') }}" method="POST" enctype="multipart/form-data" class="updateUboutus" id="updateUboutus">
                @csrf

                <div class="form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="enable"  id="enable" {{ ($data['enable'] == true ? 'checked' : '')}}>
                    <label class="form-check-label" for="enable">Enable section</label>
                </div>
                
                @if ($data['image'] != '')
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $data['image']) }}" alt="" width="700"
                            class="d-block mx-auto">
                    </div>
                @endif
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input class="form-control" type="file" id="image" name="image">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $data['title'] }}"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="subTitle">Sub title</label>
                    <input type="text" class="form-control" id="subTitle" name="subTitle"
                        value="{{ $data['subTitle'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="content">Paragraphs</label>
                </div>
                <div class="mb-3 form-repeater">
                    <div data-repeater-list="paragraphs">
                        @foreach($data['paragraphs'] as $key => $paragraph)
                        <div data-repeater-item>
                            
                            <div class="row">
                                <div class="mb-3 col-lg-6 col-xl-4 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-{{$key+1}}-1">Title</label>
                                    <input type="text" id="form-repeater-{{$key+1}}-1" class="form-control" name="title" value="{{$paragraph['title']}}" required>
                                </div>
                                <div class="mb-3 col-lg-6 col-xl-4 col-12 mb-0">
                                    <label class="form-label" for="form-repeater-{{$key+1}}-2">Content</label>
                                    <textarea rows="8" type="text" id="form-repeater-{{$key+1}}-2" class="form-control" name="content" required>{{$paragraph['content']}}</textarea>
                                </div>

                                <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                    <button class="btn btn-label-danger mt-4" data-repeater-delete>
                                        <i class="ti ti-x ti-xs me-1"></i>
                                        <span class="align-middle">Delete</span>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                        @endforeach
                    </div>
                    <div class="mb-0">
                        <button class="btn btn-primary" data-repeater-create>
                            <i class="ti ti-plus me-1"></i>
                            <span class="align-middle">Add paragraph</span>
                        </button>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <button type="submit" id="submitButton" class="col-auto btn btn-primary waves-effect waves-light">Save</button>

                </div>
            </form>
        </div>
    </div>

@endsection
