<?php
$permit=\App\Permit::find(1);
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    ادارة الفواتير
    @if (isset($type))
        {{$type==0?' شراء':($type==1?' بيع':'')}}
    @endif
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
    </style>
    <style>
        .tableFixHead {
            overflow-y: auto;
            max-height: 70vh !important;
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
            padding-bottom: 5px;
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4  pb-2 position-relative'>
        <section class='animated fadeInDown faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 text-white'>اداره الفواتير
                    @if (isset($type))
                        {{$type==0?' شراء':($type==1?' بيع':'')}}
                    @endif
                </h1>
                <div class='container-fluid'>
                    <div id='containerDate' class='row no-gutters overflow-hidden'>
                        <!--from date-->
                        <div class='col-sm-12 col-md-6'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'
                                      style='min-width: 150px'>من الثلاثاء</span>
                                <input type='text' id='dateFrom' class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                        <!--to date-->
                        <div class='col-sm-12 col-md-6 mt-1 mt-md-0'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'
                                      style='min-width: 150px'>الي الخميس</span>
                                <input type='text' id='dateTo' class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                    </div>
                    <div class='mt-2'>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>الشخص</span>
                            </div>
                            <div class="input-group-append">
                                <select id="account_id" class="selectpicker" data-live-search="true">
                                    <option value=''>الكل</option>
                                    <option value='0'>بدون</option>
                                    @foreach ($accounts as $c)
                                        <option value='{{$c->id}}'
                                                data-subtext="({{$c->tel}}) ({{round($c->account,2).'ج'}}) ({{$c->is_supplier?'مورد ':''}} {{$c->is_customer?'عميل ':''}})">{{$c->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>رقم الفاتورة</span>
                            </div>
                            <input type='number' id="inputSearch" min="0"
                                   style="height: 52px;"
                                   value="{{isset($bill_id)?$bill_id:''}}"
                                   placeholder='بحث برقم الفاتورة'
                                   class='form-control py-0'>
                            <div id="continerGetData" class="input-group-append">
                                <button class="btn-success btn font-weight-bold pointer" id="btnSearchInAllCell"><span
                                        class="h5">بحث من خلال رقم الفاتورة</span></button>
                                <button class="btn-primary btn font-weight-bold pointer" id="btnGetByDateCreate"><span
                                        class="h5">بحث بوقت الإنشاء للشخص المحدد</span></button>
                            </div>
                        </div>

                    </div>
                    <div id='divBillBackDetails' class='mb-2 tableFixHead table-responsive border-radius d-none'>
                        <h3 class='text-white h2'>تفاصيل المرتجع المحدد
                        </h3>
                        <table id="tableBillBackDetails" class='main-table sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م</th>
                                <th>إسم المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>إجمالي السعر</th>
                            </tr>
                            </thead>
                            <tbody class='table-info h4'>

                            </tbody>
                        </table>
                    </div>

                    <div id='divBillBack' class='mb-2 tableFixHead table-responsive border-radius d-none'>
                        <h3 class='text-white h2'>مرتجعات الفاتورة رقم
                            <span id="span_back_bill_id" class="font-en text-danger text"
                                  style="text-decoration: underline"></span>
                        </h3>
                        <table id="tableBillBack" class='main-table sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م</th>
                                <th>وقت المرتجع</th>
                                <th>المستخدم</th>
                                <th>الجهاز</th>
                                <th>النوع</th>
                                <th>إجمالي السعر</th>
                                <th>ملاحظة</th>
                                <th class="d-none">العمليات</th>
                            </tr>
                            </thead>
                            <tbody class='table-success h4'>

                            </tbody>
                        </table>
                    </div>

                    <div id='divBillDetails' class='mb-2 tableFixHead table-responsive border-radius d-none'>
                        <h3 class='text-white h2'>تفاصيل الفاتورة رقم
                            <span id="span_details_bill_id" class="font-en text-danger text"
                                  style="text-decoration: underline"></span>
                            <span id="span_profit_after_discount" class="font-en text-success"
                                  style="text-decoration: underline"></span>
                        </h3>
                        <table id="tableBillDetails" class='main-table sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م</th>
                                <th>إسم المنتج
                                    <div class="input-group d-inline-block" style="max-width: 100px">
                                        <div class="input-group-prepend bg-transparent">
                                            <input placeholder='بحث'
                                                   data-filter-col="1"
                                                   type='text'
                                                   id="input_search_bill_details"
                                                   class='form-control h0 d-none py-0'>
                                            <span class="input-group-text text-success bg-transparent p-0"
                                                  style="border: none"><i
                                                    onclick="$(this).parent().parent().children().toggleClass('d-none');$('#input_search_bill_details').focus();"
                                                    class="fas fa-search mr-2 tooltips"
                                                    data-placement="left"
                                                    title="بحث فى تفاصيل الفاتورة"></i></span>
                                            <span
                                                class="input-group-text text-danger bg-transparent p-0 d-none"
                                                style="border: none"><i
                                                    onclick="$(this).parent().parent().children().toggleClass('d-none');$('#input_search_bill_details').val('').trigger('keyup');"
                                                    class="fas fa-times mr-2 tooltips"
                                                    data-placement="left"
                                                    title="إلغاء البحث فى تفاصيل الفاتورة"></i></span>
                                        </div>
                                    </div>
                                </th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>إجمالي السعر
                                    <span class="font-en text-success" style="text-shadow: 2px 2px 2px black;"
                                          id="span_total_profit_for_details"></span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class='table-danger h4'>
                            {{--<tr>
                                <td>اسم المنتج</td>
                                <td class='font-en'>الباركود</td>
                                <td class='font-en'>الكمية</td>
                                <td class='font-en'>السعر</td>
                            </tr>--}}
                            </tbody>
                        </table>
                    </div>

                    <div class="h3 text-white" id="columFilterMainTable" dir="rtl">
                        <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                              value=""></label>

                        <label class="checkbox-inline pl-4" dir="ltr">رقم الفاتورة<input type="checkbox"
                                                                                         checked data-toggle="1"
                                                                                         value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">المستخدم الذى قام بإضافتة<input type="checkbox"
                                                                                                      data-toggle="2"
                                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الجهاز<input type="checkbox"
                                                                                   data-toggle="3"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">وقت الإضافة<input type="checkbox" data-toggle="4"
                                                                                        value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">وقت أخر تعديل<input type="checkbox"
                                                                                          data-toggle="5"
                                                                                          value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإسم<input type="checkbox" data-toggle="6"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">نوع الشخص<input type="checkbox" data-toggle="7"
                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">نوع الفاتورة<input type="checkbox"
                                                                                         {{$type==2?'checked':''}}
                                                                                         data-toggle="8"
                                                                                         value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">مخزن الفاتورة<input type="checkbox"
                                                                                          data-toggle="9"
                                                                                          value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الخصم<input type="checkbox" data-toggle="10"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإجمالى بعد الخصم<input type="checkbox"
                                                                                               data-toggle="11"
                                                                                               checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">البلغ المدفوع<input type="checkbox"
                                                                                          data-toggle="12"
                                                                                          checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الباقى<input type="checkbox" data-toggle="13"
                                                                                   checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">رسالة الفاتورة<input type="checkbox"
                                                                                           data-toggle="14"
                                                                                           value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">حالة المرتجع<input type="checkbox"
                                                                                         data-toggle="15"
                                                                                         checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="16"
                                                                                     checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInMainTable"
                                                                                        type="checkbox"
                                                                                        checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">إجمالى الخصم<input
                                id="checkTotalDiscountRowsInMainTable"
                                type="checkbox"
                                checked value=""></label>
                        <label class="checkbox-inline pl-4 tooltips" data-placement="left"
                               title="إجمالى الفواتير بعد الخصم للنتيجة" dir="ltr">إجمالى الفواتير<input
                                id="checkTotalBillRowsInMainTable"
                                type="checkbox"
                                checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">إجمالى المدفوع<input
                                id="checkTotalPaidRowsInMainTable"
                                type="checkbox"
                                checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">إجمالى الباقى<input
                                id="checkTotalRentRowsInMainTable"
                                type="checkbox"
                                checked value=""></label>
                        <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1"
                                data-placement="bottom" title="طباعة النتيجة">
                            <span class="h3"><i class="fas fa-print"></i></span>
                        </button>
                    </div>
                    <div class="input-group table-filters">
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>حالة المرتجع</span>
                        </div>
                        <div class="input-group-append">
                            <select id="activityType" class="selectpicker" data-live-search="true"
                                    data-filter-col="17">
                                <option value=''>الكل</option>
                                <option value='0'>بدون</option>
                                <option value='1'>يوجد مرتجع</option>
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>حالة الباقى</span>
                        </div>
                        <div class="input-group-append">
                            <select id="activityType" class="selectpicker" data-live-search="true"
                                    data-filter-col="18">
                                <option value=''>الكل</option>
                                <option value='1'>بدون</option>
                                <option value='0'>يوجد باقى</option>
                            </select>
                        </div>
                        @if ($type==2)
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>نوع الفاتورة</span>
                            </div>
                            <div class="input-group-append">
                                <select id="activityType" class="selectpicker" data-live-search="true"
                                        data-filter-col="8">
                                    <option value=''>الكل</option>
                                    <option value='شراء'>شراء</option>
                                    <option value='بيع'>بيع</option>
                                </select>
                            </div>
                        @endif
                        <input id="txtSearch" type='text' data-filter-col="0,1,2,3,4,5,6,7,8,9"
                               placeholder='ابحث في نتيجة البحث باى بيانات ' class='form-control'>
                    </div>
                    <div class='box-shadow tableFixHead table-responsive text-center'>
                        <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م
                                    <span id="countRowsInMainTable" class="font-en"></span>
                                </th>
                                <th>رقم الفاتورة</th>
                                <th>المستخدم الذى قام بإضافتة</th>
                                <th>الجهاز</th>
                                <th>وقت الإضافة</th>
                                <th>وقت أخر تعديل</th>
                                <th>الإسم</th>
                                <th>نوع الشخص</th>
                                <th>نوع الفاتورة</th>
                                <th>مخزن الفاتورة</th>
                                <th>الخصم
                                    <span id="span_total_discount" class="tooltips font-en" data-placement="left"
                                          title="إجمالى الخصم للنتيجة"></span>
                                </th>
                                <th>الإجمالى بعد الخصم
                                    <span id="span_total_bill" class="tooltips font-en" data-placement="left"
                                          title="إجمالى فواتير النتيجة بعد الخصم"></span>
                                </th>
                                <th>المبلغ المدفوع
                                    <span id="span_total_paid" class="tooltips font-en" data-placement="left"
                                          title="إجمالى المدفوع للنتيجة"></span>
                                </th>
                                <th>الباقى
                                    <span id="span_total_rent" class="tooltips font-en" data-placement="left"
                                          title="إجمالى الباقى للنتيجة"></span>
                                </th>
                                <th>رسالة الفاتورة</th>
                                <th>حالة المرتجع</th>
                                <th>العمليات</th>
                                <th class="d-none">type back by number</th>
                                <th class="d-none">type rent by number</th>
                            </tr>
                            </thead>
                            <tbody class="h4">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <iframe src="" id="iframe_print_bill" style="width: 100vw;height: 100vh" frameborder="0"></iframe>
    @if(Auth::user()->type==1||Auth::user()->allow_delete_bill_sale||Auth::user()->allow_delete_bill_buy)
        <form action='{{route('bills.destroy',0)}}' id='form_delete_bill' class='d-none' method='post'>
            @csrf
            @method('delete')
        </form>
    @endif
@endsection

@section('js')
    <script defer>
        design.dateRangFromTo('#dateFrom', '#dateTo', '#containerDate', 'datePicker');
        design.useNiceScroll();
        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getRowCounterInMainTable();
        });

        /*hide and show colum in table*/
        $('#columFilterMainTable input').each(function () {
            var index = $(this).attr('data-toggle');
            if ($(this).prop('checked')) {
                $('#mainTable tr').each(function () {
                    $(this).children().eq(index).show();
                });
            } else {
                $('#mainTable tr').each(function () {
                    $(this).children().eq(index).hide();
                });
            }

        });
        if ($('#checkCountRowsInMainTable').prop('checked')) {
            $('#countRowsInMainTable').show();
        } else {
            $('#countRowsInMainTable').hide();
        }
        $('#checkCountRowsInMainTable').change(function () {
            if ($('#checkCountRowsInMainTable').prop('checked')) {
                $('#countRowsInMainTable').show();
            } else {
                $('#countRowsInMainTable').hide();
            }
        });

        if ($('#checkTotalDiscountRowsInMainTable').prop('checked')) {
            $('#span_total_discount').show();
        } else {
            $('#span_total_discount').hide();
        }
        $('#checkTotalDiscountRowsInMainTable').change(function () {
            if ($('#checkTotalDiscountRowsInMainTable').prop('checked')) {
                $('#span_total_discount').show();
            } else {
                $('#span_total_discount').hide();
            }
        });

        if ($('#checkTotalPaidRowsInMainTable').prop('checked')) {
            $('#span_total_paid').show();
        } else {
            $('#span_total_paid').hide();
        }
        $('#checkTotalPaidRowsInMainTable').change(function () {
            if ($('#checkTotalPaidRowsInMainTable').prop('checked')) {
                $('#span_total_paid').show();
            } else {
                $('#span_total_paid').hide();
            }
        });

        if ($('#checkTotalBillRowsInMainTable').prop('checked')) {
            $('#span_total_bill').show();
        } else {
            $('#span_total_bill').hide();
        }
        $('#checkTotalBillRowsInMainTable').change(function () {
            if ($('#checkTotalBillRowsInMainTable').prop('checked')) {
                $('#span_total_bill').show();
            } else {
                $('#span_total_bill').hide();
            }
        });

        if ($('#checkTotalRentRowsInMainTable').prop('checked')) {
            $('#span_total_rent').show();
        } else {
            $('#span_total_rent').hide();
        }
        $('#checkTotalRentRowsInMainTable').change(function () {
            if ($('#checkTotalRentRowsInMainTable').prop('checked')) {
                $('#span_total_rent').show();
            } else {
                $('#span_total_rent').hide();
            }
        });

        function getRowCounterInMainTable() {
            var counterRow = 0;
            var total_discount = 0;
            var total_bill = 0;
            var total_paid = 0;
            var total_rent = 0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                    total_discount -= -$(this).attr('data-discount');
                    total_bill -= -$(this).attr('data-total');
                    total_paid -= -$(this).attr('data-paid');
                }

            });
            $('#countRowsInMainTable').html(counterRow);
            $('#span_total_discount').html(roundTo(total_discount,{{$setting->use_small_price?'3':'2'}}) + 'ج');
            $('#span_total_bill').html(roundTo(total_bill,{{$setting->use_small_price?'3':'2'}}) + 'ج');
            $('#span_total_paid').html(roundTo(total_paid,{{$setting->use_small_price?'3':'2'}}) + 'ج');
            $('#span_total_rent').html(roundTo(total_bill - total_paid,{{$setting->use_small_price?'3':'2'}}) + 'ج');

            design.useToolTip(); //to update tooltip after filter

        }

        /*hide and show colum in table*/
        $('#columFilterMainTable input').click(function () {
            var index = $(this).attr('data-toggle');
            if ($(this).prop('checked')) {
                $('#mainTable tr').each(function () {
                    $(this).children().eq(index).show();
                });
            } else {
                $('#mainTable tr').each(function () {
                    $(this).children().eq(index).hide();
                });
            }
        });

        function getData(type) {
            $('#continerGetData button').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');
            $('#tableBillDetails tbody').html('');
            $('#divBillDetails').addClass('d-none');
            $('#divBillBack').addClass('d-none');
            $('#divBillBackDetails').addClass('d-none');
            var billState = '';//state for bill buy or sale
            @if (isset($type))
            {{$type==0?' billState=0;':($type==1?' billState=1;':'')}}
            @endif
            $.ajax({
                url: '{{route('bills.getDate')}}',
                method: 'POST',
                data: {
                    stateBill: billState,//state for bill buy or sale
                    type: type,
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    id: $('#inputSearch').val(),
                    account_id: $('#account_id').val(),
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var typeAccountName = '';
                        var typeBillName = 'شراء';
                        var tableClassByBillType = 'table-success';
                        if (data[i]['type'] == 1) {
                            typeBillName = 'بيع';
                            // tableClassByBillType = 'table-warning';
                        }
                        if (data[i]['account'] != null) {
                            if (data[i]['account']['is_supplier'] == 1) {
                                typeAccountName = 'مورد';
                            }
                            if (data[i]['account']['is_customer'] == 1) {
                                typeAccountName = 'عميل';
                            }
                            if (data[i]['account']['is_customer'] == 1 && data[i]['account']['is_supplier'] == 1) {
                                typeAccountName = 'مورد_عميل';
                            }
                        }
                        //store rent for bill
                        var rent = roundTo(data[i]['total_price'] * 1 - data[i]['total_paid'] * 1,{{$setting->use_small_price?'3':'2'}});
                        var edit = '', delete_row = '', visit = '',show_visit='';

                        var print_bill = '<button data-print="' + data[i]['id'] + '" class="btn p-0 bg-transparent tooltips" style="font-size: 0.77rem" data-placement="left" title="طباعة الفاتورة"><i class="fas fa-2x text-warning fa-print"></i></button>';
                        @if((Auth::user()->type==1||Auth::user()->allow_edit_bill_buy))//edit bill buy
                        edit = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='تعديل الفاتورة ' href='../" + data[i]['id'] + "/edit'><i class='fas fa-2x fa-edit text-white'></i></a>";
                        @endif
                        @if((Auth::user()->type==1||Auth::user()->allow_edit_bill_sale))//edit bill sale
                        edit = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='تعديل الفاتورة ' href='../" + data[i]['id'] + "/edit'><i class='fas fa-2x fa-edit text-white'></i></a>";
                            @endif

                        var billBack = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='إضافة مرتجع ' href='{{route('bills.create_bill_back',['',''])}}/" + data[i]['id'] + '/' + data[i]['type'] + "'><i class='fas fa-2x fa-undo-alt'></i></a>";


                        /*if (data[i]['is_supplier']) {
                            payMoneyToSupplierOrSupplierCustomer = " <a class='btn btn-sm btn-secondary tooltips' href='accounts/add_or_subtract_debt/" + data[i]['id'] + "/2'  data-placement='left' title='سيتم خصم المبلغ من الحساب والدرج ' ><span class='h5 text-dark'>دفع مال </span></a>";
                        }
                        if (data[i]['is_customer']) {
                            takeMoneyFromCustomer = " <a class='btn btn-sm btn-primary tooltips' href='accounts/add_or_subtract_debt/" + data[i]['id'] + "/1' data-placement='left'  title='سيتم خصم المبلغ من الحساب وإضاته إلى الدج '><span class='h5 text-dark'>أخذ مال </span></a>";
                        }*/

                        @if((Auth::user()->type==1||Auth::user()->allow_delete_bill_buy) &&($type==0 ||$type==2))//delete bill buy
                        delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='حذف (لا يمكن الحذف إذا كان هناك مرتجع, سيتم إعادة المبلغ المدفوع إلى درج الجهاز الذى تمت علية العميلة) ' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        @endif
                        @if((Auth::user()->type==1||Auth::user()->allow_delete_bill_sale) &&($type==1 ||$type==2))//delete bill buy
                        delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='حذف (لا يمكن الحذف إذا كان هناك مرتجع , سيتم حذف المبلغ المدفوع من درج الجهاز الذى تمت علية العملية) ' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        @endif
                        @if(Hash::check('use_visit',$permit->use_visit))
                                if(data[i]['account'] != null){
                                visit = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='إضافة سجل زيارة ' href='{{route('visits.create')}}?id=" + data[i]['id'] + "'><i class=\"fas fa-2x text-warning fa-notes-medical\"></i></a>";
                                    if(data[i]['visit']!=''){
                                        show_visit = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='عرض سجل الزيارة ' href='{{route('visits.index')}}?id=" + data[i]['id'] + "'><i class=\" fa-2x text-dark fas fa-clipboard\"></i></a>";
                                    }
                                }
                            @if(Auth::user()->type==1 ||Auth::user()->allow_add_visit)
                                visit='';
                            @endif
                            @if(Auth::user()->type==1 ||Auth::user()->allow_manage_visit)
                                show_visit='';
                            @endif
                        @endif
                        //remove delete if bill has back
                        if (data[i]['bill_back'] != '') {
                            delete_row = '';
                            // edit = '';
                        }
                        $('#mainTable tbody').append(
                            "<tr data-id='" + data[i]['id'] + "' data-discount='" + data[i]['discount'] +
                            "' class='" + tableClassByBillType + "' " +
                            'data-total="' + data[i]['total_price'] + '"' +
                            'data-paid="' + data[i]['total_paid'] + '"' +
                            "data-main_class='" + tableClassByBillType + "'>" +
                            "<td data-id='" + data[i]['id'] + "' class='tooltips pointer' data-placement='left' title='إضغط لعرض تفاصيل الفاتورة والمرتجعات'>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['id'] + "</td>" +
                            "<td>" + data[i]['user']['name'] + "</td>" +
                            "<td>" + data[i]['device']['name'] + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td>" + data[i]['updated_at'] + "</td>" +
                            "<td " + (data[i]['account'] != null ? (" class='tooltips'  data-placement='left' title='الحساب الحالى هو " + roundTo(data[i]['account']['account']) + "ج" + "'") : '') + ">" + (data[i]['account'] == null ? 'بدون' : data[i]['account']['name']) + "</td>" +
                            "<td>" + typeAccountName + "</td>" +
                            "<td>" + typeBillName + "</td>" +
                            "<td>" + data[i]['stoke']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['discount']) + 'ج' + "</td>" +
                            "<td>" + roundTo(data[i]['total_price']) + 'ج' + "</td>" +
                            "<td>" + roundTo(data[i]['total_paid']) + 'ج' + "</td>" +
                            "<td class='" + (roundTo(rent) > 0 ? 'bg-danger' : '') + "'>" + roundTo(rent) + 'ج' + "</td>" +
                            "<td>" + data[i]['message'] + "</td>" +
                            "<td  class='" + (data[i]['bill_back'] != '' ? '' : 'bg-danger') + "'>" + (data[i]['bill_back'] == '' ? '<i class="fas fa-times"></i>' : '<i class="fas fa-check"></i>') + "</td>" +
                            "<td class='text-nowrap'>" +
                            edit + print_bill + billBack + visit+show_visit + delete_row +
                            "</td>" +
                            "<td class='d-none'>" + (data[i]['bill_back'] != '' ? '1' : '0') + "</td>" +
                            "<td class='d-none'>" + (roundTo(rent) > 0 ? '0' : '1') + "</td>" +
                            "</tr>"
                        );
                    }
                    $('#countRowsInMainTable').html(data.length);
                    /*hide and show colum in table*/
                    $('#columFilterMainTable input').each(function () {
                        var index = $(this).attr('data-toggle');
                        if ($(this).prop('checked')) {
                            $('#mainTable tr').each(function () {
                                $(this).children().eq(index).show();
                            });
                        } else {
                            $('#mainTable tr').each(function () {
                                $(this).children().eq(index).hide();
                            });
                        }

                    });
                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    alertify.success('برجاء الضغط على مسلسل أى سطر لعرض المنتجات فى الفاتورة والمرتجعات الخاصة بها !');

                    design.useSound('success');
                    $('#txtSearch').trigger('keyup');
                    $('#continerGetData button').removeAttr('disabled');
                    design.useToolTip();
                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                    $('#continerGetData button').removeAttr('disabled');
                }
            });
        }


        $('#btnGetByDateCreate').click(function () {
            getData('getDataByDateCreate');
            $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-primary');
            $('#btnSearchInAllCell').addClass('btn-success').removeClass('btn-warning');
        });

        $('#inputSearch').on('keyup',function (e) {
            var keyCode = e.keyCode;
            if (keyCode === 13) { //press enter
                $('#btnGetByDateCreate').trigger('click');
            }
        });

        $('#btnSearchInAllCell').click(function () {
            $('#btnGetByDateCreate').addClass('btn-primary').removeClass('btn-warning');
            $('#btnSearchInAllCell').addClass('btn-warning').removeClass('btn-success');
            if ($('#inputSearch').val().length == 0) {
                alertify.error('برجاء كتابة رقم واحد على الأقل قبل البحث');
                design.useSound('info');
                return;
            }
            getData('getDataByBillId');
        });

        if ($('#inputSearch').val() > 0) {
            $('#btnSearchInAllCell').trigger('click');
        } else {
            getData('getDataByDateCreate');
            $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-primary');
        }

        $('div.datePicker').click(function () {
            getRowCounterInMainTable();
            $('#mainTable tbody').html('');
            $('#divBillDetails').addClass('d-none');
            $('#divBillBack').addClass('d-none');
            $('#divBillBackDetails').addClass('d-none');
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للبحث');
            design.useSound('info');
        });
        $('#dateTo,#dateFrom,#account_id').change(function () {
            $('#mainTable tbody').html('');
            getRowCounterInMainTable();
            $('#divBillDetails').addClass('d-none');
            $('#divBillBack').addClass('d-none');
            $('#divBillBackDetails').addClass('d-none');
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للبحث');
            design.useSound('info');
        });
        $('#inputSearch').click(function () {
            getRowCounterInMainTable();
            $('#mainTable tbody').html('');
            $('#divBillDetails').addClass('d-none');
            $('#divBillBack').addClass('d-none');
            $('#divBillBackDetails').addClass('d-none');
            alertify.error('برجاء الضغط على زر بحث من خلال رقم الفاتورة للبحث');
            design.useSound('info');

        });


        //get bill details
        var stateGetDetails = false;//to prevent loop click
        var stateGetBackDetails = false;//to prevent loop click
        var stateShowProfit ={{$show_profit==1?'true':'false'}};//to prevent show profit
        $('#mainTable').on('click', 'tbody tr td[data-id]', function (e) {
            if (stateGetDetails) {
                alertify.error('برجاء الإنتظار جارى عرض تفاصيل الفاتورة');
                design.useSound('info');
                return;
            }
            stateGetDetails = true;
            var id = $(this).parent().attr('data-id');
            var discount = $(this).parent().attr('data-discount');//used to show profit
            $('#mainTable tbody tr').each(function () {
                $(this).removeClass('table-danger').addClass($(this).attr('data-main_class'));
            });
            $(this).parent().addClass('table-danger').removeClass($(this).parent().attr('data-main_class'));

            $('#divBillBack').addClass('d-none');
            $('#divBillBack table tbody').html('');
            design.updateNiceScroll();

            $.ajax({
                url: '{{route('bills.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getBillDetails',
                    id: id,
                    show_profit: stateShowProfit,
                },
                dataType: 'JSON',
                success: function (data) {
                    $.ajax({
                        url: '{{route('bills.getDate')}}',
                        method: 'POST',
                        data: {
                            type: 'getBillBack',
                            id: id,
                        },
                        dataType: 'JSON',
                        success: function (result) {
                            //add bill back
                            if (result.length > 0) {
                                $('#divBillBack').removeClass('d-none');
                                $('#divBillBackDetails').addClass('d-none');
                                $('#divBillBack table tbody').html('');
                            }
                            for (let i = 0; i < result.length; i++) {
                                var typeBackString = result[i]['type'] == 0 ? 'إستبدال' : (result[i]['type'] == 1 ? 'أخذ مال' : 'خصم من الحساب')
                                $('#tableBillBack tbody').append(
                                    "<tr data-id='" + result[i]['id'] + "' class='tooltips pointer' data-placement='bottom' title='إضغط لعرض تفاصيل المرتجع'>" +
                                    "<td>" + (i - -1) + "</td>" +
                                    "<td>" + result[i]['created_at'] + "</td>" +
                                    "<td>" + result[i]['user']['name'] + "</td>" +
                                    "<td>" + result[i]['device']['name'] + "</td>" +
                                    "<td>" + typeBackString + "</td>" +
                                    "<td>" + roundTo(result[i]['total_price'],{{$setting->use_small_price?'3':'2'}}) + 'ج' + "</td>" +
                                    "<td>" + result[i]['note'] + "</td>" +
                                    "<td class='d-none'></td>" +
                                    "</tr>"
                                );
                            }

                            $('#span_back_bill_id').html(id);

                            if(result.length>0)
                            alertify.success('برجاء الضغط على مسلسل أى مرتجع لعرض تفاصيل المرتجع !');

                            //add bill details
                            stateGetDetails = false;
                            $('#span_details_bill_id').html(data[0]['bill_id']);
                            $('#divBillDetails').removeClass('d-none');
                            $('#tableBillDetails tbody').html('');
                            var total_profit = 0;

                            for (i = 0; i < data.length; i++) {

                                //show profit
                                var temp_profit = '';
                                var profitMessageForOneQte = '';
                                var profitMessageForTotalQte = '';
                                if (stateShowProfit) {
                                    temp_profit = 0;
                                    if (data[i]['sale_make_qte_detail'].length == 0) {
                                        temp_profit = data[i]['price'] * data[i]['qte'];
                                    }
                                    for (var j = 0; j < data[i]['sale_make_qte_detail'].length; j++) {
                                        temp_profit -= -((data[i]['price'] - data[i]['sale_make_qte_detail'][j]['store']['price']) * data[i]['sale_make_qte_detail'][j]['qte']);
                                    }
                                    total_profit -= -temp_profit;
                                    var classProfit = (temp_profit > 0 ? 'text-success' : (temp_profit < 0 ? 'text-danger' : 'text-white'));
                                    profitMessageForOneQte = '<span class="font-en text-success ' + classProfit + ' tooltips mr-3" style="text-decoration: underline" data-placement="left" title="الربح ل' + data[i]['product_unit']['name'] + ' من الكمية" ' +
                                        '><i class="fas fa-dollar-sign"></i>' + roundTo(temp_profit / (data[i]['qte'] / data[i]['relation_qte'])) + 'ج' + '</span>';
                                    profitMessageForTotalQte = '<span class="font-en ' + classProfit + ' tooltips mr-3 text-success" style="text-decoration: underline" data-placement="left" title="إجمالى الربح للكمية"><i class="fas fa-dollar-sign"></i>' + roundTo(temp_profit) + 'ج' + '</span>';
                                }

                                $('#tableBillDetails tbody').append(
                                    "<tr class='table-success'>" +
                                    "<td>" + (i - -1) + "</td>" +
                                    "<td>" + data[i]['product']['name'] + "</td>" +
                                    "<td>" + roundTo(data[i]['qte'] / data[i]['relation_qte'],3) + data[i]['product_unit']['name'] + profitMessageForOneQte + "</td>" +
                                    "<td>" + roundTo(data[i]['price'] * data[i]['relation_qte'],{{$setting->use_small_price?'3':'2'}}) + 'ج' + "</td>" +
                                    "<td>" + roundTo(data[i]['qte'] * data[i]['price'],{{$setting->use_small_price?'3':'2'}}) + 'ج' + profitMessageForTotalQte + "</td>" +
                                    "</tr>"
                                );
                            }
                            //show total profit
                            if (stateShowProfit) {
                                var classTotalProfit = ((total_profit-discount) > 0 ? 'text-success' : ((total_profit-discount) < 0 ? 'text-danger ' : 'text-white'));
                                $('#span_total_profit_for_details').html(
                                    '<span class="font-en ' + classTotalProfit + ' tooltips mr-3" style="text-decoration: underline" data-placement="left" title=" إجمالى الربح للفاتورة بدون الخصم"><i class="fas fa-dollar-sign"></i>' + roundTo(total_profit) + 'ج' + '</span>'
                                );
                                $('#span_profit_after_discount').html(
                                    '<span class="font-en ' + classTotalProfit + ' tooltips mr-3" style="text-decoration: underline" data-placement="left" title=" إجمالى الربح للفاتورة بعد الخصم">' +
                                    'حيث الخصم فى الفاتورة ' + roundTo(discount) + 'ج' + ' وإجمالى الربح بعد الخصم هو ' +
                                    '<i class="fas fa-dollar-sign"></i>' + roundTo(total_profit - discount) + 'ج' + '</span>'
                                );
                            }
                            design.updateNiceScroll();
                            design.useToolTip();
                            alertify.success('تم عرض التفاصيل بنجاح');
                            design.useSound('success');
                        },
                        error: function (e) {
                            alert('error');
                            design.useSound('error');
                            console.log(e);
                            stateGetDetails = false;
                        }
                    });
                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                    stateGetDetails = false;
                }
            });
        });

        $('#tableBillBack').on('click', 'tbody tr td:not(:last-child)', function (e) {
            if (stateGetBackDetails) {
                alertify.error('برجاء الإنتظار جارى عرض مرتجع الفاتورة');
                design.useSound('info');
                return;
            }
            stateGetBackDetails = true;
            var id = $(this).parent().attr('data-id');
            $(this).parent().addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');

            $('#divBillBackDetails').addClass('d-none');
            $('#divBillBackDetails table tbody').html('');

            $.ajax({
                url: '{{route('bills.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getBillBackDetails',
                    id: id,
                },
                dataType: 'JSON',
                success: function (data) {
                    stateGetBackDetails = false;
                    $('#divBillBackDetails').removeClass('d-none');
                    $('#tableBillBackDetails tbody').html('');

                    for (i = 0; i < data.length; i++) {
                        $('#tableBillBackDetails tbody').append(
                            "<tr class='table-success'>" +
                            "<td>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['product']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['qte'] / data[i]['relation_qte'],3) + data[i]['product_unit']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['price'] * data[i]['relation_qte'],{{$setting->use_small_price?'3':'2'}}) + 'ج' + "</td>" +
                            "<td>" + roundTo(data[i]['qte'] * data[i]['price'],{{$setting->use_small_price?'3':'2'}}) + 'ج' + "</td>" +
                            "</tr>"
                        );
                    }

                    design.updateNiceScroll();
                    design.useToolTip();
                    alertify.success('تم عرض المرتجع بنجاح');
                    design.useSound('success');
                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                    stateGetDetails = false;
                }
            });
        });

        //search in table bill details
        $('#tableBillDetails').filtable({controlPanel: $('#tableBillDetails thead')});
        $('#tableBillDetails').on('aftertablefilter', function (event) {
            design.useToolTip();
        });

        //print main table
        $('#printMainTable').click(function () {
            $('#mainTable').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });
        @if(Auth::user()->type==1||Auth::user()->allow_delete_bill_sale||Auth::user()->allow_delete_bill_buy)
        $('#mainTable').on('click', 'tbody tr a[data-delete]', function (e) {
            var id = $(this).attr('data-delete');
            var parent = $(this).parent().parent();
            $('#mainTable tbody tr').each(function () {
                $(this).removeClass('table-danger').addClass($(this).attr('data-main_class'));
            });
            parent.addClass('table-danger').removeClass(parent.attr('data-main_class'));
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد حذف الفاتورة المحددة سيتم ضبط الكمية فى المخزن والحساب والدرج الخاصين بالفاتورة؟",
                title: "حذف فاتورة",
                confirm: function (button) {
                    var action = $('#form_delete_bill').attr('action');
                    action = action.replace(/[0-9]$/, id);
                    $('#form_delete_bill').attr('action', action).submit();
                },
                cancel: function (button) {

                },
                post: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-default",
                dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
            });
        });
        @endif
    </script>

    <script defer>
        //print bill
        var printLink = "{{route('bills.print',0)}}";

        $('#mainTable').on('click', 'tbody tr button[data-print]', function (e) {
            var parent = $(this).parent().parent();
            $('#mainTable tbody tr').each(function () {
                $(this).removeClass('table-danger').addClass($(this).attr('data-main_class'));
            });
            parent.addClass('table-danger').removeClass(parent.attr('data-main_class'));
            var id = $(this).attr('data-print');
            alertify.success('جارى الطباعة!');
            design.useSound();
            // $('#load').css('display', 'block');
            // $('#iframe_print_bill').removeClass('d-none');
            window.open(printLink.replace(/[0-9]$/, id), '_blank');

        });

        //print main table
        //print main table
        $('#printMainTable').click(function () {
            $('#mainTable').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });

    </script>
@endsection
