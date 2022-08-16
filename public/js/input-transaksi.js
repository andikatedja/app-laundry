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
    fixTotal += tempPotongan;
    tempPotongan = potongan;
    let total = fixTotal;
    total -= potongan;
    total < 0 ? (total = 0) : total;
    fixTotal = total;
    $("#total-harga").val(fixTotal);
    $("#kembalian").html($("#input-bayar").val() - fixTotal);
});

$("#service-type").on("change", function () {
    let cost = $("option:selected", this).data("type-cost");
    fixTotal -= tempCost;
    tempCost = cost;
    let total = fixTotal;
    total += cost;
    fixTotal = total;
    $("#total-harga").val(fixTotal);
    $("#kembalian").html($("#input-bayar").val() - fixTotal);
});
