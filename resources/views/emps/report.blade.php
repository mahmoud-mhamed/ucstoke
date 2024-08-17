<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    الحسابات والحركة التفصيلية للموظفين
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
                <h1 class='font-weight-bold pb-3 text-white'> الحسابات والحركة التفصيلية للموظفين</h1>
                <div class='container-fluid'>
                    <div id='containerDate' class='row no-gutters overflow-hidden'>
                        <!--from date-->
                        <div class='col-sm-12 col-md-6'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'
                                      style='min-width: 150px'>من الثلاثاء</span>
                                <input type='text' id='dateFrom' style="height: 53px" class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                        <!--to date-->
                        <div class='col-sm-12 col-md-6 mt-1 mt-md-0'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'
                                      style='min-width: 150px'>الي الخميس</span>
                                <input type='text' id='dateTo' style="height: 53px" class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                    </div>
                    <div class="input-group row no-gutters">
                        <div class="input-group-append col-2">
                            <span class='input-group-text font-weight-bold w-100 d-block'>إسم الموظف</span>
                        </div>
                        <div class="input-group-append col-7" style="direction: rtl;text-align: right" >
                            <select id="select_account_name"  style="direction: rtl;text-align: right" class="selectpicker form-control show-tick" data-live-search="true"
                                    data-filter-col="11">
                                <option data-style="padding-bottom: 50px!important;" value="">الكل</option>
                                @foreach($emps as $e)
                                    <option data-style="padding-bottom: 50px!important;" value="{{$e->id}}">{{$e->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="continerGetData" class="input-group-append col">
                            <button class="btn-primary btn font-weight-bold pointer tooltips" data-placement="bottom" title="وقت الإنشاء هو وقت إدخال العملية على النظام"
                                    id="btnGetByDateCreate"><span
                                    class="h5">بحث بوقت الإنشاء للشخص المحدد</span></button>
                            <button class="btn-primary btn font-weight-bold pointer tooltips" data-placement="bottom" title="وقت العملية هو اليوم المقصود بالعملية"
                                    id="btnGetByDate"><span
                                    class="h5">بحث بوقت العملية للشخص المحدد</span></button>
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
                        <label class="checkbox-inline pl-4" dir="ltr">الحساب الحالى<input type="checkbox" data-toggle="5"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الوظيفة<input type="checkbox" data-toggle="6"
                                                                                        value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">نوع العملية<input type="checkbox" data-toggle="7"
                                                                                       checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">المبلغ<input type="checkbox" data-toggle="8"
                                                                                    checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الحساب بعد العملية<input type="checkbox" data-toggle="9"
                                                                                   checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="10"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4 d-none" dir="ltr">العمليات<input type="checkbox" data-toggle="11"
                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                        type="checkbox"
                                                                                        checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">إجمالى المبلغ<input id="checkSumValRowsInTable"
                                                                                            type="checkbox"
                                                                                            checked value=""></label>
                        <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1" data-placement="bottom" title="طباعة النتيجة">
                            <span class="h3"><i class="fas fa-print"></i></span>
                        </button>
                    </div>
                    <div class='mt-2'>
                        <div class="input-group table-filters">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>الجهاز</span>
                            </div>
                            <div class="input-group-append">
                                <select class="selectpicker" data-live-search="true"
                                        data-filter-col="2">
                                    <option value=''>الكل</option>
                                    @foreach ($devices as $j)
                                        <option value='{{$j->name}}'>{{$j->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>الوظيفة</span>
                            </div>
                            <div class="input-group-append">
                                <select  class="selectpicker" data-live-search="true"
                                        data-filter-col="6">
                                    <option value=''>الكل</option>
                                   @foreach ($jops as $j)
                                        <option value='{{$j->name}}'>{{$j->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>النوع</span>
                            </div>
                            <div class="input-group-append">
                                <select  class="selectpicker" data-live-search="true"
                                         data-filter-col="7">
                                    <option value=''>الكل</option>
                                    <option value='عند الإضافة'>عند الإضافة</option>
                                    <option value='أضافى'>أضافى</option>
                                    <option value='خصم'>خصم</option>
                                    <option value='سلفة'>سلفة</option>
                                    <option value='دفع مال'>دفع مال</option>
                                    <option value='تسجيل حضور'>تسجيل حضور</option>
                                    <option value='تسجيل غياب'>تسجيل غياب</option>
                                </select>
                            </div>
                            <input id="txtSearch" type='text' data-filter-col="0,1,2,3,4,5,6,7,8,9,10,11,12,13"
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
                                <th>المستخدم</th>
                                <th>الجهاز</th>
                                <th>وقت العملية</th>
                                <th>الإسم</th>
                                <th>الحساب الحالى</th>
                                <th>الوظيفة</th>
                                <th>نوع العملية</th>
                                <th>المبلغ
                                    <span id="span_total_val" class="font-en tooltips" data-placement='right'
                                          title='إجمالى (حضور + إضافى) - (الخصم - السلف - الغياب - دفع المال) لنتيجة البحث'></span>
                                </th>
                                <th>الحساب بعد هذه العملية</th>
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
       {{-- @if(Auth::user()->type==1||Auth::user()->allow_delete_account_buy_take_money)
            <form action='{{route('account_calculation.destroy',0)}}' id='form_delete_account' class='d-none' method='post'>
                @csrf
                @method('delete')
            </form>
        @endif--}}
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
            var  counterRow = 0,totalValue=0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                    $(this).children().eq(0).html(counterRow);
                    console.log($(this).attr('data-operation'));
                    if($(this).attr('data-operation')=='add'){
                        totalValue -= -$(this).children().eq(0).attr('data-value');
                    }else{
                        totalValue -= $(this).children().eq(0).attr('data-value');
                    }
                }
            });
            $('#span_total_val').html(roundTo(totalValue * 1));
            $('#countRowsInTable').html(counterRow);
            design.useToolTip();
        }

        function getData(type='getDataByEmpIdAndDate') {
            $('#continerGetData button').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');

            $.ajax({
                url: '{{route('emps.getData')}}',
                method: 'POST',
                data: {
                    type: type,
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    emp_id: $('#select_account_name').val(),
                },
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var typeOpration='';
                        var operation=(data[i]['type']==2 || data[i]['type']==3 || data[i]['type']==4 || data[i]['type']==6)?'<i class="fas text-warning ml-2 fa-minus"></i>':'<i class="fas text-primary ml-2 fa-plus"></i>';
                        var operation_value=(data[i]['type']==2 || data[i]['type']==3 || data[i]['type']==4 || data[i]['type']==6)?'minus':'add';

                        //set typeOpertation
                        if (data[i]['type'] == 0){
                            typeOpration='عند الإضافة';
                        }else if(data[i]['type'] == 1){
                            typeOpration='أضافى';
                        }else if(data[i]['type'] == 2){
                            typeOpration='خصم';
                        }else if(data[i]['type'] == 3){
                            typeOpration='سلفة';
                        }else if(data[i]['type'] == 4){
                            typeOpration='دفع مال';
                        }else if(data[i]['type'] == 5){
                            typeOpration='تسجيل حضور';
                        }else if(data[i]['type'] == 6){
                            typeOpration='تسجيل غياب';
                        }if(data[i]['type'] == 7){
                            typeOpration='تسجيل حضور';
                        }

                        var  delete_row = '';

                        {{--@if(Auth::user()->type==1||Auth::user()->allow_delete_account_buy_take_money)
                            if(data[i]['type']==1){
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم خصم المبلغ من الدرج وإضافتة للحساب الحساب' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                            if(data[i]['type']==2){
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم إضافة المبلغ إلى الدرج وإضافتة للحساب' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                        @endif--}}
                        $('#mainTable tbody').append(
                            "<tr data-operation='"+operation_value+"' class='table-success'>" +
                            "<td data-id='" + data[i]['id'] + "' data-account='"+data[i]['emp']['account']+"' data-value='"+data[i]['value']+"'></td>" +
                            "<td>" + data[i]['user']['name'] + "</td>" +
                            "<td>" + data[i]['device']['name'] + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td class='tooltips pointer' data-placement='left' title='الحساب الحالى "+roundTo(data[i]['emp']['account'])+'ج'+"'>" + data[i]['emp']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['emp']['account'])+'ج' + "</td>" +
                            "<td>" + data[i]['emp']['emp_jop']['name'] + "</td>" +
                            "<td>" + typeOpration +(data[i]['type']==5||data[i]['type']==6||data[i]['type']==7?' ليوم '+data[i]['date']:'') + "</td>" +
                            "<td>" +operation+ roundTo(data[i]['value']) +'ج'+ "</td>" +
                            "<td>" + roundTo(data[i]['account_after_this_action']) +'ج'+ "</td>" +
                            "<td>" + (data[i]['note']==null?'':data[i]['note'])+ "</td>" +
                            "<td class='text-nowrap'>" +
                             delete_row +
                            "</td>" +
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
                    $('#accountType').trigger('change');
                    $('#txtSearch').trigger('keyup');
                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    design.useSound('success');
                    getSumForAccountAndValueForRowNotHiddenAndSetIndex();
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
        $('#btnGetByDateCreate').addClass('btn-success').removeClass('btn-warning');
        $('#btnGetByDate').addClass('btn-warning').removeClass('btn-success');

        $('#btnGetByDateCreate').click(function () {
            getData();
            $('#btnGetByDateCreate').addClass('btn-success').removeClass('btn-warning');
            $('#btnGetByDate').addClass('btn-warning').removeClass('btn-success');
        });
        $('#btnGetByDate').click(function () {
            getData('getDataBy_move_day');
            $('#btnGetByDate').addClass('btn-success').removeClass('btn-warning');
            $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-success');
        });


        $('div.datePicker').click(function () {
            getSumForAccountAndValueForRowNotHiddenAndSetIndex();
            $('#mainTable tbody').html('');
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء أو العملية للشخص المحدد للبحث');
            design.useSound('info');
        });
        $('#dateTo,#dateFrom,#select_account_name').change(function () {
            $('#mainTable tbody').html('');
            getSumForAccountAndValueForRowNotHiddenAndSetIndex();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء أو العملية للشخص المحدد للبحث');
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
