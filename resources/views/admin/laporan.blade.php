@extends('admin.main')

@section('main-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Laporan Keuangan</h1>
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
                            <div class="col-sm-5">
                                <div class="form-group row">
                                    <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="tahun">
                                            <option>2018</option>
                                            <option>2019</option>
                                            <option>2020</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                                    <div class="col-sm-6">
                                        <select class="form-control" id="bulan">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <img class="img-fluid d-block" src="https://dummyimage.com/800x800/000/fff" alt=""
                                    srcset="">
                                <button class="mt-3 btn btn-primary">Cetak</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
@endsection