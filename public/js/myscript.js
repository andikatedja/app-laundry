function previewImage() {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(document.getElementById("foto_profil").files[0]);

    oFReader.onload = function (oFREvent) {
        document.getElementById("profil_preview").src = oFREvent.target.result;
    };
};
