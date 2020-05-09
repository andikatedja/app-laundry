const base_url = $('meta[name="base_url"]').attr('content');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
});
$(document).on('click', '.btn-detail', function () {
    let id_transaksi = $(this).data('id');
    $('#id-transaksi-detail').html(id_transaksi);

    $.ajax({
        url: base_url + '/ambil-detail-transaksi',
        data: {
            id_transaksi: id_transaksi,
        },
        method: 'POST',
        dataType: 'json',
        success: function (data) {
            let table = '';
            let j = 1;
            $.each(data, function (i, val) {
                //console.log(data);
                table +=
                    '<tr>' +
                    '<td>' + j++ + '</td>' +
                    '<td>' + val.nama_barang + '</td>' +
                    '<td>' + val.nama_servis + '</td>' +
                    '<td>' + val.nama_kategori + '</td>' +
                    '<td>' + val.banyak + '</td>' +
                    '<td>' + val.sub_total + '</td>' +
                    '</tr>'
            });
            $('#tbl-ajax').html(table);
        },
    });
});

$(document).on('change', '.select-status', function () {
    let id_transaksi = $(this).data('id');
    if (confirm('Apakah anda yakin menyelesaikan transaksi ini?')) {
        $.ajax({
            url: base_url + '/ubah-status-transaksi',
            data: {
                id_transaksi: id_transaksi,
            },
            method: 'POST',
            success: function (data) {
                location.reload();
            },
        });
    } else {
        $(this).val('1');
        return;
    }
});
