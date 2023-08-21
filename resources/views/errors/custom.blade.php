<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name', 'ChandraEnterprise') }}</title>
    <!-- Favicon-->
    <link rel="icon" href="{!! asset('assets/img/icon.png') !!}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{!! asset('assets/plugins/bootstrap/css/bootstrap.css') !!}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{!! asset('assets/plugins/node-waves/waves.css') !!}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{!! asset('assets/css/style.css') !!}" rel="stylesheet">
</head>

<body class="four-zero-four">
    <div class="four-zero-four-container">
        <div class="error-code">403</div>
        <div class="error-message">This page doesn't exist</div>
        <div class="button-place">
            <a href="{{ route('dashboard') }}" class="btn btn-default btn-lg waves-effect">GO TO DASHBOARD</a>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="{!! asset('assets/plugins/jquery/jquery.min.js') !!}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{!! asset('assets/plugins/bootstrap/js/bootstrap.js') !!}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{!! asset('assets/plugins/node-waves/waves.js') !!}"></script>
</body>

</html>