const base_url = $('meta[name="base_url"]').attr("content");
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).on("click", ".btn-ubah-harga", function () {
    let id_harga = $(this).data("id");
    $("#id-harga-modal").val(id_harga);
    $.ajax({
        url: base_url + "/ambil-harga",
        data: {
            id_harga: id_harga,
        },
        method: "POST",
        dataType: "json",
        success: function (data) {
            $("#harga-modal").val(data.price);
        },
    });
});

$(document).on("click", ".btn-update-cost", function () {
    const id_cost = $(this).data("id");
    $("#id-cost-modal").val(id_cost);
    $.ajax({
        url: base_url + "/get-service-type",
        data: {
            id_cost: id_cost,
        },
        method: "GET",
        dataType: "json",
        success: function (data) {
            $("#cost-modal").val(data.cost);
        },
    });
});
