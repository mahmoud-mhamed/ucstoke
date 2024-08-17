<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 28/01/2019
 * Time: 10:46 ص
 */?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport'
          content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>restore database</title>
    <link rel='icon' href='{{asset('img/icon.ico')}}'>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
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

    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <style>
        body{
            background: linear-gradient(rgba(0,0,0,.3),rgba(0,0,0,.3)), url("{{asset('img/bg2.jpg')}}");
            background-size: 100% 100%;
            min-height: 100vh;
            background-repeat: no-repeat;
        }
    </style>

<!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>

    <script src="{{ asset('js/lib/sweetalert2.all.min.js') }}" ></script>
    <script src="{{ asset('js/lib/wow.min.js') }}" ></script>
    <script src='{{ asset('js/lib/bootstrap-validate.js') }}'></script>
    <script src='{{ asset('js/lib/alertify.min.js') }}'></script>
    <script src='{{ asset('js/lib/moment.min.js') }}'></script>
    <script src='{{ asset('js/lib/jquery.daterangepicker.min.js') }}'></script>
    <script src='{{ asset('js/lib/jquery.PrintArea.js') }}'></script>

    <script src='{{ asset('js/jquery.confirm.js') }}'></script>
    <script src='{{ asset('js/classes.js') }}'></script>

</head>
<body>
<div id='load' class='position-fixed text-center' style='display:none;top: 0;left: 0;z-index: 1000000;
                    width: 100vw;height: 100vh;background: rgba(255,255,255,1);'>
    <div class='' style='margin-top: 4%'>
        <img src='{{asset('img/load.gif')}}'  style='width:200px;height:200px;max-width: 70%;max-height: 70%' alt='loding image'>
        <h2 class=''>برجاء الانتظار جاري التحميل</h2>
    </div>
</div>
<main  dir='rtl' class='pt-4 px-2 pb-2'>
    <section class='animated fadeInDown ml-auto faster container'  >
        <div class='text-center'>
            <h1 class='text-white font-weight-bold pb-3'>استعادة نسخه محفوظة</h1>
            <div class='container box p-2 font-weight-bold'>
                <div class=' mt-2 '>
                    <form enctype='multipart/form-data' action='{{route('backups.restore')}}'  class='d-inline-block' method='post'>
                        @csrf
                        <div class="btn-group" role="group">
                            <input type='submit' name='restoreDataBase' disabled class='btn text-white font-weight-bold btn-primary' value='استعادة نسخة محفوظة'/>
                            <button  type='button'
                                     class='btn px-3 border-radius position-relative text-white font-weight-bold btn-primary border-right'
                                     style='z-index: 1'
                                     onclick='$(this).next().trigger("click")'>حدد المسار</button>
                            <input type='file' name='restoreDb' style='right: -86px;top: 5px;border: none!important;' required class='position-relative' accept='.sql'>
                        </div>
                        <input type='password'
                               class='form-control'
                               placeholder='password'
                               name='password'  required  >
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

</body>
<script>

    /*restore Backup*/
    $('input[type="file"]').change(function () {
        $('input[type="submit"]').addClass('btn-success').removeAttr('disabled');
    });

    $('input[type="submit"]').click(function (e) {
        e.preventDefault();
        $(this).confirm({
            text: "هل تريد استعادة النسخة المحددة هذة العميلة ستودي لحذف كل البيانات بعد وقت هذة العملية برجاء انشاء نسخة احتياطية قبل الحذف وعدم التعامل مع البرنامج حتي تنتهي عملية الاستعادة هذه العملية قد تستغرق بعض الوقت؟",
            title: "استعادة نسخة محفوظة",
            confirm: function(button) {
                $('#load').css('display','block');
                $('form').submit();
            },
            cancel: function(button) {

            },
            post: true,
            confirmButtonClass: "btn-danger",
            cancelButtonClass: "btn-default",
            dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
        });

    });
</script>
</html>
