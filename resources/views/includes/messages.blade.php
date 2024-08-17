<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 21/01/2019
 * Time: 05:21 م
 */ ?>
@if(Session::has('success'))
    <script>
        Swal.fire(
            'تمت العملية بنجاح',
            '{{Session::get('success')}}',
            'success'
        );
        design.useSound();
    </script>
@endif
@if(Session::has('fault'))
    <script>
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            html: '{{Session::get('fault')}}',
        });
        design.useSound('error');
    </script>
@endif
@if($errors->any() )
    <script>
        @if (Route::currentRouteAction()=='App\Http\Controllers\Auth\LoginController@showLoginForm')
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'اسم المستخدم او كلمة المرور خاطئة حاول مرة اخري !',
        });
        @else
        Swal.fire({
            type: 'error',
            title: 'Oops...',
            text: 'حصل خطاء في العملية!',
        });
        @foreach($errors->all() as $e)
        alertify.error("{{$e}}");
        @endforeach


        @endif
        design.useSound('error');
    </script>
@endif
