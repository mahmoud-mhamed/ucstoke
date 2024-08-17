@include('includes.social')
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

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

@yield('css')

<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/classes.js') }}"></script>
    <script src="{{ asset('js/lib/sweetalert2.all.min.js') }}"></script>
    <script src='{{ asset('js/lib/alertify.min.js') }}'></script>
    <script src='{{ asset('js/lib/Cookie.min.js')}}'></script>



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
@yield('content')
@include('includes.social')
<audio src="{{asset('sound/success.mp3')}}" id="sound_success"></audio> {{--for success--}}
<audio src="{{asset('sound/info.mp3')}}" id="sound_info"></audio> {{--for info--}}
<audio src="{{asset('sound/error.mp3')}}" id="sound_error"></audio> {{--for error--}}
</body>


@yield('js')
<script defer>
    window.onload = function () {
        $('#load').css('display', 'none');
    };
    @if(env('stop_oncontextmenu')=='on')
        window.oncontextmenu = function (e) {
        e.preventDefault();

        design.toggleFullscreen();
        design.useSound();
    };
    @endif

    //refresh page when session auth finish
    var dateWhenOpenPage = new Date();
    var reloadThisPage=true;
    window.setInterval(function () {
        var diff = Math.abs(new Date() - dateWhenOpenPage);
        // console.log(diff);
        // console.log('time is '+diff);
        if (diff > 7203000 && reloadThisPage) {
            reloadThisPage=false;
            window.location.reload(true);
        }
    }, 1000);

    //reload page when page open by back button in browser
    window.onhashchange = function() {
        window.location.reload(true);
    };

    //show load img when reload page by browser
    window.onbeforeunload = function(){
        $('#load').css('display', 'block');
    }
</script>
@include('includes.messages')

</html>
