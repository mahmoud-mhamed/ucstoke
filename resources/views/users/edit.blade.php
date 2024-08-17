<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 20/01/2019
 * Time: 12:11 م
 */?>
@extends('layouts.app')
@section('title')
    تعديل مستخدم
@endsection
@section('css')
    <style>
        label{
            font-size: 1.5rem;
        }
        button,a{
            height: 47px!important;
        }
    </style>
@stop
@section('content')
<!--edit user-->
<main dir='rtl' class='pt-4 px-3 pb-2 text-right'>
    <section class='box animated fadeInDown ml-auto faster container ' style='max-width: 1000px!important;'>
        <div class='container  pt-3 pb-3'>
            <h1 class='text-center mb-4'>تعديل المستخدم</h1>
            <form class='editMember h5 ' method='post' action='{{route('users.update',$user->id)}}' autocomplete='off'>
                @csrf
                @method('PUT')
                <!--fullName-->
                <div class='form-group row'>
                    <label class='col-sm-3 text-md-left pt-2'>اســــم المستـخــدم</label>
                    <div class='col-sm-9'>
                        <input data-validate='min:3' {{(Auth::user()->type==2 &&Auth::user()->allow_edit_account_name==false)?'readonly':''}} data-patternType='min' type='text' required name='name' value='{{$user->name}}'
                               class='form-control'>
                    </div>
                </div>
                <!--userName-->
                <div class='form-group row'>
                    <label class='col-sm-3 text-md-left pt-2'>اسم تسجيل الدخول</label>
                    <div class='col-sm-9 has-error'>
                        <input data-validate='min:3' {{(Auth::user()->type==2 &&Auth::user()->allow_edit_account_email==false)?'readonly':''}} data-patternType='min' type='text'  required name='email' value='{{$user->email}}'
                               class='form-control form-control-danger'>
                    </div>
                </div>
                <!--password-->
                <div class='form-group row'>
                    <label class='col-sm-3 text-md-left pt-2'>كلـمـــــة المــــــــرور</label>
                    <div class='col-sm-9'>
                        <div class='input-group-prepend password'>
                            <input data-validate='min:3' data-patternType='min' data-type='password' type='password' autocomplete='off'  name='password' value=''
                                   class='form-control'>
                            <span class='input-group-text pointer'><i class='fa fa-eye '></i></span>
                        </div>
                    </div>
                </div>
                <!--select-->
                    @if (Auth::user()->type==1)
                        <div class='form-group row'>
                            <label class='col-sm-3 text-md-left pt-2'>نــــوع المستــخـــدم</label>
                            <div class='col-sm-9'>
                                <select name='type' class='form-control-lg custom-select-lg pr-5 custom-select'>
                                    @if(Auth::user()->type==2)
                                        <option  value='2'>مستخدم عادي</option>
                                    @else
                                        <option  value="2" {{$user->type==2?'selected':''}}>مستخدم عادي</option>
                                        <option  value="1" {{$user->type==1?'selected':''}}>VIP Account</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    @endif
                <!--button-->
                <div class='form-group row '>
                    <div class='col-sm-6'>
                        <button type='submit'  class='font-weight-bold mt-2 mb-2 form-control btn btn-success  animated bounceInRight fast'>
                            <span class='h3 font-weight-bold'>حفظ التعديل</span>
                        </button>
                    </div>
                    <div class='col-sm-6'>
                        <a  href='{{Auth::user()->type==1?route('users.index'):route('home')}}'
                            class='font-weight-bold text-white mt-1 mt-md-2 mb-2 form-control btn btn-success  animated bounceInLeft fast'>
                            <span class='h3 font-weight-bold'>الغاء</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection

@section('js')
{{--    <script src='{{asset('js/lib/validate.js')}}'></script>--}}
    <script defer>
        design.show_password();

        validateByAttr();

    </script>
@endsection

