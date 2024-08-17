<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
{{--@extends('layouts.app')--}}
@extends('layouts.app', ['include_no_header' => true,'include_no_message'=>true])
@section('title')
    طباعة فاتورة
@endsection
@section('css')
    @if ($design->use_small_size==0)
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
                padding: 2px;
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
    @else
        <style>
            body{
                margin-top: -23px!important;
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
    @endif
@endsection
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <div>
            @if ($design->use_small_size==0)
                <section id="section_bill_design" class='mx-auto font-ar pt-3 pr-3 pl-3 pb-0 bg-white text-center position-relative'
                         style="max-width: 1024px ;">
                    <div id="backgroundImg" class="position-absolute w-100 h-100"
                    style="top:0;left:0;background-image: url('{{$design->icon}}');background-repeat: no-repeat;background-size: 100% 100%;background-position: center;opacity: {{$design->opacity_background}}"></div>
                    <div class='row no-gutters'>
                        <div class='col-7 text-right font-weight-bold' style="font-size: {{$design->header_size}}rem!important;">
                            <img height='80px'
                                 class='' id="img_design" src='{{$design->icon}}'
                                 style='vertical-align: top;max-width: 80px'>
                            <div class='d-inline-block text-center'>
                                <p id="p_design_company_name" class="mb-0">{{$design->company_name}}</p>
                                <p id="p_design_row_under_company_name">{{$design->row_under_company_name}}</p>
                            </div>
                        </div>
                        <div class='col-5 text-left font-weight-bold' style="font-size: {{$design->bill_number_date_size}}rem!important;">
                            <p id="saleId" class='b ml-3 mb-1 d-inline-block px-2' style="">
                                <label> رقم الفاتورة : </label>
                                <span data-type='billNumber' class='font-en'>{{$bill->id}}</span>
                            </p>
                            <div class="text-left">
                                <div class='b d-inline-block ml-auto  px-2' style="">
                                    <label> التاريخ : </label>
                                    <span data-type='date' class='font-en'>{{$bill->created_at}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($bill->type==0)
                        <p class="font-weight-bold" style="font-size: {{$design->header_size}}rem!important;">فاتــورة شــــراء</p>
                    @endif
                    <div class='font-en py-1 text-right' style="font-size: {{$design->contact_size}}rem!important;">
                        <div class='text-right' id="div_row_contact1">
                            {{$design->row_contact1}}
                        </div>
                        <div id="div_row_contact2" class='text-right pl-3'>
                            {{$design->row_contact2}}
                        </div>
                    </div>
                    <table class='w-100' style="font-size: {{$design->account_size}}rem!important;" border='3'>
                        @if (isset($bill->account->name))
                            <tr>
                                <td class='width-15 pb-1'>إســــم
                                    {{$bill->type==0?'المورد':'العميل'}}
                                </td>
                                <td style='width: 40%'>{{$bill->account?$bill->account->name:''}}
                                </td>
                                <td class='width-15'>رقم الهاتف</td>
                                <td style='width: 40%'>{{$bill->account?$bill->account->tel:''}}
                                </td>
                            </tr>
                            <tr>
                                <td class='width-15'>عنوان
                                    {{$bill->type==0?'المورد':'العميل'}}
                                </td>
                                <td style='width: 40%' class="text-right" readonly=""
                                    colspan="3">{{$bill->account?$bill->account->address:''}}</td>
                            </tr>
                        @endif
                        <tr style="display: none" id="driver">
                            <td class='width-15'>اسم السائق</td>
                            <td style='width: 40%'></td>
                            <td class='width-15'>رقم السائق</td>
                            <td style='width: 40%'></td>
                        </tr>
                    </table>
                    <table class='w-100 mt-2' border='3' id="tableData">
                        <thead>
                        <tr class='' style="background: #e4e4db;font-size: {{$design->table_header_size}}rem!important;">
                            <th style='width:50px'>م</th>
                            <th colspan='2'>الصنف</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>إجمالي</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($bill->details as $d)
                            <tr class='' style="font-size: {{$design->table_body_size}}rem!important;">
                                <td style='width:50px'>{{$loop->index+1}}</td>
                                <td colspan='2'>{{$d->product->name}}</td>
                                <td>{{round($d->qte/$d->relation_qte,($setting->use_small_price?3:2))}} {{$setting->show_unit_when_print_bill?$d->productUnit->name:''}}</td>
                                <td>{{round($d->price*$d->relation_qte,($setting->use_small_price?3:2))}}
                                    ج
                                </td>
                                <td>{{round($d->price*$d->qte,($setting->use_small_price?3:2))}}
                                    ج
                                </td>
                            </tr>
                        @endforeach
                        <tr style='height: 20px;border: 0px red solid;border: none'></tr>
                        </tbody>
                        <tfoot style="font-size: {{$design->table_footer_size}}rem!important;">
                        @if($bill->discount >0)
                            <tr class='' id="tr_discount">
                                <td colspan="2">الخصم</td>
                                <td class='font-en'>
                                    {{round($bill->discount,2)}}
                                    ج
                                </td>
                                <td colspan='2'>إجمالى الفاتورة قبل الخصم</td>
                                <td class='font-en'>
                                    {{round($bill->total_price + $bill->discount,2)}}
                                    ج
                                </td>
                            </tr>
                        @endif
                        <tr class='' style="background: #ccc">
                            <td colspan="5">إجمالى الفاتورة
                                @if($bill->discount >0)
                                    <span id="td_span_total_if_has_dicount">بعد الخصم</span>
                                @endif
                                @if(round($bill->total_price) == round(($bill->total_paid + $bill->discount)))
                                    <span id="td_span_total_if_it_cash" class="small">(خالص مع الشكر)</span>
                                @endif
                            </td>
                            <td class='font-en'>
                                {{round($bill->total_price,2)}}
                                ج
                            </td>
                        </tr>
                        @if(round($bill->total_price) != round(($bill->total_paid + $bill->discount)))
                            <tr class='' id="tr_bill_cash">
                                <th colspan='3'>
                                    المبلغ المدفوع
                                </th>
                                <th class='font-en'>
                                    {{round($bill->total_paid,2)}}
                                    جنية
                                </th>
                                <th colspan='1'>
                                    الباقى
                                </th>
                                <th class='font-en'>
                                    {{round($bill->total_price-$bill->total_paid,2)}}
                                    جنية
                                </th>
                            </tr>
                        @endif
                        @if(isset($bill->account)&&$bill->account->account!=0)
                            <tr class='' id="tr_account_old_account">
                                <td colspan='5'>الحساب السابق</td>
                                <td class='font-en'>
                                    {{$bill->account?round($bill->account->account-($bill->total_price-$bill->total_paid),2):''}}
                                    جنية
                                </td>
                            </tr>
                            <tr class='' id="tr_account_new_account" style="background: #ccc">
                                <th colspan='5'>
                                    الحساب الحالى
                                </th>
                                <th class='font-en'>
                                    {{round($bill->account?$bill->account->account:'',2)}}
                                    جنية
                                </th>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                    <div class='pt-1 m-0 font-en font-weight-bold overflow-hidden' style="font-size: {{$design->message_uc_size}}rem!important;">
                        <p class='text-left float-left text-dark'><span
                                style='width: 200px;text-align: left;background: transparent'>{{$bill->message}}</span>
                        </p>
                        <p class='text-left float-right text-dark'>
                            <span style='width: 400px;text-align: right;background: transparent'>تصميم وبرمجة UltimateCode 01018030420</span>
                        </p>
                    </div>
                </section>
            @else
                <section id="section_small_bill_design" style="width: {{$design->small_size}}cm!important;margin-left: auto;margin-right: auto;padding:0.25rem!important;text-align: center;"
                         class='mx-auto small_design font-ar p-1 pb-0 bg-white text-center'>
                    <div class=''>
                        <div class='text-right font-weight-bold' style="text-align:right;font-weight: bolder;background: #d5d5d5;border-radius: 4px">
                            <div class='d-block text-center font-weight-bold' style="display:block;text-align: center;font-weight: bolder;font-size: {{$design->header_size}}rem!important;">
                                <img
                                    class='' id="img_design1" src='{{$design->icon}}'
                                    style='max-width:19%;vertical-align: top;float: right;'>
                                <p id="p_design_company_name1" class="mb-0" style="margin-bottom: 0;">{{$design->company_name}}</p>
                                <p id="p_design_row_under_company_name1"
                                   class="mb-0 mt-0" style="margin-bottom: 0">{{$design->row_under_company_name}}</p>
                            </div>
                            <div style="clear: both"></div>
                        </div>
                    </div>
                    <div class='font-en py-1 text-right' style="padding-top: 0.25rem!important;padding-bottom: 0.25rem!important;text-align: right;font-size: {{$design->contact_size}}rem!important;">
                        <div class='text-right small_bold' style="text-align: right;" id="div_row_contact11">{{$design->row_contact1}}</div>
                        <div id="div_row_contact21" style="text-align: right" class='text-right small_bold'>{{$design->row_contact2}}</div>
                    </div>
                    @if ($bill->type==0)
                        <p class="font-weight-bold" style="font-weight: bolder;font-size: {{$design->header_size}}rem!important;">فاتــورة شــــراء</p>
                    @endif
                    @if (isset($bill->account->name))
                        <div class='text-right small_bold px-1' style="text-align: right;font-weight: bolder;padding-right: 0.25rem;padding-left: 0.25rem!important;font-size: {{$design->account_size}}rem!important;">
                            إسم
                            {{$bill->type==0?'المورد':'العميل'}}
                            :
                            {{$bill->account?$bill->account->name:''}}
                            @if ($bill->account->tel!='')
                                <br>
                                رقم الهاتف:
                                <span class="font-en">{{$bill->account?$bill->account->tel:''}}</span>
                            @endif
                        </div>
                    @endif
                    <div class='text-left mb-0  px-1' style="text-align: left;margin-bottom: 0px;padding-right: 0.25rem!important;padding-left: 0.25rem!important;font-size: {{$design->bill_number_date_size}}rem!important;">
                        <p id="saleId" class='float-right mb-0' style="float: right;margin-bottom: 0px">
                            <label> رقم الفاتورة : </label>
                            <span data-type='billNumber'
                                  class='font-en font-weight-bold text-underline'
                                  style="font-weight: bolder;text-decoration: underline">{{$bill->id}}</span>
                        </p>
                        <div class="text-left float-left" style="float: left;text-align: left;">
                            <label>  </label>
                            <span data-type='date' class='font-en'>{{$bill->created_at}}</span>
                        </div>
                        <div style="clear: both" ></div>
                    </div>
                    <table class='w-100' style="border-collapse: collapse;width:100%" id="tableData1">
                        <thead>
                        <tr class='' style="background: #e4e4db;font-size: {{$design->table_header_size}}rem!important;">
                            <th>الصنف</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>إجمالي</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($bill->details as $d)
                            <tr style="font-size: {{$design->table_body_size}}rem!important;">
                                <td>{{$d->product->name}}</td>
                                <td>{{round($d->qte/$d->relation_qte,2)}} {{$setting->show_unit_when_print_bill?$d->productUnit->name:''}}</td>
                                <td>{{round($d->price*$d->relation_qte,2)}}
                                    ج
                                </td>
                                <td>{{round($d->price*$d->qte,2)}}
                                    ج
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot style="font-size: {{$design->table_footer_size}}rem!important;">
                        @if($bill->discount >0)
                            <tr class='' id="tr_discount11">
                                <td colspan="3">الإجمالى قبل الخصم</td>
                                <td class='font-en'>
                                    {{round($bill->total_price + $bill->discount,2)}}
                                    ج
                                </td>
                            </tr>
                            <tr class='' id="tr_discount12">
                                <td colspan="3">الـــــــــــــــــــــــــــــــخــــصــم</td>
                                <td class='font-en'>
                                    {{round($bill->discount,2)}}
                                    ج
                                </td>
                            </tr>
                        @endif
                        <tr class='' style="background: #ccc">
                            <td colspan="3">الإجمالى
                                @if($bill->discount > 0)
                                    <span id="td_span_total_if_has_dicount1">بعد الخصم</span>
                                @endif
                                @if(round($bill->total_price) == round($bill->total_paid + $bill->discount))
                                    <span id="td_span_total_if_it_cash1" class="small">(خالص)</span>
                                @endif
                            </td>
                            <td class='font-en'>
                                {{round($bill->total_price,2)}}
                                ج
                            </td>
                        </tr>
                        @if(round($bill->total_price) != round(($bill->total_paid + $bill->discount)) )
                            <tr class='' id="tr_bill_cash1">
                                <th colspan='3'>
                                    المدفوع
                                </th>
                                <th class='font-en'>
                                    {{round($bill->total_paid,2)}}
                                    ج
                                </th>
                            </tr>
                            <tr class='' id="tr_bill_cash11">
                                <td colspan='3'>الباقى</td>
                                <td class='font-en'>
                                    {{round($bill->total_price-$bill->total_paid,2)}}
                                    ج
                                </td>
                            </tr>
                        @endif
                        @if(isset($bill->account)&&$bill->account->account!=0)
                            <tr class='' id="tr_account_old_account1">
                                <td colspan='3'>الحساب السابق</td>
                                <td class='font-en'>
                                    {{$bill->account?round($bill->account->account-($bill->total_price-$bill->total_paid),2):''}}
                                    ج
                                </td>
                            </tr>
                            <tr class='' id="tr_account_new_account1" style="background: #ccc">
                                <th colspan='3'>
                                    الحساب الحالى
                                </th>
                                <th class='font-en'>
                                    {{round($bill->account?$bill->account->account:'',2)}}
                                    ج
                                </th>
                            </tr>
                        @endif
                        </tfoot>
                    </table>
                    <div class='pt-1 m-0 font-en small small_bold overflow-hidden' style='padding-top: 0.25rem!important;font-size: {{$design->message_uc_size}}rem!important;'>
                        <p class='text-left float-left p-0 m-0 text-dark' style="text-align: left;float: left;padding: 0;margin: 0;"><span
                                style='text-align: left;background: transparent'>{{$bill->message}}</span></p>
                        <p class='text-right float-right p-0 m-0 text-dark' style="text-align: right;float: right;padding: 0;margin: 0;">
                            <span
                                style='text-align: right;background: transparent'>{{($design->small_size > 6)?($design->small_size>8?'تصميم وبرمجةUltimateCode 01004126301':'تصميم تصميمUc.01004126301'):'تصميمUc.01004126301'}}</span>
                        </p>
                        <div style="clear: both"></div>
                    </div>
                </section>
            @endif
        </div>
    </main>
@endsection

@section('js')
    <script defer>
        @if ($design->use_small_size==0)
        $('#section_bill_design').parent().printArea({
            extraCss: '{{asset('css/print_bill.css')}}',
            autoCloseAfterPrint: true,
        });
        @else
        $('#section_small_bill_design').parent().printArea({
            extraCss: '{{asset('css/print_bill.css')}}',
            // mode:'popup',
            stopHeader:true,
            // autoReloadAfterPrint: false,
            autoCloseAfterPrint: true,
        });
        @endif
        /*setTimeout(function () {
            window.close();
        }, 1000);*/
    </script>
@endsection
