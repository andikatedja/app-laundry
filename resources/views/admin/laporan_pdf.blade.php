<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Keuangan</title>
    <link rel="stylesheet" href="{{public_path('vendor/adminlte/css/adminlte.min.css')}}">
</head>

<body>

    <center>
        <h3>Laporan Keuangan</h3>
        <h4>{{config('app.name')}}</h4>
        <h5>Bulan {{$bulan}} Tahun {{$tahun}}</h5>
    </center>
    <hr>

    <p>Total Pendapatan: Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>

</body>

</html>