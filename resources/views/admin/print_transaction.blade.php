<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Transaksi</title>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.min.css') }}">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mt-5">
                <h4>{{ config('app.name') }}</h4>
                <h5>Bukti Transaksi</h5>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-6">
                <p>No Transaksi: {{ $transaction->id }}</p>
            </div>
            <div class="col-6 text-right">
                <p>{{ date('d F Y', strtotime($transaction->created_at)) }}</p>
                <p>{{ $transaction->member->name }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead class="">
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Servis</th>
                            <th>Kategori</th>
                            <th>Banyak</th>
                            <th>Harga</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $tot = 0;
                        @endphp

                        @foreach ($transaction->transaction_details as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->price_list->item->name }}</td>
                                <td>{{ $item->price_list->service->name }}</td>
                                <td>{{ $item->price_list->category->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->getFormattedPrice() }}</td>
                                <td>{{ $item->getFormattedSubTotal() }}</td>
                            </tr>
                            @php
                                $tot += $item->sub_total;
                            @endphp
                        @endforeach

                        <tr>
                            <td colspan="6" class="text-center"><b>Sub Total Harga</b></td>
                            <td>{{ 'Rp ' . number_format($tot, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center"><b>{{ $transaction->service_type->name }}</b></td>
                            <td>{{ $transaction->getFormattedServiceCost() }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center"><b>Potongan</b></td>
                            <td>- {{ $transaction->discount }}</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center"><b>Total</b></td>
                            <td><b>{{ $transaction->getFormattedTotal() }}</b></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center"><b>Dibayar</b></td>
                            <td><b>{{ $transaction->getFormattedPaymentAmount() }}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4 text-center">
                <p>Badung, {{ date('d F Y') }}</p>
                <br>
                <br>
                <br>
                <p>{{ $transaction->admin->name }}</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4 text-center">
                <p>Badung, {{ date('d F Y') }}</p>
                <br>
                <br>
                <br>
                <p>{{ $transaction->member->name }}</p>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>
