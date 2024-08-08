@if ($message = Session::get('success'))
    <div class="bs-toast toast toast-flash-message toast-ex animate__animated my-2" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
        <div class="toast-header  bg-success text-white">
            <div class="icon"><i class="ti ti-bell ti-xs me-2"></i></div>
            <div class="me-auto fw-semibold title">Success :</div>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ $message }}
        </div>
    </div>
@endif


@if ($message = Session::get('error'))
    <div class="bs-toast toast toast-flash-message  toast-ex animate__animated my-2" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
        <div class="toast-header  bg-danger text-white">
            <div class="icon"><i class="ti ti-bell ti-xs me-2"></i></div>
            <div class="me-auto fw-semibold title">Error :</div>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ $message }}
        </div>
    </div>
@endif


@if ($message = Session::get('warning'))
    <div class="bs-toast toast toast-flash-message toast-ex animate__animated my-2" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
        <div class="toast-header  bg-warning text-white">
            <div class="icon"><i class="ti ti-bell ti-xs me-2"></i></div>
            <div class="me-auto fw-semibold title">Alert :</div>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ $message }}
        </div>
    </div>
@endif


@if ($message = Session::get('info'))
    <div class="bs-toast toast toast-flash-message toast-ex animate__animated my-2" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
        <div class="toast-header  bg-info text-white">
            <div class="icon"><i class="ti ti-bell ti-xs me-2"></i></div>
            <div class="me-auto fw-semibold title">Info :</div>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ $message }}
        </div>
    </div>
@endif


@if ($errors->any())
    <div class="bs-toast toast toast-flash-message toast-ex animate__animated my-2" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="5000" data-bs-autohide="true">
        <div class="toast-header  bg-danger text-white">
            <div class="icon"><i class="ti ti-bell ti-xs me-2"></i></div>
            <div class="me-auto fw-semibold title">Error :</div>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
