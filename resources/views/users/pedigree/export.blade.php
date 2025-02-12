<!-- modal export -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="exportModalSpinner"
                class="d-none position-absolute top-0 start-0 end-0 bottom-0 w-100 h-100 justify-content-center align-items-center"
                style="z-index: 1000">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Print Family tree</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="row" id="suggested_size">
                    <div class="col">
                        <div class="alert alert-info msg" role="alert"></div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="col-md p-6">
                        <div class="form-check form-check-inline mt-4">
                            <input class="form-check-input" type="checkbox" id="include_note" value="1">
                            <label class="form-check-label" for="include_note">Include Notes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="include_weapon" value="1">
                            <label class="form-check-label" for="include_weapon">Include Weapon</label>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" class="form-select">
                        @foreach ($print_types as $type)
                            <option value="{{ $type }}">{{ Str::upper($type) }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- 
                <div class="mb-4" id="formatContainer">
                    <label for="format" class="form-label">Format</label>
                    <select id="format" class="form-select">
                        @foreach ($selected_output_png as $key => $output_png)
                            <option value="{{ $key }}">{{ $output_png }}</option>
                        @endforeach
                    </select>
                </div>
                --}}
                {{-- 
                <div class="mb-4" style="display: none;" id="formatPdfContainer">
                    <label for="formatPdf" class="form-label">Format</label>
                    <select id="formatPdf" class="form-select">
                        @foreach ($selected_output_pdf as $key => $output_pdf)
                            <option value="{{ $key }}">{{ Str::upper($output_pdf) }}</option>
                        @endforeach
                    </select>
                </div>
                --}}
                <div class="mb-4" style="display: none;" id="orientationContainer">
                    <label for="orientation" class="form-label">Orientation</label>
                    <select id="orientation" class="form-select">
                        <option value="p">Portrait</option>
                        <option value="l" selected>Landscape</option>
                    </select>
                </div>
                <div class="row mt-4 justify-content-end">
                    <div class="col-auto">
                        <button type="button" id="exportBtn" class="btn btn-primary">
                            <span class="ti-xs ti ti-printer me-1"></span>
                            Print
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
