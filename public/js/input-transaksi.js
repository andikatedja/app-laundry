let total = $('#total-harga').html();

$('#tambah-transaksi').on('click', function () {
    console.log('ok');
});

$('#bayarModal').on('shown.bs.modal', function () {
    $(this).find('#input-bayar').focus();
});

$('#input-bayar').on('keyup', function () {
    $('#kembalian').html($('#input-bayar').val() - total);
});

$('#btn-simpan').on('click', function (event) {
    if ($('#input-bayar').val() < total) {
        event.preventDefault();
        alert('Pembayaran kurang!');
    }
});
