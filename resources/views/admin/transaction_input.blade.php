@extends('admin.main')

@section('css')
<link href="{{asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

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
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{url('admin/tambah-transaksi')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="id-member" class="col-sm-2 col-form-label">ID Member</label>
                                <div class="col-sm-2">
                                    <input type="number" min="1" class="form-control" id="id-member" name="member-id"
                                        @if (isset($memberIdSessionTransaction)) value="{{$memberIdSessionTransaction}}"
                                        disabled title="Harap selesaikan transaksi yang ada untuk mengganti id member"
                                        @endif required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="barang" class="col-sm-2 col-form-label">Barang</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="barang" name="item">
                                        @foreach ($items as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="servis" class="col-sm-2 col-form-label">Servis</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="servis" name="service">
                                        @foreach ($services as $service)
                                        <option value="{{$service->id}}">{{$service->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="kategori" name="category">
                                        @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
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
                                        <input type="text" id="quantity" name="quantity"
                                            class="form-control input-number" value="1" min="1" max="100">
                                        <span class="input-group-btn">
                                            <button type="button" class="quantity-right-plus btn btn-success btn-number"
                                                data-type="plus" data-field="">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button type="submit" id="tambah-transaksi" class="btn btn-primary">Tambah
                                        Transaksi</button>
                                </div>
                            </div>
                        </form>
                        <table id="tbl-input-transaksi" class="table mt-2 dt-responsive nowrap" style="width: 100%">
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
                                @if (isset($sessionTransaction))
                                @foreach ($sessionTransaction as $transaction)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$transaction['itemName']}}</td>
                                    <td>{{$transaction['serviceName']}}</td>
                                    <td>{{$transaction['categoryName']}}</td>
                                    <td>{{$transaction['quantity']}}</td>
                                    <td>{{$transaction['subTotal']}}</td>
                                    <td>
                                        <a href="{{url('admin/hapus-transaksi') . '/' . $transaction['rowId']}}"
                                            class="btn btn-danger">Hapus</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if (isset($sessionTransaction))
                        <button id="btn-bayar" class="btn btn-success" data-toggle="modal"
                            data-target="#bayarModal">Bayar</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>

<!-- Modal -->
<div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bayarModalLabel">Bayar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('admin/simpan-transaksi')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sub-total">Sub Total</label>
                        <input type="number" class="form-control form-control-lg" id="sub-total"
                            value="{{isset($totalPrice) ? $totalPrice : '0'}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="service-type">Tipe Servis</label>
                        <select name="service-type" class="form-control form-control-lg" id="service-type" required>
                            <option value="" selected hidden disabled>Pilih tipe service</option>
                            @foreach ($serviceTypes as $type)
                            <option value="{{$type->id}}" data-type-cost="{{$type->cost}}">
                                {{$type->name}} ({{$type->cost}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="voucher">Voucher</label>
                        <select name="voucher" class="form-control form-control-lg" id="voucher">
                            @if (isset($vouchers) && $vouchers != [])
                            <option value="0" data-potong="0">Pilih voucher</option>
                            @foreach ($vouchers as $voucher)
                            <option value="{{$voucher->id}}" data-potong="{{$voucher->voucher->discount_value}}">
                                {{$voucher->voucher->name}}</option>
                            @endforeach
                            @else
                            <option value="0" data-potong="0">Tidak ada voucher yang dimiliki</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="total-harga">Harga Yang Dibayar</label>
                        <input type="number" class="form-control form-control-lg" id="total-harga"
                            value="{{isset($totalPrice) ? $totalPrice : '0'}}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="input-bayar">Bayar</label>
                        <input type="number" class="form-control form-control-lg" id="input-bayar"
                            name="payment-amount">
                    </div>
                    <h4>Kembalian : <span id="kembalian"></span></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btn-simpan" type="button" class="btn btn-primary">Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/quantity-increment.js')}}"></script>
<script src="{{asset('js/input-transaksi.js')}}"></script>
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendor/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl-input-transaksi').DataTable({
            "searching": false,
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false
        });
    });
</script>

@if (session('id_trs'))
<script type="text/javascript">
    window.open('{{url('admin/cetak-transaksi' . '/' . session('id_trs'))}}', '_blank');
</script>
@endif
@endsection
