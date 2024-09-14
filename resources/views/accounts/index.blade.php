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
    ادارة الموردين والعملاء
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
            padding-top: 5px
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4  pb-2 position-relative'>
        <section class='animated fadeInDown faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 text-white'>اداره الموردين والعملاء
                    {{Hash::check('sup_cust',$permit->sup_cust)?'و الموردين العملاء':''}}</h1>
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
                    <div class='mt-2 table-filters'>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>بحث</span>
                            </div>
                            <input type='text' id="inputSearch"
                                   placeholder='بحث بأى بيانات (الإسم , الرقم , العنوان , ملاحظة إلخ)'
                                   class='form-control'>
                            <div id="continerGetData" class="input-group-append">
                                <button class="btn-success btn font-weight-bold pointer" id="btnSearchInAllCell"><span
                                        class="h5">بحث من خلال النص</span></button>
                                <button class="btn-primary btn font-weight-bold pointer" id="btnGetByDateCreate"><span
                                        class="h5">بحث بوقت الإنشاء</span></button>
                                <button class="btn-success btn font-weight-bold pointer" id="btnGetByAccount"><span
                                        class="h5">بحث كل من له حساب</span></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>النوع</span>
                            </div>
                            <div class="input-group-append">
                                <select id="activityType" class="selectpicker" data-live-search="true"
                                        data-filter-col="11">
                                    <option value=''>الكل</option>
                                    <option value='0'>الموردين</option>
                                    <option value='1'>العملاء</option>
                                    @if (Hash::check('sup_cust',$permit->sup_cust))
                                        <option value='2'>الموردين العملاء</option>
                                    @endif
                                </select>
                            </div>
                            <input id="txtSearch" type='text' data-filter-col="0,1,2,3,4,5,6,7,8,9"
                                   placeholder='ابحث في نتيجة البحث باى بيانات ' class='form-control'>
                        </div>
                    </div>
                    <div class="h3 text-white" id="columFilter" dir="rtl">
                        <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                              value=""></label>

                        <label class="checkbox-inline pl-4" dir="ltr">المستخدم الذى قام بإضافتة<input type="checkbox"
                                                                                                      data-toggle="1"
                                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">وقت الإضافة<input type="checkbox" data-toggle="2"
                                                                                        value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">وقت أخر تعديل<input type="checkbox"
                                                                                          data-toggle="3"
                                                                                          value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإسم<input type="checkbox" data-toggle="4"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">النوع<input type="checkbox" data-toggle="5"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">رقم الهاتف<input type="checkbox" data-toggle="6"
                                                                                       checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العنوان<input type="checkbox" data-toggle="7"
                                                                                    checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الحساب<input type="checkbox" data-toggle="8"
                                                                                   checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="9"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="10"
                                                                                     checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">أخر جهاز تعامل معة<input type="checkbox" data-toggle="12"
                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                        type="checkbox"
                                                                                        checked value=""></label>
                        <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1"
                                data-placement="bottom" title="طباعة النتيجة">
                            <span class="h3"><i class="fas fa-print"></i></span>
                        </button>
                    </div>
                    <div class='box-shadow tableFixHead table-responsive text-center'>
                        <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م
                                    <span id="countRowsInTable" class="font-en"></span>
                                </th>
                                <th>المستخدم الذى قام بإضافتة</th>
                                <th>وقت الإضافة</th>
                                <th>وقت أخر تعديل</th>
                                <th>الإسم</th>
                                <th>النوع</th>
                                <th>رقم الهاتف</th>
                                <th>العنوان</th>
                                <th class="tooltips" data-placement='left'
                                    title='الحساب للعميل (هو قيمة الدين على العميل) , للمورد (هو القيمة التى أدين بها للمورد)
                                        {{Hash::check('sup_cust',$permit->sup_cust)?', للمورد العميل (هو القيمة التى أدين بها للمورد العميل)':''}}'>
                                    الحساب
                                    <span id="span_total_account" class="font-en tooltips" data-placement='right'
                                          title='إجمالى الحساب لنتيجة البحث'></span>
                                </th>
                                <th>ملاحظة</th>
                                <th>العمليات</th>
                                <th class="d-none">type</th>
                                <th>أخر جهاز تعامل معه</th>
                            </tr>
                            </thead>
                            <tbody class="h4">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        @if(Auth::user()->type==1||Auth::user()->allow_delete_account)
            <form action='{{route('accounts.destroy',0)}}' id='form_delete_account' class='d-none' method='post'>
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
            getRowCounterAndTotalAccount();
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

        function getRowCounterAndTotalAccount() {
            var counterRow = 0;
            var totalAccount = 0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                    if ($('#activityType').val() != '') {
                        totalAccount -= -($(this).find('[data-account]').attr('data-account'));
                    }
                }

            });
            $('#countRowsInTable').html(counterRow);
            totalAccount = totalAccount == 0 ? '' : totalAccount + 'ج';
            $('#span_total_account').html(totalAccount);

            design.useToolTip(); //to update tooltip after filter

        }

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

        function getData(type) {
            $('#continerGetData button').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');

            $.ajax({
                url: '{{route('accounts.getDate')}}',
                method: 'POST',
                data: {
                    type: type,
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    search: $('#inputSearch').val(),
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var typeName = '';
                        var tableClass = '';
                        var typeNumber = '';
                        if (data[i]['is_supplier'] == 1) {
                            typeName = 'مورد';
                            tableClass = 'table-success';
                            typeNumber = 0;
                        }
                        if (data[i]['is_customer'] == 1) {
                            typeName = 'عميل';
                            tableClass = 'table-info';
                            typeNumber = 1;
                        }
                        if (data[i]['is_customer'] == 1 && data[i]['is_supplier'] == 1) {
                            typeName = 'مورد_عميل';
                            tableClass = 'table-warning';
                            typeNumber = 2;
                        }
                        var edit = '', delete_row = '', adjustAccount = '', payMoneyToSupplierOrSupplierCustomer = '',
                            takeMoneyFromCustomer = '';

                        @if(Auth::user()->type==1||Auth::user()->allow_edit_account)
                            edit = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='تعديل البيانات ' href='accounts/" + data[i]['id'] + "/edit'><i class='fas fa-2x fa-edit text-white'></i></a>";
                        @endif
                            @if(Auth::user()->type==1 || Auth::user()->allow_adjust_account)
                            adjustAccount = " <a class='btn btn-sm text-white btn-secondary tooltips' href='accounts/adjust_account/" + data[i]['id'] + "' data-placement='left' title='ضبط الحساب (سيتم إجبار الحساب على قيمة معينة دون التعديل فى الدرج) '><span class='h5 text-dark'>ضبط الحساب <i class='fas fa-cog'></i></span></a>";
                        @endif

                        if (data[i]['is_supplier']) {
                            payMoneyToSupplierOrSupplierCustomer = " <a class='btn btn-sm btn-secondary tooltips' href='accounts/add_or_subtract_debt/" + data[i]['id'] + "/2'  data-placement='left' title='سيتم خصم المبلغ من الحساب والدرج ' ><span class='h5 text-dark'>دفع مال </span></a>";
                        }
                        if (data[i]['is_customer']) {
                            takeMoneyFromCustomer = " <a class='btn btn-sm btn-primary tooltips' href='accounts/add_or_subtract_debt/" + data[i]['id'] + "/1' data-placement='left'  title='سيتم خصم المبلغ من الحساب وإضاته إلى الدج '><span class='h5 text-dark'>أخذ مال </span></a>";
                        }
                        if (data[i]['account'] == 0) {
                            // takeMoneyFromCustomer='';
                            // payMoneyToSupplierOrSupplierCustomer='';
                        }
                        @if(Auth::user()->type==1||Auth::user()->allow_delete_account)
                        if (data[i]['account'] == 0) {
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='حذف (لا يمكن الحذف إذا كان هناك فواتير تخصه) ' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                        @endif
                        $('#mainTable tbody').append(
                            "<tr class='" + tableClass + "' data-main_class='" + tableClass + "'>" +
                            "<td data-id='" + data[i]['id'] + "'>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['user']['name'] + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td>" + data[i]['updated_at'] + "</td>" +
                            "<td>" + data[i]['name'] + "</td>" +
                            "<td>" + typeName + "</td>" +
                            "<td>" + data[i]['tel'] + "</td>" +
                            "<td>" + data[i]['address'] + "</td>" +
                            "<td data-account='" + roundTo(data[i]['account'] * 1) + "'>" + roundTo(data[i]['account'] * 1) + " جنية " + "</td>" +
                            "<td>" + data[i]['note'] + "</td>" +
                            "<td class='text-nowrap'>" +
                            adjustAccount + edit + payMoneyToSupplierOrSupplierCustomer + takeMoneyFromCustomer + delete_row +
                            "</td>" +
                            "<td class='d-none'>" + typeNumber + "</td>" +
                            "<td>" + data[i]['device']['name'] + "</td>" +
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
                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    design.useSound('success');
                    $('#activityType').trigger('change');
                    $('#txtSearch').trigger('keyup');
                    $('#continerGetData button').removeAttr('disabled');
                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                    $('#continerGetData button').removeAttr('disabled');
                }
            });
        }

        getData('getDataByDateCreate');
        $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-primary');

        $('#btnGetByDateCreate').click(function () {
            getData('getDataByDateCreate');
            $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-primary');
            $('#btnSearchInAllCell').addClass('btn-success').removeClass('btn-warning');
            $('#btnGetByAccount').addClass('btn-success').removeClass('btn-warning');
        });

        $('#btnGetByAccount').click(function () {
            getData('getByHasAccount');
            $('#btnGetByDateCreate').addClass('btn-primary').removeClass('btn-warning');
            $('#btnSearchInAllCell').addClass('btn-success').removeClass('btn-warning');
            $('#btnGetByAccount').addClass('btn-warning').removeClass('btn-success');
        });

        $('#btnSearchInAllCell').click(function () {
            $('#btnGetByDateCreate').addClass('btn-primary').removeClass('btn-warning');
            $('#btnSearchInAllCell').addClass('btn-warning').removeClass('btn-success');
            $('#btnGetByAccount').addClass('btn-success').removeClass('btn-warning');
            if ($('#inputSearch').val().length < 3) {
                alertify.error('برجاء كتابة 3 حروف على الأقل قبل البحث');
                design.useSound('info');
                return;
            }
            getData('searchInAllCell');
        });

        $('div.datePicker').click(function () {
            $('#mainTable tbody').html('');
            getRowCounterAndTotalAccount();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للبحث');
            design.useSound('info');
        });
        $('#dateTo,#dateFrom').change(function () {
            $('#mainTable tbody').html('');
            getRowCounterAndTotalAccount();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للبحث');
            design.useSound('info');
        });
        $('#inputSearch').click(function () {
            $('#mainTable tbody').html('');
            getRowCounterAndTotalAccount();
            alertify.error('برجاء الضغط على زر بحث من خلال النص للبحث');
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
            $('#mainTable tbody tr').each(function () {
                $(this).removeClass('table-danger').addClass($(this).attr('data-main_class'));
            });
            parent.addClass('table-danger').removeClass(parent.attr('data-main_class'));
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد حذف الشخص أو المؤسسة المحدده؟",
                title: "حذف شخص أو مؤسسة",
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
