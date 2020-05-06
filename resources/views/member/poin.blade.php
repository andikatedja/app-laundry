@extends('member.main')

@section('main-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Tukar Poin</h1>
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
                        <h4>Poin Anda : {{$user->poin}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Tukarkan poin anda dengan:</h4>
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Poin</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>10</td>
                                    <td>Diskon 10%</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>100</td>
                                    <td>Gratis Cuci 1 kg</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection