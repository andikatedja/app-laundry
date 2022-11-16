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
        url: route("admin.transactions.show", { transaction: id_transaksi }),
        method: "GET",
        dataType: "json",
        success: function (data) {
            let table = "";
            let j = 1;
            $.each(data.transaction_details, function (i, val) {
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
            $("#service-type").html(data.service_type.name);
            $("#payment-amount").html(data.payment_amount);
        },
    });
});

$(document).on("change", ".select-status", function () {
    let id_transaksi = $(this).data("id");
    if (confirm("Apakah anda yakin mengubah status transaksi ini?")) {
        let val = $(this).val();
        $.ajax({
            url: route("admin.transactions.update", {
                transaction: id_transaksi,
            }),
            data: {
                val: val,
            },
            method: "PATCH",
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
    let year = $(this).val();
    let option = "";
    $.ajax({
        url: route("admin.reports.get-month"),
        data: {
            year: year,
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
