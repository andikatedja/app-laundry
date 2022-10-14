@extends('admin.main')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('admin') }}">
    <link href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
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
                            <form action="" method="get">
                                <div class="form-group row">
                                    <label for="tahun" class="col-auto col-form-label">Tahun</label>
                                    <div class="col-auto">
                                        <select class="form-control" id="tahun" name="year">
                                            @foreach ($years as $year)
                                                @if ($year->tahun == $currentYear)
                                                    <option value="{{ $year->Tahun }}" selected>{{ $year->Tahun }}
                                                    </option>
                                                @else
                                                    <option value="{{ $year->Tahun }}">{{ $year->Tahun }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="bulan" class="col-auto col-form-label">Bulan</label>
                                    <div class="col-auto">
                                        <select class="form-control" id="bulan" name="month">
                                            @for ($i = 1; $i <= 12; $i++)
                                                @if ($i == $currentMonth)
                                                    <option value="{{ $i }}" selected>
                                                        {{ $i }}</option>
                                                @else
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" id="btn-filter" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Transaksi Berjalan (Priority Service)</h4>
                            <table id="tbl-transaksi-priority" class="table dt-responsive nowrap" style="width: 100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama Member</th>
                                        <th>Status</th>
                                        <th>Biaya Service</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ongoingPriorityTransactions as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->member->name }}</td>
                                            <td>
                                                @if ($item->status_id == 3)
                                                    <span class="text-success">Selesai</span>
                                                @else
                                                    <select name="" id="status" data-id="{{ $item->id }}"
                                                        data-val="{{ $item->status_id }}" class="select-status">
                                                        @foreach ($status as $s)
                                                            @if ($item->status_id == $s->id)
                                                                <option selected value="{{ $s->id }}">
                                                                    {{ $s->name }}</option>
                                                            @else
                                                                <option value="{{ $s->id }}">{{ $s->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                            <td>{{ $item->getFormattedServiceCost() }}</td>
                                            <td>{{ $item->getFormattedTotal() }}</td>
                                            <td>
                                                <a href="#" class="badge badge-info btn-detail" data-toggle="modal"
                                                    data-target="#detailTransaksiModal"
                                                    data-id="{{ $item->id }}">Detail</a>
                                                <a href="{{ url('admin/cetak-transaksi') . '/' . $item->id }}"
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Transaksi Berjalan</h4>
                            <table id="tbl-transaksi-belum" class="table dt-responsive nowrap" style="width: 100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama Member</th>
                                        <th>Status</th>
                                        <th>Biaya Service</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ongoingTransactions as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->member->name }}</td>
                                            <td>
                                                @if ($item->status_id == 3)
                                                    <span class="text-success">Selesai</span>
                                                @else
                                                    <select name="" id="status" data-id="{{ $item->id }}"
                                                        data-val="{{ $item->status_id }}" class="select-status">
                                                        @foreach ($status as $s)
                                                            @if ($item->status_id == $s->id)
                                                                <option selected value="{{ $s->id }}">
                                                                    {{ $s->name }}</option>
                                                            @else
                                                                <option value="{{ $s->id }}">{{ $s->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                            <td>{{ $item->getFormattedServiceCost() }}</td>
                                            <td>{{ $item->getFormattedTotal() }}</td>
                                            <td>
                                                <a href="#" class="badge badge-info btn-detail" data-toggle="modal"
                                                    data-target="#detailTransaksiModal"
                                                    data-id="{{ $item->id }}">Detail</a>
                                                <a href="{{ url('admin/cetak-transaksi') . '/' . $item->id }}"
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-3">Transaksi Selesai</h4>
                            <table id="tbl-transaksi-selesai" class="table dt-responsive nowrap" style="width: 100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Nama Member</th>
                                        <th>Status</th>
                                        <th>Biaya Service</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($finishedTransactions as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->member->name }}</td>
                                            <td>
                                                @if ($item->status_id == 3)
                                                    <span class="text-success">Selesai</span>
                                                @else
                                                    <select name="" id="status" data-id="{{ $item->id }}"
                                                        data-val="{{ $item->status_id }}" class="select-status">
                                                        @foreach ($status as $s)
                                                            @if ($item->status_id == $s->id)
                                                                <option selected value="{{ $s->id }}">
                                                                    {{ $s->name }}</option>
                                                            @else
                                                                <option value="{{ $s->id }}">{{ $s->name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                            <td>{{ $item->getFormattedServiceCost() }}</td>
                                            <td>{{ $item->getFormattedTotal() }}</td>
                                            <td>
                                                <a href="#" class="badge badge-info btn-detail" data-toggle="modal"
                                                    data-target="#detailTransaksiModal"
                                                    data-id="{{ $item->id }}">Detail</a>
                                                <a href="{{ url('admin/cetak-transaksi') . '/' . $item->id }}"
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
                    <h5>Tipe Servis: <span id="service-type"></span></h5>
                    <h5>Dibayar: <span id="payment-amount"></span></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tbl-transaksi-selesai').DataTable();
            $('#tbl-transaksi-belum').DataTable();
            $('#tbl-transaksi-priority').DataTable();
        });
    </script>
@endsection
