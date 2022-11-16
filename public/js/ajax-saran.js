const base_url = $('meta[name="base_url"]').attr("content");
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).on("click", ".lihat-isi", function () {
    let id = $(this).data("id");
    $("#btn-kirim-balasan").data("id", id);
    $("#btn-hapus-aduan").data("id", id);
    $.ajax({
        url: route("admin.complaint-suggestions.show", {
            complaintSuggestion: id,
        }),
        method: "GET",
        dataType: "json",
        success: function (data) {
            $("#isi-aduan").html(data.body);
            $("#balas").prop("disabled", false);
            $("#balas").val("");
        },
    });
});

$(document).on("click", "#btn-kirim-balasan", function () {
    let id = $(this).data("id");
    if (id != "") {
        let reply = $("#balas").val();
        $.ajax({
            url: route("admin.complaint-suggestions.update", {
                complaintSuggestion: id,
            }),
            data: {
                reply: reply,
            },
            method: "PATCH",
            success: function () {
                alert("Balasan berhasil dikirim");
                location.reload();
            },
        });
    }
});
