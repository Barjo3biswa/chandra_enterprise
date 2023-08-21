<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name', 'ChandraEnterprise') }}</title>
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <!-- Favicon-->
    <link rel="icon" href="{!! asset('assets/img/icon.png') !!}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link media="all" href="{!! asset('assets/plugins/bootstrap/css/bootstrap.css') !!}" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    {{-- <link href="{!! asset('assets/plugins/bootstrap-select/css/bootstrap-select.css')!!}" rel="stylesheet" /> --}}
    
    

    <!-- Waves Effect Css -->
    <link href="{!! asset('assets/plugins/node-waves/waves.css') !!}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{!! asset('assets/plugins/animate-css/animate.css') !!}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{!! asset('assets/css/style.css') !!}" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{!! asset('assets/plugins/sweetalert2/sweetalert2.css') !!}">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{!! asset('assets/css/themes/all-themes.css') !!}" rel="stylesheet" />
    <link rel="stylesheet" href="{!! asset('assets/css/intlTelInput.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/plugins/select2/select2.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/plugins/select2/select2-bootstrap.min.css') !!}">
    <style>
        .version i{
            color: #00BCD4;
        }
        .slimScrollBar {
            width: 15px!important;
        }
        .select2-container--default .select2-selection--single {
            border: 0px solid #aaa1;
        }
        .select2-container{ 
            width: 100% !important;
        }
    </style>
    @yield('styles')
</head>

<body class="theme-blue-grey">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
   
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="{{ route('dashboard') }}"> {{ strtoupper(Auth::user()->role) }} - CHANDRA ENTERPRISE</a>
            </div>
            
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="{!!asset('assets/img/gambar-user-png-2.png') !!}" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    @php

                    $f_name = Auth::user()->first_name;
                    $m_name = Auth::user()->middle_name;
                    $l_name = Auth::user()->last_name;
                    $full_name = $f_name.' '.$m_name.' '.$l_name;

                    @endphp
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ ucwords($full_name) }}</div>
                    <div class="email">{{ Auth::user()->email }}</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                           <!-- <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                            <li role="seperator" class="divider"></li>-->
                            <li><a href="{{ url('logout') }}"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            
                @include('layouts._menu')

            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2019 <a href="javascript:void(0);">{{ config('app.name', 'ENTERPRISE') }}</a>.
                </div>
                <div class="version">
                    <span>Made with</span> <i class="fa fa-heart"></i> <span>by <a href="https://webcomindia.biz/" target="_blank">Web.Com India Pvt Ltd</a></span>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        
    </section>

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    @if (session('status'))
                        <div class="alert bg-green alert-dismissible" role="alert">
                            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </div>


            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @yield('content')

        </div>
    </section>
    

    <!-- Jquery Core Js -->
    <script src="{!! asset('assets/plugins/jquery/jquery.min.js') !!}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{!! asset('assets/plugins/bootstrap/js/bootstrap.js') !!}"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
    </script>

    <!-- Select Plugin Js -->
    <!--<script src="{!! asset('assets/plugins/bootstrap-select/js/bootstrap-select.js') !!}"></script>-->

    <!-- Slimscroll Plugin Js -->
    <script src="{!! asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.js') !!}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{!! asset('assets/plugins/node-waves/waves.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/utils.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/intlTelInput.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/isValidNumber.js') !!}"></script>

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

        @if (Session::has('reset_password'))

        swal({
            title: 'Successfully reset password',
            type: 'success',
            html: 'for <b>{{ Session::get("reset_password") }}</b>',
              // showCloseButton: true,
              // showCancelButton: true,
            focusConfirm: false,
            });

        
        @endif

    });
    </script>

    <!-- Jquery Validation Plugin Css -->
    <script src="{!! asset('assets/plugins/jquery-validation/jquery.validate.js')!!}"></script>

  
    <!-- Custom Js -->
    <script src="{!! asset('assets/js/admin.js') !!}"></script>
    {{-- <script src="{!! asset('assets/js/form-validation.js')!!}"></script> --}}

    <script src="{!! asset('assets/plugins/widgets/infobox/infobox-4.js')!!}"></script>
    <script src="{!! asset('assets/plugins/select2/select2.full.min.js')!!}"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
            $("select").select2({
                sorter: function(data) {
                    return data.sort();
                }
            });
        });
     
    </script>

@yield('scripts')
    
</body>

</html>