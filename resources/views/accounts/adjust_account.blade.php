<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 23/01/2019
 * Time: 01:52 م
 */
?>
@extends('layouts.app')
@section('title')
    ضبط حساب مورد أو عميل أو مورد عميل
@endsection
@section('css')
    <style>
        label {
            font-size: 1.5rem;
        }

        button, a {
            height: 47px !important;
        }

        button.dropdown-toggle {
            padding-top: 0px;
        }

        input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
        }
        div.error-price,div.error-negative_price{
            width: 25%;
            padding-right: 15px;
        }
        input,span.input-group-text{
            font-size: 1.2rem!important;
        }
    </style>
@stop
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='box animated fadeInDown faster container text-right mb-2'>
            <div class='container  pt-3 pb-3'>
                <h1 class='text-center mb-4'>ضبط حساب
                    {{$r->is_supplier?'مورد':''}}
                    {{$r->is_customer?'عميل':''}}
                    بإسم
                   <span class="font-en"> {{$r->name}}</span>
                </h1>
                <form id='formAdjustAccount'
                      action='{{route('accounts.adjust_account',$r->id)}}' class='h5'
                      method='post'>
                    @csrf
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'> اسم الشخص أو الجهة</label>
                        <div class='col-sm-9'>
                            <input type='text'
                                   id="inputName" disabled
                                   data-validate='min:3' data-patternType='min' value="{{$r->name}}" autofocus required name='name'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الحــــسـاب الحـــــــالى</label>
                        <div class="input-group col-sm-9">
                            <input type="text"   style="height: 45px"  value="{{$r->account}}" disabled
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>القيمة الجديدة للحساب</label>
                        <div class="input-group col-sm-9">
                            <input type="text" id="account"
                                   value="{{$r->account}}"
                                   onclick="$(this).select();"
                                   onkeyup="getChangeInAccount();"
                                   data-validate='{{!$setting->allow_account_with_negative_account?'price':'negative_price'}}'
                                   data-patternType='{{!$setting->allow_account_with_negative_account?'price':'negative_price'}}'
                                   style="height: 45px" name='account'  required
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>قيمة التغير فى الحساب</label>
                        <div class="input-group col-sm-9">
                            <input type="text" disabled id="inputChangeValue"
                                   style="height: 45px"
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>مـــــــــــــــــلاحـــــظـــــة</label>
                        <div class="input-group col-sm-9">
                            <textarea class='form-control text-right tooltips' name="note"
                                      style="font-size: 1.22rem;height: 94px" title="ملاحظة العملية"
                                      data-placement="bottom" rows='2'></textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='button' id="submitForm"
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>ضبط الحساب</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <a href='{{route('accounts.index')}}'
                               class='font-weight-bold mt-2 mb-2 form-control text-white btn btn-success animated bounceInLeft fast'>
                                <span class='h4 font-weight-bold'>الغاء</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
@section('js')
    <script defer>
        validateByAttr();
        design.useSound();
        design.useNiceScroll();

        var oldAccount='{{$r->account}}';
        function getChangeInAccount() {
            $('#inputChangeValue').val(($('#account').val()-oldAccount));
        }
        getChangeInAccount();


        $('#submitForm').click(function (e) {
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد ضبط الحساب على القيمة المحددة (لن يتم التعديل فى الدرج)؟",
                title: "ضبط حساب",
                confirm: function (button) {
                    $('#formAdjustAccount').submit();
                },
                cancel: function (button) {

                },
                post: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-default",
                dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
            });
        });
    </script>
@endsection
