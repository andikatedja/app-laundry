@extends('member.template.main')

@section('main-content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard Member</h1>
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
                                <div class="col-10">
                                    <img class="img-circle img-fit float-left" width="100" height="100"
                                        src="{{ $user->getFileAsset() }}" alt="Foto Profil">
                                    <div class="member-content">
                                        <h2 class="m-0">{{ $user->name }}</h2>
                                        <p class="small m-0">ID Member: {{ $user->id }}</p>
                                        <a href="{{ route('profile.index') }}" class="badge badge-primary badge-pill">Edit
                                            Profil</a>
                                    </div>
                                </div>
                                <div class="col-2 text-center">
                                    <p class="small m-0">Poin</p>
                                    <h2 class="m-0">{{ $user->point }}</h2>
                                    <a href="{{ route('member.points.index') }}"
                                        class="badge badge-primary badge-pill">Tukar
                                        Poin</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="my-3 text-center">Transaksi Terakhir</h3>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestTransactions as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                            <td>
                                                @if ($item->status_id != '3')
                                                    <span class="text-warning">{{ $item->status->name }}</span>
                                                @else
                                                    <span class="text-success">{{ $item->status->name }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('member.transactions.show', ['transaction' => $item->id]) }}"
                                                    class="badge badge-primary">Lihat Detail ></a>
                                            </td>
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
