<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    تصميم الفواتير
@endsection
@section('css')
    <style>
        table{
            /*word-break: break-all;*/
            /*overflow-wrap: break-word;*/
            white-space: normal;
        }
        #section_bill_container_check input[type='checkbox'], #section_bill_container_check input[type="radio"],#section_edit input[type='checkbox'] {
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
    </style>

    <style>
        #div_container_fonts_size input{
            font-size: 1.5rem!important;
            text-align: center!important;
        }
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
            padding: 4px!important;
        }

    </style>

@endsection
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section id="section_bill_type" class='animated fadeInDown faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 text-white'> تصميم الفاتورة
                    <select class="custom-select" id="select_bill_name" style="max-width: 300px">
                        @foreach ($bills as $b)
                            <option value="{{$b->id}}">{{$b->name}}</option>
                        @endforeach
                    </select>
                    <button class='btn btn-info tooltips'
                            onclick="$(this,'#printBillDesign').addClass('d-none');$('#section_edit').removeClass('d-none');
                    $('#select_bill_name').attr('disabled','disabled');design.useSound();design.updateNiceScroll();"
                            data-placement='left' title='تعديل التصميم '><i class='fas fa-2x fa-edit text-white'></i>
                    </button>
                    <button id="printBillDesign" class="btn btn-info  text-white tooltips mt-1" data-placement="bottom"
                            title="طباعة معاينة">
                        <span class="h3"><i class="fas fa-print"></i></span>
                    </button>
                </h1>
            </div>
        </section>
        <section id="section_edit" class="box mt-2 d-none mx-auto font-en h3 text-center"
                 style="max-width: 1024px;background: #ebedf7">
            <form enctype='multipart/form-data' id="form_update_print" action='{{route('bill_prints.update',0)}}'
                  method='post' class="p-3">
                @csrf
                @method('put')
                <div class='form-group font-en row'>
                    <label class='col-4 pt-2'>لــــوجـو الشــــركـة</label>
                    <div class='col-8 text-right'>
                        <input type='file' name='img' value="تحميل لوجو من الجهاز" id="input_file_logo"
                               class="btn btn-primary"
                               style='right: -103px;top: 5px;border: none!important;'
                               accept='image/x-png,image/jpeg,image/ico'>
                    </div>
                </div>
                <div class='form-group font-en row'>
                    <label class='col-4 pt-2'>إســـــــــم تصمـيـم الطبــــاعــــة</label>
                    <div class='col-8'>
                        <input type="text" style="font-size: 1.5rem!important;height: 51px" id="input_edit_name"
                               required name="name"
                               value="برجاء إدخال إسم لتصميم الطباعة" class="form-control">
                    </div>
                </div>
                <div class='form-group font-en row'>
                    <label class='col-4 pt-2'>إســـــم الشـــركـة فى الفــاتـورة</label>
                    <div class='col-8'>
                        <input type="text" style="font-size: 1.5rem!important;height: 51px" required name="company_name"
                               id="input_company_name"
                               value="برجاء إدخال إسم الشركة" class="form-control">
                    </div>
                </div>
                <div class='form-group font-en row'>
                    <label class='col-4 pt-2'>النـــص تحت إســــم الشـــركــة</label>
                    <div class='col-8'>
                        <input type="text" style="font-size: 1.5rem!important;height: 51px"
                               name="row_under_company_name" id="input_row_under_company_name"
                               value="برجاء إدخال النص تحت إسم الشركة" class="form-control">
                    </div>
                </div>
                <div class='form-group font-en row'>
                    <label class='col-4 pt-2'>السطر 1 فى بيانات التواصل</label>
                    <div class='col-8'>
                        <input type="text" style="font-size: 1.5rem!important;height: 51px" name="row_contact1"
                               id="input_row_contact1"
                               value="برجاء إدخال النص تحت إسم الشركة" class="form-control">
                    </div>
                </div>
                <div class='form-group font-en row'>
                    <label class='col-4 pt-2'>السطر 2 فى بيانات التواصل</label>
                    <div class='col-8'>
                        <input type="text" style="font-size: 1.5rem!important;height: 51px" name="row_contact2"
                               id="input_row_contact2"
                               value="برجاء إدخال النص تحت إسم الشركة" class="form-control">
                    </div>
                </div>
                <div class="row no-gutters" id="div_container_fonts_size">
                    <input type="text" id="header_size" name="header_size" class="form-control tooltips col" data-placement="bottom" title="حجم الخط فى إسم الشركة والسطر تحت إسم الشركة">
                    <input type="text" id="bill_number_date_size" name="bill_number_date_size" class="form-control tooltips col" data-placement="bottom" title="حجم الخط فى رقم وتاريخ الفاتورة">
                    <input type="text" id="contact_size" name="contact_size" class="form-control tooltips col" data-placement="bottom" title="حجم الخط فى بيانات التواصل مع الشركة">
                    <input type="text" id="account_size" name="account_size" class="form-control tooltips col" data-placement="bottom" title="حجم الخط فى بيانات الشخص صاحب الفاتورة">
                    <input type="text" id="table_header_size" name="table_header_size" class="form-control tooltips col" data-placement="bottom" title="حجم الخط فى أسماء الأعمدة فى الفاتورة">
                    <input type="text" id="table_body_size" name="table_body_size" class="form-control tooltips col" data-placement="bottom" title="حجم الخط فى المنتجات فى الفاتورة">
                    <input type="text" id="table_footer_size" name="table_footer_size" class="form-control tooltips col" data-placement="bottom" title="حجم الخط فى الحساب والإجمالى للفاتورة">
                    <input type="text" id="message_uc_size" name="message_uc_size" class="form-control tooltips col" data-placement="bottom" title="حجم الخط فى رسالة الفاتورة">
                    <input type="text" id="opacity_background" name="opacity_background" class="form-control tooltips col" data-placement="bottom" title="درجة الشفافية للوجو فى الخلفية وتقبل قيمة من 0 إلى 1 ">
                </div>
                <label class="checkbox-inline pl-4 pointer tooltips" data-placement="left"
                       title="حجم ورق الطباعة الصغير (أقل من A5) 6 سم , 7.7 سم ,10 سم إلخ ..." dir="ltr">الحجم
                    الصغير<input type="checkbox"
                                 id="input_use_small_size"
                                 name="use_small_size" value="1"></label>
                <div class="d-inline-block"  style="width: 200px">
                    <div class="pl-0 input-group" >
                        <input type="text"
                               id="input_small_size"
                               onclick="$(this).select();"
                               style="font-size: 1.2rem!important;height: 48px;"
                               name="small_size" value="6"
                               class="form-control tooltips" data-placement='bottom'
                               title='حجم ورق الطباعة بال سم عند إستخدام الحجم الصغير' placeholder="حجم ورق الطباعة">
                        <span class="input-group-append input-group-text" style="font-size: 1.2rem!important;">سم</span>
                    </div>
                </div>

                <div class='form-group row'>
                    <div class='col-sm-6'>
                        <button type='submit'
                                class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                            <span class='h4 font-weight-bold'>حفظ التعديل</span>
                        </button>
                    </div>
                    <div class='col-sm-6'>
                        <button type='button' onclick="design.useSound();window.location.reload(true);"
                                class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInLeft fast'>
                            <span class='h4 font-weight-bold'>إلغاء التعديل</span>
                        </button>
                    </div>
                </div>
            </form>
        </section>
        <section id="section_bill_container_check">
            <div class="text-white text-center h3 mx-auto">
                <label class="checkbox-inline pl-4 pointer tooltips" data-placement="left"
                       title="معاينة فى حالة عدم وجود باقى وعدم وجود حساب سابق" dir="ltr">كاش<input type="radio"
                                                                                                    id="check_bill_cash"
                                                                                                    checked
                                                                                                    name="print_type"></label>
                <label class="checkbox-inline pl-4 pointer tooltips" data-placement="left"
                       title="معاينة فى حالة وجود باقى أو وجود حساب سابق" dir="ltr">أجل<input type="radio"
                                                                                              id="check_bill_not_cash"
                                                                                              name="print_type"></label>
                <label class="checkbox-inline pl-4 pointer tooltips" data-placement="left"
                       title="معاينة فى حالة وجود خصم" dir="ltr">وجود خصم<input type="checkbox" checked
                                                                                id="check_has_discount"></label>
                <label class="checkbox-inline pl-4 pointer tooltips" data-placement="left"
                       title="معاينة فى حالة وجود حساب سابق" dir="ltr">بحساب سابق<input type="checkbox"
                                                                                        id="check_has_account"></label>
                <label class="checkbox-inline pl-4 pointer tooltips" data-placement="left"
                       title="معاينة فى حالة حجم ورق الطباعة الصغير (أقل من A5) 6 سم , 7.7 سم ,10 سم إلخ ..." dir="ltr">الحجم
                    الصغير<input type="checkbox"
                                 id="check_small_size"></label>
                <div class="d-inline-block" id="div_size_small_bill_design" style="width: 200px">
                    <div class="pl-0 input-group" >
                        <input type="text" name=''
                               style="font-size: 1.2rem!important;height: 48px;"
                               value="6"
                               onclick="$(this).select();"
                               class="form-control tooltips" data-placement='bottom'
                               title='حجم ورق الطباعة بال سم' placeholder="حجم ورق الطباعة">
                        <span class="input-group-append input-group-text" style="font-size: 1.2rem!important;">سم</span>
                    </div>
                </div>
            </div>
        </section>
        <div>
            <section id="section_bill_design" class='mx-auto small_design font-ar p-3 pb-0 bg-white text-center'
                     style="max-width: 1024px">
                <div class='row no-gutters'>
                    <div class='col-7 text-right font-weight-bold'>
                        <img height='80px'
                             class='' id="img_design" src='{{asset('img/icon.ico')}}'
                             style='vertical-align: top;max-width: 80px'>
                        <div class='d-inline-block text-center'>
                            <p id="p_design_company_name" class="mb-0">شركة سيفي بلاست لتصنيع المنتجات البلاستيكيه</p>
                            <p id="p_design_row_under_company_name"> سعداء بخدمتكم</p>
                        </div>

                    </div>
                    <div class='col-5 text-left font-weight-bold'>
                        <p id="saleId" class='b ml-3 mb-1 d-inline-block px-2' style="">
                            <label> رقم الفاتورة : </label>
                            <span data-type='billNumber' class='font-en'>5</span>
                        </p>
                        <div class="text-left">
                            <div class='b d-inline-block ml-auto  px-2' style="">
                                <label> التاريخ : </label>
                                <span data-type='date' class='font-en'>{{$date}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='font-en py-1 contact text-right'>
                    <div class='text-right' id="div_row_contact1">
                        ادارة: أ/ محفوظ :
                        01140757478
                    </div>
                    <div id="div_row_contact2" class='text-right pl-3'>
                        للتواصل مع خدمة العملاء
                        أ/ تامر:
                        01148000353
                    </div>
                </div>
                <table class='w-100 account' border='3'>
                    <tr>
                        <td class='width-15 pb-1'>إســــم العميل</td>
                        <td style='width: 40%'>أحمد محمد</td>
                        <td class='width-15'>رقم الهاتف</td>
                        <td style='width: 40%'>010180304220</td>
                    </tr>
                    <tr>
                        <td class='width-15'>عنوان العميل</td>
                        <td style='width: 40%' class="text-right" readonly="" colspan="3"></td>
                    </tr>
                    <tr style="display: none" id="driver">
                        <td class='width-15'>اسم السائق</td>
                        <td style='width: 40%'></td>
                        <td class='width-15'>رقم السائق</td>
                        <td style='width: 40%'></td>
                    </tr>
                </table>
                <table class='w-100 mt-2' border='3' id="tableData">
                    <thead>
                    <tr class='' style="background: #e4e4db;">
                        <th style='width:50px'>م</th>
                        <th colspan='2'>الصنف</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>إجمالي</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class=''>
                        <td style='width:50px'>1</td>
                        <td colspan='2'>منتج 1</td>
                        <td>10 قطعة</td>
                        <td>5 ج</td>
                        <td>50 ج</td>
                    </tr>
                    <tr class=''>
                        <td style='width:50px'>2</td>
                        <td colspan='2'>منتج 2</td>
                        <td>10 قطعة</td>
                        <td>5 ج</td>
                        <td>50 ج</td>
                    </tr>
                    <tr style='height: 20px;border: 0px red solid;border: none'></tr>
                    </tbody>
                    <tfoot>
                    <tr class='' id="tr_discount">
                        <td colspan="2">الخصم</td>
                        <td class='font-en'>
                            5 ج
                        </td>
                        <td colspan='2'>إجمالى الفاتورة قبل الخصم</td>
                        <td class='font-en'>
                            100 ج
                        </td>
                    </tr>
                    <tr class='' style="background: #ccc">
                        <td colspan="5">إجمالى الفاتورة
                            <span id="td_span_total_if_has_dicount">بعد الخصم</span>
                            <span id="td_span_total_if_it_cash" class="small">(خالص مع الشكر)</span>
                        </td>
                        <td class='font-en'>
                            5 ج
                        </td>
                    </tr>
                    <tr class='' id="tr_bill_cash">
                        <th colspan='3'>
                            المبلغ المدفوع
                        </th>
                        <th class='font-en'>
                            30 جنية
                        </th>
                        <th colspan='1'>
                            الباقى
                        </th>
                        <th class='font-en'>
                            30 جنية
                        </th>
                    </tr>
                    <tr class='' id="tr_account_old_account">
                        <td colspan='5'>الحساب السابق</td>
                        <td class='font-en'>
                            200 جنية
                        </td>
                    </tr>
                    <tr class='' id="tr_account_new_account" style="background: #ccc">
                        <th colspan='5'>
                            الحساب الحالى
                        </th>
                        <th class='font-en'>
                            230 جنية
                        </th>
                    </tr>
                    </tfoot>
                </table>
                <div class='pt-1 m-0 font-en message overflow-hidden font-weight-bold' style="overflow: hidden">
                    <p class='text-left float-left text-dark'><span
                            style='width: 200px;text-align: left;background: transparent'>شكرا لزيارتكم</span></p>
                    <p class='text-left float-right text-dark'>
                        <span style='width: 400px;text-align: right;background: transparent'>تصميم وبرمجة Ultimate Code 01018030420</span>
                    </p>
                </div>
            </section>
        </div>
        <div>
            <section id="section_small_bill_design" class='mx-auto small_design font-ar p-1 bg-white text-center'>
                <div class=''>
                    <div class='text-right font-weight-bold' style="background: #d5d5d5;border-radius: 4px">
                        <div class='d-inline-block text-center font-weight-bold'>
                            <img
                                class='' id="img_design1" src='{{asset('img/icon.ico')}}'
                                style='max-width:19%;vertical-align: top;float: right;'>
                            <p id="p_design_company_name1" class="mb-0">شركة سيفي بلاست لتصنيع المنتجات البلاستيكيه</p>
                            <p id="p_design_row_under_company_name1" class="mb-0"> سعداء بخدمتكم</p>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
                <div class='font-en py-1 contact text-right'>
                    <div class='text-right small_bold' id="div_row_contact11">
                        ادارة: أ/ محفوظ :
                        01140757478
                    </div>
                    <div id="div_row_contact21" class='text-right small_bold'>
                        للتواصل مع خدمة العملاء
                        أ/ تامر:
                        01148000353
                    </div>
                </div>
                <div class='text-right account small_bold pl-3'>
                    إسم العميل:
                    أحمد محمد
                    <br>
                    رقم العميل:
                    <span class="font-en">010180304220</span>
                </div>
                <div class='text-left mb-0'>
                    <p id="saleId" class='float-right mb-0' style="">
                        <label></label>
                        <span data-type='billNumber' class='font-en font-weight-bold text-underline'>5</span>
                    </p>
                    <div class="text-left float-left" style="">
                        <span data-type='date' class='font-en'>{{$date}}</span>
                    </div>
                    <div style="clear: both"></div>
                </div>
                <table class='w-100' id="tableData1">
                    <thead>
                    <tr class='' style="background: #e4e4db;">
                        <th>الصنف</th>
                        <th>الكمية</th>
                        <th>السعر</th>
                        <th>إجمالي</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class=''>
                        <td>منتج 1</td>
                        <td>10 قطعة</td>
                        <td>5 ج</td>
                        <td>50 ج</td>
                    </tr>
                    <tr class=''>
                        <td>منتج 2</td>
                        <td>10 قطعة</td>
                        <td>5 ج</td>
                        <td>50 ج</td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr class='' id="tr_discount11">
                        <td colspan="3">الإجمالى قبل الخصم</td>
                        <td class='font-en'>
                            5ج
                        </td>
                    </tr>
                    <tr class='' id="tr_discount12">
                        <td colspan="3">الـخــصــم</td>
                        <td class='font-en'>
                            5ج
                        </td>
                    </tr>
                    <tr class='' style="background: #ccc">
                        <td colspan="3">الإجمالى
                            <span id="td_span_total_if_has_dicount1">بعد الخصم</span>
                            <span id="td_span_total_if_it_cash1" class="small">(خالص)</span>
                        </td>
                        <td class='font-en'>
                            5ج
                        </td>
                    </tr>
                    <tr class='' id="tr_bill_cash1">
                        <th colspan='3'>
                            المدفوع
                        </th>
                        <th class='font-en'>
                            30 جنية
                        </th>
                    </tr>
                    <tr class='' id="tr_bill_cash11">
                        <td colspan='3'>الباقى</td>
                        <td class='font-en'>
                            200ج
                        </td>
                    </tr>
                    <tr class='' id="tr_account_old_account1">
                        <td colspan='3'>الحساب السابق</td>
                        <td class='font-en'>
                            200ج
                        </td>
                    </tr>
                    <tr class='' id="tr_account_new_account1" style="background: #ccc">
                        <th colspan='3'>
                            الحساب الحالى
                        </th>
                        <th class='font-en'>
                            230ج
                        </th>
                    </tr>
                    </tfoot>
                </table>
                <div class='pt-1 m-0 font-en overflow-hidden message small_bold' style=''>
                    <p class='text-left float-left p-0 m-0 text-dark'><span
                            style='text-align: left;background: transparent'>شكرا لزيارتكم</span></p>
                    <p class='text-right float-right p-0 m-0 text-dark'>
                        <span style='text-align: right;background: transparent'>تصميمUc.01018030420</span>
                    </p>
                    <div style="clear: both"></div>
                </div>
            </section>
        </div>
    </main>
@endsection

@section('js')
    <script defer>
        design.useNiceScroll();

        //check if bill has discount
        function check_has_discount() {
            if ($('#check_has_discount').prop('checked')) {
                $('#tr_discount,#td_span_total_if_has_dicount,#tr_discount11,#tr_discount12,#td_span_total_if_has_dicount1').removeClass('d-none');
            } else {
                $('#tr_discount,#td_span_total_if_has_dicount,#tr_discount11,#tr_discount12,#td_span_total_if_has_dicount1').addClass('d-none');
            }
            design.updateNiceScroll();
        }

        check_has_discount();
        $('#check_has_discount').change(function () {
            design.useSound();
            check_has_discount();
            design.updateNiceScroll();
        });

        //check if bill is cash and don't has old account
        function check_type_cash() {
            if ($('#check_bill_cash').prop('checked')) {
                $('#td_span_total_if_it_cash,#td_span_total_if_it_cash1').removeClass('d-none');
                $('#tr_bill_cash,#tr_bill_cash1,#tr_bill_cash11').addClass('d-none');
                if ($('#check_has_account').prop('checked')) {
                    $('#tr_account_new_account,#tr_account_old_account,#tr_account_old_account1,#tr_account_new_account1').removeClass('d-none');
                } else {
                    $('#tr_account_new_account,#tr_account_old_account,#tr_account_old_account1,#tr_account_new_account1').addClass('d-none');
                }
            } else {
                $('#td_span_total_if_it_cash,#td_span_total_if_it_cash1').addClass('d-none');
                $('#tr_bill_cash,#tr_account_new_account,#tr_account_old_account,#tr_account_old_account1,#tr_bill_cash1,#tr_bill_cash11,#tr_account_new_account1').removeClass('d-none');
            }
        }

        check_type_cash();
        $('#check_bill_cash,#check_bill_not_cash').change(function () {
            design.useSound();
            check_type_cash();
            design.updateNiceScroll();
        });


        $('#check_has_account').change(function () {
            if ($(this).prop('checked')) {
                $('#tr_account_new_account,#tr_account_old_account,#tr_account_old_account1,#tr_account_new_account1').removeClass('d-none');
            } else {
                if ($('#check_bill_not_cash').prop('checked')) {

                } else {
                    $('#tr_account_new_account,#tr_account_old_account,#tr_account_old_account1,#tr_account_new_account1').addClass('d-none');
                }
            }
        });

    </script>
    <script defer>
        function getData() {
            var id = $('#select_bill_name').val();
            $.ajax({
                url: '{{route('bill_prints.getDate')}}',
                method: 'POST',
                data: {
                    id: id,
                },
                dataType: 'JSON',
                success: function (data) {
                    //update section edit data
                    $('#input_edit_name').val(data['name']);
                    $('#input_company_name').val(data['company_name']);
                    $('#input_row_under_company_name').val(data['row_under_company_name']);
                    $('#input_row_contact1').val(data['row_contact1']);
                    $('#input_row_contact2').val(data['row_contact2']);
                    $('#opacity_background').val(data['opacity_background']);

                    if(data['use_small_size']==1){
                        $('#input_use_small_size,#check_small_size').prop('checked',true);
                        $('#check_small_size').trigger('change');
                    }else{
                        $('#input_use_small_size,#check_small_size').prop('checked',false);
                        $('#check_small_size').trigger('change');
                    }
                    $('#input_small_size,#div_size_small_bill_design input').val(data['small_size']);
                    $('#div_size_small_bill_design input').trigger('keyup');
                    //update live data
                    $('#img_design,#img_design1').attr('src', data['icon']);
                    $('#img_design,#img_design1').parent().css('font-size',data['header_size']+'rem');
                    $('span[data-type="billNumber"]').parent().parent().css('font-size',data['bill_number_date_size']+'rem');
                    $('div.account,table.account').css('font-size',data['account_size']+'rem');
                    $('div.contact').css('font-size',data['contact_size']+'rem');
                    $('div.contact').css('font-size',data['contact_size']+'rem');
                    $('#tableData thead tr,#tableData1 thead tr').css('font-size',data['table_header_size']+'rem');
                    $('#tableData tbody tr,#tableData1 tbody tr').css('font-size',data['table_body_size']+'rem');
                    $('#tableData tfoot tr,#tableData1 tfoot tr').css('font-size',data['table_footer_size']+'rem');
                    $('div.message').css('font-size',data['message_uc_size']+'rem');

                    $('#header_size').val(data['header_size']);
                    $('#bill_number_date_size').val(data['bill_number_date_size']);
                    $('#contact_size').val(data['contact_size']);
                    $('#account_size').val(data['account_size']);
                    $('#table_header_size').val(data['table_header_size']);
                    $('#table_body_size').val(data['table_body_size']);
                    $('#table_footer_size').val(data['table_footer_size']);
                    $('#message_uc_size').val(data['message_uc_size']);

                    $('#p_design_company_name,#p_design_company_name1').html(data['company_name']);
                    $('#p_design_row_under_company_name,#p_design_row_under_company_name1').html(data['row_under_company_name']);
                    $('#div_row_contact1,#div_row_contact11').html(data['row_contact1']);
                    $('#div_row_contact2,#div_row_contact21').html(data['row_contact2']);
                    design.useSound();

                    $('#input_use_small_size').trigger('change');
                    var action = $('#form_update_print').attr('action');
                    action = action.replace(/[0-9]$/, id);
                    $('#form_update_print').attr('action', action);

                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                    $('#continerGetData button').removeAttr('disabled');
                }
            });
        }

        $('#select_bill_name').change(function () {
            getData();
        });
        $('#select_bill_name').trigger('change');

        //check if file exist before update
        $('#input_file_logo').click(function () {
            alertify.log('برجاء إختيار صورة صغيرة الحجم حتى لا تقلل من سرعة البرنامج هناك مواقع كثيرة لتصغير الصور ومن أفضلها ' +
                "<a href='https://tinypng.com/' style='text-decoration: underline;color: darkblue' target='_blank'>tinypng</a>", 'error', 0);
            design.useSound();
        });
        $('#input_file_logo').change(function () {
            if (hasExtension('input_file_logo', ['.jpg', '.png', '.ico', '.JPG', '.PNG', '.ICO'])) {
                if (this.files[0].size / 1024 / 1024 > 2) {
                    $('#input_file_logo').val('');
                    alertify.error("برجاء تحديد صورة حجمها أصغر من 2 ميجا ");
                    return;
                }
                $(this).parent().submit();
            } else {
                $('#input_file_logo').val('');
                alertify.error("برجاء تحديد صورة بإمتداد " + '<br/>' + '.ico أو .jpg أو .png');
            }
        });

        function hasExtension(inputID, exts) {
            var fileName = document.getElementById(inputID).value;
            return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
        }

        //toggle show betwen small and large design
        if($('#check_small_size').prop('checked')){
            $('#section_small_bill_design').parent().removeClass('d-none');
            $('#div_size_small_bill_design').removeClass('d-none').addClass('d-inline-block');

            $('#section_bill_design').parent().addClass('d-none');
        }else{
            $('#section_small_bill_design').parent().addClass('d-none');
            $('#div_size_small_bill_design').addClass('d-none').removeClass('d-inline-block');
            $('#section_bill_design').parent().removeClass('d-none');
        }
        design.updateNiceScroll();
        $('#check_small_size').change(function () {
            design.useSound();
            if($('#check_small_size').prop('checked')){
                $('#section_small_bill_design').parent().removeClass('d-none');
                $('#div_size_small_bill_design').removeClass('d-none').addClass('d-inline-block');
                $('#section_bill_design').parent().addClass('d-none');
            }else{
                $('#section_small_bill_design').parent().addClass('d-none');
                $('#div_size_small_bill_design').addClass('d-none').removeClass('d-inline-block');
                $('#section_bill_design').parent().removeClass('d-none');
            }
            design.updateNiceScroll();
        });
        $('#div_size_small_bill_design input').keyup(function () {
            $('#section_small_bill_design').css('max-width',$(this).val()+'cm');
        });
        $('#div_size_small_bill_design input').trigger('keyup');

        if($('#input_use_small_size').prop('checked')){
            $('#input_small_size').parent().parent().addClass('d-inline-block').removeClass('d-none');
        }else{
            $('#input_small_size').parent().parent().removeClass('d-inline-block').addClass('d-none');
        }
        $('#input_use_small_size').change(function () {
            design.useSound();
            if($('#input_use_small_size').prop('checked')){
                $('#input_small_size').parent().parent().addClass('d-inline-block').removeClass('d-none');
            }else{
                $('#input_small_size').parent().parent().removeClass('d-inline-block').addClass('d-none');
            }
        });

        //print bill design
        $('#printBillDesign').click(function () {
            alertify.success('جارى الطباعة!');
            design.useSound();
            if ($('#check_small_size').prop('checked')) {
                $('#section_small_bill_design').parent().printArea({
                    extraCss: '{{asset('css/print_bill.css')}}'
                });
            } else {
                $('#section_bill_design').parent().printArea({
                    extraCss: '{{asset('css/print_bill.css')}}'
                });
            }

        });
    </script>
@endsection
