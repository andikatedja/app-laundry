@extends('admin.main')

@section('main-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Input Transaksi</h1>
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
                        <div class="form-group row">
                            <label for="id-member" class="col-sm-2 col-form-label">ID Member</label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="id-member" name="id_member" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="barang" class="col-sm-2 col-form-label">Barang</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="barang" name="barang">
                                    <option value="b">Baju</option>
                                    <option value="c">Celana</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="servis" class="col-sm-2 col-form-label">Servis</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="servis" name="servis">
                                    <option value="c">Cuci</option>
                                    <option value="s">Setrika</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-4">
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="s">Satuan</option>
                                    <option value="k">Kiloan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="banyak" class="col-sm-2 col-form-label">Banyak</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-left-minus btn btn-danger btn-number"
                                            data-type="minus" data-field="">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </span>
                                    <input type="text" id="quantity" name="banyak" class="form-control input-number"
                                        value="1" min="1" max="100">
                                    <span class="input-group-btn">
                                        <button type="button" class="quantity-right-plus btn btn-success btn-number"
                                            data-type="plus" data-field="">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <button id="tambah-transaksi" class="btn btn-primary">Tambah Transaksi</button>
                            </div>
                        </div>

                        <table id="tbl-input-transaksi" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Servis</th>
                                    <th>Kategori</th>
                                    <th>Banyak</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
@endsection

@section('scripts')
<script src="{{asset('js/quantity-increment.js')}}"></script>
<script src="{{asset('js/input-transaksi.js')}}"></script>
@endsection