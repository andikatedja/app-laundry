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
    if (potongan != 0 && tempPotongan == 0) {
        potongHargaVoucher(potongan);
    } else if (potongan != 0 && tempPotongan != 0) {
        fixTotal += tempPotongan;
        potongHargaVoucher(potongan);
    } else {
        fixTotal += tempPotongan;
        tempPotongan = potongan;
        $("#total-harga").val(fixTotal);
    }
});

$("#service-type").on("change", function () {
    let cost = $("option:selected", this).data("type-cost");
    if (cost != 0) {
        tempCost = cost;
        let total = fixTotal;
        total += cost;
        fixTotal = total;
        $("#total-harga").val(fixTotal);
    } else {
        fixTotal -= tempCost;
        tempCost = cost;
        $("#total-harga").val(fixTotal);
    }
});

function potongHargaVoucher(potongan) {
    tempPotongan = potongan;
    let total = fixTotal;
    total -= potongan;
    total < 0 ? (total = 0) : total;
    fixTotal = total;
    $("#total-harga").val(fixTotal);
}
