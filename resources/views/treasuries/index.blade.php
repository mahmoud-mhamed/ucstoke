<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    حركة أخذ ووضع مال فى الدرج الدرج
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
        <section class='animated fadeInDown faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 text-white'>حركة أخذ ووضع مال فى الدرج الدرج</h1>
                <div class='container-fluid'>
                    <div id='containerDate' class='row no-gutters overflow-hidden'>
                        <!--from date-->
                        <div class='col-sm-12 col-md-6'>
                            <div class='input-group-prepend text-center '>
                                <span
                                    class='input-group-text font-weight-bold span-date-range-picker'>من الثلاثاء</span>
                                <input type='text' id='dateFrom'
                                       class='font-weight-bold text-right text-md-center form-control'>
                            </div>
                        </div>
                        <!--to date-->
                        <div class='col-sm-12 col-md-6 mt-1 mt-md-0'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold span-date-range-picker'>الي الخميس</span>
                                <input type='text' id='dateTo'
                                       class='font-weight-bold text-right text-md-center form-control'>
                            </div>
                        </div>
                    </div>
                    <div class='mt-2 '>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>الدرج</span>
                            </div>
                            <div class="input-group-append">
                                <select id="select_device" class="selectpicker" >
                                    <option value=''>الكل</option>
                                    @foreach ($devices as $d)
                                        <option value='{{$d->id}}'>{{$d->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>بحث</span>
                            </div>
                            <input type='text' id="inputSearch"
                                   data-placement='bottom'
                                   style="height: 52px"
                                   title='بحث من خلال الملاحظة فى فترة الإنشاء المحدده للدرج المحدد'
                                   placeholder='بحث من خلال الملاحظة فى فترة الإنشاء المحدده للدرج المحدد'
                                   class='form-control tooltips'>
                            <div id="continerGetData" class="input-group-append mt-2 mt-md-0">
                                <button class="btn-success btn font-weight-bold pointer" id="btnSearchInAllCell"><span
                                        class="h5">بحث بالملاحظة فى الفتره للدرج المحدد</span></button>
                                <button class="btn-primary btn font-weight-bold pointer" id="btnGetByDateCreate"><span
                                        class="h5">بحث بوقت الإنشاء للدرج المحدد</span></button>
                            </div>
                        </div>
                        <div class="input-group table-filters">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>النوع</span>
                            </div>
                            <div class="input-group-append">
                                <select id="activityType" class="selectpicker" data-live-search="true"
                                        data-filter-col="5">
                                    <option value=''>الكل</option>
                                    <option value='وضع مال'>وضع مال</option>
                                    <option value='أخذ مال'>أخذ مال</option>
                                </select>
                            </div>

                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>المستخدم</span>
                            </div>
                            <div class="input-group-append">
                                <select id="activityType" class="selectpicker" data-live-search="true"
                                        data-filter-col="1">
                                    <option value=''>الكل</option>
                                    @foreach ($users as $d)
                                        <option value='{{$d->id}}'>{{$d->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>الدرج</span>
                            </div>
                            <div class="input-group-append">
                                <select  class="selectpicker" data-live-search="true"
                                         data-filter-col="3">
                                    <option value=''>الكل</option>
                                    @foreach ($devices as $d)
                                        <option value='{{$d->id}}'>{{$d->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input id="txtSearch" type='text' data-filter-col="0,1,2,3,4,5,6" data-placement='right'
                                   title='ابحث في نتيجة البحث باى بيانات'
                                   placeholder='ابحث في نتيجة البحث باى بيانات ' class='form-control tooltips'>
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
                        <label class="checkbox-inline pl-4" dir="ltr">الخزينة<input type="checkbox" checked
                                                                                    data-toggle="3"
                                                                                    value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">القيمة<input type="checkbox" data-toggle="4"
                                                                                   checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">النوع<input type="checkbox" data-toggle="5"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="6"
                                                                                   checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="7"
                                                                                     checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                        type="checkbox"
                                                                                        checked value=""></label>
                        <button id="printTable" class="btn btn-primary mt-1"><span class="h3">طباعة النتيجة</span>
                        </button>
                    </div>
                    <div class="alert h3" style="background: #ffc107;" role="alert">
                        إجمالى أخذ المال فى نتيجة البحث
                        <span class="font-weight-bold font-en px-2" id="span_total_take_mony">0</span>ج
                        وإجمالى وضع المال فى نتيجة البحث
                        <span class="font-weight-bold font-en px-2" id="span_total_add_mony">0</span>ج
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
                                <th>الخزينة</th>
                                <th>القيمة</th>
                                <th>النوع</th>
                                <th>ملاحظة</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody class="h4">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        @if(Auth::user()->type==1||Auth::user()->allow_delete_treasury)
            <form action='{{route('treasuries.destroy',0)}}' id='form_delete' class='d-none' method='post'>
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
                url: '{{route('treasuries.getDate')}}',
                method: 'POST',
                data: {
                    type: type,
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    search: $('#inputSearch').val(),
                    device_id:$('#select_device').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var typeName = data[i]['type'] == 0 ? 'وضع مال' : 'أخذ مال';
                        var tableClass = data[i]['type'] == 0 ? 'table-success' : 'table-info';


                        var delete_row = '';

                        @if(Auth::user()->type==1||Auth::user()->allow_delete_treasury)
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='حذف (لا يمكن الحذف إذا كان هناك فواتير تخصه) ' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        @endif
                        $('#mainTable tbody').append(
                            "<tr class='" + tableClass + "' data-main_class='" + tableClass + "'>" +
                            "<td data-id='" + data[i]['id'] + "'>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['user']['name'] + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td>" + data[i]['device']['name'] + "</td>" +
                            "<td data-type='" + data[i]['type'] + "'>" + roundTo(data[i]['val'] * 1) + "</td>" +
                            "<td>" + typeName + "</td>" +
                            "<td>" + data[i]['note'] + "</td>" +
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
                    getSumForTakeAndAddMoneyForRowNotHidden();
                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    design.useSound('success');
                    $('#continerGetData button').removeAttr('disabled');
                    $('.table-filters select,.table-filters input').trigger('change');
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
        });


        $('#btnSearchInAllCell').click(function () {
            $('#btnGetByDateCreate').addClass('btn-primary').removeClass('btn-warning');
            $('#btnSearchInAllCell').addClass('btn-warning').removeClass('btn-success');
            if ($('#inputSearch').val().length < 3) {
                alertify.error('برجاء كتابة 3 حروف على الأقل قبل البحث');
                design.useSound('info');
                return;
            }
            getData('searchInNote');
        });

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
        $('#select_device').change(function () {
            $('#mainTable tbody').html('');
            getSumForTakeAndAddMoneyForRowNotHidden();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء أو بحث بالملاحظة للبحث');
            design.useSound('info');
        });
        $('#inputSearch').click(function () {
            $('#mainTable tbody').html('');
            getSumForTakeAndAddMoneyForRowNotHidden();
            alertify.error('برجاء الضغط على زر بحث من خلال الملاحظة فى فترة الإنشاء المحدده للبحث');
            design.useSound('info');

        });

        function getSumForTakeAndAddMoneyForRowNotHidden() {
            var takeMoney = 0, addMone = 0, counterRow = 0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                    var cell = $(this).children().eq(4);
                    if (cell.attr('data-type') == 0) {
                        addMone -= -cell.text();
                    } else {
                        takeMoney -= -cell.text();
                    }
                }
            });
            $('#span_total_take_mony').html(roundTo(takeMoney * 1));
            $('#span_total_add_mony').html(roundTo(addMone * 1));
            $('#countRowsInTable').html(counterRow);
        }

        @if(Auth::user()->type==1||Auth::user()->allow_delete_treasury)
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
