<?php
$permit=\App\Permit::first();
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 23/01/2019
 * Time: 01:52 م
 */
?>
@extends('layouts.app')
@section('title')
    إضافة مورد أو عميل
    {{Hash::check('sup_cust',$permit->sup_cust)?'أو مورد عميل':''}}
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
        main input{
            font-size: 1.5rem!important;
        }
    </style>
@stop
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='box animated fadeInDown faster container text-right mb-2'>
            <div class='container  pt-3 pb-3'>
                <h1 class='text-center mb-4'>إضافة مورد أو عميل
                    {{Hash::check('sup_cust',$permit->sup_cust)?'أو مورد عميل':''}}
                </h1>
                <form id='formAddAccount'
                      action='{{route('accounts.store')}}' class='h5'
                      method='post'>
                    @csrf
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>النــــــــــــــــــــــــــــــوع</label>
                        <div class='col-sm-9'>
                            <label class="checkbox-inline pl-4 mr-3 pointer" dir="ltr">مورد<input type="checkbox" id="checkSupplier" name="is_supplier" value="1"></label>
                            <label class="checkbox-inline pl-4 pointer" dir="ltr">عميل<input type="checkbox" id="checkCustomer" name="is_customer" value="1"></label>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'> اسم الشخص أو الجهة</label>
                        <div class='col-sm-9'>
                            <input type='text'
                                   id="inputName"
                                   data-validate='min:3' data-patternType='min' autofocus required name='name'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>الأسمــــاء المــوجــــودة</label>
                        <div class='col-sm-9'>
                            <select id='selectAccountName' class="selectpicker  show-tick form-control"
                                    data-live-search="true">
                                @foreach($accounts as $a)
                                    <option data-style="padding-bottom: 50px!important;" data-subtext="({{$a->is_supplier?'مورد ':''}} {{$a->is_customer?'عميل ':''}})">{{$a->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>رقـــــــــــم الـهــــاتــف</label>
                        <div class='col-sm-9'>
                            <input type='text' data-validate='tel' data-patternType='tell'
                                   {{$setting->allow_account_without_tel?'':'required'}}
                                   name='tel'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الـعـنـــــــــــــــــــــــوان</label>
                        <div class='col-sm-9'>
                            <input type='text' name='address' data-validate='name'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الحــــسـاب الســـــابـق</label>
                        <div class="input-group col-sm-9">
                            <input type="text"  onclick="$(this).select();" data-validate='{{!$setting->allow_account_with_negative_account?'price':'negative_price'}}' data-patternType='{{!$setting->allow_account_with_negative_account?'price':'negative_price'}}'  style="height: 45px" name='account' value="0" required
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>مـلاحـظــــــــــــــــــــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' name='note'></textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit' id="button_submit"
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>إضافة</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <a href='{{route('home')}}'
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
    <script src='{{ asset('js/lib/select_search.js') }}'></script>
    <script defer>
        //select type if add has old type
        design.useSound();
        var type=location.search;
        if(type.indexOf('is_supplier')!=-1){
            $('#checkSupplier').prop('checked',true);
        }else{
            $('#checkSupplier').prop('checked',false);
        }
        if(type.indexOf('is_customer')!=-1){
            $('#checkCustomer').prop('checked',true);
        }else{
            $('#checkCustomer').prop('checked',false);
        }

        @if(Hash::check('sup_cust',$permit->sup_cust)==false)
            $('#checkCustomer').change(function () {
            if ($(this).prop('checked')){
                $('#checkSupplier').prop('checked',false);
            }
        });
        $('#checkSupplier').change(function () {
            if ($(this).prop('checked')){
                $('#checkCustomer').prop('checked',false);
            }
        });
            @endif

        $('#inputName').keyup(function () {
            select_search($('#inputName').val(), $('#selectAccountName option'), 'لا يوجد شخص بهذا الاسم');
            $('#selectAccountName').trigger('change');
        });
    </script>
    <script defer>
        validateByAttr();
        $('#checkSupplier,#checkCustomer').change(function () {
            design.useSound('info');
            if ($('#checkSupplier').prop('checked') && $('#checkCustomer').prop('checked')) {
                alertify.success('الحساب للمورد العميل (هو القيمة التى أدين بها للمورد العميل) ');
            }else if($('#checkSupplier').prop('checked')){
                alertify.success('الحساب للمورد هو القيمة التى أدين بها للمورد ');
            }else if($('#checkCustomer').prop('checked')){
                alertify.success('الحساب للعميل هو قيمة الدين على العميل ');
            }
        });
        design.disable_input_submit_when_enter('#formAddAccount input');
        design.click_when_key_add('#button_submit');
        $('#formAddAccount').submit(function (e) {
            if (!$('#checkSupplier').prop('checked') && !$('#checkCustomer').prop('checked')) {
                e.preventDefault();
                alertify.error('برجاء تحديد نوع الشخص أو المؤسسة المراد إضافتة ');
                design.useSound('info');
                return;
            }

            if ($('#inputName').val().length < 3) {
                e.preventDefault();
                alertify.error('برجاء كتابة 3 أحرف على الأقل فى إسم الشخص ');
                design.useSound('info');
                return;
            }
            design.check_submit($(this), e);
        });
        design.useNiceScroll();
    </script>
@endsection
