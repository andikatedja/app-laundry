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
                            <h5>Tambah Harga</h5>
                            <form action="{{ route('admin.price-lists.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="barang">Barang</label>
                                            <div class="row">
                                                <div class="col-8">
                                                    <select class="form-control" id="barang" name="item">
                                                        @foreach ($items as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <a id="tambah-barang" class="text-white btn btn-primary"
                                                        data-toggle="modal" data-target="#tambahBarangModal"><i
                                                            class="fas fa-plus"></i>
                                                        Barang</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="servis">Servis</label>
                                            <div class="row">
                                                <div class="col-8">
                                                    <select class="form-control" id="servis" name="service">
                                                        @foreach ($services as $service)
                                                            <option value="{{ $service->id }}">{{ $service->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <a id="tambah-servis" class="text-white btn btn-primary"
                                                        data-toggle="modal" data-target="#tambahServisModal"><i
                                                            class="fas fa-plus"></i>
                                                        Servis</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="kategori">Kategori</label>
                                            <select class="form-control" id="kategori" name="category">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="harga-modal">Harga</label>
                                            <input type="number" class="form-control" id="harga" name="price"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Tambah Harga</button>
                            </form>
                            <hr>
                            <h5 class="mt-3">Daftar Harga</h5>
                            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="kiloan-tab" data-toggle="tab" href="#kiloan"
                                        role="tab" aria-controls="kiloan" aria-selected="true">Kiloan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="satuan-tab" data-toggle="tab" href="#satuan" role="tab"
                                        aria-controls="satuan" aria-selected="false">Satuan</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="kiloan" role="tabpanel"
                                    aria-labelledby="kiloan-tab">
                                    <table id="tbl-kiloan" class="table dataTable dt-responsive nowrap"
                                        style="width:100%">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Barang</th>
                                                <th>Servis</th>
                                                <th>Harga</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kiloan as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->item->name }}</td>
                                                    <td>{{ $item->service->name }}</td>
                                                    <td>{{ $item->getFormattedPrice() }} /kilo</td>
                                                    <td>
                                                        <a href="#" class="badge badge-warning btn-ubah-harga"
                                                            data-id="{{ $item->id }}" data-toggle="modal"
                                                            data-target="#ubahHargaModal">Ubah Harga</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="satuan" role="tabpanel" aria-labelledby="satuan-tab">
                                    <table id="tbl-satuan" class="table dataTable dt-responsive nowrap"
                                        style="width:100%">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Barang</th>
                                                <th>Servis</th>
                                                <th>Harga</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($satuan as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->item->name }}</td>
                                                    <td>{{ $item->service->name }}</td>
                                                    <td>{{ $item->getFormattedPrice() }} /pcs</td>
                                                    <td>
                                                        <a href="#" class="badge badge-warning btn-ubah-harga"
                                                            data-id="{{ $item->id }}" data-toggle="modal"
                                                            data-target="#ubahHargaModal">Ubah Harga</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <h5 class="mt-3">Daftar Tipe Service</h5>
                            <div class="tab-content mt-3" id="myTabContent">
                                <table id="tbl-kiloan" class="table dataTable dt-responsive nowrap" style="width:100%">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Tipe Service</th>
                                            <th>Biaya</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($serviceTypes as $serviceType)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $serviceType->name }}</td>
                                                <td>{{ $serviceType->getFormattedCost() }}</td>
                                                <td>
                                                    <a href="#" class="badge badge-warning btn-update-cost"
                                                        data-id="{{ $serviceType->id }}" data-toggle="modal"
                                                        data-target="#updateCostModal">Ubah Harga</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="ubahHargaModal" tabindex="-1" role="dialog" aria-labelledby="ubahHargaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahHargaModalLabel">Ubah Harga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.price-lists.update', ['priceList' => 'id']) }}" method="post"
                        id="updatePriceForm">
                        @csrf
                        @method('PATCH')
                        <input id="id-harga-modal" type="hidden" name="id_harga">
                        <div class="form-group">
                            <label for="harga-modal">Harga</label>
                            <input type="number" class="form-control" id="harga-modal" name="price">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Ubah Harga</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateCostModal" tabindex="-1" role="dialog" aria-labelledby="updateCostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateCostModalLabel">Ubah Biaya Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="serviceTypeForm"
                        action="{{ route('admin.service-types.update', ['serviceType' => 'id']) }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <input id="id-cost-modal" type="hidden" name="id_cost">
                        <div class="form-group">
                            <label for="cost-modal">Biaya</label>
                            <input type="number" class="form-control" id="cost-modal" name="cost">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Ubah Biaya</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahBarangModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahBarangModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahBarangModalLabel">Tambah Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.items.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="barang">Nama Barang</label>
                            <input type="text" class="form-control" id="barang" name="item_name">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Barang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahServisModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahServisModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahServisModalLabel">Tambah Servis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.services.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="servis">Nama Servis</label>
                            <input type="text" class="form-control" id="servis" name="service_name">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Servis</button>
                    </form>
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
    <script src="{{ asset('js/ajax-harga.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tbl-satuan').DataTable();
            $('#tbl-kiloan').DataTable();
        });
    </script>
@endsection
