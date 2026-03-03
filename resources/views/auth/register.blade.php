<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login V1</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('auth/images/icons/favicon.ico') }}"/>

    <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/vendor/bootstrap/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">

    <!-- Animate -->
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/vendor/animate/animate.css') }}">

    <!-- Hamburgers -->
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/vendor/css-hamburgers/hamburgers.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/vendor/select2/select2.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('auth/css/main.css') }}">

    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="{{ asset('auth/images/img-01.png') }}" alt="IMG">
            </div>

            <form class="login100-form validate-form" method="POST" action="{{ route('register') }}">
                @csrf
					<span class="login100-form-title">
						Register
					</span>
                <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                    <input class="input100" type="text" name="name" placeholder="Name">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                    <input class="input100" type="email" name="email" placeholder="Email">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                    <input class="input100" type="password" name="password" placeholder="Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Password is required">
                    <input class="input100" type="password" name="password_confirmation" placeholder="Confirm Password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn">
                        Register
                    </button>
                </div>

                <div class="text-center p-t-12">
{{--						<span class="txt1">--}}
{{--							Forgot--}}
{{--						</span>--}}
{{--                    <a class="txt2" href="#">--}}
{{--                        Username / Password?--}}
{{--                    </a>--}}
                </div>

                <div class="text-center p-t-136">
                    <a class="txt2" href="{{ route('login') }}">
                        Already Have an account.
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- jQuery -->
<script src="{{ asset('auth/vendor/jquery/jquery-3.2.1.min.js') }}"></script>

<!-- Popper -->
<script src="{{ asset('auth/vendor/bootstrap/js/popper.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('auth/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('auth/vendor/select2/select2.min.js') }}"></script>

<!-- Tilt -->
<script src="{{ asset('auth/vendor/tilt/tilt.jquery.min.js') }}"></script>

<script>
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>

<!-- Main JS -->
<script src="{{ asset('auth/js/main.js') }}"></script>

</body>
</html>
