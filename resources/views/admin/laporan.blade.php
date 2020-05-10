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
                                <form action="{{url('admin/cetak-laporan')}}" method="post">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="tahun" name="tahun">
                                                @foreach ($tahun as $item)
                                                <option value="{{$item->Tahun}}">{{$item->Tahun}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                                        <div class="col-sm-6">
                                            <select class="form-control" id="bulan" name="bulan">
                                                @foreach ($bulan as $item)
                                                <option value="{{$item->Bulan}}">{{$item->Bulan}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-3 btn btn-primary">Cetak</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
@endsection