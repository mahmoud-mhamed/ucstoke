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
    تعديل
    {{$r->is_supplier?'مورد':''}}
    {{$r->is_customer?'عميل':''}}
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

        ul li a{
            display: block!important;
        }
        input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
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
                <h1 class='text-center mb-4'>تعديل
                    {{$r->is_supplier?'مورد':''}}
                    {{$r->is_customer?'عميل':''}}
                    بإسم
                    <span class="font-en text-danger"> {{$r->name}}</span>
                </h1>
                <form id='formAddAccount'
                      action='{{route('accounts.update',$r->id)}}' class='h5'
                      method='post'>
                    @csrf
                    @method('PUT')
                    <div
                        class='form-group row {{Auth::user()->type==1 || Auth::user()->allow_edit_account_type?'':'d-none'}}'>
                        <label class='col-sm-3 pt-2 text-md-left'>النــــــــــــــــــــــــــــــوع</label>
                        <div class='col-sm-9'>
                            <label class="checkbox-inline pl-4 mr-3 pointer" dir="ltr">مورد<input type="checkbox"
                                                                                                  {{Hash::check('sup_cust',$permit->sup_cust)?'':'disabled'}}
                                                                                                  {{$r->is_supplier?'checked':''}}
                                                                                                  {{$r->account!=0?'disabled':''}}
                                                                                                  id="checkSupplier"
                                                                                                  name="is_supplier"
                                                                                                  value="1"></label>
                            <label class="checkbox-inline pl-4 pointer" dir="ltr">عميل<input type="checkbox"
                                                                                             id="checkCustomer"
                                                                                             {{Hash::check('sup_cust',$permit->sup_cust)?'':'disabled'}}
                                                                                             {{$r->is_customer?'checked':''}}
                                                                                             {{$r->account!=0 && !$r->is_supplier?'disabled':''}}
                                                                                             name="is_customer"
                                                                                             value="1"></label>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'> اسم الشخص أو الجهة</label>
                        <div class='col-sm-9'>
                            <input type='text'
                                   id="inputName"
                                   {{Auth::user()->type==1 || Auth::user()->allow_edit_account_name?'':'readonly'}}
                                   data-validate='min:3' data-patternType='min' value="{{$r->name}}" autofocus required
                                   name='name'
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
                                   {{Auth::user()->type==1 || Auth::user()->allow_edit_account_tel?'':'readonly'}}
                                   {{$setting->allow_account_without_tel?'':'required'}}
                                   name='tel'
                                   value="{{$r->tel}}"
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الـعـنـــــــــــــــــــــــوان</label>
                        <div class='col-sm-9'>
                            <input type='text' name='address' value="{{$r->address}}" data-validate='name'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الحــــسـاب الحـــــــالى</label>
                        <div class="input-group col-sm-9">
                            <input type="text" style="height: 45px" value="{{$r->account}}" disabled
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>مـلاحـظــــــــــــــــــــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' name='note'>{{$r->note}}</textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit' id="button_submit"
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>تعديل</span>
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
    <script src='{{ asset('js/lib/select_search.js') }}'></script>
    <script defer>
        design.useSound();
        @if($r->is_supplier)
        $('#checkSupplier').prop('checked', true);
        @else
        $('#checkSupplier').prop('checked', false);
        @endif
        @if($r->is_customer==1)
        $('#checkCustomer').prop('checked', true);
        @else
        $('#checkCustomer').prop('checked', false);
        @endif

        $('#inputName').keyup(function () {
            select_search($('#inputName').val(), $('#selectAccountName option'), 'لا يوجد شخص بهذا الاسم');
            $('#selectAccountName').trigger('change');
        });
        $('#inputName').trigger('keyup');

    </script>
    <script defer>
        validateByAttr();

        alertify.log('عند تعديل نوع الشخص يمكن تحويل المورد إلى مورد عميل والعكس </br> ,ولكن  عند تحويل عميل إلى مورد أو مورد عميل يجب أن يكون حسابة الحالى (0) ','success',0);

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
                alertify.error('برجاء تحديد نوع للشخص أو المؤسسة المراد تعديله ');
                design.useSound('info');
                return;
            }

            $('#checkSupplier,#checkCustomer').removeAttr('disabled');

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
