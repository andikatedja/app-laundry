@extends('member.template.main')

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
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('member.complaints.store') }}" method="POST">
                                        @csrf
                                        <h5>Silahkan isi form dibawah ini sesuai kriteria</h5>
                                        <div class="form-group">
                                            <select class="form-control" id="tipe" name="type">
                                                <option value="1">Saran</option>
                                                <option value="2">Komplain</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control" id="form_sarankomplain" rows="4" name="body"></textarea>
                                        </div>
                                        <button class="btn btn-primary badge-pill float-right w-25"
                                            type="submit">Kirim</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <h5>Telepon atau Chat:</h5>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Line</td>
                                                <td>@idline</td>
                                            </tr>
                                            <tr>
                                                <td>Whatsapp</td>
                                                <td>081999999</td>
                                            </tr>
                                            <tr>
                                                <td>Telepon</td>
                                                <td>(0361)123456</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">Ketentuan: Hanya melayani saat jam kerja</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Riwayat Saran atau Komplain</h5>
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Isi</th>
                                        <th>Balasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($complaintSuggestions as $complaintSuggestion)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d F Y', strtotime($complaintSuggestion->created_at)) }}</td>
                                            <td>
                                                @if ($complaintSuggestion->type == 1)
                                                    Saran
                                                @else
                                                    Komplain
                                                @endif
                                            </td>
                                            <td>{{ $complaintSuggestion->body }}</td>
                                            @if ($complaintSuggestion->reply == null)
                                                <td class="text-danger">
                                                    Belum ada balasan
                                                </td>
                                            @else
                                                <td>
                                                    {{ $complaintSuggestion->reply }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
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
