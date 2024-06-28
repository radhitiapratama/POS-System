<html lang="en" dir="ltr" data-nav-layout="enable" loader="enable">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>POS | Login</title>
    <!-- Favicon -->
    <link rel="icon" href="/noa-assets/assets/images/brand-logos/favicon.ico" type="image/x-icon">
    <!-- Main Theme Js -->
    <script src="/noa-assets/assets/js/authentication-main.js"></script>
    <!-- Sweetalerts CSS -->
    <link rel="stylesheet" href="noa-assets/assets/libs/sweetalert2/sweetalert2.min.css">
    <!-- Bootstrap Css -->
    <link id="style" href="/noa-assets/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Style Css -->
    <link href="/noa-assets/assets/css/styles.min.css" rel="stylesheet">
    <!-- Icons Css -->
    <link href="/noa-assets/assets/css/icons.min.css" rel="stylesheet">
    {{-- My CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="sign-in-basic-page">
    <div id="loader" class="d-flex">
        <img src="/noa-assets/assets/images/media/loader.svg" alt="">
    </div>
    <div class="container px-3">
        <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                <div class="my-4 d-flex justify-content-center">
                    <a href="index.html">
                        <img src="/noa-assets/assets/images/brand-logos/desktop-dark.png" alt="logo" class="">
                    </a>
                </div>
                <div class="card custom-card">
                    <form action="{{ url('login') }}" method="post">
                        @csrf
                        <div class="card-body p-4 pb-3">
                            <h4 class="fw-semibold mb-4 text-center">Login</h4>
                            <div class="mb-3">
                                <label for="form-text1" class="form-label fs-14 text-dark">Username</label>
                                <div class="input-box">
                                    <input type="text" class="form-control form-control-lg" id="signin-username"
                                        placeholder="--------" name="username" value="{{ old('username') }}">
                                    <span class="authentication-input-icon">
                                        <i class="ri-user-line  text-default fs-15 op-7"></i>
                                    </span>
                                </div>
                                @error('username')
                                    <small class="text-danger mt-2 d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="form-text1" class="form-label fs-14 text-dark">Password</label>
                                <div class="input-box input-group">
                                    <input type="password" class="form-control form-control-lg" id="signin-password"
                                        placeholder="--------" minlength="8" name="password"
                                        value="{{ old('password') }}">
                                    <span class="authentication-input-icon">
                                        <i class="ri-lock-line text-default fs-15 op-7"></i>
                                    </span>
                                    <button type="button" aria-label="button" class="btn btn-light"
                                        onclick="createpassword('signin-password',this)" id="button-addon2">
                                        <i class="ri-eye-off-line align-middle"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <small class="text-danger mt-2 d-block">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-xl-12 d-grid mb-3">
                                <button type="submit" class="btn btn-lg btn-primary">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Jquery --}}
    <script src="{{ asset('noa-assets/assets/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        function hideLoader() {
            $("#loader").addClass("d-none")
        }

        $(document).ready(function() {
            hideLoader()
        });
    </script>
    <!-- Bootstrap JS -->
    <script src="/noa-assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Show Password JS -->
    <script src="/noa-assets/assets/js/show-password.js"></script>
    {{-- Sweetalert --}}
    <script src="noa-assets/assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <script>
        @if (session()->has('error'))
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                text: '{{ session('error_message') }}',
            });
        @endif
    </script>
</body>

</html>
