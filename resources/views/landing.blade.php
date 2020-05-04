<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ config('app.name', 'Laundry XYZ') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Javascript -->
    <script defer src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script defer src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mr-sm-3 mb-2 mb-sm-0">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @lang('landing.langtext')
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{url('id')}}">Indonesia</a>
                                <a class="dropdown-item" href="{{url('en')}}">English</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success" href="{{url('login')}}">@lang('landing.loginOrRegister')</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="bg-primary py-5">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 text-white mt-5 mb-2">@lang('landing.welcome')</h1>
                    <p class="lead mb-5 text-white-50">@lang('landing.tagline')</p>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid d-none d-lg-block" src="https://dummyimage.com/600x400/000/fff" alt=""
                        srcset="">
                </div>
            </div>
        </div>
    </header>

    <section class="p-5 text-center">
        <h3>@lang('landing.why')</h3>
    </section>

    <!-- Page Content -->
    <section class="kelebihan bg-primary text-white">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-6">
                    <h4>Kelebihan 1</h4>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae nisi dolores quam id laboriosam
                        totam
                        laudantium soluta eos recusandae exercitationem consequuntur doloribus, sed deleniti sint qui ut
                        accusamus aliquid libero.</p>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid d-none d-lg-block" src="https://dummyimage.com/600x200/000/fff" alt=""
                        srcset="">
                </div>
            </div>
        </div>
    </section>

    <section class="kelebihan bg-primary text-white">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-6">
                    <img class="img-fluid d-none d-lg-block" src="https://dummyimage.com/600x200/000/fff" alt=""
                        srcset="">
                </div>
                <div class="col-lg-6">
                    <h4>Kelebihan 2</h4>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae nisi dolores quam id laboriosam
                        totam
                        laudantium soluta eos recusandae exercitationem consequuntur doloribus, sed deleniti sint qui ut
                        accusamus aliquid libero.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="kelebihan bg-primary text-white">
        <div class="container p-5">
            <div class="row">
                <div class="col-lg-6">
                    <h4>Kelebihan 3</h4>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Beatae nisi dolores quam id laboriosam
                        totam
                        laudantium soluta eos recusandae exercitationem consequuntur doloribus, sed deleniti sint qui ut
                        accusamus aliquid libero.</p>
                </div>
                <div class="col-lg-6">
                    <img class="img-fluid d-none d-lg-block" src="https://dummyimage.com/600x200/000/fff" alt=""
                        srcset="">
                </div>
            </div>
        </div>
    </section>

    <section class="text-center p-5">
        <h3>Apa saja yang bisa kami laundry?</h3>
    </section>

    <section class="bg-primary p-5 text-center">
        <div class="container">
            <div class="row flex-row flex-nowrap kategori">
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="https://dummyimage.com/400x300/000/fff" class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">Baju</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="https://dummyimage.com/400x300/000/fff" class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">Celana</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="https://dummyimage.com/400x300/000/fff" class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">Bed Cover</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 mb-2">
                    <div class="card">
                        <img src="https://dummyimage.com/400x300/000/fff" class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">Selimut</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="text-center p-5">
        <h3>Temukan kami!</h3>
    </section>

    <section class="text-white bg-primary">
        <div class="container p-5">
            <div class="row">
                <div class="col-md-6 mb-4 mb-sm-0">
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sed iste architecto suscipit voluptatum,
                    consectetur fuga fugiat possimus quasi, iure enim esse, molestias necessitatibus sint quas nihil!
                    Placeat iure dolorum reiciendis?
                </div>
                <div class="col-md-6">
                    <img class="img-fluid" src="https://dummyimage.com/600x300/000/fff" alt="" srcset="">
                </div>
            </div>
        </div>
    </section>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; {{config('app.name')}} 2020</p>
        </div>
        <!-- /.container -->
    </footer>

</body>

</html>