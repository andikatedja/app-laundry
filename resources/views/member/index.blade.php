@extends('member.main')

@section('main-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard Member</h1>
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
                        <div class="row">
                            <div class="col-10">
                                <img class="img-circle img-fit float-left" width="100" height="100"
                                    src="{{asset('img/profile') . '/' . $user->profil}}" alt="Foto Profil">
                                <div class="member-content">
                                    <h2 class="m-0">{{$user->nama}}</h2>
                                    <p class="small m-0">ID Member: {{$user->id_user}}</p>
                                    <a href="{{url('member/edit')}}" class="badge badge-primary badge-pill">Edit
                                        Profil</a>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <p class="small m-0">Poin</p>
                                <h2 class="m-0">{{$user->poin}}</h2>
                                <a href="{{url('member/poin')}}" class="badge badge-primary badge-pill">Tukar Poin</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="my-3 text-center">Transaksi Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>24 Januari 2020</td>
                                    <td><span class="text-success">Selesai</span></td>
                                    <td>
                                        <a href="#" class="badge badge-primary">Lihat Detail ></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>28 Januari 2020</td>
                                    <td><span class="text-warning">Dalam Pengerjaan</span></td>
                                    <td>
                                        <a href="#" class="badge badge-primary">Lihat Detail ></a>
                                    </td>
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