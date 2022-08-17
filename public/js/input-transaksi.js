const subTotal = parseInt($("#sub-total").val());
let fixTotal = subTotal;
let tempPotongan = 0;
let tempCost = 0;
// let total = subTotal;

$("#bayarModal").on("shown.bs.modal", function () {
    $(this).find("#input-bayar").focus();
});

$("#input-bayar").on("keyup", function () {
    $("#kembalian").html($("#input-bayar").val() - fixTotal);
});

$("#btn-simpan").on("click", function (event) {
    if (
        parseInt($("#input-bayar").val()) < parseInt(fixTotal) ||
        $("#input-bayar").val() == ""
    ) {
        event.preventDefault();
        alert("Pembayaran kurang!");
    }
});

$("#voucher").on("change", function () {
    let potongan = $("option:selected", this).data("potong");
    tempPotongan = potongan;
    fixTotal = subTotal - tempPotongan + tempCost;
    fixTotal < 0 ? (fixTotal = 0) : fixTotal;
    $("#total-harga").val(fixTotal);
    $("#kembalian").html($("#input-bayar").val() - fixTotal);
});

$("#service-type").on("change", function () {
    let cost = $("option:selected", this).data("type-cost");
    tempCost = cost;
    fixTotal = subTotal + tempCost - tempPotongan;
    fixTotal < 0 ? (fixTotal = 0) : fixTotal;
    $("#total-harga").val(fixTotal);
    $("#kembalian").html($("#input-bayar").val() - fixTotal);
});
