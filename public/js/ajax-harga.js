const base_url = $('meta[name="base_url"]').attr("content");
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).on("click", ".btn-ubah-harga", function () {
    let id_harga = $(this).data("id");

    $("#updatePriceForm").attr(
        "action",
        route("admin.price-lists.update", { priceList: id_harga })
    );

    $.ajax({
        url: route("admin.price-lists.show", { priceList: id_harga }),
        method: "GET",
        dataType: "json",
        success: function (data) {
            $("#harga-modal").val(data.price);
        },
    });
});

$(document).on("click", ".btn-update-cost", function () {
    const serviceTypeId = $(this).data("id");

    $("#serviceTypeForm").attr(
        "action",
        route("admin.service-types.show", {
            serviceType: serviceTypeId,
        })
    );

    $.ajax({
        url: route("admin.service-types.show", {
            serviceType: serviceTypeId,
        }),
        method: "GET",
        dataType: "json",
        success: function (data) {
            $("#cost-modal").val(data.cost);
        },
    });
});
