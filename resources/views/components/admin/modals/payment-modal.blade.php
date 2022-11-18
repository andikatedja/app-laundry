@if ($show)
    @props(['serviceTypes', 'vouchers', 'totalPrice', 'show' => false])

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Bayar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.transactions.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sub-total">Sub Total</label>
                            <input type="number" class="form-control form-control-lg" id="sub-total"
                                value="{{ isset($totalPrice) ? $totalPrice : '0' }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="service-type">Tipe Servis</label>
                            <select name="service-type" class="form-control form-control-lg" id="service-type" required>
                                <option value="" selected hidden disabled>Pilih tipe service</option>
                                @foreach ($serviceTypes as $type)
                                    <option value="{{ $type->id }}" data-type-cost="{{ $type->cost }}">
                                        {{ $type->name }} ({{ $type->getFormattedCost() }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="voucher">Voucher</label>
                            <select name="voucher" class="form-control form-control-lg" id="voucher">
                                @if (isset($vouchers) && !blank($vouchers))
                                    <option value="0" data-potong="0">Pilih voucher</option>
                                    @foreach ($vouchers as $voucher)
                                        <option value="{{ $voucher->id }}"
                                            data-potong="{{ $voucher->voucher->discount_value }}">
                                            {{ $voucher->voucher->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="0" data-potong="0">Tidak ada voucher yang dimiliki</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="total-harga">Harga Yang Dibayar</label>
                            <input type="number" class="form-control form-control-lg" id="total-harga"
                                value="{{ isset($totalPrice) ? $totalPrice : '0' }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="input-bayar">Bayar</label>
                            <input type="number" class="form-control form-control-lg" id="input-bayar"
                                name="payment-amount">
                        </div>
                        <h4>Kembalian : <span id="kembalian"></span></h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-simpan" type="button" class="btn btn-primary">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script src="{{ asset('js/quantity-increment.js') }}"></script>
        <script src="{{ asset('js/input-transaksi.js') }}"></script>
    @endpush
@endif
