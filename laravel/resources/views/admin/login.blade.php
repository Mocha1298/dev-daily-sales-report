<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>GCG | Login</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/gcg_ico.png" />

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="/assets/css/libs.min.css" />


    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="/assets/css/hope-ui.min.css?v=2.0.0" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="/assets/css/custom.min.css?v=2.0.0" />

    <style>
        .form-check-input{
            border-color: #00A7E6;
        }
        .form-check-input:checked{
            color: #00A7E6;
        }
    </style>
</head>

<body class=" " data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0" tabindex="0">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    <!-- loader END -->

    <div class="wrapper">
        <section class="login-content">
            <div class="row m-0 align-items-center bg-white vh-100">
                <div class="col-md-5 d-md-block d-none bg-primary p-0 mt-n1 vh-100 overflow-hidden">
                    <img src="/edited.jpg" class="img-fluid gradient-main" alt="images">
                </div>
                <div class="col-md-7">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card d-flex justify-content-center mb-0 auth-card">
                                <div class="card-body">
                                    <a href="#" class="navbar-brand d-flex justify-content-center mb-3">
                                    </a>
                                    <p style="font-size: 15px" class="text-center">Masukkan email dan password Anda.</p>
                                    <form method="POST" action="/custom-login" style="font-size: 13px">
                                        <div class="row">
                                            @csrf
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="email"
                                                        class="block mt-1 w-full" type="email" name="email"
                                                        :value="old('email')" required autofocus
                                                        autocomplete="username">
                                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" aria-describedby="password" required
                                                        autocomplete="current-password" placeholder="Password">
                                                </div>
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-check mb-3">
                                                    <input type="checkbox" class="form-check-input" id="customCheck1">
                                                    <label class="form-check-label" for="customCheck1">Ingat Saya</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button style="border:none;background: #00A7E6;" type="submit" class="btn btn-primary">Sign In</button>
                                        </div>
                                        {{-- <div class="col-lg-12 text-center mt-2">
                                            @if (Route::has('password.request'))
                                                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                                    href="{{ route('password.request') }}">
                                                    {{ __('Lupa Password?') }}
                                                </a>
                                            @endif
                                        </div> --}}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="sign-bg">
                        <img src="/logo.png" alt="" width="280px" srcset="" style="opacity: 0.05;">
                    </div> --}}
                </div>
            </div>
        </section>
    </div>

    <!-- Library Bundle Script -->
    <script src="/assets/js/core/libs.min.js"></script>

    <!-- External Library Bundle Script -->
    <script src="/assets/js/core/external.min.js"></script>

    <!-- App Script -->
    <script src="/assets/js/hope-ui.js" defer></script>

</body>

</html>
