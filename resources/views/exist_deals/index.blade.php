<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    إدارة وتقارير الأرباح والخسائر الخارجية
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
@endsection
@section('content')
    <main dir='rtl' class='pt-4  pb-2 position-relative'>
        <section class='animated container-fluid fadeInDown faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 text-white'>إدارة وتقارير الأرباح والخسائر الخارجية</h1>
                <div class=''>
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
                            <div id="continerGetData" class="input-group-append">
                                <button class="btn-primary btn font-weight-bold pointer" id="btnGetByDateCreate"><span
                                        class="h5">بحث بوقت الإنشاء</span></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h3 text-white" id="columFilter" dir="rtl">
                    <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                          value=""></label>

                    <label class="checkbox-inline pl-4" dir="ltr">إسم المستخدم<input type="checkbox"
                                                                                     data-toggle="1"
                                                                                     value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">وقت العملية<input type="checkbox" data-toggle="2"
                                                                                    value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">الجهاز<input type="checkbox" checked
                                                                               data-toggle="3"
                                                                               value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">الشخص<input type="checkbox" data-toggle="4"
                                                                              checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">النوع<input type="checkbox" data-toggle="5"
                                                                              checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">القيمة<input type="checkbox" data-toggle="6"
                                                                               checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">المبلغ المدفوع<input type="checkbox"
                                                                                       data-toggle="7"
                                                                                       checked
                                                                                       value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">الباقى<input type="checkbox" data-toggle="8"
                                                                               checked  value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="9"
                                                                           checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="10"
                                                                                 checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                    type="checkbox"
                                                                                    checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">إجمالى القيمة<input id="checkTotalValue"
                                                                                      type="checkbox"
                                                                                      checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">إجمالى المبلغ المدفوع<input id="checkTotalPaid"
                                                                                              type="checkbox"
                                                                                              checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">إجمالى الباقى<input id="checkTotalRent"
                                                                                      type="checkbox"
                                                                                      checked value=""></label>
                    <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1"
                            data-placement="bottom" title="طباعة النتيجة">
                        <span class="h3"><i class="fas fa-print"></i></span>
                    </button>
                </div>
                <div class="table-filters">
                    <div class="input-group ">
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>النوع</span>
                        </div>
                        <div class="input-group-append">
                            <select class="selectpicker" data-live-search="true"
                                    data-filter-col="5">
                                <option value=''>الكل</option>
                                <option value='أرباح'>أرباح</option>
                                <option value='خسائر'>خسائر</option>
                            </select>
                        </div>
                        <input id="txtSearch" type='text' data-filter-col="0,1,2,3,4,5,6,7"
                               placeholder='ابحث في نتيجة البحث باى بيانات ' class='form-control'>
                    </div>
                </div>
                <div class='box-shadow table-responsive text-center'>
                    <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م
                                <span id="countRowsInTable" class="font-en"></span>
                            </th>
                            <th>إسم المستخدم</th>
                            <th>وقت العملية</th>
                            <th>الجهاز</th>
                            <th>الشخص</th>
                            <th>النوع</th>
                            <th>القيمة
                                <span class="font-weight-bold font-en px-2 tooltips" data-placement="left"
                                      title="إجمالى القيمة لنتيجة البحث (أرباح - خسائر)" id="span_total_value">0</span>
                            </th>
                            <th>المبلغ الدفوع
                                <span class="font-weight-bold font-en px-2 tooltips" data-placement="left"
                                      title="إجمالى المدفوع لنتيجة البحث (أرباح - خسائر)" id="span_total_paid">0</span>
                            </th>
                            <th>الباقى
                                <span class="font-weight-bold font-en px-2 tooltips" data-placement="left"
                                      title="إجمالى الباقى لنتيجة البحث وتم تعديل الحساب بقيمتة (أرباح - خسائر)"
                                      id="span_total_rent">0</span>
                            </th>
                            <th>ملاحظة</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody class="h4">

                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        @if(Auth::user()->type==1||Auth::user()->allow_delete_exit_deal)
            <form action='{{route('exist_deals.destroy',0)}}' id='form_delete' class='d-none' method='post'>
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
            getSumForTakeAndAddMoneyForRowNotHidden();
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

        if ($('#checkTotalValue').prop('checked')) {
            $('#span_total_value').show();
        } else {
            $('#span_total_value').hide();
        }
        $('#checkTotalValue').change(function () {
            if ($('#checkTotalValue').prop('checked')) {
                $('#span_total_value').show();
            } else {
                $('#span_total_value').hide();
            }
        });

        if ($('#checkTotalPaid').prop('checked')) {
            $('#span_total_paid').show();
        } else {
            $('#span_total_paid').hide();
        }
        $('#checkTotalPaid').change(function () {
            if ($('#checkTotalPaid').prop('checked')) {
                $('#span_total_paid').show();
            } else {
                $('#span_total_paid').hide();
            }
        });

        if ($('#checkTotalRent').prop('checked')) {
            $('#span_total_rent').show();
        } else {
            $('#span_total_rent').hide();
        }
        $('#checkTotalRent').change(function () {
            if ($('#checkTotalRent').prop('checked')) {
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

        function getData(type) {
            $('#continerGetData button').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');

            $.ajax({
                url: '{{route('exist_deals.getDate')}}',
                method: 'POST',
                data: {
                    type: type,
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    id:'{{isset($id)?$id:''}}'//to get data by id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var typeText = data[i]['type'] == 0 ? 'أرباح' : 'خسائر';
                        var tableClass = data[i]['type'] == 0 ? 'table-success' : 'table-info';


                        var delete_row = '';

                        @if(Auth::user()->type==1||Auth::user()->allow_delete_exit_deal)
                            delete_row = " <a class='btn btn-sm p-0'  data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        @endif
                        $('#mainTable tbody').append(
                            "<tr class='" + tableClass + "' data-type='" + data[i]['type'] + "' data-value='" + data[i]['value'] + "' data-value_add_to_treasury='" + data[i]['value_add_to_treasury'] + "'>" +
                            "<td data-id='" + data[i]['id'] + "'>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['user']['name'] + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td>" + data[i]['device']['name'] + "</td>" +
                            "<td>" + (data[i]['account'] != null ? data[i]['account']['name'] : 'بدون') + "</td>" +
                            "<td>" + typeText + "</td>" +
                            "<td>" + roundTo(data[i]['value'] * 1) + "ج" + "</td>" +
                            "<td>" + roundTo(data[i]['value_add_to_treasury'] * 1) + "ج" + "</td>" +
                            "<td>" + roundTo(data[i]['value'] - data[i]['value_add_to_treasury']) + "ج" + "</td>" +
                            "<td>" + (data[i]['note'] ? data[i]['note'] : '') + "</td>" +
                            "<td class='text-nowrap'>" +
                            delete_row +
                            "</td>" +
                            "</tr>"
                        );
                    }
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
                    $('#mainTable').filtable({controlPanel: $('.table-filters')});

                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    design.useSound('success');
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
        });

        @if($id!='')
        getData('getDataById');
        @else
        getData('getDataByDateCreate');
        @endif

        $('div.datePicker').click(function () {
            $('#mainTable tbody').html('');
            getSumForTakeAndAddMoneyForRowNotHidden();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للبحث');
            design.useSound('info');
        });
        $('#dateTo,#dateFrom').change(function () {
            $('#mainTable tbody').html('');
            getSumForTakeAndAddMoneyForRowNotHidden();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للبحث');
            design.useSound('info');
        });


        function getSumForTakeAndAddMoneyForRowNotHidden() {
            var totalValue = 0, totalPaid = 0, counterRow = 0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    if ($(this).attr('data-type') == 0) {
                        totalValue -= -$(this).attr('data-value');
                        totalPaid -= -$(this).attr('data-value_add_to_treasury');
                    } else {
                        totalValue = -$(this).attr('data-value');
                        totalPaid = -$(this).attr('data-value_add_to_treasury');
                    }
                    counterRow -= -1;
                }
            });
            $('#span_total_value').html(roundTo(totalValue * 1) + 'ج');
            $('#span_total_paid').html(roundTo(totalPaid * 1) + 'ج');
            $('#span_total_rent').html(roundTo(totalValue - totalPaid) + 'ج');
            $('#countRowsInTable').html(counterRow);
        }

        //print main table
        $('#printMainTable').click(function () {
            design.useSound();
            alertify.success('برجاء الإنتظار جارى الطباعة!');
            $('#mainTable').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });
        @if(Auth::user()->type==1||Auth::user()->allow_delete_exit_deal)
        $('#mainTable').on('click', 'tbody tr a[data-delete]', function (e) {
            var id = $(this).attr('data-delete');
            var parent = $(this).parent().parent();

            $('#mainTable tbody tr').each(function () {
                $(this).removeClass('table-danger').addClass($(this).attr('data-main_class'));
            });
            parent.addClass('table-danger').removeClass(parent.attr('data-main_class'));
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد حذف العملية المحدده؟",
                title: "حذف عملية",
                confirm: function (button) {
                    var action = $('#form_delete').attr('action');
                    action = action.replace(/[0-9]$/, id);
                    $('#form_delete').attr('action', action).submit();
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
