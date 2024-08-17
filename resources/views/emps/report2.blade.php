<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    تقارير إجمالية للموظفين
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
                <h1 class='font-weight-bold pb-3 text-white'>تقارير إجمالية للموظفين</h1>
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
                    <div class="input-group mb-5 row no-gutters">
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
                            {{--<button class="btn-primary btn font-weight-bold pointer tooltips" data-placement="bottom" title="وقت الإنشاء هو وقت إدخال العملية على النظام"
                                    id="btnGetByDateCreate"><span
                                    class="h5">بحث بوقت الإنشاء للشخص المحدد</span></button>--}}
                            <button class="btn-primary btn font-weight-bold pointer tooltips" data-placement="bottom" title="وقت العملية هو اليوم المقصود بالعملية"
                                    id="btnGetByDate"><span
                                    class="h5">بحث بوقت العملية للشخص المحدد</span></button>
                            <button id="printMainTable" class="btn border-0 mr-1 pointer text-success bg-transparent p-0 tooltips mt-1" data-placement="bottom" title="طباعة النتيجة">
                                <span class="h3"><i class="fas fa-print"></i></span>
                            </button>
                        </div>
                    </div>
                    <div class="h4 border-radius" id="divContainerData">
                        <div class="bg-white mb-0 h4 text-center text-dark" dir="rtl">
                            <style>
                                #mainTable td,#mainTable tr{
                                    border: 4px black solid!important;
                                }

                                h3{
                                    border: 4px black solid;
                                    border-bottom: 0px!important;
                                }
                            </style>
                            <h3 class="mb-0 h3 py-2">حساب
                                " <span id="printName" class="font-weight-bold">مروان مصطفى السيد</span> "
                                في الفترة من
                                <span id="printFrom" class="font-en"></span>
                                الي
                                <span id="printTo" class="font-en"></span>

                            </h3>
                            <table id="mainTable" class='m-0 h3 sorted table table-hover table-bordered' style="text-align: center">
                                <tbody>
                                <tr class=''>
                                    <td style="border:4px black solid!important;">اجمالي الايام حضور</td>
                                    <td id="printDaySalaryCount" style="border:4px black solid!important;"><i class="fas text-primary ml-2 fa-plus"></i> <span></span>أيام</td>
                                    <td id="printDaysSalary"><i class="fas text-primary ml-2 fa-plus"></i>  <span></span> ج</td>
                                </tr>
                                <tr class=''>
                                    <td colspan="2" style="border:4px black solid!important;">اجمالي الإضافي</td>
                                    <td id="printDayAddition"><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                </tr>
                                <tr class=''>
                                    <td colspan="2" style="border:4px black solid!important;">اجمالي الخصم</td>
                                    <td id="printDayDiscount"><i class="fas text-warning ml-2 fa-minus"></i> <span></span> ج</td>
                                </tr>
                                <tr class=''>
                                    <td colspan="2" style="border:4px black solid!important;">اجمالي السلف</td>
                                    <td id="printDayBorrow"><i class="fas text-warning ml-2 fa-minus"></i> <span></span> ج</td>
                                </tr>
                                <tr class=''>
                                    <td colspan="2" style="border:4px black solid!important;">اجمالي المدفوع في الفترة المحدده</td>
                                    <td id="printDayPay"><i class="fas text-warning ml-2 fa-minus"></i> <span></span> ج</td>
                                </tr>
                                <tr class=''>
                                    <td colspan="2" style="border:4px black solid!important;" class="tooltips" data-placement="top" title="باقى الحساب هو باقى الحساب الحالى للموظين المحددين فى نتيجة البحث!">باقي الحساب </td>
                                    <td id="printEmpAccount"><i class="fas text-primary ml-2 fa-plus"></i> <span></span>ج</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection

@section('js')
    <script defer>
        design.dateRangFromTo('#dateFrom', '#dateTo', '#containerDate', 'datePicker');
        design.useNiceScroll();

        function getData(type='getDataByEmpIdAndDateWithEmp') {
            $('#continerGetData button').attr('disabled', 'disabled');
            $('#mainTable tbody tr span').html('-');

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
                    $('#mainTable tbody tr span').html('-');
                    var total_days=0,total_number_days=0,total_paid=0,total_addition=0,total_discount=0,total_borrow=0,total_rent=0;
                    var emps=[];
                    for (i = 0; i < data.length; i++) {
                        //check if officers exist before to add rent to total rent
                        if(emps.indexOf(data[i]['emp_id'])==-1){
                            emps.push(data[i]['emp_id']);
                            total_rent-=-data[i]['emp']['account'];
                        }
                        var type=data[i]['type'];
                        var value=data[i]['value'];
                        if(type==0 || type==1){
                            total_addition -=-value;
                            continue;
                        }
                        if(type==2){
                            total_discount -=-value;
                            continue;
                        }
                        if(type==3){
                            total_borrow -=-value;
                            continue;
                        }
                        if(type==4){
                            total_paid -=-value;
                            continue;
                        }
                        if(type==5){
                            total_days -=-value;
                            total_number_days -=-1;
                            continue;
                        }
                    }

                    $('#printName').html($('#select_account_name option:selected').html());
                    $('#printFrom').html($('#dateFrom').val());
                    $('#printTo').html($('#dateTo').val());

                    $('#printDaySalaryCount span').html(total_number_days);
                    $('#printDaysSalary span').html(total_days);
                    $('#printDayAddition span').html(total_addition);
                    $('#printDayDiscount span').html(total_discount);
                    $('#printDayBorrow span').html(total_borrow);
                    $('#printDayPay span').html(total_paid);
                    $('#printEmpAccount span').html(total_rent);




                    alertify.success('تم البحث بنجاح');
                    design.useSound('success');
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
            // $('#btnGetByDateCreate').addClass('btn-warning').removeClass('btn-success');
        });


        $('div.datePicker').click(function () {
            $('#mainTable tbody tr span').html('-');
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء أو العملية للشخص المحدد للبحث');
            design.useSound('info');
        });
        $('#dateTo,#dateFrom,#select_account_name').change(function () {
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء أو العملية للشخص المحدد للبحث');
            design.useSound('info');
        });

        //print main table
        $('#printMainTable').click(function () {
            design.useSound();
            alertify.success('برجاء الإنتظار جارى الطباعة!');

            $('#divContainerData').printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });

    </script>
@endsection
