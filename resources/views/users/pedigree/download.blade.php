<!-- modal settings -->
<div class="modal fade" id="downloadPedigree" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel1">Download your FamilyTree</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <form action="{{ route('users.pedigree.download') }}" method="POST">
                    @csrf
                    <div class="row mt-4 justify-content-end">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <span class="ti-xs ti ti-download me-1"></span>
                                Download
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
