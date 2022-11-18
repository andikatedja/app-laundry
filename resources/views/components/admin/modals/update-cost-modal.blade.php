<div class="modal fade" id="updateCostModal" tabindex="-1" role="dialog" aria-labelledby="updateCostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateCostModalLabel">Ubah Biaya Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="serviceTypeForm" action="{{ route('admin.service-types.update', ['serviceType' => 'id']) }}"
                    method="POST">
                    @method('PATCH')
                    @csrf
                    <input id="id-cost-modal" type="hidden" name="id_cost">
                    <div class="form-group">
                        <label for="cost-modal">Biaya</label>
                        <input type="number" class="form-control" id="cost-modal" name="cost">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Ubah Biaya</button>
                </form>
            </div>
        </div>
    </div>
</div>
