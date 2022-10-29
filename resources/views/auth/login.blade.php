@extends('auth.main')

@section('title', config('app.name') . ' - ' . __('auth.log_title'))

@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card bg-light o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-3">@lang('auth.log_title')</h3>
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
                        <form action="" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="email">@lang('auth.email_label')</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="@lang('auth.email_placeholder')"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">@lang('auth.password_label')</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="@lang('auth.password_placeholder')">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">@lang('auth.remember_me')</label>
                            </div>
                            <button class="btn btn-primary btn-block" type="submit">@lang('auth.log_title')</button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a href="{{ url('register') }}">@lang('auth.register_link')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
