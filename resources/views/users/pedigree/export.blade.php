<!-- modal export -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Print Family tree</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <div class="mb-4">
                    <label for="type" class="form-label">Type</label>
                    <select id="type" class="form-select">
                        <option value="png">PNG</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div class="mb-4" id="formatContainer">
                    <label for="format" class="form-label">Format</label>
                    <select id="format" class="form-select">
                        <option value="1">1344 x 839 px</option>
                        <option value="2">2688 x 1678 px</option>
                        <option value="3">4032 x 2517 px</option>
                        <option value="4">5376 x 3356 px</option>
                        <option value="5">6720 x 4195 px</option>
                    </select>
                </div>
                <div class="mb-4" style="display: none;" id="formatPdfContainer">
                    <label for="formatPdf" class="form-label">Format</label>
                    <select id="formatPdf" class="form-select">
                        <option value="a4">A4</option>
                        <option value="a3">A3</option>
                        <option value="a2">A2</option>
                        <option value="a1">A1</option>
                        <option value="a0">A0</option>
                    </select>
                </div>
                <div class="mb-4" style="display: none;"  id="orientationContainer">
                    <label for="orientation" class="form-label">Orientation</label>
                    <select id="orientation" class="form-select">
                        <option value="p">Portrait</option>
                        <option value="l">Landscape</option>
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
