<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In | Chandra Enterprise</title>
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <!-- Favicon-->
    <link rel="icon" href="{!! asset('assets/img/icon.png') !!}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{!! asset('assets/plugins/bootstrap/css/bootstrap.css') !!}" rel="stylesheet">

    <link rel="stylesheet" href="{!! asset('assets/plugins/sweetalert2/sweetalert2.css') !!}">

    <!-- Waves Effect Css -->
    <link href="{!! asset('assets/plugins/node-waves/waves.css') !!}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{!! asset('assets/plugins/animate-css/animate.css') !!}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{!! asset('assets/css/style.css') !!}" rel="stylesheet">
</head>

<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Admin<b></b></a>
            <small>Chandra Enterprise</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}
                    <div class="msg">Sign in to start your session</div>
                   

                    <div class="input-group {{ $errors->has('emp_code') ? ' has-error' : '' }}">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="emp_code" value="{{ old('emp_code') }}" placeholder="Emp code" required autofocus>
                        </div>
                        @if ($errors->has('emp_code'))
                            <span class="error">
                                <strong>{{ $errors->first('emp_code') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="input-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        @if ($errors->has('password'))
                            <span class="error">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                        </div>
                    </div>
                    <div class="row m-t-15 m-b--20">
                        
                        <div class="col-xs-8">
                            <a href="{{ route('password.request') }}">Forgot Password?</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="{!! asset('assets/plugins/jquery/jquery.min.js') !!}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{!! asset('assets/plugins/bootstrap/js/bootstrap.js') !!}"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
    </script>

    <script type="text/javascript" src="{!! asset('assets/plugins/sweetalert2/sweetalert2.min.js') !!}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        @if (Session::has('error'))

        swal(
            'Error',
            '{{ Session::get("error") }}',
            'error'
            );

        @endif

        @if (Session::has('success'))

        swal(
            'Success',
            '{{ Session::get("success") }}',
            'success'
            );

        @endif
    });
    </script>

    <!-- Waves Effect Plugin Js -->
    <script src="{!! asset('assets/plugins/node-waves/waves.js') !!}"></script>

    <!-- Validation Plugin Js -->
    <script src="{!! asset('assets/plugins/jquery-validation/jquery.validate.js') !!}"></script>

    <!-- Custom Js -->
    <script src="{!! asset('assets/js/admin.js') !!}"></script>
    <script src="{!! asset('assets/js/sign-in.js') !!}"></script>
</body>

</html>