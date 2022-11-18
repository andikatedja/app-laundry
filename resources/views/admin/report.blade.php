@extends('admin.template.main')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('admin') }}">
@endsection

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
                                    <form action="{{ route('admin.reports.print') }}" method="post">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="tahun" class="col-sm-4 col-form-label">Tahun</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="tahun" name="year">
                                                    <option value="0" selected="selected" disabled="true">-- Please
                                                        Select
                                                        --</option>
                                                    @foreach ($years as $year)
                                                        <option value="{{ $year->Tahun }}">{{ $year->Tahun }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="bulan" class="col-sm-4 col-form-label">Bulan</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="bulan" name="month">
                                                    <option value="0" selected="selected" disabled="true">-- Select
                                                        Year
                                                        First --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" id="btn-cetak"
                                            class="mt-3 btn btn-primary d-none">Cetak</button>
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

@section('scripts')
    <script src="{{ asset('js/ajax.js') }}"></script>
@endsection
