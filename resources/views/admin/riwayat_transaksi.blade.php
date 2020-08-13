@extends('admin.main')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="base_url" content="{{url('admin')}}">
<link href="{{asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section('main-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Riwayat Transaksi</h1>
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
                        <table id="tbl-riwayat-transaksi" class="table dt-responsive nowrap" style="width: 100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>ID Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Nama Member</th>
                                    <th>Status</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->id_transaksi}}</td>
                                    <td>{{date('d F Y', strtotime($item->tgl_masuk))}}</td>
                                    <td>{{$item->nama}}</td>
                                    <td>
                                        @if ($item->id_status == 3)
                                        <span class="text-success">Selesai</span>
                                        @else
                                        <select name="" id="status" data-id="{{$item->id_transaksi}}"
                                            data-val="{{$item->id_status}}" class="select-status">
                                            @foreach ($status as $s)
                                            @if ($item->id_status == $s->id_status)
                                            <option selected value="{{$s->id_status}}">{{$s->nama_status}}</option>
                                            @else
                                            <option value="{{$s->id_status}}">{{$s->nama_status}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @endif
                                    </td>
                                    <td>{{$item->total_harga}}</td>
                                    <td>
                                        <a href="#" class="badge badge-info btn-detail" data-toggle="modal"
                                            data-target="#detailTransaksiModal"
                                            data-id="{{$item->id_transaksi}}">Detail</a>
                                        <a href="{{url('admin/cetak-transaksi') . '/' . $item->id_transaksi}}"
                                            class="badge badge-primary" target="_blank">Cetak</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>

<!-- Modal -->
<div class="modal fade" id="detailTransaksiModal" tabindex="-1" role="dialog"
    aria-labelledby="detailTransaksiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailTransaksiModalLabel">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>ID Transaksi: <span id="id-transaksi-detail"></span></h5>
                <table id="tbl-detail-transaksi" class="table dt-responsive nowrap" style="width: 100%">
                    <thead class="thead-light">
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
                    <tbody id="tbl-ajax">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendor/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('js/ajax.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl-riwayat-transaksi').DataTable();
    });
</script>
@endsection