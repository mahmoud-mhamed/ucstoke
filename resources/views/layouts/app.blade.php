<?php
$permit = \App\Permit::first();
?>
@if (Route::currentRouteAction()=='App\Http\Controllers\HomeController@index')
@include('includes.social')
@endif
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title','Ultimate Code')</title>
    <link rel='icon' href='{{asset('img/icon.ico')}}'>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'font-ar';
            src: url("{{asset('fonts/ArbFONTS-4_88.ttf')}}");
        }
    </style>
    <link rel='stylesheet' href='{{asset('fonts/awesome/all.css')}}'>


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/hover-min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/alertify.core.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/alertify.default.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/daterangepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/validate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/lib/bootstrap-select.css') }}" rel="stylesheet">

    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <style>
        body {
            min-width: 100vw;
            min-height: 100vh;
            width: 100%;
            height: 100%;
            @if(Auth::user()->bg_img!='')
                         background: linear-gradient(rgba(0, 0, 0, .3), rgba(0, 0, 0, .3)), url("{{Auth::user()->bg_img}}");

            @else
                         background: linear-gradient(rgba(0, 0, 0, .3), rgba(0, 0, 0, .3)), url("{{asset('img/'.Auth::user()->bg.'.jpg')}}");
            @endif
                     background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        /*hide dropdown-divider if repeated before link in header*/
        #navUl li .dropdown-divider + .dropdown-divider {
            display: none;
        }

        #navUl li div.dropdown-menu .dropdown-divider:last-child {
            display: none;
        }
    </style>

@yield('css')

<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{ asset('js/lib/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/lib/moment.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery.daterangepicker.min.js') }}"></script>
    <script src='{{ asset('js/lib/alertify.min.js') }}'></script>
    <script src='{{ asset('js/lib/jquery.nicescroll.min.js') }}'></script>
    <script src='{{asset('js/lib/validate.js')}}'></script>
    <script src='{{asset('js/lib/jquery.PrintArea.js')}}'></script>
    <script src='{{asset('js/lib/filtable.min.js')}}'></script>
    <script src='{{asset('js/lib/jquery.tablesort.min.js')}}'></script>
    <script src='{{asset('js/lib/bootstrap-select.min.js')}}'></script>
    <script src='{{asset('js/lib/JsBarcode.all.min.js')}}'></script>
    <script src='{{asset('js/lib/Cookie.min.js')}}'></script>

    <script src='{{ asset('js/jquery.confirm.js') }}'></script>
    @yield('jsHeader')
    <script src="{{ asset('js/classes.js') }}"></script>
</head>
<body>
<div id='load' class='position-fixed text-center' style='top: 0;left: 0;z-index: 1000000;
                    width: 100vw;height: 100vh;background: rgba(255,255,255,1);'>
    <div class='' style='margin-top: 4%'>
        <img src='{{asset('img/load.gif')}}' style='width:200px;height:200px;max-width: 70%;max-height: 70%'
             alt='loding image'>
        <h2 class=''>برجاء الانتظار جاري التحميل</h2>
    </div>
</div>
<div id="goToTop" class='position-fixed'>
    <button class='btn btn-primary font-weight-bold'>
        <i class="fas fa-angle-double-up fa-2x"></i>
    </button>
</div>
@if(!isset($include_no_header))
    @include('includes.header')
@endif
@yield('content')
@if (\App\Setting::first()->allow_sound)
    <audio src="{{asset('sound/success.mp3')}}" id="sound_success"></audio> {{--for success--}}
    <audio src="{{asset('sound/info.mp3')}}" id="sound_info"></audio> {{--for info--}}
    <audio src="{{asset('sound/error.mp3')}}" id="sound_error"></audio> {{--for error--}}
@endif

</body>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@yield('js')

<script defer>
    window.onload = function () {
        $('#load').css('display', 'none');
    };
    //use go to top
    $(window).scroll(function () {
        if ($(window).scrollTop() > $('header').height()) {
            $('#goToTop').fadeIn();
        } else {
            $('#goToTop').fadeOut();
        }
    });
    $('#goToTop').click(function () {
        if ($(document).scrollTop() > 800) {
            $('html').animate({
                scrollTop: 0
            }, 600);
        } else {
            $('html').animate({
                scrollTop: 0
            }, 200);
        }

        $(this).fadeOut();
    });

    //deisable auto complete
    $('form').attr('autocomplete', 'off');

    //redirect to lgoin page if logout by other tap
    var checkStateLogin = true;
    window.setInterval(function () {
        if (checkStateLogin && Cookie.get('succsess_login') != true) {
            checkStateLogin = false;
            window.open('{{route('login')}}', '_parent');
        }
    }, 1000);

    //for sorted table
    $('table.sorted').tablesort();
    //for search in select box
    $('.selectpicker').selectpicker({
        showSubtext: true,
        container: 'body',
        showTick: true
    });

    @if(env('stop_oncontextmenu')=='on')
        window.oncontextmenu = function (e) {
        e.preventDefault();

        design.toggleFullscreen();
        design.useSound();
    };
    @endif

    //set nav open in header is active
    $('#topHeader #navUl a[href="' + window.location.href + '"]').css({
        paddingRight: '40px',
        fontWeight: 'bold'
    }).parent().prev().css({
        color: 'rgba(0,0,0,.9)',
        fontWeight: 'bolder'
    });


    design.useToolTip();
    //remove tooltips when dont disaper
    $('body').on('click', 'div.tooltip-inner', function () {
        $(this).parent().hide(); //work correct
        // $('.tooltips').tooltip('hide'); not working
    });
    //refresh page when session auth finish
    var dateWhenOpenPage = new Date();
    var reloadThisPage = true;
    window.setInterval(function () {
        var diff = Math.abs(new Date() - dateWhenOpenPage);
        // console.log(diff);
        // console.log('time is '+diff);
        if (diff > 7203000 && reloadThisPage) {
            reloadThisPage = false;
            window.location.reload(true);
        }
    }, 1000);
    //reload page when page open by back button in browser
    window.onhashchange = function () {
        window.location.reload(true);
    };


    //open create bill sale when click key * in keyborad
    {{--    @if (\Illuminate\Support\Facades\Request::ip() =='127.0.0.1')--}}
    @if(env('use_abbreviation')=='on')
    $('body').on('keydown', function (e) {
        var keyCode = e.keyCode;
        @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale)
        if (keyCode === 106) { //press *
            window.open('{{route('bills.create',1)}}', '_blank');
            return false;
        }
        @endif
            @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_buy)
        if (keyCode === 112) { // press f1
            window.open('{{route('bills.create',0)}}', '_blank');
            return false;
        }
        @endif
            @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale_show)
        if (keyCode === 113) { // press f2
            window.open('{{route('bills.create',2)}}', '_blank');
            return false;
        }
        @endif
            @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_product)
        if (keyCode === 114) { //press f3
            window.open('{{route('products.index')}}', '_blank');
            return false;
        }
        @endif
            @if(Hash::check('use_expenses',$permit->use_expenses))
            @if (Auth::user()->type==1 ||Auth::user()->allow_add_expenses_and_expenses_type)
        if (keyCode === 115) { //press f4
            window.open('{{route('expenses.create')}}', '_blank');
            return false;
        }
        @endif
            @endif
            @if(Hash::check('use_emp',$permit->use_emp))
            @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp_attend)
        if (keyCode === 33) { //press PageUp
            window.open('{{route('emps.show_emp_attend')}}', '_blank');
            return false;
        }
        @endif
            @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp_operation)
        if (keyCode === 34) { //press PageDown
            window.open('{{route('emps.index')}}?show_opertaion=true', '_blank');
            return false;
        }
        @endif
            @endif
            @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_bill_buy)
        if (keyCode === 118) { //press f7
            window.open('{{route('bills.index',0)}}', '_blank');
            return false;
        }
        @endif
            @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_bill_sale)
        if (keyCode === 119) { //press f8
            window.open('{{route('bills.index',1)}}', '_blank');
            return false;
        }
        @endif
            @if (Auth::user()->type ==1 || Auth::user()->allow_access_index_account)
        if (keyCode === 120) { //press f9
            window.open('{{route('accounts.index')}}', '_blank');
            return false;
        }
        @endif
            @if(Hash::check('product_make',$permit->product_make))
            @if (Auth::user()->type ==1 ||Auth::user()->allow_add_make)
        if (keyCode === 121) { //press f10
            window.open('{{route('makings.create')}}', '_blank');
            return false;
        }
        @endif
            @endif
            @if (Auth::user()->type==1||Auth::user()->allow_access_total_report)
        if (keyCode === 122) { //press f11
            window.open('{{route('users.report')}}', '_blank');
            return false;
        }
        @endif
            @if (Auth::user()->type==1 ||Auth::user()->allow_access_product_in_stoke)
        if (keyCode === 123) { //press f12
            window.open('{{route('stores.index')}}', '_blank');
            return false;
        }
        @endif
        if (keyCode === 36) { //press Home
            window.open('{{route('home')}}', '_parent');
            return false;
        }
        @if (Auth::user()->type ==1 || (Auth::user()->allow_manage_bill_sale)&& Auth::user()->allow_manage_bill_buy)
        if (keyCode === 45) { //press Home
            window.open('{{route('bills.index',2)}}', '_blank');
            return false;
        }
        @endif
            @if (Auth::user()->type==1 ||Auth::user()->allow_manage_activities)
        if (keyCode === 35) { //press End
            window.open('{{route('activity.index')}}', '_blank');
            return false;
        }
        @endif
        if (keyCode === 116) { //press F5
            window.location.reload();
            return false;
        }
        if (keyCode === 117) { //press f6
            window.open('{{route('treasuries.index')}}', '_blank');
            return false;
        }
        if (keyCode >= 112 && keyCode <= 123) { //from f4 to f12
            return false;
        }
    });
    @endif
    {{--        @endif--}}
    //show load img when reload page by browser
    window.onbeforeunload = function () {
        $('#load').css('display', 'block');
    }
</script>
@if(!isset($include_no_message))
    @include('includes.messages')
@endif
</html>
