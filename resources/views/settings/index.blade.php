<?php
$permit = \App\Permit::first();
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 22/01/2019
 * Time: 10:56 م
 */ ?>
@extends('layouts.app')
@section('title')
    ضبط خصائص البرنامج
@endsection
@section('css')
    <style>
        input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
        }
        input[type='text']{
            font-weight: bolder;
        }

        .data-container label {
            cursor: pointer;
            margin: 10px 20px;
        }

        div > h1 i, div > h1 {
            transition: all ease-in-out 0.3s;
            color: #7c7c7d;;
            text-shadow: 1px 1px #1e4858, 1px 1px 4px #5624e4;
        }

        div.show > h1 i {
            transform: rotate(-90deg);
            color: white;
        }

        div.show > h1 {
            color: white;
        }

        #contentAllData > div > h1 {
            cursor: pointer;
        }
        #contentAllData >div {
            display: inline-block;
            margin-left: 90px;
        }
        #contentAllData >div.show {
            display: block;
            margin-left: 0px;
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4 px-2 pb-2'>
        <form method="post" id="formSetting" action="{{route('settings.update',1)}}">
            @csrf
            @method('put')
            <section class='animated text-center fadeInDown ml-auto faster px-1 px-md-4'>
                <h1 class='text-white font-weight-bold pb-3'>ضبط خصائص البرنامج</h1>
                <div  id="contentAllData">
                    <div class='show'>
                        <h1 data-changeshow class='font-weight-bold text-right mr-2'>إعدادات عامة
                            <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                               style="top: 10px;right: 10px"></i></span>
                        </h1>
                        <div class='box-shadow bg-white data-container text-dark'>
                            <div class="h3 " dir="rtl">
                                <label class="checkbox-inline" dir="ltr">
                                    عرض المبلغ فى الدرج عند الوقوف على إسم الشركة إذا كان هناك مال فى الدرج
                                    <input type="checkbox" name="show_treasury_value_in_header" {{$setting->show_treasury_value_in_header?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline d-none" dir="ltr">
                                    إستخدام الصوت فى البرنامج
                                    <input type="checkbox" name="allow_sound" {{$setting->allow_sound?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    إستخدام
                                    <span class="font-en">3</span>
                                    خانات للكسور فى السعر
                                    <input type="checkbox" name="use_small_price" {{$setting->use_small_price?'checked':''}} value="1">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <h1 data-changeshow class='font-weight-bold text-right mr-2'>إعدادات الموردين والعملاء
                            <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                               style="top: 10px;right: 10px"></i></span>
                        </h1>
                        <div class='box-shadow bg-white data-container text-dark'>
                            <div class="h3 " dir="rtl">
                                <label class="checkbox-inline" dir="ltr">
                                    السماح بتكرار إسم المورد
                                    <input type="checkbox" name="allow_repeat_supplier_name" {{$setting->allow_repeat_supplier_name?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    السماح بتكرار إسم العميل
                                    <input type="checkbox" name="allow_repeat_customer_name" {{$setting->allow_repeat_customer_name?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    السماح بتكرار رقم الهاتف فى المودين والعملاء
                                    <input type="checkbox" name="allow_repeat_tell_account" {{$setting->allow_repeat_tell_account?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    السماح بمورد أو عميل بدون رقم هاتف
                                    <input type="checkbox" name="allow_account_without_tel" {{$setting->allow_account_without_tel?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    السماح بإضافة مورد أو عميل بحساب سالب
                                    <input type="checkbox" name="allow_account_with_negative_account" {{$setting->allow_account_with_negative_account?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    السماح بدفع مال لمورد أو مورد عميل بقيمة سالب
                                    <input type="checkbox" name="allow_pay_money_to_account_with_negative_account" {{$setting->allow_pay_money_to_account_with_negative_account?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    السماح بأخذ مال من عميل أو مورد عميل بقيمة سالب
                                    <input type="checkbox" name="allow_take_money_from_account_with_negative_account" {{$setting->allow_take_money_from_account_with_negative_account?'checked':''}} value="1">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 data-changeshow class='font-weight-bold text-right mr-2'>المصروفات
                            <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                               style="top: 10px;right: 10px"></i></span>
                        </h1>
                        <div class='box-shadow bg-white data-container text-dark'>
                            <div class="h3 " dir="rtl">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="allow_add_expenses_without_subtract_from_treasury" {{$setting->allow_add_expenses_without_subtract_from_treasury?'checked':''}} value="1">
                                    السماح بإضافة مصروفات لا تخصم من الدرج
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 data-changeshow class='font-weight-bold text-right mr-2'>المنتجات
                            <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                               style="top: 10px;right: 10px"></i></span>
                        </h1>
                        <div class='box-shadow bg-white data-container text-dark'>
                            <div class="h3 " dir="rtl">
                                <label class="checkbox-inline" dir="rtl">
                                    <input type="checkbox" name="edit_auto_for_default_min_qte_unit" {{$setting->edit_auto_for_default_min_qte_unit?'checked':''}} value="1">
                                    التعديل التلقائى للقيمة الإفتراضية لأقل كمية من وحدة عند إضافة منتج جديد
                                </label>
                                <label class="checkbox-inline {{Hash::check('use_price2',$permit->use_price2)?'':'d-none'}}" dir="rtl">
                                    إسم سعر البيع الأول
                                    <input type="text" data-validate='min:3' data-patternType='min' class="form-control form-inline" required name="price1_name" value='{{$setting->price1_name}}'>
                                </label>
                                <label class="checkbox-inline {{Hash::check('use_price2',$permit->use_price2)?'':'d-none'}}" dir="rtl">
                                    إسم سعر البيع الثانى
                                    <input type="text" data-validate='min:3' data-patternType='min' class="form-control form-inline" required name="price2_name" value='{{$setting->price2_name}}'>
                                </label>
                                <label class="checkbox-inline {{Hash::check('use_price3',$permit->use_price3)?'':'d-none'}}" dir="rtl">
                                    إسم سعر البيع الثالث
                                    <input type="text" data-validate='min:3' data-patternType='min' class="form-control form-inline" required name="price3_name" value='{{$setting->price3_name}}'>
                                </label>
                                <label class="checkbox-inline {{Hash::check('use_price4',$permit->use_price4)?'':'d-none'}}" dir="rtl">
                                    إسم سعر البيع الرابع
                                    <input type="text" data-validate='min:3' data-patternType='min' class="form-control form-inline" required name="price4_name" value='{{$setting->price4_name}}'>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h1 data-changeshow class='font-weight-bold text-right mr-2'>الفواتير
                            <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                               style="top: 10px;right: 10px"></i></span>
                        </h1>
                        <div class='box-shadow bg-white data-container text-dark'>
                            <div class="h3 " dir="rtl">
                                <label class="checkbox-inline" dir="ltr">
                                    تعديل السعر الإفتراضى لمنتج عند التعديل فى فاتورة شراء
                                    <input type="checkbox" name="auto_update_price_product_bill_buy" {{$setting->auto_update_price_product_bill_buy?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    تعديل السعر الإفتراضى لمنتج عند التعديل فى فاتورة بيع
                                    <input type="checkbox" name="auto_update_price_product_bill_sale" {{$setting->auto_update_price_product_bill_sale?'checked':''}} value="1">
                                </label>
                                <label class="checkbox-inline" dir="ltr">
                                    طباعة وحدة المنتج فى الفاتورة
                                    <input type="checkbox" name="show_unit_when_print_bill" {{$setting->show_unit_when_print_bill?'checked':''}} value="1">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit'
                                    class='font-weight-bold  mt-2 pb-5 form-control btn btn-success animated bounceInRight fast'>
                                <span class='font-weight-bold h1'>حفظ</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <button type="button" onclick="window.location.reload('true');"
                                    class='font-weight-bold mt-2  pb-5 form-control text-white btn btn-success animated bounceInLeft fast'>
                                <span class='font-weight-bold h1'>إلغاء</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </main>
@endsection
@section('js')
    <script defer>
        design.useNiceScroll();
        design.useSound();
        validateByAttr();
        /*toggle between div*/
        $('#contentAllData h1[data-changeshow]').click(function () {
            // $(this).parent().addClass('show').siblings().removeClass('show');
            if($(this).parent().hasClass('show')){
                $(this).parent().removeClass('show').siblings().removeClass('show');
            }else{
                $(this).parent().addClass('show').siblings().removeClass('show');
            }
            changeShow(300);
            design.updateNiceScroll();
            design.useSound('success');
        });
        changeShow();

        function changeShow(time = 300) {
            $('#contentAllData>div').each(function () {
                if ($(this).hasClass('show')) {
                    $(this).children('div').slideDown(time);
                } else {
                    $(this).children('div').slideUp(time);
                }
            });
        }

        //update check
        $('input[type="checkbox"]').each(function () {
           if($(this).attr('checked')){
               $(this).prop('checked',true);
           } else{
               $(this).prop('checked',false);
           }
        });

        $('#formSetting').submit(function (e) {
            design.check_submit($(this),e);
        });
    </script>
@endsection
