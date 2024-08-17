<?php
$permit = \App\Permit::first();

/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 02:16 م
 */ ?>
@extends('layouts.app')
@section('title')
       إنشاء
            {{$type==0?'فاتورة شراء':($type==1?'فاتورة بيع':'عرض أسعار بيع')}}
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

        input[type='checkbox'], input[type="radio"] {
            transform: scale(2);
            margin-left: 10px;
        }

        div.error-price, div.error-negative_price {
            width: 25%;
            padding-right: 15px;
        }

        ::placeholder {
            font-size: 1.5rem;
        }

        input ::placeholder {
            text-align: center;
        }

        .h0 {
            font-size: 1.5rem;
            height: 47px;
        }

        #divContainerMoney .col {
            min-width: 350px;
        }

        .invalid-feedback {
            position: absolute;
            width: 100% !important;
            z-index: 3;
        }

        #div_container_new_account .invalid-feedback {
            text-align: left;
            padding-left: 5px;
        }

        .overlay {
            position: fixed;
            z-index: 50;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, .7);
        }

        #divSettingForAddExistProductToDetailsWithSameUnit, #divSettingForDefaultValForAccount {
            position: fixed;
            top: 200px;
            background: #eee;
            z-index: 51;
            margin-left: calc((100vw - 800px) / 2);
            width: 800px;
            border-radius: 20px;
        }

        #divEditRowInTable {
            position: fixed;
            top: 200px;
            background: #eee;
            z-index: 51;
            margin-left: calc((100vw - 800px) / 2);
            width: 800px;
            border-radius: 20px;
            border: 25px;
        }
    </style>
    <style>
        .tableFixHead {
            overflow-y: auto;
            max-height: 50vh !important;
        }

        .tableFixHead thead th {
            position: sticky;
            top: 0;
        }

        /* Just common table stuff. Really. */
        #mainTable {
            border-collapse: collapse;
            width: 100%;
        }

        #mainTable td {
            padding: 0px;
            padding-top: 5px
        }
    </style>
    <style>
        #section_bill_container_check input[type='checkbox'], #section_bill_container_check input[type="radio"] {
            transform: scale(2);
            margin-left: 10px;
        }

        #section_bill_type select {
            height: 53px !important;
            font-size: 1.5rem;
        }

        #section_bill_design .b {
            border: 2px black solid;
        }

        #section_bill_design .b-left {
            border-left: 2px black solid;
        }

        #section_bill_design .width-15 {
            width: 15%;
        }

        #section_bill_design th {
            font-weight: 900;
        }

        #section_bill_design input {
            padding-right: 10px;
            width: 100%;
            border: none;
        }

        #section_bill_design span[data-type='billNumber'], span[data-type='date'] {
            cursor: pointer;
        }

        #section_bill_design #tableData input {
            text-align: center;
        }

        #section_bill_design table tr td {
            padding: 5px 15px;
        }

        #section_bill_design table tr * {
            white-space: nowrap;
        }

        #section_bill_design #tableData tbody tr td:nth-child(2) {
            white-space: normal !important;
        }

        #section_bill_design input {
            min-height: 37px;
        }
    </style>
    <style>
        #section_small_bill_design .b {
            border: 2px black solid;
        }

        #section_small_bill_design .small_bold {
            font-weight: 600 !important;
        }

        #section_small_bill_design .text-underline {
            text-decoration: underline !important;
        }

        #section_small_bill_design td, #section_small_bill_design tr, #section_small_bill_design th {
            border: .2px black solid;
        }


        #section_small_bill_design th {
            font-weight: 900;
        }

        #section_small_bill_design th, #section_small_bill_design td {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        #section_small_bill_design input {
            padding-right: 10px;
            width: 100%;
            border: none;
        }

        #section_small_bill_design span[data-type='billNumber'], #section_small_bill_design span[data-type='date'] {
            cursor: pointer;
        }

        #section_small_bill_design #tableData1 input {
            text-align: center;
        }

        #section_small_bill_design table tr td, #section_small_bill_design table tr th {
            padding: 5px 15px;
        }

        #div_container_type_price label {
            font-size: 1.7rem;
        }
    </style>
@endsection
@section('content')
    <div id="divContainerSettingForAddExistProductToDetailsWithSameUnit" class="d-none">
        <div class="overlay"
             onclick="$('#divContainerSettingForAddExistProductToDetailsWithSameUnit').addClass('d-none');design.useSound();"></div>
        <div id="divSettingForAddExistProductToDetailsWithSameUnit" class="text-center pt-2 box-shadow" dir="rtl">
            <h1>عند إضافة منتج بنفس الوحدة إلى الفاتورة</h1>
            <div>
                <label class="checkbox-inline pl-4 mr-3 pointer" dir="ltr">
                    إضافة الكمية إلى الكمية الموجودة<input type="radio" value="0" checked
                                                           name="radioSettingAddExistProduct">
                </label>
                <label class="checkbox-inline pl-4 pointer" dir="ltr">عدم الإضافة إلى الفاتورة
                    <input type="radio" value="1" name="radioSettingAddExistProduct">
                </label>
                <label class="checkbox-inline pl-4 pointer" dir="ltr">
                    إظهار رسالة بالإختيار<input type="radio" value="2" name="radioSettingAddExistProduct">
                </label>
            </div>
        </div>
    </div>
    <div id="divContainerEditRowInTable" class="d-none">
        <div class="overlay" onclick="$('#divContainerEditRowInTable').addClass('d-none');design.useSound();"></div>
        <div id="divEditRowInTable" class="text-center pt-2 box-shadow" dir="rtl">
            <h1>تعديل المنتج
                <span id="spanEditProductName" class="text-danger" style="border-bottom: 2px red solid"></span>
                <button type="button" class="btn px-0 bg-transparent text-primary" id="buttonSaveEditRowInTable"><i
                        class="far fa-2x fa-save"></i></button>
            </h1>
            <div class="row no-gutters">
                <div class='col px-0'>
                    <span class='input-group-text h0'>الكمية</span>
                    <div class="input-group">
                        <input id="input_edit_product_qte" type='text'
                               data-validate='qte' data-patternType="qte"
                               value='0' class='form-control h0'>
                        <div class="input-group-prepend">
                            <div class="input-group-prepend">
                                <span class="input-group-text h0" id="spanEditUnitName"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col px-0'>
                    <span class='input-group-text h0'>السعر</span>
                    <div class="input-group">
                        <input value='0' type='text' data-validate='price'
                               {{$setting->use_small_price?'data-small_price':''}}
                               data-patternType="price"
                               id="inputEditProductPrice" data-max_qte="0" class='form-control h0'>
                        <div class="input-group-prepend">
                            <span class="input-group-text h0">ج</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="divContainerSettingForDefaultAccount" class="d-none">
        <div class="overlay"
             onclick="$('#divContainerSettingForDefaultAccount').addClass('d-none');design.useSound();"></div>
        <div id="divSettingForDefaultValForAccount" class="text-center pt-2 box-shadow" dir="rtl">
            <h1>القيمة الإفتراضية للشخص صاحب الفاتورة عند إنشاء هى
                {{$type==0?'فاتورة شراء':($type==1?'فاتورة بيع':'عرض أسعار بيع')}}
            </h1>
            <div>
                <label class="checkbox-inline pl-4 mr-3 pointer" dir="ltr">
                    برجاء التحديد<input type="radio" value="0" checked
                                        name="radioAddBillDefaultAccount">
                </label>
                <label class="checkbox-inline pl-4 pointer" dir="ltr">بدون
                    <input type="radio" value="1" name="radioAddBillDefaultAccount">
                </label>
                <label class="checkbox-inline pl-4 pointer" dir="ltr">
                    جديد<input type="radio" value="2" name="radioAddBillDefaultAccount">
                </label>
            </div>
        </div>
    </div>
    <input type="hidden" id="inputCheckQteBeforeAddToDetails" value="{{$type==1?'true':'false'}}"
           name="checkQteBeforeAddToDetails">
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='text-right h5'>
            <div class=' container-fluid px-0 px-md-2 ml-auto '>
                <div class='text-center'>
                    <h1 class='font-weight-bold pb-3 text-white'>إنشاء
                        {{$type==0?'فاتورة شراء':($type==1?'فاتورة بيع':'عرض أسعار')}}
                    </h1>
                    @if ($type!=0)
                        <div id="div_container_type_price"
                             class="text-white text-right text-md-center {{Hash::check('use_price2',$permit->use_price2)?'':'d-none'}}">
                            <label class="checkbox-inline d-block d-md-inline-block pl-4 mr-2 pointer "
                                   dir="ltr"> {{$setting->price1_name}}
                                <input type="radio" name="input_price_type" id="input_price1" value="1">
                            </label>
                            <label class="checkbox-inline d-block d-md-inline-block pl-4 mr-2 pointer "
                                   dir="ltr"> {{$setting->price2_name}}
                                <input type="radio" name="input_price_type" id="input_price2" value="2">
                            </label>
                            @if (Hash::check('use_price3',$permit->use_price3))
                                <label class="checkbox-inline d-block d-md-inline-block pl-4 mr-2 pointer "
                                       dir="ltr"> {{$setting->price3_name}}
                                    <input type="radio" name="input_price_type" id="input_price3" value="3">
                                </label>
                            @endif
                            @if (Hash::check('use_price4',$permit->use_price4))
                                <label class="checkbox-inline d-block d-md-inline-block pl-4 mr-2 pointer "
                                       dir="ltr"> {{$setting->price4_name}}
                                    <input type="radio" name="input_price_type" id="input_price4" value="4">
                                </label>
                            @endif
                        </div>
                    @endif
                    <form id="mainForm" action="{{route('bills.store')}}" method="post">
                        @csrf
                        <div class='container-fluid box-shadow p-1 text-white' style='background: rgba(12,84,96,.6);'>
                            <div class='row no-gutters '>
                                <div class='col'>
                                    <div class="tooltips" data-placement='bottom' title="المخزن">
                                        <select id="select_stoke_id" class="col form-control">
                                            @if($devise_stokes->default_stoke=='')
                                                <option value="">برجاء التحديد</option>
                                            @endif
                                            @foreach ($devise_stokes['allowedStoke'] as $d)
                                                @if ($d->stoke->state)
                                                    <option
                                                        value='{{$d->stoke->id}}' {{$d->stoke->id==$devise_stokes->default_stoke?'selected':''}}>{{$d->stoke->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="tooltips position-relative" data-placement='bottom'
                                         title="الشخص صاحب الفاتورة">
                                        <select id='select_account_name' class="col form-control"
                                                data-live-search="true">
                                            <option value='' selected>برجاء التحديد</option>
                                            <option value='0'>بدون</option>
                                            <option value='-1'>جديد</option>
                                            @foreach ($accounts as $c)
                                                <option value='{{$c->id}}'
                                                        data-subtext="({{$c->tel}}) ({{round($c->account,2).'ج'}}) ({{$c->is_supplier?'مورد ':''}} {{$c->is_customer?'عميل ':''}})">{{$c->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button"
                                                class="btn bg-transparent position-absolute px-0 mr-auto tooltips"
                                                onclick="$('#divContainerSettingForDefaultAccount').toggleClass('d-none');design.useSound('info');"
                                                data-placement="bottom"
                                                title="ضبط القيمة الأفتراضية للشخص صاحب الفاتورة!"
                                                style="color: #38c172;top: 0;left: 50px;"><i
                                                class="fas fa-paper-plane"></i>
                                        </button>
                                        <button type="button"
                                                id="button_set_paid_total"
                                                class="btn bg-transparent position-absolute px-0 mr-auto tooltips"
                                                data-placement="bottom"
                                                title="ضبط المبلغ المدفوع ليكون الإجمالى بعد الخصم إتوماتيك!"
                                                style="color: #38c172;top: 0;left: 88px;">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <button type="button"
                                                id="button_disable_set_paid_total"
                                                class="btn bg-transparent position-absolute px-0 mr-auto tooltips"
                                                data-placement="bottom"
                                                title="إلغاء ضبط المبلغ المدفوع ليكون الإجمالى بعد الخصم إتوماتيك!"
                                                style="color: darkred;top: 0;left: 88px;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class='col-4' dir='rtl'>
                                    <select id='selectMessage' onchange="$('#message').val($(this).val());"
                                            class="selectpicker col show-tick form-control">
                                        <option value="" data-subtext="بدون"></option>
                                        @foreach ($bill_messages as $m)
                                            <option
                                                {{$type==0 && $setting->bill_message_buy_id ==$m->id?'selected':''}}
                                                {{$type==1 && $setting->bill_message_sale_id ==$m->id?'selected':''}}>{{$m->name}}</option>
                                        @endforeach
                                    </select>
                                    <textarea class='form-control text-right tooltips' id="message"
                                              style="font-size: 1.22rem;height: 47px" title="رسالة الفاتورة"
                                              data-placement="bottom" rows='1'
                                              placeholder='ملاحظة الفاتورة'></textarea>
                                </div>
                            </div>
                            <div class="input-group row no-gutters d-none"
                                 id="div_container_new_account">
                                <div class="input-group-prepend col">
                                    <input class='form-control pr-5 tooltips h0' type='text'
                                           id="input_new_account_name"
                                           onkeyup="titleItem.html(titleVal+'('+$(this).val()+')');"
                                           data-placement="bottom" title="إسم الشخص المراد إضافتة"
                                           data-validate='text' placeholder='إسم الشخص'>
                                </div>
                                <div class="input-group-prepend col" style="{{$type==2?'display:none!important;':''}}">
                                    <input type="text" placeholder='رقم الهاتف'
                                           data-validate='tel' data-patternType='tell'
                                           {{$setting->allow_account_without_tel?'':'data-required'}}
                                           id="input_new_account_tell"
                                           data-placement="bottom" title="رقم الشخص المراد إضافتة"
                                           class="form-control pr-5 h0 tooltips">
                                </div>
                                <div class="input-group-prepend col" style="{{$type==2?'display:none!important;':''}}">
                                    <input type="text" placeholder='الحساب السابق' value="0"
                                           data-validate='{{!$setting->allow_account_with_negative_account?'price':'negative_price'}}'
                                           data-patternType='{{!$setting->allow_account_with_negative_account?'price':'negative_price'}}'
                                           id="input_new_account_account"
                                           data-placement="bottom"
                                           onclick="$(this).select();"
                                           title='الحساب السابق للشخص المراد إضافتة
                                           {{Hash::check('sup_cust',$permit->sup_cust)?'وفى حالة المورد العميل (هو القيمة التى أدين بها للمورد العميل)':''}}'
                                           class="form-control pr-5 tooltips h0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text h0">ج</span>
                                    </div>
                                </div>
                                <div
                                    class='form-group col-4 pt-2 tooltips {{Hash::check('sup_cust',$permit->sup_cust)?'':'d-none'}}'
                                    style="{{$type==2?'display:none!important;':''}}" data-placement="bottom"
                                    title="نوع الشخص">
                                    <label class="checkbox-inline pl-4 mr-3 pointer" dir="ltr">مورد<input
                                            type="checkbox" id="input_is_supplier"
                                            {{$type==0?'checked disabled':''}}
                                            value="1"></label>
                                    <label class="checkbox-inline pl-4 pointer" dir="ltr">عميل<input type="checkbox"
                                                                                                     id="input_is_customer"
                                                                                                     {{$type!=0?'checked disabled':''}}
                                                                                                     value="1"></label>
                                </div>
                            </div>
                            <div class='row no-gutters'>
                                <div class='col-12 col-md-1'>
                                    {{--<input type="checkbox" id="input_add_fast_with_barcode"
                                           class="tooltips position-absolute" data-placement="top"
                                           title="الإضافة السريعة عند إستخدام الباركود"
                                           style="left: 10px;top: 10px">--}}
                                    <button type="button" id='addToDetails' class='btn w-100 btn-info h-100 tooltips'
                                            data-placement="bottom" title="إضافة إلى الفاتورة"><span
                                            class="h3">إضافة
                                        </span>
                                    </button>
                                    <input type="checkbox" id="checkProductSpecial" class="tooltips position-absolute"
                                           data-placement="bottom" title="قائمة المنتجات الخاصه"
                                           style="left: 10px;bottom: 10px">
                                </div>
                                <div class='col-4 '>
                                    <div class="input-group tooltips" data-placement="top" title="طريقة البحث عن منتج">
                                        <label
                                            style="{{Hash::check('use_barcode',$permit->use_barcode)?'width: 50%':'width: 100%'}}"
                                            class="checkbox-inline pb-0 pt-1 d-inline-block  pointer input-group-text h0">
                                            <input type="radio" checked id="radioName" name="billType" class="ml-3"
                                                   value="1">
                                            بالإسم
                                        </label>
                                        @if(Hash::check('use_barcode',$permit->use_barcode))
                                            <label style="width:50%"
                                                   class="checkbox-inline py-0  d-inline-block  pointer input-group-text h0">
                                                <input type="radio" id="radioBarcode" name="billType" class="ml-3">
                                                بالباركود
                                            </label>
                                        @endif
                                    </div>
                                    <div class="input-group">
                                        <div class="w-100" id="divContainerResultBarcodeSearch">
                                            <input type="text" id="input_barcode" placeholder=" بحث بالباركود"
                                                   style=""
                                                   autocomplete="off"
                                                   class=" form-control h0">
                                            {{-- <input type='text' readonly
                                                    class=' form-control h0'>--}}
                                        </div>
                                        <div id="divContainerSelectProduct" class="w-100">
                                            <select data-select='product-name'
                                                    data-container="#divContainerSelectProduct"
                                                    id="selectProduct" class="form-control"
                                                    data-live-search="true">
                                                @foreach ($products as $p)
                                                    <option data-id="{{$p->id}}"
                                                            {{$p->allow_no_qte?'data-no_qte="true"':''}}
                                                            data-special="{{$p->special}}">{{$p->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class='col'>
                                <span class='input-group-text h0'>الكمية
                                <button type="button" class="btn bg-transparent px-0 mr-auto tooltips"
                                        onclick="$('#divContainerSettingForAddExistProductToDetailsWithSameUnit').toggleClass('d-none');design.useSound('info');"
                                        data-placement="bottom"
                                        title="عرض الخيارات عند إضافة منتج موجود فى الفاتورة بنفس الوحدة"
                                        style="color: #38c172;"><i class="fas fa-redo"></i>
                                </button>
                                </span>
                                    <div class="input-group">
                                        <input id="input_product_qte" type='text'
                                               data-validate='qte' data-patternType="qte"
                                               value='0' class='form-control h0'>
                                        <div class="input-group-prepend">
                                            <select class="custom-select h0" id='select_unit'>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class='col '>
                                    <span class='input-group-text h0'>السعر</span>
                                    <div class="input-group">
                                        <input value='0' type='text' data-validate='price'
                                               {{$setting->use_small_price?'data-small_price':''}}
                                               data-patternType="price"
                                               id="inputProductPrice" class='form-control h0'>
                                        <div class="input-group-prepend d-none d-md-inline-block">
                                            <span class="input-group-text h0">ج</span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col '>
                                    <span class='input-group-text h0'>الاجمالي</span>
                                    <div class="input-group">
                                        <input id="inputTotalBeforeAddToTable" value='0' readonly type='text'
                                               name='priceForProduct' class='form-control h0'>
                                        <div class="input-group-prepend d-none d-md-inline-block">
                                            <span class="input-group-text h0">ج</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='table-responsive'>
                                <div class="tableFixHead">
                                    <table id="mainTable" class='m-0 table table-hover table-bordered'>
                                        <thead class='thead-dark h3'>
                                        <tr>
                                            <th>م</th>
                                            <th>إسم المنتج
                                                <div class="input-group d-inline-block" style="max-width: 100px">
                                                    <div class="input-group-prepend bg-transparent">
                                                        <input placeholder='بحث'
                                                               data-filter-col="1"
                                                               type='text'
                                                               id="input_th_product_name_search"
                                                               class='form-control h0 d-none'>
                                                        <span class="input-group-text text-success bg-transparent p-0"
                                                              style="border: none"><i
                                                                onclick="design.useSound();$(this).parent().parent().children().toggleClass('d-none');$('#input_th_product_name_search').focus();"
                                                                class="fas fa-2x fa-search mr-2 tooltips"
                                                                data-placement="left"
                                                                title="بحث فى الفاتورة"></i></span>
                                                        <span
                                                            class="input-group-text text-danger bg-transparent p-0 d-none"
                                                            style="border: none"><i
                                                                onclick="design.useSound();$(this).parent().parent().children().toggleClass('d-none');$('#input_th_product_name_search').val('').trigger('keyup');;"
                                                                class="fas fa-2x fa-times mr-2 tooltips"
                                                                data-placement="left"
                                                                title="إلغاء البحث فى الفاتورة"></i></span>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>الاجمالي
                                                <span class="font-en small tooltips" id="span_total_table"
                                                      data-placement="left" title="جنية"></span>
                                            </th>
                                            <th>العمليات
                                                <button type="button" class="btn bg-transparent p-0  tooltips"
                                                        data-placement="left" title="حذف الكل"
                                                        onclick="$('#mainTable tbody').html('');addIndexAndTotalToTable(false);design.useSound();alertify.error('تم حذف المنتجات من الفاتورة بنجاح ');">
                                                    <i class="fas fa-2x text-danger fa-trash-alt"></i>
                                                </button>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="h4 text-dark">
                                        </tbody>
                                        {{--<tfoot class='bg-warning text-dark h4'>
                                        <tr>
                                            <th colspan='5' class='text-left h0'>ضريبة القيمة المضافة 14 %</th>
                                            <th class='font-en'><span id='totalTableFooter'>100</span> جنية</th>
                                        </tr>
                                        <tr>
                                            <th colspan='5' class='text-left h0'>اجمالي الفاتورة بعد الضرائب</th>
                                            <th class='font-en'><span id='totalTableFooter'>0</span> جنية</th>
                                        </tr>
                                        </tfoot>--}}
                                    </table>
                                </div>
                                <div class='row no-gutters' id="divContainerMoney">
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>الخـصــــــــــــم</span>
                                        </div>
                                        <input id="input_discount" type='text'
                                               data-validate='price'
                                               required
                                               {{$setting->use_small_price?'data-small_price':''}}
                                               data-patternType="price"
                                               name='priceDiscount' value='0' class='form-control h0'>
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>ج</span>
                                        </div>
                                    </div>
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>الاجمالي بعد الخصم</span>
                                        </div>
                                        <input id="input_totalPriceAfterDiscount" type='text' value='0'
                                               readonly='true' class='form-control h0'>
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>ج</span>
                                        </div>
                                    </div>
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>المبلغ المدفوع</span>
                                        </div>
                                        <input id="input_totalPaid" type='text'
                                               {{$type==2?'readonly':''}}
                                               data-validate='price'
                                               required
                                               {{$setting->use_small_price?'data-small_price':''}}
                                               data-patternType="price"
                                               name='priceBuy' value='0' class='form-control h0'>
                                        <div class="input-group-prepend">
                                        <span class='input-group-text h0 tooltips'
                                              style="cursor: pointer!important;"
                                              data-placement="top" title="إضغط لدفع المبلغ بالكامل"
                                              onclick="$('#input_totalPaid').val($('#input_totalPriceAfterDiscount').val());updateTotalAndRentAfterDiscount();$('#input_totalPaid').trigger('keyup');design.useSound();">
                                            ج
                                        </span>
                                        </div>
                                    </div>
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>البــــاقـــــــــــــــــــي</span>
                                        </div>
                                        <input id="input_rent" type='text' value='0' readonly='true'
                                               class='form-control h0'>
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>ج</span>
                                        </div>
                                    </div>
                                </div>
                                <div class='text-center mt-1 {{$type==2?'d-none':''}}'>
                                    <input id="check_print_bill" type="checkbox" class="tooltips"
                                           data-placement="right" title="طباعة الفاتورة عند الحفظ">
                                    <button type="submit" id="button_save_bill"
                                            class='btn font-weight-bold btn-warning px-4 tooltips'
                                            data-placement="left" title="يمكن إستخدام الزر + لحفظ الفاتورة ">
                                        <input id="check_print_in_new_window" type="checkbox" class="tooltips"
                                               data-placement="right" title="حفظ وطباعة فى نافذة جديد">
                                        <span class='h0 font-weight-bold'>حفظ الفاتورة</span>
                                        <i class="fas text-danger fa-2x fa-save"></i>
                                    </button>
                                </div>
                                <div class='text-center mt-1 {{$type!=2?'d-none':''}}'>
                                    <button type="submit" id="button_print_show_price"
                                            class='btn font-weight-bold btn-warning px-4 h0 tooltips'
                                            data-placement="left"
                                            title="إضغط على زر + للطباعة"><span
                                            class='h0 font-weight-bold'>طباعة عرض الأسعار
                                        <i class="fas fa-print text-success"></i>
                                        </span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    @if($type==2)

        <section id="section_print_price_show" class="d-none">
            @if ($print_design->use_small_size==0)
                <section id="section_bill_design" dir="rtl"
                         class='mx-auto font-ar pt-3 pb-0 pl-3 pr-3 bg-white text-center position-relative'
                         style="max-width: 1024px">
                    <div id="backgroundImg" class="position-absolute w-100 h-100"
                         style="top:0;left:0;background-image: url('{{$print_design->icon}}');background-repeat: no-repeat;background-size: 100% 100%;background-position: center;opacity: {{$print_design->opacity_background}}"></div>
                    <div class='row no-gutters'>
                        <div class='col-7 text-right font-weight-bold'
                             style="font-size: {{$print_design->header_size}}rem!important;">
                            <img height='80px'
                                 class='' id="img_design" src='{{$print_design->icon}}'
                                 style='vertical-align: top;max-width: 80px'>
                            <div class='d-inline-block text-center'>
                                <p id="p_design_company_name" class="mb-0">{{$print_design->company_name}}</p>
                                <p id="p_design_row_under_company_name"> {{$print_design->row_under_company_name}}</p>
                            </div>

                        </div>
                        <div class='col-5 text-left font-weight-bold'
                             style="font-size: {{$print_design->bill_number_date_size}}rem!important;">
                            <div class="text-left">
                                <div class='b d-inline-block ml-auto  px-2' style="">
                                    <label> التاريخ : </label>
                                    <span data-type='date'
                                          class='font-en'>{{(new \DateTime())->format('h:i:sa Y-m-d')}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="font-weight-bold" style="font-size: {{$print_design->header_size}}rem!important;">عـــرض
                        أسعـــار</p>
                    <div class='font-en py-1 text-right'
                         style="font-size: {{$print_design->contact_size}}rem!important;">
                        <div class='text-right' id="div_row_contact1">
                            {{$print_design->row_contact1}}
                        </div>
                        <div id="div_row_contact2" class='text-right pl-3'>
                            {{$print_design->row_contact2}}
                        </div>
                    </div>
                    <table class='w-100' style="font-size: {{$print_design->account_size}}rem!important;" border='3'>
                        <tr>
                            <td class='width-15'>إســــم العميل</td>
                            <td style='width: 40%' class="text-right pr-1" id="tr_bill_offer_account_name"></td>
                        </tr>
                        <tr style="display: none" id="driver">
                            <td class='width-15'>اسم السائق</td>
                            <td style='width: 40%'><input readonly value=''/></td>
                            <td class='width-15'>رقم السائق</td>
                            <td style='width: 40%'><input value=''/></td>
                        </tr>
                    </table>
                    <table class='w-100 mt-2' border='3' id="tableOfferData">
                        <thead>
                        <tr class=''
                            style="background: #e4e4db;font-size: {{$print_design->table_header_size}}rem!important;">
                            <th style='width:50px'>م</th>
                            <th colspan="2">الصنف</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>إجمالي</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: {{$print_design->table_body_size}}rem!important;">

                        </tbody>
                        <tfoot style="font-size: {{$print_design->table_footer_size}}rem!important;">
                        <tr class='' id="tr_offer_discount">
                            <td colspan="2">الخصم</td>
                            <td class='font-en'>
                                5 ج
                            </td>
                            <td colspan='2'>إجمالى الفاتورة قبل الخصم</td>
                            <td class='font-en'>
                                ج
                            </td>
                        </tr>
                        <tr class='' id="tr_offer_total_after_discount" style="background: #ccc">
                            <td colspan="5">إجمالى الفاتورة
                                <span id="td_span_total_if_has_dicount">بعد الخصم</span>
                            </td>
                            <td class='font-en'>
                                5 ج
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                    <div class='pt-1 m-0 font-en overflow-hidden font-weight-bold'
                         style="font-size: {{$print_design->message_uc_size}}rem!important;">
                        <p class='text-left float-left text-dark'><span
                                id="span_offer_message"
                                style='width: 200px;text-align: left;background: transparent'></span></p>
                        <p class='text-left float-right text-dark'>
                            <span style='width: 400px;text-align: right;background: transparent'>تصميم وبرمجة Ultimate Code 01018030420</span>
                        </p>
                    </div>
                </section>
            @else
                <section id="section_small_bill_design" dir="rtl"
                         style="width: {{$print_design->small_size}}cm!important;"
                         class='mx-auto small_design font-ar p-1 pb-0 bg-white text-center'>
                    <div class=''>
                        <div class='text-right font-weight-bold' style="background: #d5d5d5;border-radius: 4px">
                            <div class='d-block text-center font-weight-bold'
                                 style="font-size: {{$print_design->header_size}}rem!important;">
                                <img
                                    class='' src='{{$print_design->icon}}'
                                    style='max-width:19%;vertical-align: top;float: right;'>
                                <p class="mb-0">{{$print_design->company_name}}</p>
                                <p
                                    class="mb-0">{{$print_design->row_under_company_name}}</p>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                    <div class='font-en py-1 text-right'
                         style="font-size: {{$print_design->contact_size}}rem!important;">
                        <div class='text-right small_bold'>{{$print_design->row_contact1}}</div>
                        <div class='text-right small_bold'>{{$print_design->row_contact2}}</div>
                    </div>
                    <p class="h5 font-weight-bold">عـــرض أسـعــار</p>
                    <div class='text-right small_bold px-1'
                         style="font-size: {{$print_design->account_size}}rem!important;">
                        إسم
                        العميل
                        :
                        <span id="span_bill_offer_account_name"></span>
                    </div>
                    <div class='text-left mb-0  px-1'
                         style="font-size: {{$print_design->bill_number_date_size}}rem!important;">
                        <div class="text-left float-right">
                            <label> التاريخ : </label>
                            <span data-type='date' class='font-en'>{{(new \DateTime())->format('h:i:sa Y-m-d')}}</span>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                    <table class='w-100' id="tableOfferData">
                        <thead style="font-size: {{$print_design->table_header_size}}rem!important;">
                        <tr class='' style="background: #e4e4db;">
                            <th>الصنف</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>إجمالي</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: {{$print_design->table_body_size}}rem!important;">
                        <tr class=''>
                            <td></td>
                            <td></td>
                            <td>
                                ج
                            </td>
                            <td>
                                ج
                            </td>
                        </tr>
                        </tbody>
                        <tfoot style="font-size: {{$print_design->table_footer_size}}rem!important;">
                        <tr class='' id="tr_discount11">
                            <td colspan="3">الإجمالى قبل الخصم</td>
                            <td class='font-en'>
                            </td>
                        </tr>
                        <tr class='' id="tr_discount12">
                            <td colspan="3">الـــــخــصـم</td>
                            <td class='font-en'>
                            </td>
                        </tr>
                        <tr class='' style="background: #ccc">
                            <td colspan="3">الإجمالى
                                <span id="td_span_total_if_has_dicount1">بعد الخصم</span>
                            </td>
                            <td class='font-en' id="tr_offer_total_after_discount">
                                ج
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                    <div class='pt-1 m-0 font-en small overflow-hidden small_bold'
                         style="font-size: {{$print_design->message_uc_size}}rem!important;">
                        <p class='text-left float-left p-0 m-0 text-dark'><span
                                id="span_offer_message"
                                style='text-align: left;background: transparent'></span></p>
                        <p class='text-right float-right p-0 m-0 text-dark'>
                            <span
                                style='text-align: right;background: transparent'>{{($print_design->small_size > 6)?($print_design->small_size>7.7?'تصميم وبرمجةUltimateCode 01018030420':'تصميم تصميمUc.01018030420'):'تصميمUc.01018030420'}}</span>
                        </p>
                        <div style="clear: both"></div>
                    </div>
                </section>
            @endif
        </section>

    @endif
    <iframe src="" class="d-none" id="iframe_print_bill" style="width: 100vw;height: 100vh" frameborder="0"></iframe>
@endsection

@section('js')
    <script defer>
        validateByAttr();
        design.useNiceScroll();
        $('#mainTable').filtable({controlPanel: $('#input_th_product_name_search').parent()});

        //use selectPicer in select product
        $('#selectProduct,#select_stoke_id,#select_account_name').selectpicker({
            showSubtext: true,
            container: 'body',
            showTick: true
        });
        $('#selectProduct,#select_stoke_id,#select_account_name').selectpicker('refresh');
        //set default message
        $('#selectMessage').trigger('change');

        //create new account
        var titleVal="{{$type==0?'فاتورة شراء ':($type==1?'فاتورة بيع ':'عرض أسعار بيع ')}}";
        var titleItem=$('title');
        $('#select_account_name').change(function () {
            titleItem.html(titleVal+'('+$('#select_account_name option:selected').html()+')');
            if ($(this).val() == '-1') {
                if ($('#div_container_new_account').hasClass('d-none')) {
                    $('#div_container_new_account').removeClass('d-none');
                    design.useSound('info');
                    @if($type!=2)
                    if ($('#input_new_account_tell').is('[data-required]')) {
                        $('#input_new_account_tell').attr('required', 'required');
                    }
                    @endif
                    $('#input_new_account_name,#input_new_account_account').attr('required', 'required');
                    $('#input_new_account_account').val('0');
                    alertify.success('برجاء كتابة بيانات الشخص الجديد');
                    @if($type==2)
                    alertify.success('ملاحظة لا يتم إضافة الشخص الجديد للعملاء ولكن يستخدم فى طباعة عرض الأسعار فقط !');
                    @endif
                    design.updateNiceScroll();
                }
                titleItem.html(titleVal+'('+$('#input_new_account_name').val()+')');
            } else {
                $('#input_new_account_tell,#input_new_account_name').removeAttr('required');
                if (!$('#div_container_new_account').hasClass('d-none')) {
                    $('#div_container_new_account').addClass('d-none');
                    design.useSound('info');
                    $('#input_new_account_name').val('');
                    $('#input_new_account_tell').val('');
                    $('#input_new_account_account').val('0');
                    design.updateNiceScroll();
                }
            }
        });

        //toggle between add to bill by barcode or product name
        function checkTypAdd() {
            design.useSound();
            if ($('#radioName').prop('checked')) {
                //search by name
                $('#addToDetails').parent().removeClass('d-none');
                Cookie.remove('billBarcode{{$type}}');
                $('#divContainerResultBarcodeSearch,#input_barcode').addClass('d-none');
                $('#divContainerSelectProduct,#checkProductSpecial,#select_unit').removeClass('d-none');
                $('#selectProduct').trigger('change');
            } else {
                //search by barcode
                $('#addToDetails').parent().addClass('d-none');
                Cookie.set('billBarcode{{$type}}', true, {expires: 365});
                $('#divContainerResultBarcodeSearch,#input_barcode').removeClass('d-none');
                $('#divContainerSelectProduct,#checkProductSpecial,#select_unit').addClass('d-none');
                $('#input_barcode').focus().select();
                $('#input_product_qte').val('1');
                $('#inputProductPrice').val('0');
            }
        }


        if (Cookie.get('billBarcode{{$type}}') != null) {
            $('#radioBarcode').prop('checked', 'checked');
        } else {
            $('#radioName').prop('checked', 'checked');
        }
        // $('#radioName').trigger('change');
        checkTypAdd();

        $('#radioBarcode,#radioName').change(function () {
            checkTypAdd();
        });

        $('#input_barcode').on('keypress', function (e) {
            if ($(this).val().trim() != '' && e.which == 13) {
                searchBarcode($(this).val().trim());
            }
        });
        //search product by barcode
        var stateSearchBarcode = true;//to prevent search when old search not finish
        function searchBarcode(barcode='') {
            if (stateSearchBarcode) {
                if(barcode!=''){
                    $('#input_barcode').attr('readonly', 'readonly');
                    stateSearchBarcode = false;
                    $.ajax({
                        url: '{{route('products.getDate')}}',
                        method: 'POST',
                        data: {
                            type: 'findBarcode',
                            barcode: barcode,
                            billType: '{{$type}}',
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            stateSearchBarcode = true;
                            $('#input_barcode').removeAttr('readonly');
                            if (data == '') {
                                design.useSound('error');
                                alertify.error("باركود خاطئ!");
                                return;
                            }
                            var qte = $('#input_product_qte').val();
                            if (qte == '' || qte == 0) {
                                qte = 1;
                            }
                            //if main unit
                            if (data['relation_product_unit'] == undefined) {
                                var price = $('#inputProductPrice').val();
                                if (price == '' || price == '0') {
                                    @if($type==0)
                                        price = data['price_buy'];
                                    @elseif(Hash::check('use_price2',$permit->use_price2))
                                        price = data['price_sale' + $('#div_container_type_price input[type="radio"]:checked').val()];
                                    @else
                                        price = data['price_sale1'];
                                    @endif
                                }
                                if (!(price > 0) && $('#inputProductPrice').val() != '00' ) {
                                    design.useSound('error');
                                    alertify.error("لا يوجد سعر إفتراضى للمنتج المحدد,برجاء كتابة السعر!");
                                    return;
                                }

                                if(price=='00')
                                    price=0;
                                if (checkIfProductExistInTable(data['id'], '0', price)) {
                                    addRowToTable(data['id'], data['name'], '0', data['product_unit']['name'],
                                        qte, price, 1);
                                }
                            } else {//if sub unit
                                var price = $('#inputProductPrice').val();
                                if (price == '' || price == 0) {
                                    @if($type==0)
                                        price = data['relation_product_unit'][0]['price_buy'];
                                    @elseif(Hash::check('use_price2',$permit->use_price2))
                                        price = data['relation_product_unit'][0]['price_sale' + $('#div_container_type_price input[type="radio"]:checked').val()];
                                    @else
                                        price = data['relation_product_unit'][0]['price_sale1'];
                                    @endif
                                }
                                if (!price > 0) {
                                    design.useSound('error');
                                    alertify.error("لا يوجد سعر إفتراضى للمنتج المحدد,برجاء كتابة السعر!");
                                }

                                if (checkIfProductExistInTable(data['id'], data['relation_product_unit'][0]['id'], price)) {
                                    addRowToTable(data['id'], data['name'], data['relation_product_unit'][0]['id'], data['relation_product_unit'][0]['product_unit']['name'],
                                        qte, price, data['relation_product_unit'][0]['relation_qte']);
                                }
                            }

                            $('#input_product_qte').val(1);
                            $('#inputProductPrice').val(0);
                            $('#input_barcode').val('');
                            /* addRowToTable(data['id'], data['name'], '0', data['product_unit']['name'],
                                 qte, price, 1);*/
                            design.useSound('success');

                        },
                        error: function (e) {
                            stateSearchBarcode = true;
                            $('#input_barcode').removeAttr('readonly');
                            alert('error');
                            design.useSound('error');
                            console.log(e);
                        }
                    });
                }else{
                    design.useSound('info');
                    alertify.error("برجاء إدخال الباركود!");
                    $('#input_barcode').select();
                }
            } else {
                design.useSound('info');
                alertify.error("برجاء الإنتظار جارى البحث!");
            }
        }

        //toggle select product special
        $('#checkProductSpecial').change(function () {
            if ($(this).prop('checked')) {
                $('#selectProduct option[data-special="0"]').addClass('d-none');
            } else {
                $('#selectProduct option[data-special="0"]').removeClass('d-none');
            }
            $('#selectProduct').selectpicker('refresh');
        });

        //get units and price for product
        function getUnitForProductWithPrice(id) {
            $('#select_unit').html('');
            if (id == null) {
                alertify.error('لا يوجد منتجات برجاء إضافة منتجات لإستخدامها فى الفاتورة ');
                design.useSound('error');
                return;
            }
            $.ajax({
                url: '{{route('products.getDate')}}',
                method: 'POST',
                data: {
                    type: 'findUnitForProduct',
                    product_id: id,
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#select_unit').html('');
                    @if($type==0)
                    $('#select_unit').append(
                        "<option data-unit_id='0' data-price='" + data['price_buy'] + "' data-relation_qte='1'>" + data['product_unit']['name'] + "</option>"
                    );
                    @elseif(Hash::check('use_price2',$permit->use_price2))
                    $('#select_unit').append(
                        "<option data-unit_id='0' data-price='" + data['price_sale' + $('#div_container_type_price input[type="radio"]:checked').val()] + "' data-relation_qte='1'>" + data['product_unit']['name'] + "</option>"
                    );
                    @else
                    $('#select_unit').append(
                        "<option data-unit_id='0' data-price='" + data['price_sale1'] + "' data-relation_qte='1'>" + data['product_unit']['name'] + "</option>"
                    );
                    @endif
                    if (data['relation_product_unit'].length > 0) {
                        for (var i = 0; i < data['relation_product_unit'].length; i++) {
                            @if($type==0)
                            $('#select_unit').append(
                                "<option data-unit_id='" + data['relation_product_unit'][i]['id'] +
                                "' data-price='" + data['relation_product_unit'][i]['price_buy'] + "' " +
                                "data-relation_qte='" + data['relation_product_unit'][i]['relation_qte'] + "'>" + data['relation_product_unit'][i]['product_unit']['name'] + "</option>"
                            );
                            @elseif(Hash::check('use_price2',$permit->use_price2))
                            $('#select_unit').append(
                                "<option data-unit_id='" + data['relation_product_unit'][i]['id'] +
                                "' data-price='" + data['relation_product_unit'][i]['price_sale' + $('#div_container_type_price input[type="radio"]:checked').val()] + "' " +
                                "data-relation_qte='" + data['relation_product_unit'][i]['relation_qte'] + "'>" + data['relation_product_unit'][i]['product_unit']['name'] + "</option>"
                            );
                            @else
                            $('#select_unit').append(
                                "<option data-unit_id='" + data['relation_product_unit'][i]['id'] +
                                "' data-price='" + data['relation_product_unit'][i]['price_sale1'] + "' " +
                                "data-relation_qte='" + data['relation_product_unit'][i]['relation_qte'] + "'>" + data['relation_product_unit'][i]['product_unit']['name'] + "</option>"
                            );
                            @endif

                        }
                    }
                    updatePrice();
                    design.useSound('success');
                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                }
            });
        }

        //get units and price when select product
        $('#selectProduct').change(function () {
            getUnitForProductWithPrice($('#selectProduct option:selected').attr('data-id'));
            $('#input_product_qte').trigger('change');
            $('#input_product_qte').val(0).focus().select();
        });
        if ($('#radioName').prop('checked')) {
            $('#selectProduct').trigger('change');
        }

        //update price when select unit
        function updatePrice() {
            if ($('#select_unit option').length > 0) {
                $('#inputProductPrice').val($('#select_unit option:selected').attr('data-price'));
            } else {
                $('#inputProductPrice').val('0');
            }
        }

        $('#select_unit').change(function () {
            updatePrice();
            $('#input_product_qte').select();
        });
        $('#input_product_qte,#inputProductPrice').click(function () {
            $(this).select();
        });

        //get total for qte and price before add to table
        $('#inputProductPrice,#input_product_qte').on('keyup', function () {
            var qte = $('#input_product_qte').val(), price = $('#inputProductPrice').val();
            if (isNaN(qte) ||
                isNaN(price) ||
                qte == 0 ||
                price == 0) {
                $('#inputTotalBeforeAddToTable').val(0);
            } else {
                $('#inputTotalBeforeAddToTable').val(roundTo(qte * price));
            }
        });

        //add row to table
        function addRowToTable(productId, productName, relation_unit_id, qteName, qteVal, price, qteAddByMainUnit = '', existQteInStoke = '', mainUnitName = '', relation_qte = '1') {
            if (price=='00')
                price=0;
            $('#mainTable tbody').prepend(
                "<tr class='table-success'>" +
                "<td data-relation_qte='" + relation_qte + "' data-price='" + price + "' data-product_id='" + productId + "' data-unit_id='" + relation_unit_id + "' data-qte_val='" + qteVal + "' data-qte_name='" + qteName + "'" +
                "data-qte_add_by_main_unit='" + qteAddByMainUnit + "' data-qte_in_stoke='" + existQteInStoke + "' data-main_unit_name='" + mainUnitName + "'></td>" +
                "<td>" + productName + "</td>" +
                "<td>" + qteVal + ' ' + qteName + "</td>" +
                "<td>" + price + "</td>" +
                "<td></td>" +
                "<td>" +
                " <button type='button' class='btn  bg-transparent mx-2 text-primary px-0 tooltips' data-edit='' data-placement='right' title='تعديل'>" +
                "<i class='fas fa-2x fa-edit'></i>" +
                "</button>" +
                "<button type='button' class='btn  bg-transparent tooltips p-0' data-delete='' data-placement='left' title='حذف'>" +
                "<i class='fas fa-2x fa-trash-alt text-danger'></i>" +
                "</button>" +
                "</td>" +
                "</tr>"
            );
            design.updateNiceScroll();
            design.useToolTip();
            addIndexAndTotalToTable();
            design.useSound('success');
        }

        //add index and total for row and total for all row in table
        function addIndexAndTotalToTable(select_product_qte = true) {
            var index = $('#mainTable tbody tr').length;
            var total = 0;
            $('#mainTable tbody tr').each(function () {
                var td = $(this).children();
                td.eq(0).html(index);
                index--;
                var tempTotal = td.eq(0).attr('data-qte_val') * td.eq(3).html();
                td.eq(4).html(roundTo(tempTotal));
                total -= -tempTotal;
            });
            $('#span_total_table').html(roundTo(total));
            if ($('#radioName').prop('checked')) {
                $('#input_product_qte').val('0');
                if (select_product_qte) {
                    $('#input_product_qte').select();
                }
            } else {
                $('#input_product_qte').val(1);
                $('#inputProductPrice').val(0);
            }

            updateTotalAndRentAfterDiscount();
        }

        //checkQteAndPriceBeforeAddToTable
        function checkQteAndPriceBeforeAddToTable() {
            //check if qte not valid
            if ($('#input_product_qte').hasClass('is-invalid') || $('#input_product_qte').val() == 0) {
                alertify.error('برجاء التحقق من الكمية ');
                design.useSound('error');
                $('#input_product_qte').select();
                return false;
            }
            //check if price not valid
            if (($('#inputProductPrice').hasClass('is-invalid') || $('#inputProductPrice').val() == 0) && $('#inputProductPrice').val() != '00') {
                alertify.error('برجاء التحقق من السعر ');
                design.useSound('error');
                $('#inputProductPrice').select();
                return false;
            }
            return true;
        }

        var statePreventLoopCheckBeforeAddToDetails = false;//used if bill type is bill sale
        //add row to table
        $('#addToDetails').click(function () {
            //check if no stoke selected
            if ($('#select_stoke_id').val() == '') {
                alertify.error('برجاء تحديد مخزن للفاتورة ');
                design.useSound('error');
                return;
            }
            //check if no account selected
            if ($('#select_account_name').val() == '') {
                alertify.error('برجاء تحديد شخص للفاتورة ');
                design.useSound('error');
                return;
            }
            //check if no product exist in select
            if ($('#selectProduct option').length == 0) {
                alertify.error('لا يوجد منتجات برجاء إضافة منتجات لإستخدامها فى الفاتورة ');
                design.useSound('error');
                return;
            }
            if (!checkQteAndPriceBeforeAddToTable()) {
                return;
            }
            if ($('#inputCheckQteBeforeAddToDetails').val() == 'true') {
                if (!checkIfProductExistInTableWithCheckQte()) {
                    return;
                }
            } else {
                if (!checkIfProductExistInTable()) {
                    return;
                }
            }


            //prevent change stoke if bill type is bill sale
            if ($('#inputCheckQteBeforeAddToDetails').val() == 'true') {
                $('#select_stoke_id').attr('disabled', 'disabled').selectpicker('refresh');
            }

            var product_id = $('#selectProduct option:selected').attr('data-id');
            var product_name = $('#selectProduct option:selected').html();
            var relation_unit_id = $('#select_unit option:selected').attr('data-unit_id');
            var relation_qte = $('#select_unit option:selected').attr('data-relation_qte');
            var unit_name = $('#select_unit option:selected').html();
            var qte = $('#input_product_qte').val();
            var price = $('#inputProductPrice').val();

            if ($('#inputCheckQteBeforeAddToDetails').val() == 'true') {
                //check if qte exist in store if type bill is sale if product type not no qte
                if ($('#selectProduct option:selected').is('[data-no_qte]')) {
                    addRowToTable(product_id, product_name, relation_unit_id, unit_name, qte, price, relation_qte);
                } else {
                    if (statePreventLoopCheckBeforeAddToDetails) {
                        alertify.error('برجاء الإنتظار جارى الإضافة لتفاصيل الفاتورة');
                        design.useSound('info');
                        return;
                    }
                    statePreventLoopCheckBeforeAddToDetails = true;
                    $.ajax({
                        url: '{{route('stores.getDate')}}',
                        method: 'POST',
                        data: {
                            type: 'getQteForProductInStoke',
                            stoke_id: $('#select_stoke_id').val(),
                            product_id: $('#selectProduct option:selected').attr('data-id'),
                            relation_unit_id: relation_unit_id,
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            statePreventLoopCheckBeforeAddToDetails = false;
                            if (data.length > 0) {
                                var existQteInStoke = 0;
                                for (var i = 0; i < data.length; i++) {
                                    existQteInStoke -= -data[i]['qte'];
                                }
                                var relation_qte_unit = relation_unit_id == 0 ? 1 : data[0]['relation_product_unit']['relation_qte'];
                                var qteAddByMainUnit = qte * relation_qte_unit;
                                if (qteAddByMainUnit <= existQteInStoke) {
                                    addRowToTable(product_id, product_name, relation_unit_id, unit_name, qte, price, qteAddByMainUnit, existQteInStoke, data[0]['product']['product_unit']['name'], relation_qte);
                                } else {
                                    if ($('#mainTable tbody tr').length == 0) {
                                        $('#select_stoke_id').removeAttr('disabled').selectpicker('refresh');
                                    }
                                    //if unit used in add to details is main unit
                                    if (relation_unit_id == 0) {
                                        alertify.error("هذه الكمية غير متاحة الكمية المتاحة " + '</br>' + roundTo(existQteInStoke) + " " + unit_name);
                                    } else {
                                        alertify.error("هذه الكمية غير متاحة الكمية المتاحة " + '</br>' + roundTo(existQteInStoke) + " " + data[0]['product']['product_unit']['name'] +
                                            '</br>' + roundTo(existQteInStoke / relation_qte_unit) + " " + unit_name);
                                    }
                                    design.useSound('error');
                                }
                            } else {
                                alertify.error("هذه المنتج غير موجود في المخزن المحدد ");
                                design.useSound('error');

                                if ($('#mainTable tbody tr').length == 0) {
                                    $('#select_stoke_id').removeAttr('disabled').selectpicker('refresh');
                                }
                            }

                        },
                        error: function (e) {
                            statePreventLoopCheckBeforeAddToDetails = false;
                            alertify.error("حصل خطاء في العملية ربما يكون هذا المنتج غير موجود في المخزن حاول مرة اخري في حالة تكرار الخطاء اتصل بالمبرمج");
                            console.log(e);
                        }
                    });

                }

            } else {
                addRowToTable(product_id, product_name, relation_unit_id, unit_name, qte, price);
            }
        });

        //delete row in table
        $('#mainTable').on('click', 'tbody button[data-delete]', function () {
            $(this).parent().parent().remove();
            addIndexAndTotalToTable();
            design.useSound();

            //allow change stoke if bill type is bill sale
            if ($('#inputCheckQteBeforeAddToDetails').val() == 'true') {
                if ($('#mainTable tbody tr').length == 0) {
                    $('#select_stoke_id').removeAttr('disabled').selectpicker('refresh');
                }
            }
        });

        //add row to table when enter in input qte
        $('#input_product_qte,#inputProductPrice').on('keypress', function (e) {
            if (e.which == 13) {
                if ($('#radioName').prop('checked')) {
                    $('#addToDetails').trigger('click');
                } else {
                    searchBarcode($('#input_barcode').val());
                }
            }
        });

        //set default value for radioSettingAddExistProduct
        if (Cookie.get('valueRadioSettingAddExistProduct') != '') {
            var tempValue = Cookie.get('valueRadioSettingAddExistProduct');
            $('#divSettingForAddExistProductToDetailsWithSameUnit input[type="radio"][value="' + tempValue + '"]').prop('checked', true);
        }
        //hide overlay when change in div containerSettingForAddExistProductToDetailsWithSameUnit
        $('#divSettingForAddExistProductToDetailsWithSameUnit input[type="radio"]').change(function () {
            $('#divContainerSettingForAddExistProductToDetailsWithSameUnit').addClass('d-none');
            //update cooke to set default value
            Cookie.set('valueRadioSettingAddExistProduct', $('#divSettingForAddExistProductToDetailsWithSameUnit input[type="radio"]:checked').val(), {
                expires: 365
            });
            design.useSound();
            alertify.success('عند إضافة نفس المنتج بنفس الوحدة إلى الفاتورة سيتم ' + $(this).parent().text());
        });

        //set default value for radioSettingAddExistProduct
        if (Cookie.get('radioAddBillDefaultAccount{{$type}}') != '') { //0 for plese select , 1 for no account ,2 for new account
            var tempValue = Cookie.get('radioAddBillDefaultAccount{{$type}}');
            $('#divSettingForDefaultValForAccount input[type="radio"][value="' + tempValue + '"]').prop('checked', true);
            if (tempValue == 0) {
                $('#select_account_name option').eq(0).prop('selected', true);
            } else if (tempValue == 1) {
                $('#select_account_name option').eq(1).prop('selected', true);
            } else if (tempValue == 2) {
                $('#select_account_name option').eq(2).prop('selected', true);
            }
            $('#select_account_name').selectpicker('refresh');
            $('#select_account_name').trigger('change');
        }
        //hide overlay when change in div containerSettingForAddExistProductToDetailsWithSameUnit
        $('#divSettingForDefaultValForAccount input[type="radio"]').change(function () {
            $('#divContainerSettingForDefaultAccount').addClass('d-none');
            //update cooke to set default value
            Cookie.set('radioAddBillDefaultAccount{{$type}}', $('#divSettingForDefaultValForAccount input[type="radio"]:checked').val(), {
                expires: 365
            });
            design.useSound();
            alertify.success('عند إضافة ' + "{{$type==0?'فاتورة شراء':($type==1?'فاتورة بيع':'عرض أسعار بيع')}}" + ' القيمة الإفتراضية لإسم الشخص هى ' + $(this).parent().text());

            var tempValue = $('#divSettingForDefaultValForAccount input[type="radio"]:checked').val();
            if (tempValue == 0) {
                $('#select_account_name option').eq(0).prop('selected', true);
            } else if (tempValue == 1) {
                $('#select_account_name option').eq(1).prop('selected', true);
            } else if (tempValue == 2) {
                $('#select_account_name option').eq(2).prop('selected', true);
            }
            $('#select_account_name').selectpicker('refresh');
            $('#select_account_name').trigger('change');
        });

        //check if product exist in bill with out check qte
        function checkIfProductExistInTable(product_id = '', unit_id = '', price = '') {
            product_id = product_id == '' ? $('#selectProduct option:selected').attr('data-id') : product_id;
            unit_id = unit_id == '' ? $('#select_unit option:selected').attr('data-unit_id') : unit_id;
            price = price == '' ? $('#inputProductPrice').val() : price;
            /*var product_id = $('#selectProduct option:selected').attr('data-id');
            var unit_id = $('#select_unit option:selected').attr('data-unit_id');*/
            var result = true;
            var rowExist = 0;
            var rowPrice = 0;
            $('#mainTable tbody tr').each(function () {
                if (product_id == $(this).children().eq(0).attr('data-product_id') && unit_id == $(this).children().eq(0).attr('data-unit_id')) {
                    rowExist = $(this);
                    rowPrice = $(this).children().eq(0).attr('data-price');
                    result = false;
                    return;
                }
            });
            if (!result) {
                var action = $('#divSettingForAddExistProductToDetailsWithSameUnit input[name="radioSettingAddExistProduct"]:checked').val();
                if (action == 1) {//don't add qte
                    design.useSound('error');
                    alertify.error('هذا المنتج موجود بنفس الوحدة');
                } else if (action == 0) {//add qte to qte
                    //check if price change
                    if (rowPrice != price) {
                        design.useSound('error');
                        alertify.error('هذا المنتج موجود بنفس الوحدة ولكن بسعر مختلف ');
                    } else {
                        var child = rowExist.children();
                        var newQte = child.eq(0).attr('data-qte_val') - -$('#input_product_qte').val();
                        //update qte
                        child.eq(0).attr('data-qte_val', newQte);
                        var tempQteText = newQte + ' ' + child.eq(0).attr('data-qte_name');
                        child.eq(2).html(tempQteText);
                        design.useSound();
                        alertify.success('المنتج موجود بنفس الوحدة وتم إضافة الكمية للكمية الموجودة لتصبح ' + tempQteText);
                        addIndexAndTotalToTable();
                    }
                } else {//ask before add qte
                    //check if price change
                    if (rowPrice != $('#inputProductPrice').val()) {
                        design.useSound('error');
                        alertify.error('هذا المنتج موجود بنفس الوحدة ولكن بسعر مختلف ');
                    } else {
                        design.useSound('info');
                        $(this).confirm({
                            text: "هذا المنتج موجود فى الفاتورة بنفس الوحدة هل تريد الإضافة للكمية الموجودة؟",
                            title: "إضافة كمية لمنتج بنفس الوحدة",
                            confirm: function (button) {
                                var child = rowExist.children();
                                var newQte = child.eq(0).attr('data-qte_val') - -$('#input_product_qte').val();
                                //update qte
                                child.eq(0).attr('data-qte_val', newQte);
                                var tempQteText = newQte + ' ' + child.eq(0).attr('data-qte_name');
                                child.eq(2).html(tempQteText);
                                design.useSound();
                                alertify.success('المنتج موجود بنفس الوحدة وتم إضافة الكمية للكمية الموجودة لتصبح ' + tempQteText);
                                addIndexAndTotalToTable();
                            },
                            cancel: function (button) {
                                alertify.success('تم إلغاء الإضافة ');
                            },
                            post: true,
                            confirmButtonClass: "btn-danger",
                            cancelButtonClass: "btn-default",
                            dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                        });
                    }
                }
            }
            return result;
        }


        //check if product exist in details before with other unit or
        // this unit and check if qteIn store less than total qte for this porduct in store
        function checkIfProductExistInTableWithCheckQte() {
            var product_id = $('#selectProduct option:selected').attr('data-id');
            var unit_id = $('#select_unit option:selected').attr('data-unit_id');
            var addQteByMainUnit = $('#input_product_qte').val() * $('#select_unit option:selected').attr('data-relation_qte');
            var resultExistInDetails = true;
            var rowExist = 0;
            var rowPrice = 0;
            var totalExistQteByMainUnit = 0;
            var qteInStokeByMainUnit = 0;
            var messageCheckQte = '';
            var rowExistWithOtherUnit = '';
            $('#mainTable tbody tr').each(function () {
                if (product_id == $(this).children().eq(0).attr('data-product_id')) {
                    if (unit_id == $(this).children().eq(0).attr('data-unit_id')) {
                        resultExistInDetails = false;
                        rowExist = $(this);
                        rowPrice = $(this).children().eq(0).attr('data-price');
                    }
                    rowExistWithOtherUnit = $(this);
                    totalExistQteByMainUnit -= -$(this).children().eq(0).attr('data-qte_add_by_main_unit');
                    qteInStokeByMainUnit = $(this).children().eq(0).attr('data-qte_in_stoke') * 1;
                }
            });

            //check if total qte in detaisl + qte add less than qte in stoke
            if (!resultExistInDetails || rowExistWithOtherUnit != '') {
                if (addQteByMainUnit - -totalExistQteByMainUnit > qteInStokeByMainUnit && $('#radioName').prop('checked')) {
                    messageCheckQte = ('هذة الكمية غير متاحة (المنتج موجود فى الفاتورة) والكمية الممكن إضافتها للكمية الموجودة ' + '</br>' +
                        roundTo(qteInStokeByMainUnit - totalExistQteByMainUnit) + ' ' + (rowExist != '' ? rowExist.children().eq(0).attr('data-main_unit_name') : rowExistWithOtherUnit.children().eq(0).attr('data-main_unit_name')));
                    design.useSound('error');
                    alertify.error(messageCheckQte);
                    messageCheckQte = '';
                    resultExistInDetails = false;
                    return false;
                }
            }

            //check if product exist by this unit
            if (!resultExistInDetails) {
                var action = $('#divSettingForAddExistProductToDetailsWithSameUnit input[name="radioSettingAddExistProduct"]:checked').val();
                if (action == 1) {//don't add qte
                    design.useSound('error');
                    alertify.error('هذا المنتج موجود بنفس الوحدة');
                } else if (action == 0) {//add qte to qte
                    //check if price change
                    if (rowPrice != $('#inputProductPrice').val()) {
                        design.useSound('error');
                        alertify.error('هذا المنتج موجود بنفس الوحدة ولكن بسعر مختلف ');
                    } else {
                        //check qte before add qte to qte
                        if (messageCheckQte != '') {
                            design.useSound('error');
                            alertify.error(messageCheckQte);
                            messageCheckQte = '';
                            return false;
                        }

                        var child = rowExist.children();
                        var newQte = child.eq(0).attr('data-qte_val') - -$('#input_product_qte').val();
                        //update qte
                        child.eq(0).attr('data-qte_val', newQte);
                        child.eq(0).attr('data-qte_add_by_main_unit', (child.eq(0).attr('data-qte_add_by_main_unit') - -addQteByMainUnit));
                        var tempQteText = newQte + ' ' + child.eq(0).attr('data-qte_name');
                        child.eq(2).html(tempQteText);
                        design.useSound();
                        alertify.success('المنتج موجود بنفس الوحدة وتم إضافة الكمية للكمية الموجودة لتصبح ' + tempQteText);
                        addIndexAndTotalToTable();
                    }
                } else {//ask before add qte
                    //check if price change
                    if (rowPrice != $('#inputProductPrice').val()) {
                        design.useSound('error');
                        alertify.error('هذا المنتج موجود بنفس الوحدة ولكن بسعر مختلف ');
                    } else {
                        //check qte before add qte to qte
                        if (messageCheckQte != '') {
                            design.useSound('error');
                            alertify.error(messageCheckQte);
                            messageCheckQte = '';
                            return false;
                        }

                        design.useSound('info');
                        $(this).confirm({
                            text: "هذا المنتج موجود فى الفاتورة بنفس الوحدة هل تريد الإضافة للكمية الموجودة؟",
                            title: "إضافة كمية لمنتج بنفس الوحدة",
                            confirm: function (button) {
                                var child = rowExist.children();
                                var newQte = child.eq(0).attr('data-qte_val') - -$('#input_product_qte').val();
                                //update qte
                                child.eq(0).attr('data-qte_val', newQte);
                                child.eq(0).attr('data-qte_add_by_main_unit', (child.eq(0).attr('data-qte_add_by_main_unit') - -addQteByMainUnit));
                                var tempQteText = newQte + ' ' + child.eq(0).attr('data-qte_name');
                                child.eq(2).html(tempQteText);
                                design.useSound();
                                alertify.success('المنتج موجود بنفس الوحدة وتم إضافة الكمية للكمية الموجودة لتصبح ' + tempQteText);
                                addIndexAndTotalToTable();
                            },
                            cancel: function (button) {
                                alertify.success('تم إلغاء الإضافة ');
                            },
                            post: true,
                            confirmButtonClass: "btn-danger",
                            cancelButtonClass: "btn-default",
                            dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                        });
                    }
                }
            }
            return resultExistInDetails;
        }

        //show edit rowInTable
        var tempRowEditInTable = '';
        $('#mainTable').on('click', 'tbody button[data-edit]', function () {
            tempRowEditInTable = $(this).parent().siblings();
            $('#spanEditProductName').html(tempRowEditInTable.eq(1).html());
            $('#input_edit_product_qte').val(tempRowEditInTable.eq(0).attr('data-qte_val'));
            @if($type==1)
            // console.log(tempRowEditInTable.eq(0).attr('data-relation_qte'));
            $('#input_edit_product_qte').attr('data-max_qte', (tempRowEditInTable.eq(0).attr('data-qte_in_stoke') / tempRowEditInTable.eq(0).attr('data-relation_qte')));
            @else
            $('#input_edit_product_qte').attr('data-max_qte', 1000000);
            @endif
            $('#spanEditUnitName').html(tempRowEditInTable.eq(0).attr('data-qte_name'));
            $('#inputEditProductPrice').val(tempRowEditInTable.eq(0).attr('data-price'));
            $('#divContainerEditRowInTable').removeClass('d-none');
            design.useSound();
        });
        //save edit rowInTable
        $('#buttonSaveEditRowInTable').click(function () {
            //check if qte not valid
            if ($('#input_edit_product_qte').hasClass('is-invalid') || $('#input_edit_product_qte').val() == 0) {
                alertify.error('برجاء التحقق من الكمية ');
                design.useSound('error');
                $('#input_edit_product_qte').select();
                return;
            }
            //check if price not valid
            if (($('#inputEditProductPrice').hasClass('is-invalid') || $('#inputEditProductPrice').val() == 0)&& $('#inputEditProductPrice').val() != '00' ) {
                alertify.error('برجاء التحقق من السعر ');
                design.useSound('error');
                $('#inputEditProductPrice').select();
                return;
            }

            //check if qte lesth than qte in stoke (sale )and when type is (bay) max_qte is million
            var tempMaxQteWhenEdit = $('#input_edit_product_qte').attr('data-max_qte') * 1;
            if ($('#input_edit_product_qte').val() * 1 > tempMaxQteWhenEdit && $('#radioName').prop('checked')) {
                alertify.error("هذه الكمية غير متاحة الكمية المتاحة " + '</br>' + roundTo(tempMaxQteWhenEdit) + " " + $('#spanEditUnitName').html());
                design.useSound('error');
                $('#input_edit_product_qte').select();
                return;
            }

            //update price and qte
            tempRowEditInTable.eq(0).attr('data-qte_val', $('#input_edit_product_qte').val());
            tempRowEditInTable.eq(0).attr('data-qte_add_by_main_unit', $('#input_edit_product_qte').val() * tempRowEditInTable.eq(0).attr('data-relation_qte'));
            var tempQteText = $('#input_edit_product_qte').val() + ' ' + tempRowEditInTable.eq(0).attr('data-qte_name');
            tempRowEditInTable.eq(2).html(tempQteText);
            tempRowEditInTable.eq(0).attr('data-price', $('#inputEditProductPrice').val());
            tempRowEditInTable.eq(3).html($('#inputEditProductPrice').val());
            addIndexAndTotalToTable();
            $('#divContainerEditRowInTable').addClass('d-none');
            alertify.success('تم التعديل بنجاح');
            design.useSound();
        });
        $('#inputEditProductPrice,#input_edit_product_qte').click(function () {
            $(this).select();
        });
        $('#inputEditProductPrice,#input_edit_product_qte').on('keypress', function (e) {
            if (e.which == 13) {
                $('#buttonSaveEditRowInTable').trigger('click');
            }
        });


        //updateTotalAndRentAfterDiscount
        function updateTotalAndRentAfterDiscount() {
            var totalAfterDis = $('#span_total_table').html() - $('#input_discount').val();
            //make paid is total after discoutn automatic
            if ($('#select_account_name').val() == '0' && Cookie.get('make_paid_total{{$type}}') != '') {
                var tempValue = Cookie.get('make_paid_total{{$type}}');
                if (tempValue == 'true') {
                    $('#input_totalPaid').val(roundTo(totalAfterDis));
                }
            }
            $('#input_totalPriceAfterDiscount').val((!$('#input_discount').hasClass('is-invalid') ? roundTo(totalAfterDis) : '0'));
            $('#input_rent').val((!$('#input_totalPaid').hasClass('is-invalid') && !$('#input_discount').hasClass('is-invalid') ? roundTo(totalAfterDis - $('#input_totalPaid').val()) : '0'));
        }

        $('#input_discount,#input_totalPaid').keyup(function () {
            updateTotalAndRentAfterDiscount();
        });
        $('#input_discount,#input_totalPaid').click(function () {
            $(this).select();
        });


        //disable submit when enter in input in form
        var stateSubmitByInput = false;
        design.disable_input_submit_when_enter('#mainForm input');
       /* $('#mainForm input').keydown(function (e) {
            if (e.keyCode == 13) {
                stateSubmitByInput = true;
            }
        });*/

        var stateCheckIfTotalPaidGreaterThanTotalPrice = false;
        var printLink = "{{route('bills.print')}}";
        $('#mainForm').submit(function (e) {
            var account_id = $('#select_account_name').val();
            var totalDetailForBill = $('#span_total_table').html() * 1;
            var discount = $('#input_discount').val() * 1;
            var totalPaid = $('#input_totalPaid').val() * 1;
            var stoke_id = $('#select_stoke_id').val();
            //check if submit is run buy enter in input to prevent it
            if (stateSubmitByInput) {
                stateSubmitByInput = false;
                e.preventDefault();
                return;
            }
            //check if stoke is please select
            if (stoke_id == '' || stoke_id == null) {
                alertify.error('برجاء تحديد مخزن للفاتورة ');
                design.useSound('error');
                e.preventDefault();
                return;
            }
            //check if account is please select
            if (account_id == '') {
                alertify.error('برجاء تحديد شخص للفاتورة ');
                design.useSound('error');
                e.preventDefault();
                return;
            }
            @if($type!=2)
            //check if account is no account
            if (account_id == '0') {
                if (roundTo(totalPaid - -discount) != roundTo(totalDetailForBill)) {
                    alertify.error('عند إضافة فاتورة بدون شخص يجب أن يتم دفع إجمالى الفاتورة بعد الخصم ');
                    design.useSound('error');
                    e.preventDefault();
                    return;
                }
            }

            //check if totalPaid greater than total bill - discount
            if (roundTo(totalPaid - -discount) > roundTo(totalDetailForBill) && !stateCheckIfTotalPaidGreaterThanTotalPrice) {
                design.useSound('info');
                $(this).confirm({
                    text: "المبلغ المدفوع أكبر من قيمة الفاتورة هل تريد الحفظ ؟",
                    title: "حفظ فاتورة",
                    confirm: function (button) {
                        stateCheckIfTotalPaidGreaterThanTotalPrice = true;
                        $('#mainForm').trigger('submit');
                    },
                    cancel: function (button) {
                        design.useSound('success');
                        e.preventDefault();
                        return;
                        alertify.success('تم إلغاء الإضافة ');
                    },
                    post: true,
                    confirmButtonClass: "btn-danger",
                    cancelButtonClass: "btn-default",
                    dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                });
                e.preventDefault();
                return;
            }
            stateCheckIfTotalPaidGreaterThanTotalPrice = false;
            @endif
            //check if no product in bills
            if (totalDetailForBill == 0) {
                alertify.error('برجاء إضافة منتجات للفاتورة ');
                design.useSound('error');
                e.preventDefault();
                return;
            }

            //disable edit before send data
            $('#load').css('display', 'block');

            //send data by ajax
            e.preventDefault();
            var bill_details = [];
            $('#mainTable tbody tr').each(function () {
                var rowData = $(this).children().eq(0);
                bill_details.unshift([rowData.attr('data-product_id'), rowData.attr('data-unit_id'), rowData.attr('data-qte_val'), rowData.attr('data-price')]);
            });

            @if($type==2)//for print price show
            e.preventDefault();
            print_price_show();
            return;

            @endif
            $.ajax({
                url: '{{route('bills.store')}}',
                method: 'POST',
                data: {
                    type: '{{$type}}',
                    stoke_id: stoke_id,
                    //new account
                    account_id: account_id,
                    new_account_name: $('#input_new_account_name').val(),
                    new_account_tel: $('#input_new_account_tell').val(),
                    new_account_old_account: $('#input_new_account_account').val(),
                    is_supplier: $('#input_is_supplier').prop('checked') ? 1 : 0,
                    is_customer: $('#input_is_customer').prop('checked') ? 1 : 0,

                    //bill details
                    bill_details: bill_details,
                    total_price: (totalDetailForBill - discount),
                    discount: discount,
                    total_paid: totalPaid,
                    message: $('#message').val(),

                    @if(Hash::check('use_price2',$permit->use_price2 ) && $type!=0)
                    price_type: $('#div_container_type_price input[type="radio"]:checked').attr('value'),
                    @endif
                },
                // dataType: 'JSON',
                success: function (data) {
                    if (data == 'success') {
                        // design.useSound('success');
                        if ($('#check_print_bill').prop('checked')) {
                            if ($('#check_print_in_new_window').prop('checked')) {
                                window.open('{{route('bills.print')}}', '_blank');
                                window.location.reload(true);
                            } else {
                                $('#iframe_print_bill').removeClass('d-none').attr('src', printLink);
                                /*setTimeout(function () {
                                    window.location.reload(true);
                                },2000);*/
                                var reloadThisPage = true;
                                setInterval(function () {
                                    if (reloadThisPage) {
                                        reloadThisPage = false;
                                        window.location.reload(true);
                                    }
                                }, 2000);
                            }
                        } else {
                            window.location.reload(true);
                        }
                    } else {
                        console.log(data);
                        $('#load').css('display', 'none');
                        alertify.log(data, 'error', 0);
                        design.useSound('error');
                    }
                    // $('#load').css('display', 'none');
                    // console.log(data);
                },
                error: function (e) {
                    $('#load').css('display', 'none');
                    alert('error');
                    design.useSound('error');
                    console.log(e);

                }
            });
        });

        //go to barcod when use arrow up or arrow down in qte or preice when use barcode
        $('#inputProductPrice').on('keyup keypress', function (e) {
            if ($('#radioBarcode').prop('checked')) {
                var keyCode = e.keyCode || e.which;
                if (keyCode == 40) {
                    $('#input_barcode').select();
                }else if (keyCode === 38 ) {
                    $('#input_product_qte').select();
                }
            }
        });
        $('#input_product_qte').on('keyup keypress', function (e) {
            if ($('#radioBarcode').prop('checked')) {
                var keyCode = e.keyCode || e.which;
                if ( keyCode == 40) {
                    $('#inputProductPrice').select();
                }else if (keyCode === 38 ) {
                    $('#input_barcode').select();
                }
            }
        });
        //go to qte when key down in barcode and go to price when key up
        $('#input_barcode').on('keyup',function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 40 ) {
                $('#input_product_qte').select();
            }else if(keyCode===38){
                $('#inputProductPrice').select();
            }
        });
        //make paid is total after discoutn automatic
        if ($('#select_account_name').val() != '0') {
            $('#button_set_paid_total,#button_disable_set_paid_total').addClass('d-none');
        } else {
            if (Cookie.get('make_paid_total{{$type}}') == 'true') {
                $('#button_disable_set_paid_total').removeClass('d-none');
                $('#button_set_paid_total').addClass('d-none');
            } else {
                $('#button_disable_set_paid_total').addClass('d-none');
                $('#button_set_paid_total').removeClass('d-none');
            }
        }
        $('#select_account_name').change(function () {
            if ($(this).val() == '0') {
                if (Cookie.get('make_paid_total{{$type}}') == 'true') {
                    $('#button_disable_set_paid_total').removeClass('d-none');
                    $('#button_set_paid_total').addClass('d-none');
                } else {
                    $('#button_disable_set_paid_total').addClass('d-none');
                    $('#button_set_paid_total').removeClass('d-none');
                }
            } else {
                $('#button_set_paid_total,#button_disable_set_paid_total').addClass('d-none');
            }

        });
        $('#button_set_paid_total').click(function () {
            design.useSound();
            Cookie.set('make_paid_total{{$type}}', true, {expires: 365});
            alertify.success('تم ضبط المبلغ المدفوع ليصح الإجمالى بعد الخصم إتوماتيك!');
            updateTotalAndRentAfterDiscount();
            $(this).addClass('d-none');
            $('#button_disable_set_paid_total').removeClass('d-none');
        });
        $('#button_disable_set_paid_total').click(function () {
            design.useSound();
            Cookie.remove('make_paid_total{{$type}}');
            alertify.success('تم إلغاء ضبط المبلغ المدفوع ليصح الإجمالى بعد الخصم إتوماتيك!');
            updateTotalAndRentAfterDiscount();
            $(this).addClass('d-none');
            $('#button_set_paid_total').removeClass('d-none');
        });

        //print bill when click in + button
        @if($type==2)
        design.click_when_key_add('#button_print_show_price');
        // $('#button_print_show_price').trigger('click');
        @else
        design.click_when_key_add('#button_save_bill');

        // $('#button_save_bill').trigger('click');
        @endif
    </script>
    <script defer>
        //set cookie for print
        if (Cookie.get('print_bill{{$type}}') != '') {
            var tempValue = Cookie.get('print_bill{{$type}}');
            if (tempValue == 'true') {
                $('#check_print_bill').prop('checked', true);
                $('#check_print_bill').prop('checked', true);
                $('#check_print_in_new_window').removeClass('d-none');
            } else {
                $('#check_print_bill').prop('checked', false);
                $('#check_print_in_new_window').addClass('d-none');
            }
        }
        if (Cookie.get('check_print_in_new_window{{$type}}') != '') {
            var tempValue = Cookie.get('check_print_in_new_window{{$type}}');
            if (tempValue == 'true') {
                $('#check_print_in_new_window').prop('checked', true);
            } else {
                $('#check_print_in_new_window').prop('checked', false);
            }
        }
        $('#check_print_bill').change(function () {
            design.useSound();
            if ($('#check_print_bill').prop('checked')) {
                Cookie.set('print_bill{{$type}}', $(this).prop('checked'), {expires: 365});
                $('#check_print_in_new_window').removeClass('d-none');
            } else {
                Cookie.remove('print_bill{{$type}}');
                $('#check_print_in_new_window').addClass('d-none');
            }
        });
        $('#check_print_in_new_window').change(function () {
            if ($(this).prop('checked')) {
                Cookie.set('check_print_in_new_window{{$type}}', $(this).prop('checked'), {
                    expires: 365
                });
            } else {
                Cookie.remove('check_print_in_new_window{{$type}}');
            }
            design.useSound();
        });

        //set cookie for special product
        if (Cookie.get('checkProductSpecialInAddBill{{$type}}') != '') {
            var tempValue = Cookie.get('checkProductSpecialInAddBill{{$type}}');
            if (tempValue == 'true') {
                $('#checkProductSpecial').prop('checked', true);
                $('#checkProductSpecial').trigger('change');
            } else {
                $('#checkProductSpecial').prop('checked', false);
            }
        }
        $('#checkProductSpecial').change(function () {
            if ($(this).prop('checked')) {
                Cookie.set('checkProductSpecialInAddBill{{$type}}', $(this).prop('checked'), {
                    expires: 365
                });
            } else {
                Cookie.remove('checkProductSpecialInAddBill{{$type}}');
            }
            design.useSound();
        });

        //set default value for price slae , price show  type
        @if(Hash::check('use_price2',$permit->use_price2) && $type!=0)
        if (Cookie.get('bill_price{{$type}}') != null) {
            $('#input_price' + Cookie.get('bill_price{{$type}}')).prop('checked', 'checked');
        } else {
            $('#input_price1').prop('checked', 'checked');
        }

        $('#div_container_type_price input[type="radio"]').change(function () {
            //update cooke to set default value
            Cookie.set('bill_price{{$type}}', $('#div_container_type_price input[type="radio"]:checked').attr('value'), {
                expires: 365
            });
            design.useSound();
        });
        @endif
    </script>

    {{--print price show--}}
    @if ($type==2)
        @if ($print_design->use_small_size==0)
            <script defer>
                function print_price_show() {
                    alertify.success('جارى الطباعة!');
                    design.useSound();
                    //set account name
                    if ($('#select_account_name').val() == 0) {
                        $('#tr_bill_offer_account_name').parent().addClass('d-none');
                    } else if ($('#select_account_name').val() == -1) {
                        $('#tr_bill_offer_account_name').html($('#input_new_account_name').val());
                    } else {
                        $('#tr_bill_offer_account_name').html($('#select_account_name option:selected').html());
                    }
                    //set bill message
                    $('#span_offer_message').html($('#message').val());


                    //add details to bill
                    $('#tableOfferData tbody').html('');
                    $('#mainTable tbody tr').each(function () {
                        $('#tableOfferData').prepend(
                            $(this).clone()
                        );
                    });
                    //remove column oberation
                    $('#tableOfferData tbody tr').each(function () {
                        $(this).children().eq(1).attr('colspan', 2);
                        $(this).removeClass('table-success');
                        $(this).children().eq(5).remove();
                    });

                    var offer_discount = $('#input_discount').val();
                    if (offer_discount == 0) {
                        $('#tr_offer_discount,#td_span_total_if_has_dicount').addClass('d-none');
                    } else {
                        $('#tr_offer_discount').children().eq(1).html(offer_discount + 'ج');
                        $('#tr_offer_discount').children().eq(3).html($('#span_total_table').html() + 'ج');
                    }
                    $('#tr_offer_total_after_discount').children().eq(1).html($('#input_totalPriceAfterDiscount').val() + 'ج');

                    $('#section_print_price_show').removeClass('d-none');
                    $('#section_bill_design').parent().printArea({
                        extraCss: '{{asset('css/print_bill.css')}}',
                        mode:'popup',
                        popClose:true,
                        // autoCloseAfterPrint:true,
                        autoReloadAfterPrint: true,
                    });
                    /*$(document).onafterprint(function () {
                        window.location.reload();
                    })*/
                    // setInterval(function(){window.location.reload(); }, 2000);
                   /* window.open('{{route('bills.create',2)}}', '_blank');
                    setInterval(function(){
                        window.close();
                    }, 2000);*/
                }
            </script>
        @else
            <script defer>
                function print_price_show() {
                    alertify.success('جارى الطباعة!');
                    design.useSound();
                    //set account name
                    if ($('#select_account_name').val() == 0) {
                        $('#span_bill_offer_account_name').parent().addClass('d-none');
                        ;
                    } else if ($('#select_account_name').val() == -1) {
                        $('#span_bill_offer_account_name').html($('#input_new_account_name').val());
                    } else {
                        $('#span_bill_offer_account_name').html($('#select_account_name option:selected').html());
                    }
                    //set bill message
                    $('#span_offer_message').html($('#message').val());


                    //add details to bill
                    $('#tableOfferData tbody').html('');
                    $('#mainTable tbody tr').each(function () {
                        var tr = $(this).children();
                        $('#tableOfferData').append(
                            '<tr class="">' +
                            '<td>' + tr.eq(1).html() + '</td>' +
                            '<td>' + tr.eq(2).html() + '</td>' +
                            '<td>' + tr.eq(3).html() + '</td>' +
                            '<td>' + tr.eq(4).html() + '</td>' +
                            '</tr>'
                        );
                    });

                    var offer_discount = $('#input_discount').val();
                    if (offer_discount == 0) {
                        $('#tr_discount11,#tr_discount12,#td_span_total_if_has_dicount1').addClass('d-none');
                    } else {
                        $('#tr_discount12').children().eq(1).html(offer_discount + ' ج ');
                        $('#tr_discount11').children().eq(1).html($('#span_total_table').html() + 'ج');
                    }
                    $('#tr_offer_total_after_discount').html($('#input_totalPriceAfterDiscount').val() + 'ج');

                    $('#section_print_price_show').removeClass('d-none');

                    $('#section_small_bill_design').parent().printArea({
                        extraCss: '{{asset('css/print_bill.css')}}',
                        stopHeader: true,
                        // mode:'popup',
                        // popClose:true,
                        autoReloadAfterPrint: true,
                    });


                    /* setTimeout(function () {
                         window.location.reload(true);
                     },2000);*/
                    /*var reloadThisPage = true;
                    setInterval(function () {
                        if (reloadThisPage) {
                            reloadThisPage = false;
                            console.log(3);
                            window.location.reload(true);
                        }
                    }, 2000);*/

                }
            </script>
        @endif

    @endif
@endsection
