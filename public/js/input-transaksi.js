let fixTotal = $("#total-harga").val();
let total = $("#total-harga").val();

$("#bayarModal").on("shown.bs.modal", function () {
    $(this).find("#input-bayar").focus();
});

$("#input-bayar").on("keyup", function () {
    $("#kembalian").html($("#input-bayar").val() - total);
});

$("#btn-simpan").on("click", function (event) {
    if (
        parseInt($("#input-bayar").val()) < parseInt(total) ||
        $("#input-bayar").val() == ""
    ) {
        event.preventDefault();
        alert("Pembayaran kurang!");
    }
});

$("#voucher").on("change", function () {
    let potongan = $("option:selected", this).data("potong");
    if (potongan != 0) {
        total = fixTotal;
        total -= potongan;
        total < 0 ? (total = 0) : total;
        $("#total-harga").val(total);
        $("#kembalian").html($("#input-bayar").val() - total);
    } else {
        total = fixTotal;
        $("#total-harga").val(total);
        $("#kembalian").html($("#input-bayar").val() - total);
    }
});
