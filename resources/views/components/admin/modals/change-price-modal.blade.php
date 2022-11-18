<div class="modal fade" id="changePriceModal" tabindex="-1" role="dialog" aria-labelledby="changePriceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePriceModalLabel">Ubah Harga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.price-lists.update', ['priceList' => 'id']) }}" method="post"
                    id="updatePriceForm">
                    @csrf
                    @method('PATCH')
                    <input id="id-harga-modal" type="hidden" name="id_harga">
                    <div class="form-group">
                        <label for="harga-modal">Harga</label>
                        <input type="number" class="form-control" id="harga-modal" name="price">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Ubah Harga</button>
                </form>
            </div>
        </div>
    </div>
</div>
