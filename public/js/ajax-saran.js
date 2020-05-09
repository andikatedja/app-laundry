const base_url = $('meta[name="base_url"]').attr('content');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
});

$(document).on('click', '.lihat-isi', function () {
    let id = $(this).data('id');
    $.ajax({
        url: base_url + '/ambil-sarankomplain',
        data: {
            id: id,
        },
        method: 'POST',
        dataType: 'json',
        success: function (data) {
            $('#isi-aduan').html(data[0].isi);
        },
    });
});
