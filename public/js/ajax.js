const base_url = $('meta[name="base_url"]').attr("content");
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$(document).on("click", ".btn-detail", function () {
    let id_transaksi = $(this).data("id");
    $("#id-transaksi-detail").html(id_transaksi);

    $.ajax({
        url: base_url + "/ambil-detail-transaksi",
        data: {
            id_transaksi: id_transaksi,
        },
        method: "POST",
        dataType: "json",
        success: function (data) {
            let table = "";
            let j = 1;
            $.each(data, function (i, val) {
                //console.log(data);
                table +=
                    "<tr>" +
                    "<td>" +
                    j++ +
                    "</td>" +
                    "<td>" +
                    val.price_list.item.name +
                    "</td>" +
                    "<td>" +
                    val.price_list.service.name +
                    "</td>" +
                    "<td>" +
                    val.price_list.category.name +
                    "</td>" +
                    "<td>" +
                    val.quantity +
                    "</td>" +
                    "<td>" +
                    val.price +
                    "</td>" +
                    "<td>" +
                    val.sub_total +
                    "</td>" +
                    "</tr>";
            });
            $("#tbl-ajax").html(table);
            $("#service-type").html(data[0].transaction.service_type.name);
        },
    });
});

$(document).on("change", ".select-status", function () {
    let id_transaksi = $(this).data("id");
    if (confirm("Apakah anda yakin mengubah status transaksi ini?")) {
        let val = $(this).val();
        $.ajax({
            url: base_url + "/ubah-status-transaksi",
            data: {
                id_transaksi: id_transaksi,
                val: val,
            },
            method: "POST",
            success: function (data) {
                location.reload();
            },
        });
    } else {
        $(this).val($(this).data("val"));
        return;
    }
});

$(document).on("change", "#tahun", function () {
    let tahun = $(this).val();
    let option = "";
    $.ajax({
        url: base_url + "/get-month",
        data: {
            tahun: tahun,
        },
        method: "POST",
        dataType: "json",
        success: function (data) {
            $.each(data, function (i, val) {
                option +=
                    '<option value="' +
                    val.Bulan +
                    '">' +
                    val.Bulan +
                    "</option>";
            });
            $("#bulan").html(option);
            $("#btn-cetak").removeClass("d-none");
        },
    });
});
