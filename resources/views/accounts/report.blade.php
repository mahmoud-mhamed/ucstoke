<?php
$permit=\App\Permit::first();
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    حسابات الموردين والعملاء
    {{Hash::check('sup_cust',$permit->sup_cust)?'و الموردين العملاء':''}}
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
                <h1 class='font-weight-bold pb-3 text-white'>حسابات الموردين والعملاء
                    {{Hash::check('sup_cust',$permit->sup_cust)?'و الموردين العملاء':''}}
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
                        <div class="input-group row no-gutters">
                            <div class="input-group-append col-2">
                                <span class='input-group-text font-weight-bold w-100 d-block'>الإسم ورقم الهاتف</span>
                            </div>
                            <div class="input-group-append col-7" style="direction: rtl;text-align: right" >
                                <select id="select_account_name"  style="direction: rtl;text-align: right" class="selectpicker form-control show-tick" data-live-search="true">
                                    <option data-style="padding-bottom: 50px!important;" value="">الكل</option>
                                    @foreach($accounts as $a)
                                        <option value="{{$a->id}}" data-style="padding-bottom: 50px!important;" data-subtext="({{$a->is_supplier?'مورد ':''}} {{$a->is_customer?'عميل ':''}})({{$a->tel}})">{{$a->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="continerGetData" class="input-group-append col">
                                <button class="btn-primary btn font-weight-bold pointer" id="btnGetByDateCreate"><span
                                        class="h5">بحث بوقت العملية للشخص المحدد</span></button>
                            </div>
                        </div>
                        <div class="input-group table-filters">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>نوع الشخص</span>
                            </div>
                            <div class="input-group-append">
                                <select id="accountType" class="selectpicker" data-live-search="true"
                                        data-filter-col="15">
                                    <option value=''>الكل</option>
                                    <option value='0'>الموردين</option>
                                    <option value='1'>العملاء</option>
                                    @if (Hash::check('sup_cust',$permit->sup_cust))
                                        <option value='2'>الموردين العملاء</option>
                                    @endif
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>نوع العملية</span>
                            </div>
                            <div class="input-group-append">
                                <select id="operationType" class="selectpicker" data-live-search="true"
                                        data-filter-col="9">
                                    <option value=''>الكل</option>
                                    <option value='عند الإضافة'>عند الإضافة</option>
                                    <option value='أخذ مال'>أخذ مال</option>
                                    <option value='دفع مال'>دفع مال</option>
                                    <option value='ضبط الحساب'>ضبط الحساب</option>
                                    <option value='فاتورة شراء'>فاتورة شراء</option>
                                    <option value='فاتورة بيع'>فاتورة بيع</option>
                                    <option value='مرتجع خصم من الحساب'>مرتجع خصم من الحساب</option>
                                    <option value='مرتجع إستبدال'>مرتجع إستبدال</option>
                                    <option value='مرتجع أخذ مال'>مرتجع أخذ مال</option>
                                    <option value='أرباح خارجية'>أرباح خارجية</option>
                                    <option value='خسائر خارجية'>خسائر خارجية</option>
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>نوع الحركة</span>
                            </div>
                            <div class="input-group-append">
                                <select id="accountAction" class="selectpicker" data-live-search="true"
                                        data-filter-col="17">
                                    <option value=''>الكل</option>
                                    <option value='0'>لم تؤثر فى الحساب</option>
                                    <option value='1'>زيادة الحساب</option>
                                    <option value='2'>نقص الحساب</option>
                                </select>
                            </div>
                            <input id="txtSearch" type='text' data-filter-col="0,1,2,3,4,5,6,7,8,9,10,11,12,13"
                                   placeholder='ابحث في نتيجة البحث باى بيانات ' class='form-control'>
                        </div>
                    </div>
                    <div class="h3 text-white mt-2" id="columFilter" dir="rtl">
                        <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                              value=""></label>

                        <label class="checkbox-inline pl-4" dir="ltr">المستخدم الذى قام بالعملية<input type="checkbox"
                                                                                                      data-toggle="1"
                                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الجهاز<input type="checkbox" data-toggle="2"
                                                                                        value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">وقت العملية<input type="checkbox"
                                                                                          data-toggle="3"
                                                                                          value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإسم<input type="checkbox" data-toggle="4"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">رقم الهاتف<input type="checkbox" data-toggle="5"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الحساب الحالى<input type="checkbox" data-toggle="6"
                                                                                        value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">مورد<input type="checkbox" data-toggle="7"
                                                                                       checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عميل<input type="checkbox" data-toggle="8"
                                                                                    checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">نوع العملية<input type="checkbox" data-toggle="9"
                                                                                   checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">المبلغ<input type="checkbox" data-toggle="10"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الباقى<input type="checkbox" data-toggle="11"
                                                                                   checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الحساب بعد العملية<input type="checkbox" data-toggle="12"
                                                                                     checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">رقم العملية<input type="checkbox" data-toggle="13"
                                                                                               checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="14"
                                                                                               checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="15"
                                                                                     checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                        type="checkbox"
                                                                                        checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">إجمالى المبلغ<input id="checkSumValRowsInTable"
                                                                                            type="checkbox"
                                                                                            checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">إجمالى الباقى<input id="checkSumRentRowsInTable"
                                                                                          type="checkbox"
                                                                                          checked value=""></label>
                        <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1" data-placement="bottom" title="طباعة النتيجة">
                            <span class="h3"><i class="fas fa-print"></i></span>
                        </button>
                    </div>
                    <div class='box-shadow tableFixHead table-responsive text-center'>
                        <table id="mainTable" class='sorted  m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م
                                    <span id="countRowsInTable" class="font-en"></span>
                                </th>
                                <th>المستخدم</th>
                                <th>الجهاز</th>
                                <th>وقت العملية</th>
                                <th>الإسم</th>
                                <th>رقم الهاتف</th>
                                <th class="tooltips" data-placement='left'
                                    title='الحساب للعميل (هو قيمة الدين على العميل) , للمورد (هو القيمة التى أدين بها للمورد), للمورد العميل (هو القيمة التى أدين بها للمورد العميل)'>
                                    الحساب الحالى
                                </th>
                                <th>مورد</th>
                                <th>عميل</th>
                                <th>نوع العملية</th>
                                <th>المبلغ
                                    <span id="span_total_val" class="font-en tooltips" data-placement='right'
                                          title='إجمالى المبلغ لنتيجة البحث'></span>
                                </th>
                                <th>الباقى
                                    <span id="span_total_rent" class="font-en tooltips" data-placement='right'
                                          title='إجمالى الباقى لنتيجة البحث'></span>
                                </th>
                                <th> الحساب بعد هذه العملية</th>
                                <th>رقم العملية</th>
                                <th>ملاحظة</th>
                                <th>العمليات</th>
                                <th class="d-none">رقم نوع الشخص</th>
                                <th class="d-none">العلاقة بالحساب</th>
                            </tr>
                            </thead>
                            <tbody class="h4">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        @if(Auth::user()->type==1||Auth::user()->allow_delete_account_buy_take_money)
            <form action='{{route('account_calculation.destroy',0)}}' id='form_delete_account' class='d-none' method='post'>
                @csrf
                @method('delete')
            </form>
        @endif
    </main>

@endsection

@section('js')
    <script defer>
        design.dateRangFromTo('#dateFrom', '#dateTo', '#containerDate', 'datePicker');
        design.useNiceScroll();
        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getSumForAccountAndValueForRowNotHiddenAndSetIndex();
        });

        /*hide and show colum in table*/
        $('#columFilter input').each(function () {
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
        if ($('#checkCountRowsInTable').prop('checked')) {
            $('#countRowsInTable').show();
        } else {
            $('#countRowsInTable').hide();
        }
        $('#checkCountRowsInTable').change(function () {
            if ($('#checkCountRowsInTable').prop('checked')) {
                $('#countRowsInTable').show();
            } else {
                $('#countRowsInTable').hide();
            }
        });


        if ($('#checkSumValRowsInTable').prop('checked')) {
            $('#span_total_val').show();
        } else {
            $('#span_total_val').hide();
        }
        $('#checkSumValRowsInTable').change(function () {
            if ($('#checkSumValRowsInTable').prop('checked')) {
                $('#span_total_val').show();
            } else {
                $('#span_total_val').hide();
            }
        });

        if ($('#checkSumRentRowsInTable').prop('checked')) {
            $('#span_total_rent').show();
        } else {
            $('#span_total_rent').hide();
        }
        $('#checkSumRentRowsInTable').change(function () {
            if ($('#checkSumRentRowsInTable').prop('checked')) {
                $('#span_total_rent').show();
            } else {
                $('#span_total_rent').hide();
            }
        });



        /*hide and show colum in table*/
        $('#columFilter input').click(function () {
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

        function getSumForAccountAndValueForRowNotHiddenAndSetIndex() {
            var  counterRow = 0,totalValue=0;totalRent=0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                    $(this).children().eq(0).html(counterRow);
                    totalValue -= -$(this).children().eq(0).attr('data-value');
                    totalRent -= -$(this).children().eq(0).attr('data-rent');
                }
            });
            $('#span_total_val').html(roundTo(totalValue * 1));
            $('#span_total_rent').html(roundTo(totalRent * 1));
            $('#countRowsInTable').html(counterRow);
            design.useToolTip();
        }

        function getData() {
            $('#continerGetData button').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');

            $.ajax({
                url: '{{route('account_calculation.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getDataByAccountIdAndDate',
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    account_id: $('#select_account_name').val(),
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var typeSupplier = '<i class="fas fa-times"></i>';
                        var typeSupplierBg='bg-danger';
                        var typeCustomer = '<i class="fas fa-times"></i>';
                        var typeCustomerBg='bg-danger';
                        var typeNumber = '';
                        var typeOpration='';
                        var billId=(data[i]['bill_id']==null?'':data[i]['bill_id']);
                        var exitDealId=(data[i]['exist_deal_id']==null?'':data[i]['exist_deal_id']);

                        if (billId!=''){
                            billId='<a class="font-en pointer btn btn-primary tooltips" data-placement="left" title="عرض الفاتورة"  href="{{route("bills.index",0)}}?bill_id='+billId+'">'+billId+'</a>';
                        }

                        if (exitDealId!=''){
                            exitDealId='<a class="font-en pointer btn btn-primary tooltips" data-placement="left" title="عرض العملية"  href="{{route("exist_deals.index")}}?id='+exitDealId+'">'+exitDealId+'</a>';
                        }
                        if (data[i]['account']['is_supplier'] == 1) {
                            typeSupplier = '<i class="fas fa-check"></i>';
                            typeSupplierBg='';
                            typeNumber = 0;
                        }
                        if (data[i]['account']['is_customer'] == 1) {
                            typeCustomer = '<i class="fas fa-check"></i>';
                            typeCustomerBg='';
                            typeNumber = 1;
                        }
                        if (data[i]['account']['is_customer'] == 1 && data[i]['account']['is_supplier'] == 1) {
                            typeNumber = 2;
                        }

                        //set typeOpertation
                        if (data[i]['type'] == 0){
                            typeOpration='عند الإضافة';
                        }else if(data[i]['type'] == 1 ||data[i]['type'] == 11){
                            typeOpration='أخذ مال';
                        }else if(data[i]['type'] == 2){
                            typeOpration='دفع مال';
                        }else if(data[i]['type'] == 3){
                            typeOpration='ضبط الحساب';
                        }else if(data[i]['type'] == 4){
                            typeOpration='فاتورة شراء';
                        }else if(data[i]['type'] ==5){
                            typeOpration='فاتورة بيع';
                        }else if(data[i]['type'] ==6){
                            typeOpration='مرتجع خصم من الحساب';
                        }else if(data[i]['type'] ==7){
                            typeOpration='مرتجع إستبدال';
                        }else if(data[i]['type'] ==8){
                            typeOpration='مرتجع أخذ مال';
                        }if(data[i]['type'] ==9){
                            typeOpration='أرباح خارجية';
                        }if(data[i]['type'] ==10){
                            typeOpration='خسائر خارجية';
                        }

                        var  delete_row = '';

                        @if(Auth::user()->type==1||Auth::user()->allow_delete_account_buy_take_money)
                            if(data[i]['type']==1){
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم خصم المبلغ من درج الجهاز الحالى وإضافتة للحساب الحساب' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                        if(data[i]['type']==11){
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم خصم المبلغ من درج الجهاز الحالى وخصمة من الحساب' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                            if(data[i]['type']==2){
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم إضافة المبلغ إلى درج الجهاز الحالى وإضافتة للحساب' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                        @endif
                        $('#mainTable tbody').append(
                            "<tr class='table-success'>" +
                            "<td data-id='" + data[i]['id'] + "' data-account='"+roundTo(data[i]['account']['account'])+"' data-value='"+roundTo(data[i]['value'])+"' data-rent='"+roundTo(data[i]['rent'])+"'>" + (i - -1) + "</td>" +
                            "<td >" + data[i]['user']['name'] + "</td>" +
                            "<td>" + data[i]['device']['name'] + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td class='tooltips' data-placement='left' title='الحساب الحالى هو "+roundTo(data[i]['account']['account'])+'ج'+"'"+">" + data[i]['account']['name'] + "</td>" +
                            "<td>" + data[i]['account']['tel'] + "</td>" +
                            "<td>" + roundTo(data[i]['account']['account'])+"</td>" +
                            "<td class='"+typeSupplierBg+"'>" + typeSupplier + "</td>" +
                            "<td class='"+typeCustomerBg+"'>" + typeCustomer + "</td>" +
                            "<td>" + typeOpration + "</td>" +
                            "<td  class='tooltips' data-placement='left' title='الإجمالى للعملية وفى حالة الفاتورة يكون إجمالى الفاتورة بعد الخصم'>" + data[i]['value'] + "</td>" +
                            "<td  class='tooltips' data-placement='left' title='القيمة المضافة على الحساب'>" + (data[i]['relation_account']==1?'<i class="fas text-primary ml-2 fa-plus"></i>':(data[i]['relation_account']==2?'<i class="fas text-warning ml-2 fa-minus"></i>':'')) +roundTo(data[i]['rent']) + "</td>" +
                            "<td>" + roundTo(data[i]['account_after_this_action']) + "</td>" +
                            "<td>" + billId+ exitDealId+"</td>" +
                            "<td>" + (data[i]['note']==null?'':data[i]['note'])+ "</td>" +
                            "<td class='text-nowrap'>" +
                             delete_row +
                            "</td>" +
                            "<td class='d-none'>" + typeNumber + "</td>" +
                            "<td class='d-none'>" + (data[i]['rent']==0?0:data[i]['relation_account']) + "</td>" +
                            "</tr>"
                        );
                    }
                    $('#countRowsInTable').html(data.length);
                    $('#columFilter input').each(function () {
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
                    $('#accountType,#operationType,#accountAction').trigger('change');
                    $('#txtSearch').trigger('keyup');
                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    design.useSound('success');
                    getSumForAccountAndValueForRowNotHiddenAndSetIndex();
                    design.hide_option_not_exist_in_table_in_select($('#operationType'),
                        $('#mainTable tbody tr'),9,true);
                    $('#continerGetData button').removeAttr('disabled');
                },
                error: function (e) {
                    alert('error');
                    getSumForAccountAndValueForRowNotHiddenAndSetIndex();
                    design.useSound('error');
                    console.log(e);
                    $('#continerGetData button').removeAttr('disabled');
                }
            });
        }

        getData();
        $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-primary');

        $('#btnGetByDateCreate').click(function () {
            getData();
            $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-primary');
        });


        $('div.datePicker').click(function () {
            getSumForAccountAndValueForRowNotHiddenAndSetIndex();
            $('#mainTable tbody').html('');
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للشخص المحدد للبحث');
            design.useSound('info');
        });
        $('#dateTo,#dateFrom,#select_account_name').change(function () {
            $('#mainTable tbody').html('');
            getSumForAccountAndValueForRowNotHiddenAndSetIndex();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للشخص المحدد للبحث');
            design.useSound('info');
        });

        //print main table
        $('#printMainTable').click(function () {
            design.useSound();
            alertify.success('برجاء الإنتظار جارى الطباعة!');

            $('#mainTable').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });

        @if(Auth::user()->type==1||Auth::user()->allow_delete_account)
        $('#mainTable').on('click', 'tbody tr a[data-delete]', function (e) {
            var id = $(this).attr('data-delete');
            var parent = $(this).parent().parent();
            parent.addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد حذف العملية المحددة؟",
                title: "حذف عملية",
                confirm: function (button) {
                    var action = $('#form_delete_account').attr('action');
                    action = action.replace(/[0-9]$/, id);
                    $('#form_delete_account').attr('action', action).submit();
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
@endsection
