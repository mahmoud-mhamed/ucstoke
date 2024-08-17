<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 01/02/2019
 * Time: 01:13 م
 */ ?>
@extends('layouts.app')
@section('title')
    حركة البيع والأرباح للمنتجات
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
    <main dir='rtl' class='pt-4 px-2 pb-2'>
        <section class='animated h2 fadeInDown ml-auto faster px-1 px-md-4'>
            <div class='text-center'>
                <h1 class='text-white font-weight-bold pb-3'>حركة البيع والأرباح للمنتجات</h1>
                <div class='print font-en position-absolute text-center' style='top: 0;font-size: .75rem;'></div>
                <div class='box-shadow'>
                    <div class='row no-gutters' data-date='all'>
                        <!--from date-->
                        <div class='col-6'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'>من الثلاثاء</span>
                                <input style="height: 53px" type='text' id='input_date_from'
                                       class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                        <!--to date-->
                        <div class='col-6'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'>الي الخميس</span>
                                <input style="height: 53px" type='text' id='input_date_to'
                                       class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                    </div>
                    <div class='mt-2'>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>الجهاز</span>
                            </div>
                            <div class="input-group-append">
                                <select id="select_device_id" class="selectpicker" data-live-search="true">
                                    <option value=''>الكل</option>
                                    @foreach ($devices as $d)
                                        <option value='{{$d->id}}'
                                        >{{$d->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="continerGetData" class="input-group-append">
                                <button class="btn-primary btn font-weight-bold pointer" onclick="getData();"
                                        id="btnGetByDateCreate"><span
                                        class="h5">بحث بوقت الإنشاء للجهاز المحدد</span></button>
                            </div>
                        </div>
                    </div>

                    <div class='mt-2 table-filters'>
                        <div class="d-flex" style="flex-wrap: wrap">
                            <div class="input-group w-auto">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>نوع المنتج</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="4">
                                        <option value=''>الكل</option>
                                        <option value='1'>بكمية</option>
                                        <option value='0'>بدون كمية</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group w-auto">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>حالة المنتج</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="6">
                                        <option value=''>الكل</option>
                                        <option value='1'>ربح</option>
                                        <option value='0'>خسارة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group w-auto">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>القسم</span>
                                </div>
                                <div class="input-group-append">
                                    <select class="selectpicker" data-live-search="true"
                                            data-filter-col="5">
                                        <option value=''>الكل</option>
                                        @foreach($categories as $c)
                                            <option value='{{$c->id}}'>{{$c->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="input-group w-auto">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>بحث</span>
                                </div>
                                <input type='text'
                                       data-live-search="true"
                                       data-filter-col="0,1,2,3"
                                       placeholder='بحث بأى بيانات '
                                       class='form-control'>
                            </div>
                            <button id="printMainTable"
                                    class="btn border-0 text-success bg-transparent p-0 tooltips mr-2 mt-1"
                                    data-placement="bottom" title="طباعة النتيجة">
                                <span class="h3"><i class="fas fa-print"></i></span>
                            </button>
                        </div>
                    </div>
                    <div id='details' class='table-responsive d-none mb-3'>
                        <h2 class='text-white font-weight-bold mt-0'>تفاصيل فواتير وكميات المنتج المحدد</h2>
                        <table class='m-0 sorted table h2 table-hover table-bordered'>
                            <thead class='thead-dark'>
                            <tr>
                                <th>م</th>
                                <th>التاريخ</th>
                                <th>رقم الفاتورة</th>
                                <th>اسم العميل</th>
                                <th>الكمية</th>
                                <th>إجمالى الربح</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--<tr class='table-danger'>
                                <th>Id</th>
                                <th>اسم المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>اجمالي السعر</th>
                            </tr>--}}
                            </tbody>
                        </table>
                    </div>
                    <div class='table-responsive border-radius'>
                        <table id="mainTable" class='m-0 sorted table table-hover table-bordered'>
                            <thead class='thead-dark font-weight-bold'>
                            <tr>
                                <th class="tooltips" data-placement="left"
                                    title="إجمالى خصومات الفواتير للجهاز المحدد فى الفترة المحددة">اجمالي خصومات
                                    الفواتير
                                </th>
                                <th class='font-en'>
                                    <span id='totalDiscount' class='font-en px-3 '>0</span>
                                    جنية
                                </th>
                                <th>اجمالي الربح بعد الخصومات</th>
                                <th class='font-en'>
                                    <span id='totalProfitAfterDiscount' class='font-en px-3 '>0</span>
                                    جنية
                                </th>
                            </tr>
                            <tr>
                                <th>م</th>
                                <th>اسم المنتج</th>
                                <th class=''>المباع</th>
                                <th class=''>اجمالي الربح قبل الخصومات
                                    <span id='totalProfit' class='font-en px-3 '>0</span>
                                    جنية
                                </th>
                                <th class="d-none">type has qte</th>
                                <th class="d-none">category</th>
                                <th class="d-none">state_profit</th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- rows -->
                            {{--<tr class='table-success'>
                                <td>اسم المنتج</td>
                                <td >الوحدة</td>
                                <td class='font-en'>المباع</td>
                                <td class='font-en'>الربح</td>
                            </tr>--}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('js')
    <script defer>
        design.dateRangFromTo('#input_date_from', '#input_date_to',
            '[data-date=all]', 'dateReportProductProfit');

        alertify.success('برجاء الضغط على مسلسل أى سطر لعرض فواتير البيع التى تحتوى على المنتج المحدد !');

        design.useNiceScroll();

        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getRowSum();
            design.useToolTip();
            design.updateNiceScroll();
        });

        function getData() {
            $('#details').addClass('d-none');
            $('#btnGetByDateCreate').attr('disabled','disabled');
            $.ajax({
                url: '{{route('product_moves.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getProductProfit',
                    dateFrom: $("#input_date_from").val(),
                    dateTo: $("#input_date_to").val(),
                    device_id: $('#select_device_id').val(),
                },
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    $('#btnGetByDateCreate').removeAttr('disabled');
                    $('#mainTable tbody').html('');

                    var totalProfit = 0;
                    for (i = 0; i < data[0].length; i++) {

                        //create select to show data with multi unit
                        var var_option_unit = '';
                        var totalQte = data[0][i]['qte'];
                        var_option_unit += "<option>" + roundTo(totalQte * 1) + ' ' + data[0][i]['product']['product_unit']['name'] + "</option>";
                        for (let j = 0; j < data[0][i]['product']['relation_product_unit'].length; j++) {
                            var_option_unit += "<option>" + roundTo(totalQte / data[0][i]['product']['relation_product_unit'][j]['relation_qte']) + ' ' + data[0][i]['product']['relation_product_unit'][j]['product_unit']['name'] + "</option>";
                        }


                        totalProfit -= -data[0][i]['profit'];
                        $('#mainTable tbody').append(
                            "<tr class='table-success pointer'" +
                            " data-profit='" + data[0][i]['profit'] + "'>" +
                            "<td data-product_id='" + data[0][i]['product']['id'] + "'  class='tooltips' data-placement='left' title='إضغط لعرض الفواتير التى تحتوى هذا المنتج فى الفترة المحددة للجهاز المحدد'>" + (i - -1) + "</td>" +
                            "<td>" + data[0][i]['product']['name'] + "</td>" +
                            "<td>" +
                            '<select class="custom-select" style="min-width: 150px">' + var_option_unit + '</select>' +
                            "</td>" +
                            "<td class='font-en'>" + roundTo(data[0][i]['profit'], 2) + 'ج' + "</td>" +
                            "<td class='d-none'>" + (data[0][i]['has_qte'] ? 1 : 0) + "</td>" +
                            "<td class='d-none'>" + data[0][i]['product']['product_category_id'] + "</td>" +
                            "<td class='d-none'>" + (roundTo(data[0][i]['profit'], 2)>0?1:0) + "</td>" +
                            "</tr>"
                        );
                    }
                    $('#totalDiscount').attr('data-total_discount', data[1]['total_discount']).html(roundTo(data[1]['total_discount']));
                    alertify.success('تم العرض بنجاح');
                    design.useSound();
                    $('#mainTable').filtable({controlPanel: $('.table-filters')});
                },
                error: function (e) {
                    $('#btnGetByDateCreate').removeAttr('disabled');
                    alert('error');
                    console.log(e);
                }
            });
        }

        function getRowSum() {
            $('#details').addClass('d-none');
            design.updateNiceScroll();
            var counterRow = 0;
            var totalProfit = 0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                    totalProfit -= -($(this).attr('data-profit'));
                }

            });
            $('#countRowsInTable').html(counterRow);
            $('#totalProfit').html(roundTo(totalProfit));
            $('#totalProfitAfterDiscount').html(roundTo(totalProfit - $('#totalDiscount').attr('data-total_discount')));

            design.useToolTip(); //to update tooltip after filter
        }

        getData();

        //print main table
        $('#printMainTable').click(function () {
            $('#mainTable').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });

        $('div.dateReportProductProfit').click(function () {
            $('#mainTable tbody').html('');
            getRowSum();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للبحث');
            design.useSound('info');
        });
        $('#dateTo,#dateFrom,#select_device_id').change(function () {
            $('#mainTable tbody').html('');
            getRowSum();
            alertify.error('برجاء الضغط على زر بحث بوقت الإنشاء للبحث');
            design.useSound('info');
        });

        var stateShowDetails=false;
        function getDetailsData(product_id) {
            if(stateShowDetails){
                alertify.error('برجاء الإنتظار جارى عرض التفاصيل');
                design.useSound('info');
                return false;
            }
            stateShowDetails=true;
            $('#details').removeClass('d-none');
            $('#details tbody').html('');
            $.ajax({
                url: '{{route('product_moves.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getBillProductProfit',
                    dateFrom: $("#input_date_from").val(),
                    dateTo: $("#input_date_to").val(),
                    device_id: $('#select_device_id').val(),
                    product_id: product_id
                },
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    $('#details tbody').html('');
                    for (var i = 0; i < data.length; i++) {
                        if(data[i]['detail'].length==0){
                            continue;
                        }
                        var rowDetail = 0;
                        for (var j = 0; j < data[i]['detail'].length; j++) {
                            if (data[i]['detail'][j]['product_id'] == product_id) {
                                rowDetail = j;
                                // break;
                            }
                        }
                        var profit=0;
                        if(data[i]['detail'][rowDetail]['sale_make_qte_detail'].length==0){
                            profit=data[i]['detail'][rowDetail]['price']*data[i]['detail'][rowDetail]['qte'];
                        }
                        for (var j = 0; j < data[i]['detail'][rowDetail]['sale_make_qte_detail'].length; j++) {
                            profit -= - (data[i]['detail'][rowDetail]['sale_make_qte_detail'][j]['qte']*(data[i]['detail'][rowDetail]['price']- data[i]['detail'][rowDetail]['sale_make_qte_detail'][j]['store']['price']));
                        }

                        var rowDetail = [];
                        for (var j = 0; j < data[i]['detail'].length; j++) {
                            if (data[i]['detail'][j]['product_id'] == product_id) {
                                rowDetail.push(j);
                                // break;
                            }
                        }
                        var billId='<a class="font-en pointer btn btn-primary tooltips" target="_blank" data-placement="left" title="عرض الفاتورة"  href="{{route("bills.index",1)}}?bill_id='+data[i]['id']+'&show_profit=true">'+data[i]['id']+'</a>';
                        var profit=0;
                        var qte=0;
                        for (let m = 0; m < rowDetail.length; m++) {
                            if (data[i]['detail'][rowDetail[m]]['sale_make_qte_detail'].length == 0) {
                                profit = data[i]['detail'][rowDetail[m]]['price'] * data[i]['detail'][rowDetail[m]]['qte'];
                                qte = data[i]['detail'][rowDetail[m]]['price'] * data[i]['detail'][rowDetail[m]]['qte'];
                            }
                            for (var j = 0; j < data[i]['detail'][rowDetail[m]]['sale_make_qte_detail'].length; j++) {
                                profit -= -(data[i]['detail'][rowDetail[m]]['sale_make_qte_detail'][j]['qte'] * (data[i]['detail'][rowDetail[m]]['price'] - data[i]['detail'][rowDetail[m]]['sale_make_qte_detail'][j]['store']['price']));

                            }

                            $('#details tbody').append(
                                "<tr class='table-danger'>" +
                                "<td>" + (i - -1) + "</td>" +
                                "<td>" + data[i]['created_at'] + "</td>" +
                                "<td>" + billId + "</td>" +
                                "<td>" + (data[i]['account'] == null ? 'بدون' : data[i]['account']['name']) + "</td>" +
                                "<td>" + roundTo(data[i]['detail'][rowDetail[m]]['qte'] / data[i]['detail'][rowDetail[m]]['relation_qte']) + ' ' + data[i]['detail'][rowDetail[m]]['product_unit']['name'] + "</td>" +
                                "<td>" + roundTo(profit)+'ج' + "</td>" +
                                "</tr>"
                            );
                        }



                           /* var rowDetail = 0;
                            for (var j = 0; j < data[i]['detail'].length; j++) {
                                if (data[i]['detail'][j]['product_id'] == product_id) {
                                    rowDetail = j;
                                    // break;
                                }
                            }
                            var profit=0;
                            if(data[i]['detail'][rowDetail]['sale_make_qte_detail'].length==0){
                                profit=data[i]['detail'][rowDetail]['price']*data[i]['detail'][rowDetail]['qte'];
                            }
                            for (var j = 0; j < data[i]['detail'][rowDetail]['sale_make_qte_detail'].length; j++) {
                                profit -= - (data[i]['detail'][rowDetail]['sale_make_qte_detail'][j]['qte']*(data[i]['detail'][rowDetail]['price']- data[i]['detail'][rowDetail]['sale_make_qte_detail'][j]['store']['price']));
                            }

                            var rowDetail = 0;
                            for (var j = 0; j < data[i]['detail'].length; j++) {
                                if (data[i]['detail'][j]['product_id'] == product_id) {
                                    rowDetail = j;
                                    // break;
                                }
                            }
                            var profit=0;
                            if(data[i]['detail'][rowDetail]['sale_make_qte_detail'].length==0){
                                profit=data[i]['detail'][rowDetail]['price']*data[i]['detail'][rowDetail]['qte'];
                            }
                            for (var j = 0; j < data[i]['detail'][rowDetail]['sale_make_qte_detail'].length; j++) {
                                profit -= - (data[i]['detail'][rowDetail]['sale_make_qte_detail'][j]['qte']*(data[i]['detail'][rowDetail]['price']- data[i]['detail'][rowDetail]['sale_make_qte_detail'][j]['store']['price']));
                           */

                    }
                    design.useToolTip();
                    design.useSound();
                    design.updateNiceScroll();
                    alertify.success('تم عرض التفاصيل بنجاح');
                    stateShowDetails=false;
                },
                error: function (e) {
                    alert('error');
                    stateShowDetails=false;
                    console.log(e);
                }
            });
        }

        $('#mainTable').on('click', 'tbody tr td[data-product_id]', function () {
            $(this).parent().removeClass('table-success').addClass('table-danger').siblings().removeClass('table-danger').addClass('table-success');
            getDetailsData($(this).attr('data-product_id'));
        });


    </script>
@endsection
