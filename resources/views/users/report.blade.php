<?php
$permit = \App\Permit::first();
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    تقارير شاملة
@endsection
@section('css')
    <style>
        main span, main input {
            font-size: 1.5rem !important;
        }

        input {
            padding: 25px 10px !important;
        }

        select {
            height: 53px !important;
            font-size: 1.5rem;
        }

        input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
        }

        #columFilter label {
            cursor: pointer;
        }

        input[type='checkbox'], input[type="radio"] {
            transform: scale(2);
            margin-left: 10px;
        }

        #section_box > div {
            min-height: 230px;
            min-width: 259px;
            box-shadow: 2px 2px 14px black, 3px 3px #fff;
            margin-top: 8px;
        }

        #section_box p {
            overflow-x: auto;
            overflow-y: hidden;
            color: black;
        }

        [data-get_data] {
            margin-left: 3px;
        }

        @media all and (max-width: 569px) {
            #section_box > div {
                display: block !important;
                margin-bottom: 16px !important;
                overflow-x: scroll;
                overflow-y: hidden;
                width: 96%;
            }
        }

        @media all and (min-width: 569px) and (max-width: 750px) {
            #section_box > div {
                display: inline-block !important;
                margin-bottom: 16px !important;
                width: 46%;
            }
        }

        @media all and (min-width: 750px) and (max-width: 950px) {
            #section_box > div {
                display: inline-block !important;
                margin-bottom: 16px !important;
                width: 30%;
            }
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
            padding-top: 5px;
            padding-bottom: 5px
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4  pb-2 position-relative'>
        <section class='animated fadeInDown faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 text-white'>تقارير شاملة للبرنامج</h1>
                <div class='container-fluid'>
                    <div id='containerDate' class='row no-gutters overflow-hidden'>
                        <!--from date-->
                        <div class='col-sm-12 col-md-6'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'
                                      style='min-width: 150px'>من الثلاثاء</span>
                                <input type='text' id='dateFrom' style="height: 53px"
                                       class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                        <!--to date-->
                        <div class='col-sm-12 col-md-6 mt-1 mt-md-0'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'
                                      style='min-width: 150px'>الي الخميس</span>
                                <input type='text' id='dateTo' style="height: 53px"
                                       class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-2 row no-gutters">
                        <div class="input-group-append col-2">
                            <span class='input-group-text font-weight-bold w-100 d-block'>الجهاز</span>
                        </div>
                        <div class="input-group-append col-7" style="direction: rtl;text-align: right">
                            <select id="select_device_id" style="direction: rtl;text-align: right"
                                    class="selectpicker form-control show-tick" data-live-search="true"
                                    data-filter-col="11">
                                <option data-style="padding-bottom: 50px!important;" value="">الكل</option>
                                @foreach($devices as $e)
                                    <option data-style="padding-bottom: 50px!important;"
                                            value="{{$e->id}}">{{$e->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="continerGetData" class="input-group-append col">
                            <button class="btn-primary btn font-weight-bold pointer tooltips" data-placement="bottom"
                                    title="وقت العملية هو اليوم الذى تمت فية العملية"
                                    id="btnGetByDate"><span
                                    class="h5">عرض التقرير فى الفترة المحددة للجهاز المحدد</span></button>
                            <button id="printMainTable"
                                    class="btn border-0 mr-1 pointer text-success bg-transparent p-0 tooltips mt-1"
                                    data-placement="bottom" title="طباعة النتيجة">
                                <span class="h3"><i class="fas fa-print"></i></span>
                            </button>
                        </div>
                    </div>
                    {{--<div class="h3 text-white text-right">
                        طريقة عرض التقرير :
                        <label style=""
                               class="checkbox-inline pb-0 pt-1 d-inline-block mr-2 pointer h0">
                            <input type="radio" checked id="radioBox" name="typeView" class=""
                                   value="1">
                            صناديق
                        </label>
                        <label style=""
                               class="checkbox-inline py-0 d-inline-block  pointer  h0">
                            <input type="radio" id="radioTable" name="typeView" class="mr-3">
                            جدول
                        </label>
                    </div>--}}
                    <div>
                        <style>
                            #section_box > div {
                                min-height: 230px;
                                min-width: 259px;
                                box-shadow: 2px 2px 14px black, 3px 3px #fff;
                                margin-top: 8px;
                            }
                        </style>
                        <section id="section_box" class="text-right">
                            <h3 class="mb-0 text-white text-center d-none h2 py-2" id="report_title">تقرير الجهاز
                                " <span id="printName" class="font-weight-bold"></span> "
                                في الفترة من
                                <span id="printFrom" class="font-en"></span>
                                الي
                                <span id="printTo" class="font-en"></span>

                            </h3>
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 onclick="design.useSound();window.open('{{route('bills.create',0)}}', '_blank');"
                                 title="فواتير الشراء للجهاز المحدد, الإجمالى هو إجمالى الفواتير بعد الخصم"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-cart-arrow-down"></i>
                                    فواتير الشراء
                                </p>
                                <p class="mb-0">
                                    عدد الفواتير :
                                    <label class="font-en" data-get_data id="bill_buy_count"></label><br/>
                                    الإجمالى :
                                    <label class="font-en" data-get_data id="bill_buy"></label>ج<br/>
                                    المدفوع :
                                    <label class="font-en" data-get_data id="bill_buy_paid"></label>ج<br/>
                                    الباقى :
                                    <label class="font-en" data-get_data id="bill_buy_rent"></label>ج<br/>
                                </p>
                            </div>
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 onclick="design.useSound();window.open('{{route('bills.create',1)}}', '_blank');"
                                 title="فواتير البيع للجهاز المحدد, الإجمالى هو إجمالى الفواتير بعد الخصم"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-cart-plus"></i>
                                    فواتير البيع
                                </p>
                                <p class="mb-0">
                                    عدد الفواتير :
                                    <label class="font-en" data-get_data id="bill_sale_count"></label><br/>
                                    الإجمالى :
                                    <label class="font-en" data-get_data id="bill_sale"></label>ج<br/>
                                    المدفوع :
                                    <label class="font-en" data-get_data id="bill_sale_paid"></label>ج<br/>
                                    الباقى :
                                    <label class="font-en" data-get_data id="bill_sale_rent"></label>ج<br/>
                                </p>
                            </div>
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 title="الخصم لفواتير الجهاز المحدد"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas  text-warning fa-dollar-sign"></i>
                                    الخصم
                                </p>
                                <p class="mb-0">
                                    الخصم شراء
                                    :
                                    <label class="font-en " data-get_data id="bill_discount_buy"></label>ج<br/>
                                    الخصم بيع
                                    :
                                    <label class="font-en " data-get_data id="bill_discount_sale"></label>ج<br/>
                                </p>
                            </div>
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 onclick="design.useSound();window.open('{{route('product_moves.show_profit')}}', '_blank');"
                                 title="أرباح فواتير البيع للجهاز المحدد غير شاملة المنتجات بدون كمية , ناقص الخصم فى فواتير البيع"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-dollar-sign"></i>
                                    أرباح البيع
                                </p>
                                <p class="mb-0">
                                    أرباح البيع
                                    :
                                    <label class="font-en " data-get_data id="profit_sale_has_qte_without_discount"></label>ج<br/>
                                </p>
                            </div>
                            @if(Hash::check('product_no_qte',$permit->product_no_qte))
                                <div
                                    class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                    data-placement="left"
                                    title="إجمالى شراء وبيع المنتجات بدون كمية"
                                    style="font-size: 1.5rem">
                                    <p class="text-center mb-0 h2 border-bottom text-white">
                                        <i class="fas text-warning fa-dollar-sign"></i>
                                        المنتجات بدون كمية
                                    </p>
                                    <p class="mb-0">
                                        شراء
                                        :
                                        <label class="font-en " data-get_data
                                               id="profit_buy_has_no_qte_without_discount"></label>ج<br/>
                                    </p>
                                    <p class="mb-0">
                                        بيع
                                        :
                                        <label class="font-en " data-get_data
                                               id="profit_sale_has_no_qte_without_discount"></label>ج<br/>
                                    </p>
                                </div>
                            @endif
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 onclick="design.useSound();window.open('{{route('product_moves.index')}}', '_blank');"
                                 title="إجمالى تالف المخازن المصرح للجهاز الحالى بالوصول إليها"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-dollar-sign"></i>
                                    التوالف
                                </p>
                                <p class="mb-0">
                                    تالف شراء
                                    :
                                    <label class="font-en " data-get_data id="damage_stoke_buy"></label>ج<br/>
                                </p>
                                <p class="mb-0">
                                    تالف إنتاج
                                    :
                                    <label class="font-en " data-get_data id="damage_stoke_make"></label>ج<br/>
                                </p>
                            </div>
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 title="المرتجع الذى تم على الجهاز المحدد"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-dolly"></i>
                                    مرتجع شراء
                                </p>
                                <p class="mb-0">
                                    إستبدال
                                    :
                                    <label class="font-en " data-get_data id="buy_back_replace"></label>ج<br/>
                                    أخذ مال
                                    :
                                    <label class="font-en " data-get_data id="buy_back_take_money"></label>ج<br/>
                                    خصم من الحساب
                                    :
                                    <label class="font-en " data-get_data id="buy_back_discount"></label>ج<br/>
                                </p>
                            </div>
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 title="المرتجع الذى تم على الجهاز المحدد"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-dolly"></i>
                                    مرتجع بيع
                                </p>
                                <p class="mb-0">
                                    إستبدال
                                    :
                                    <label class="font-en " data-get_data id="sale_back_replace"></label>ج<br/>
                                    أخذ مال
                                    :
                                    <label class="font-en " data-get_data id="sale_back_take_money"></label>ج<br/>
                                    خصم من الحساب
                                    :
                                    <label class="font-en " data-get_data id="sale_back_discount"></label>ج<br/>
                                </p>
                            </div>
                            @if(Hash::check('use_expenses',$permit->use_expenses))
                                <div
                                    onclick="design.useSound();window.open('{{route('expenses.index')}}', '_blank');"
                                    class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                    data-placement="left"
                                    title="المصروفات التى تمت على الجهاز المحدد"
                                    style="font-size: 1.5rem">
                                    <p class="text-center mb-0 h2 border-bottom text-white">
                                        <i class="fas text-warning fa-file-invoice-dollar"></i>
                                        المصروفات
                                    </p>
                                    <p class="mb-0">
                                        عدد المصروفات :
                                        <label class="font-en" data-get_data id="expenses_count"></label><br/>
                                        الإجمالى
                                        :
                                        <label class="font-en" data-get_data id="expenses"></label>ج<br/>
                                    </p>
                                </div>
                            @endif
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 onclick="design.useSound();window.open('{{route('accounts.index')}}', '_blank');"
                                 title="إجمالى حساب الموردين والموردين العملاء على كل الأجهزة"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-users"></i>
                                    حساب الموردين
                                </p>
                                <p class="mb-0">
                                    عدد الموردين
                                    :
                                    <label class="font-en" data-get_data id="supplier_counter"></label><br/>
                                    عدد اصحاب الدين
                                    :
                                    <label class="font-en" data-get_data id="supplier_has_account_counter"></label>ج<br/>
                                    إجمالى الدين :
                                    <label class="font-en" data-get_data id="supplier_account"></label>ج<br/>
                                </p>
                            </div>
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 onclick="design.useSound();window.open('{{route('accounts.index')}}', '_blank');"
                                 title="إجمالى حساب العملاء على كل الأجهزة"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-users"></i>
                                    حساب العملاء
                                </p>
                                <p class="mb-0">
                                    عدد العملاء
                                    :
                                    <label class="font-en " data-get_data id="customer_counter"></label><br/>
                                    عدد اصحاب الدين
                                    :
                                    <label class="font-en tooltips" data-get_data id="customer_has_account_counter"></label>ج<br/>
                                    إجمالى الدين :
                                    <label class="font-en" data-get_data id="customer_account"></label>ج<br/>
                                </p>
                            </div>
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 onclick="design.useSound();window.open('{{route('stores.index')}}', '_blank');"
                                 title="إجمالى سعر المنتجات شراء وإنتاج فى المخازن المصرح للجهاز المحدد بالوصول إليها"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas text-warning fa-store"></i>
                                    المخزن
                                </p>
                                <p class="mb-0">
                                    سعر المنتجات
                                    :
                                    <label class="font-en" data-get_data id="stoke_buy"></label>ج<br/>
                                </p>
                                @if(Hash::check('product_make',$permit->product_make))
                                    <p class="text-center mb-0 h2 border-bottom text-white">
                                        <i class="fas text-warning fa-flask"></i>
                                        الإنتاج
                                    </p>
                                    <p class="mb-0">
                                        سعر المنتجات
                                        :
                                        <label class="font-en" data-get_data id="stoke_make"></label>ج<br/>
                                    </p>
                                @endif
                            </div>
                            @if(Hash::check('use_exit_deal',$permit->use_exit_deal))
                                <div
                                    onclick="design.useSound();window.open('{{route('exist_deals.index')}}', '_blank');"
                                    class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                    data-placement="left" title="إجمالى التعاملات الخارجية التى تم تسجيها على الجهاز المحدد"
                                    style="font-size: 1.5rem">
                                    <p class="text-center mb-0 h2 border-bottom text-white">
                                        <i class="fas text-warning fa-door-open"></i>
                                        التعاملات الخارجية
                                    </p>
                                    <p class="mb-0">
                                        الأرباح الخارجية
                                        :
                                        <label class="font-en" data-get_data id="exist_deal_profit"></label>ج<br/>
                                        الخسائر الخارجية
                                        :
                                        <label class="font-en" data-get_data id="exist_deal_loses"></label>ج<br/>
                                    </p>
                                </div>
                            @endif
                            @if(Hash::check('use_emp',$permit->use_emp))
                                <div
                                    onclick="design.useSound();window.open('{{route('emps.report2')}}', '_blank');"
                                    class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                    data-placement="left"
                                    title="إجمالى حساب الموظفين المصرح للجهاز المحدد بالوصول إليهم , المدفوع للموظفين هو إجمالى دفع المال والسلف للموظفين المصرح للجهاز المحدد بالوصل إليهم"
                                    style="font-size: 1.5rem">
                                    <p class="text-center mb-0 h2 border-bottom text-white">
                                        <i class="fas text-warning fa-bible"></i>
                                        الموظفين
                                    </p>
                                    <p class="mb-0">
                                        حساب الموظفين
                                        :
                                        <label class="font-en" data-get_data id="emp_account"></label>ج<br/>
                                        المدفوع للموظفين
                                        :
                                        <label class="font-en" data-get_data id="emp_paid_and_borrow"></label>ج<br/>
                                    </p>
                                </div>
                            @endif
                            <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                 data-placement="left"
                                 onclick="design.useSound();window.open('{{route('treasuries.index')}}', '_blank');"
                                 title="وضع وأخذ المال على الجهاز المحدد"
                                 style="font-size: 1.5rem">
                                <p class="text-center mb-0 h2 border-bottom text-white">
                                    <i class="fas  text-warning fa-dollar-sign"></i>
                                    الدرج
                                </p>
                                <p class="mb-0">
                                    وضع مال :
                                    <label class="font-en" data-get_data id="treasury_add"></label>ج<br/>
                                    أخذ مال
                                    :
                                    <label class="font-en" data-get_data id="treasury_take"></label>ج<br/>
                                </p>
                            </div>
                            @if(Hash::check('use_visit',$permit->use_visit))
                                <div
                                    onclick="design.useSound();window.open('{{route('visits.index')}}', '_blank');"
                                    class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                                    data-placement="left"
                                    title="إجمالى المهام والزيارات التى تم تعينها كمنتهية على الجهاز المحدد"
                                    style="font-size: 1.5rem">
                                    <p class="text-center mb-0 h2 border-bottom text-white">
                                        <i class="text-white text-warning fas fa-clipboard"></i>
                                        المهام و الزيارات
                                    </p>
                                    <p class="mb-0">
                                        المهام :
                                        <label class="font-en" data-get_data id="total_mission"></label>ج<br/>
                                        الزيارات
                                        :
                                        <label class="font-en" data-get_data id="total_visit"></label>ج<br/>
                                    </p>
                                </div>
                            @endif
                        </section>
                    </div>
                    {{--<section id="section_table" class="d-none">
                        <div class='mt-2 table-filters'>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>الحالة</span>
                                </div>
                                <div class="input-group-append">
                                    <select class="selectpicker" data-live-search="true"
                                            id="select_report_state"
                                            data-filter-col="4">
                                        <option value=''>الكل</option>
                                        <option selected value='0'>يوجد قيمة</option>
                                        <option value='1'>لا يوجد قيمة</option>
                                    </select>
                                </div>
                                <input type='text' data-filter-col="0,1,2,3,4,5,6,7,8,9"
                                       placeholder='ابحث في نتيجة البحث باى بيانات ' class='form-control'>
                            </div>
                        </div>
                        <div class="h4 border-radius" id="divContainerData">
                            <div class="bg-white mb-0 h4 text-center text-dark" dir="rtl">
                                <h3 class="mb-0 h3 py-2">تقرير الجهاز
                                    " <span id="printName" class="font-weight-bold"></span> "
                                    في الفترة من
                                    <span id="printFrom" class="font-en"></span>
                                    الي
                                    <span id="printTo" class="font-en"></span>

                                </h3>
                                <div class="tableFixHead">
                                    <table id="mainTable" class='m-0 h3 sorted table table-hover table-bordered' style="text-align: center">
                                        <thead class='thead-dark h3'>
                                        <tr>
                                            <th>م
                                                <span id="countRowsInTable" class="font-en"></span>
                                            </th>
                                            <th>نوع الإجمالى</th>
                                            <th>القيمة
                                                <span id="countTotalValueInTable" class="font-en tooltips" data-plaement="left" title="إجمالى المجموع للنتيجة"></span>
                                            </th>
                                            <th class="d-none">قيمة العملية</th>
                                            <th class="d-none">حالة القيمة =0 او لا</th>--}}{{--1 for profit ,2 for expenses ,3 for assets--}}{{--
                                        </tr>
                                        </thead>
                                        <tbody class="table-success text-dark">
                                        <tr id="exist_deal_profit" class='{{Hash::check('use_exit_deal',$permit->use_exit_deal)?'':'permits_hide'}}'>
                                            <td></td>
                                            <td>اجمالي الأربـــاح الخارجية</td>
                                            <td><i class="fas text-primary ml-2 fa-plus"></i>  <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">1</td>
                                        </tr>
                                        <tr id="exist_deal_loses" class='{{Hash::check('use_exit_deal',$permit->use_exit_deal)?'':'permits_hide'}}'>
                                            <td></td>
                                            <td>اجمالي الخسائر الخارجية</td>
                                            <td><i class="fas text-danger  ml-2 fa-minus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="expenses" class='{{Hash::check('use_expenses',$permit->use_expenses)?'':'permits_hide'}}'>
                                            <td></td>
                                            <td>اجمالــي الـــمـصــروفــات</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="supplier_account" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجالى حساب الموردين والموردين العملاء الحالى على كل الأجهزة">اجمالي الدين للموردين والموردين العملاء</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="customer_account" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجالى حساب العملاء الحالى على كل الأجهزة">اجــــمـالـــــــــي الـــــديــن عــلــى الــــــعـمــلاء</td>
                                            <td><i class="fas text-primary ml-2 fa-plus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">1</td>
                                        </tr>
                                        <tr id="treasury_take" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى أخذ المال من درج الجهاز المحدد!">إجمالى أخــــذ المال</td>
                                            <td><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">1</td>
                                        </tr>
                                        <tr id="treasury_add" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى وضع المال فى درج الجهاز المحدد!">إجمالى وضع المال</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="stoke_buy" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى سعر المنتجات (شراء) فى المخازن المصرح للجهاز المحدد بالوصول إليها بسعر الشراء!">إجمالى سعر المنتجات (شراء) فى المخازن</td>
                                            <td><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">1</td>
                                        </tr>
                                        <tr id="stoke_make" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى سعر المنتجات (إنتاج) فى المخازن المصرح للجهاز المحدد بالوصول إليها بسعر الشراء!">إجمالى سعر المنتجات (إنتاج) فى المخازن</td>
                                            <td><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">1</td>
                                        </tr>
                                        <tr id="damage_stoke_buy" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى توالف المنتجات شراء التى تم عملها على الجهاز المحدد!">إجمالى توالف المنتجات شراء</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="damage_stoke_make" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى توالف المنتجات إنتاج التى تم عملها على الجهاز المحدد!">إجمالى توالف المنتجات إنتاج</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="bill_buy" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى فواتير الشراء بعد الخصم التى تمت على الجهاز المحدد!">إجمالى فواتير الشراء بعد الخصم</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="bill_sale" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى فواتير البيع بعد الخصم التى تمت على الجهاز المحدد!">إجمالى فواتير الــبيع بعد الخصم</td>
                                            <td><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="profit_sale_has_no_qte_without_discount" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى أرباح المنتجات بدون كمية للجهاز المحدد قبل الخصم!">إجمالى أرباح المنتجات بــــــــــــدون كمية للجهاز المحدد</td>
                                            <td><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="profit_buy_has_no_qte_without_discount" class='{{Hash::check('product_no_qte',$permit->product_no_qte)?'':'permits_hide'}}'>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى سعر شراء المنتجات بدون كمية للجهاز المحدد قبل الخصم!">إجمالى سعر شراء المنتجات بدون كمية للجهاز المحدد</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="profit_sale_has_qte_without_discount" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى أرباح المنتجات بكمية للجهاز المحدد قبل الخصم!">إجــــمالى أربـــــــاح المنتجات بكمية للجهاز المحدد</td>
                                            <td><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="bill_discount_buy" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى خصومات الفواتير شراء للجهاز المحدد فى الفترة المحددة!">إجـــمالى خصومات الفواتير شـــراء للجهاز المحدد</td>
                                            <td ><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="bill_discount_sale" class=''>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى خصومات فواتير البيع للجهاز المحدد فى الفترة المحددة!">إجــــــمالى خصومات فـــواتير البيع للجهاز المحدد</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="emp_account" class='{{Hash::check('use_emp',$permit->use_emp)?'':'permits_hide'}}'>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى حساب الموظفين الحالى للجهاز المحدد!">إجــــــــــــــــمالى حـــســـاب الـــمـــوظفين للـــجهاز الــــــــــمحدد</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>
                                            <td class="d-none">2</td>
                                        </tr>
                                        <tr id="emp_paid_and_borrow" class='{{Hash::check('use_emp',$permit->use_emp)?'':'permits_hide'}}'>
                                            <td></td>
                                            <td class="tooltips" data-placement="bottom" title="إجمالى دفع المال+السلف للموظفين التى تمت على الجهاز المحدد فى الفترة المحددة!">إجمالى دفع المال والسلف للموظفين على الجهاز المحدد</td>
                                            <td><i class="fas text-danger ml-2 fa-minus"></i> <span></span> ج</td>
                                            <td class="d-none">value</td>--}}{{--0 =>value is 0 ,1 => has value--}}{{--
                                            <td class="d-none">2</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>--}}
                </div>
            </div>
        </section>
    </main>

@endsection

@section('js')
    <script defer>
        design.dateRangFromTo('#dateFrom', '#dateTo', '#containerDate', 'datePicker');
        design.useNiceScroll();

        /*$('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getRowTotalInRows();
            design.updateNiceScroll();
        });*/

        /*//change view type
        $('#radioBox,#radioTable').change(function () {
            design.useSound();
            if($('#radioTable').prop('checked')){
                $('#section_box').addClass('d-none');
                $('#section_table').removeClass('d-none');
            }else{
                $('#section_box').removeClass('d-none');
                $('#section_table').addClass('d-none');
            }
            design.updateNiceScroll();
        });*/
        /* function getRowTotalInRows() {
             var counterRow = 0;
             var countTotalValueInTable=0;
             $('#mainTable tbody tr').each(function () {
                 if ($(this).hasClass('hidden') == false) {
                     counterRow -= -1;
                     $(this).children().eq(0).html(counterRow);
                     countTotalValueInTable -=-($(this).children().eq(3).html());
                 }
             });
             $('#countRowsInTable').html(counterRow);
             $('#countTotalValueInTable').html(roundTo(countTotalValueInTable)+'ج');

             design.useToolTip(); //to update tooltip after filter

         }*/
        function getData(type = 'getDataByEmpIdAndDateWithEmp') {
            $('#continerGetData button').attr('disabled', 'disabled');
            $('#mainTable tbody tr span').html('-');

            $.ajax({
                url: '{{route('users.getReport')}}',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    type: type,
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    device_id: $('#select_device_id').val(),
                },
                success: function (data) {
                    $('#report_title').removeClass('d-none');
                    $('#printName').html($('#select_device_id option:selected').html());
                    $('#printFrom').html($('#dateFrom').val());
                    $('#printTo').html($('#dateTo').val());

                    // $('#mainTable tbody tr span').html('-');
                    jQuery.each(data, function (key, value) {
                        // console.log( "key", key, "value", value );
                        var el = $('#' + key);
                        el.html(roundTo(value));
                    });
                    // getRowTotalInRows();

                    $('#bill_buy_rent').html(roundTo(($('#bill_buy').html() - $('#bill_buy_paid').html())));
                    $('#bill_sale_rent').html(roundTo(($('#bill_sale').html() - $('#bill_sale_paid').html())));
                    $('#profit_sale_has_qte_without_discount').html(roundTo(($('#profit_sale_has_qte_without_discount').html() - $('#bill_discount_sale').html())));
                    // $('#select_report_state').trigger('change');
                    $('#continerGetData button').removeAttr('disabled');
                    design.useSound();
                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                    $('#continerGetData button').removeAttr('disabled');
                }
            });
        }

        getData('getDataByEmpIdAndDateMoveWithEmp');
        $('#btnGetByDate').addClass('btn-success').removeClass('btn-warning');
        // $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-success');

        /*$('#btnGetByDateCreate').click(function () {
            getData();
            $('#btnGetByDateCreate').addClass('btn-success').removeClass('btn-warning');
            $('#btnGetByDate').addClass('btn-warning').removeClass('btn-success');
        });*/
        $('#btnGetByDate').click(function () {
            getData('getDataByEmpIdAndDateMoveWithEmp');
            $('#btnGetByDate').addClass('btn-success').removeClass('btn-warning');
        });


        $('div.datePicker,#dateTo,#dateFrom').click(function () {
            // getSumForAccountAndValueForRowNotHiddenAndSetIndex();
            $('[data-get_data]').html('-');
            $('#report_title').addClass('d-none');
            alertify.error('برجاء الضغط على زر عرض التقرير فى الفترة المحددة للجهاز المحدد للبحث');
            design.useSound('info');
        });
        $('#dateTo,#dateFrom,#select_device_id').change(function () {
            // getSumForAccountAndValueForRowNotHiddenAndSetIndex();
            $('[data-get_data]').html('-');
            $('#report_title').addClass('d-none');
            alertify.error('برجاء الضغط على زر عرض التقرير فى الفترة المحددة للجهاز المحدد للبحث');
            design.useSound('info');
        });


        //print main table
        $('#printMainTable').click(function () {
            design.useSound();
            alertify.success('برجاء الإنتظار جارى الطباعة!');

            $('#section_box').parent().printArea({
                extraCss: '{{asset('css/print2.css')}}'
            });
        });

    </script>
@endsection
