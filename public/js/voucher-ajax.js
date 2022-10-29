const base_url = $('meta[name="base_url"]').attr("content");
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).on("click", ".aktif-check", function () {
    let id = $(this).val();
    $.ajax({
        url: route("admin.vouchers.update", { voucher: id }),
        method: "PATCH",
        success: function () {
            alert("Status aktif berhasil diubah!");
        },
    });
});
