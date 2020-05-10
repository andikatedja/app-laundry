@extends('admin.main')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base_url" content="{{url('admin')}}">
@endsection

@section('main-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Saran atau Komplain</h1>
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
                        <h5>Daftar Saran atau Komplain</h5>
                        <div class="row">
                            <div class="col-sm-6">

                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($komplain as $item)
                                        <tr class="bg-warning">
                                            <td>{{$item->nama}}</td>
                                            <td><button href="#" class="badge badge-info lihat-isi"
                                                    data-id="{{$item->id}}">Lihat isi
                                                    komplain</button></td>
                                        </tr>
                                        @endforeach
                                        @foreach ($saran as $item)
                                        <tr>
                                            <td>{{$item->nama}}</td>
                                            <td><button href="#" class="badge badge-info lihat-isi"
                                                    data-id="{{$item->id}}">Lihat isi
                                                    saran</button></td>
                                        </tr>
                                        @endforeach
                                        <hr>
                                        <tr class="text-center">
                                            <td>Jumlah</td>
                                            <td>{{$jumlah}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="isi-aduan">Isi Aduan</label>
                                    <textarea class="form-control" id="isi-aduan" rows="3" disabled></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="balas">Balas</label>
                                    <textarea class="form-control" id="balas" rows="3" disabled></textarea>
                                </div>
                                <button id="btn-kirim-balasan" class="btn btn-primary" data-id="">Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('scripts')
<script src="{{asset('js/ajax-saran.js')}}"></script>
@endsection