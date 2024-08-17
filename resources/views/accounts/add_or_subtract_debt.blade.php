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
    {{$type==1?'أخذ مال':'دفع مال'}}
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

        div.error-price, div.error-negative_price {
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
                <h1 class='text-center mb-4 tooltips' data-placement='bottom'
                    title='{{$r->is_supplier==1?'سيتم خصم المبلغ من الحساب وإضافتة إلى الدرج':'سيتم خصم المبلغ من الحساب وخصمة من الدرج'}}'>{{$type==1?'أخذ مال من':'دفع مال  إلى'}}
                    {{$r->is_supplier?'مورد':''}}
                    {{$r->is_customer?'عميل':''}}
                    بإسم
                    <span class="font-en"> {{$r->name}}</span>
                </h1>
                <form id='formAdjustAccount'
                      action='{{route('accounts.post_add_or_subtract_debt',[$r->id,$type])}}' class='h5'
                      method='post'>
                    @csrf
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'> اسم الشخص أو الجهة</label>
                        <div class='col-sm-9'>
                            <input type='text'
                                   id="inputName" disabled
                                   data-validate='min:3' data-patternType='min' value="{{$r->name}}" autofocus required
                                   name='name'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الحــــسـاب الحـــــــالى</label>
                        <div class="input-group col-sm-9">
                            <input type="text" style="height: 45px" id="input_max_account" value="{{$r->account}}" disabled
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>المبلغ المـــراد
                            {{$type==1?'أخـــــذه':'دفعــــه'}}</label>
                        <div class="input-group col-sm-9">
                            <input type="text" id="account"
                                   value="0"
                                   onclick="$(this).select();"
                                   onkeyup="getChangeInAccount();"
                                   <?php
                                   if ($type == 1) {
                                       $tempSettingTakeMoney_validate_name = !$setting->allow_take_money_from_account_with_negative_account ? 'price' : 'negative_price';
                                       echo "data-validate='$tempSettingTakeMoney_validate_name'" . ' ' .
                                           "data-patternType='$tempSettingTakeMoney_validate_name'";
                                   } else {
                                       $tempSettingTakeMoney_validate_name = !$setting->allow_pay_money_to_account_with_negative_account ? 'price' : 'negative_price';
                                       echo "data-validate='$tempSettingTakeMoney_validate_name'" . ' ' .
                                           "data-patternType='$tempSettingTakeMoney_validate_name'";
                                   }
                                   ?>
                                   style="height: 45px" required
                                   name="price"
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>القيمةالجديدة للحساب</label>
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
                            <button type='submit' id="submitForm"
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>حفظ</span>
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

        var oldAccount = '{{$r->account}}';

        var stop_submit=false;
        function getChangeInAccount() {
            $('#inputChangeValue').val((oldAccount {{$r->is_supplier && $type==1?'-':''}} - $('#account').val()));
            if($('#account').val()*1 > $('#input_max_account').val()*1){
                stop_submit=true;
            }else{
                stop_submit=false;
            }
        }

        getChangeInAccount();

        $('#formAdjustAccount').submit(function (e) {
            if ($('#account').val() * 1 == 0) {
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء كتابة مبلغ صحيح');
                return;
            }

            if($('#account').val()*1 > $('#input_max_account').val()*1){
                design.useSound('info');
                $(this).confirm({
                    text: "المبلغ المدفوع أكبر من قيمة الحساب هل تريد الإضافة وتعديل الحساب بالباقى؟",
                    title: "التحقق قبل الإضافة ؟",
                    confirm: function (button) {
                        stop_submit=false;
                        $('#formAdjustAccount').trigger('submit');
                    },
                    cancel: function (button) {
                        alertify.success('تم إلغاء الإضافة ');
                        return;
                    },
                    post: true,
                    confirmButtonClass: "btn-danger",
                    cancelButtonClass: "btn-default",
                    dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                });
            }
            if(!stop_submit){
                design.check_submit($(this), e);
            }else{
                e.preventDefault();
            }

        });
    </script>
@endsection
