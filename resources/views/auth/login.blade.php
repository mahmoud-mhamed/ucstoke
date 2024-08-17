@extends('layouts.login')
@section('title','تسجيل الدخول')
@section('css')
    <style>
        /*main css*/
        body {
            background: linear-gradient(rgba(0, 0, 0, .3), rgba(0, 0, 0, .3)), url("{{asset('img/bg1.png')}}");
            background-size: 100vw 100vh;
            background-repeat: no-repeat;
        }

        /*end main*/
        main.container {
            max-width: 500px;
            background: rgba(0, 0, 0, .7);
            border-radius: 10px;
            box-shadow: 0 0 14px 10px black;
            padding: 0 40px;

        }

        main .input-group-prepend span {
            min-width: 150px;
            text-align: center;
            display: inline;
            border-radius: 0 5px 5px 0 !important;
            font-size: 1.5rem !important;
            font-weight: 400 !important;

        }

        main input {
            text-align: right !important;
            padding-right: 8px;
            font-size: 1.3rem !important;
            height: auto !important;
        }

        main h2 {
            text-shadow: 1px 2px yellow, 0px -1px yellow
        }

        .h1 {
            font-size: 2.5rem;
        }
    </style>
@endsection
@section('content')
    <main class='text-center mx-auto container py-4'>
        @if($checkDevice!='' && $expire_date =='no')
            <form id="loginForm" action='{{ route('login') }}' method='post'>
                @csrf
                <h2 class='pb-3 h1 font-ar text-white font-weight-bold animated bounceInDown fast'>
                    <span>تسجيل الدخول</span>
                </h2>
                <div class="input-group mb-3 animated fadeIn">
                    <input id='email' type="text" value='{{old('email')}}' name='email' required class="form-control"
                           placeholder="اسم المستخدم">
                    <div class="input-group-prepend d-none d-md-flex ">
                        <span class="input-group-text font-ar">اسم المستخدم</span>
                    </div>
                </div>
                <div class="input-group mb-3 animated fadeIn">
                    <div class="input-group-prepend">
                        <label class="input-group-text">
                            <i class="fas fa-eye fa-2x pointer"></i>
                        </label>
                    </div>
                    <input id="password" type="password" value='{{old('password')}}' name='password' required
                           data-type='password' class="form-control" placeholder="كلمة المرور">
                    <div class="input-group-prepend d-none d-md-flex">
                        <span class="input-group-text font-ar">كلمة المرور</span>
                    </div>
                </div>
                <div>
                    <button type='submit'
                            class='btn btn-success px-4 py-2 font-weight-bold animated bounceInLeft
                                hvr-icon-wobble-horizontal'>
                        <span class='h3 font-weight-bold font-ar mr-2'>دخول</span> <i
                            class="fas fa-sign-in-alt hvr-icon"></i>
                    </button>
                </div>
            </form>
        @endif
    </main>

@endsection
@section('js')
    <script defer>
        design.useSound();
        /*set main.container in center screen*/
        $('main.container').css({
            marginTop: ($(window).height() - $('main.container').outerHeight(true)) / 2
        });
        $(window).resize(function () {
            $('main.container').css({
                marginTop: ($(window).height() - $('main.container').outerHeight(true)) / 2
            });
        });

        design.show_password();

        //submit form
        $('#loginForm').submit(function (e) {
            design.useSound();
            $('#load').css('display', 'block');
            design.check_submit($('#loginForm'), e);
        });

        $('#email').select();

        /*show message if error login*/
        @if($errors->any())
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'اسم المستخدم او كلمة المرور خاطئة حاول مرة اخري !',
        });
        @endif
        <?php
        if ($check_db == 'error') {
            echo "
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'غير مصرح لهذا الجهاز بالتعامل مع البرنامج برجاء الاتصال بالدعم الفنى للشركة علي 01018030420 ! (قاعدة البيانات غير موجودة!)',
                    onAfterClose:function(){
                        $('#load').css('display', 'block');
                        window.location='.';
                    }
                });design.useSound('error');";
            echo "console.log('" . substr(exec('getmac'), 0, 17) . "');";
            echo "console.log('" . \Illuminate\Support\Facades\Request::ip() . "');";
        } else if ($checkDevice == '') {
            echo "
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'غير مصرح لهذا الجهاز بالتعامل مع البرنامج برجاء الاتصال بالدعم الفنى للشركة علي 01018030420 ! ',
                        onAfterClose:function(){
                            $('#load').css('display', 'block');
                            window.location='.';
                        }
                    });design.useSound('error');";
            echo "console.log('" . substr(exec('getmac'), 0, 17) . "');";
            echo "console.log('" . \Illuminate\Support\Facades\Request::ip() . "');";
        }
        ?>

        @if($expire_date !='no')
            <?php
            echo "
        swal({
            type: 'error',
            title: 'Oops...',
            text: 'حصل خطاء فى التصريح الخاص بكم برجاء الإتصال بالدعم الفنى للشركة على 01018030420  ! ',
            onAfterClose:function(){
                $('#load').css('display', 'block');
                window.location='.';
            }
        });design.useSound('error');";
            ?>
        @endif
    </script>

    <script>
        //check device
        $.ajax({
            url: '{{route('users.check_device')}}',
            method: 'POST',
            data: {
                serial: Cookie.get('device_serial'),
            },
            dataType: 'JSON',
            success: function (data) {
                if (data == 'change') {
                    alertify.success('تم تغير ال Ip بنجاح');
                    design.useSound('info');
                }
            },
            error: function (e) {

            }
        });
    </script>
    <script defer>
        //redirect to home page if login by other login tap
        var checkStateLogin=true;
        window.setInterval(function () {
            if (checkStateLogin && Cookie.get('succsess_login') == true) {
                checkStateLogin=false;
                window.open('{{route('home')}}', '_parent');
            }
        },1000);
    </script>
@endsection







