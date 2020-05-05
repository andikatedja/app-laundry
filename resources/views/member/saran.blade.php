@extends('member.main')

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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{url('member/kirimsaran')}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="form_sarankomplain">Silahkan isi saran atau komplain pada form
                                            ini:</label>
                                        <textarea class="form-control" id="form_sarankomplain" rows="3"
                                            name="isi_sarankomplain"></textarea>
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
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
@endsection