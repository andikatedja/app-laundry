@extends('member.main')

@section('main-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Daftar Harga</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="paket-tab" data-toggle="tab" href="#paket" role="tab"
                                    aria-controls="paket" aria-selected="true">Paket</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="satuan-tab" data-toggle="tab" href="#satuan" role="tab"
                                    aria-controls="satuan" aria-selected="false">Satuan</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="paket" role="tabpanel"
                                aria-labelledby="paket-tab">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Paket</th>
                                            <th>Keterangan</th>
                                            <th>Servis</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Paket Cuci 1</td>
                                            <td>5 Baju, 2 Celana</td>
                                            <td>Cuci</td>
                                            <td>10.000</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Paket Cuci 2</td>
                                            <td>2 Baju, 5 Celana</td>
                                            <td>Cuci</td>
                                            <td>15.000</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="satuan" role="tabpanel" aria-labelledby="satuan-tab">
                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Satuan</th>
                                            <th>Servis</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Cuci Baju</td>
                                            <td>Cuci</td>
                                            <td>2.000</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Cuci Celana</td>
                                            <td>Cuci</td>
                                            <td>2.500</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection