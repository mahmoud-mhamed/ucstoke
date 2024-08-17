<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    إدارة عمليات الإنتاج
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

        #section_add_damage div.error-qte {
            margin: -35px;
            padding-right: 15px;
            position: absolute;
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
        #mainTable, #tableDetails {
            border-collapse: collapse;
            width: 100%;
        }

        #mainTable td, #tableDetails td {
            padding: 0px;
            padding-top: 5px;
            padding-bottom: 5px
        }

        #mainTable select, #tableDetails select {
            margin-top: -5px;
            max-height: 42px !important;
            padding-top: 0px
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4  pb-2 position-relative'>
        <section class='animated fadeInDown faster'>
            <div class='text-center container-fluid'>
                <h1 class='font-weight-bold pb-3 text-white'>إدارة عمليات الإنتاج</h1>
                <div class=''>
                    <div id='containerDate' class='row no-gutters overflow-hidden'>
                        <!--from date-->
                        <div class='col-sm-12 col-md-6'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'
                                      style='min-width: 150px'>من الثلاثاء</span>
                                <input type='text'
                                       style="height: 53px"
                                       id='dateFrom' class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                        <!--to date-->
                        <div class='col-sm-12 col-md-6 mt-1 mt-md-0'>
                            <div class='input-group-prepend text-center '>
                                <span class='input-group-text font-weight-bold'
                                      style='min-width: 150px'>الي الخميس</span>
                                <input type='text' id='dateTo'
                                       style="height: 53px"
                                       class='font-weight-bold text-center form-control'>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>المخزن</span>
                        </div>
                        <div class='input-group-append'>
                            <select id="select_stoke_id" name="stoke_id" class="selectpicker ">
                                @foreach ($stokes as $s)
                                    <option
                                        value='{{$s->stoke_id}}' {{\Auth::user()->device->default_stoke==$s->stoke_id?'selected':''}}>{{$s->stoke->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn-success btn font-weight-bold pointer tooltips"
                                data-placement="bottom" title="بحث الإنتاج فى المخزن المحدد فى الفترة المحددة"
                                id="btnGetStokeData"><span
                                class="h5">بحث الإنتاج فى المخزن</span></button>
                    </div>
                </div>
                <div id='divDetails' class='tableFixHead table-responsive border-radius d-none'>
                    <h3 class='text-white h2'>تفاصيل المنتجات المستخدمة فى إنتاج
                        <span id="span_details_product_name" class="font-en text-danger text"
                              style="text-decoration: underline"></span>
                        <button id="printDetailsTable"
                                class="btn border-0 text-success bg-transparent p-0 tooltips mt-1"
                                data-placement="bottom" title="طباعة التفاصيل"><span class="h3"><i
                                    class="fas fa-print"></i></span>
                        </button>
                    </h3>
                    <table id="tableDetails" class='main-table sorted m-0 table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م</th>
                            <th>إسم المنتج</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>اجمالي السعر</th>
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
                <div class="input-group table-filters">
                    <div class="input-group-append">
                        <span class='input-group-text font-weight-bold'>إسم المنتج</span>
                    </div>
                    <div class="input-group-append">
                        <select id="activityType" class="selectpicker" data-live-search="true"
                                data-filter-col="5">
                            <option value=''>الكل</option>
                            @foreach ($products as $p)
                                <option value='{{$p->name}}'>{{$p->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input id="txtSearch" style="min-width: 150px" type='text'
                           data-filter-col="0,1,2,3,4,5,6,7,8,9,10,11,12,13"
                           placeholder='ابحث في النتيجة باى بيانات ' class='form-control'>
                </div>
                <div class="h3 text-white" id="columFilter" dir="rtl">
                    <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                          value=""></label>

                    <label class="checkbox-inline pl-4" dir="ltr">وقت العملية<input type="checkbox"
                                                                                    checked data-toggle="1"
                                                                                    value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">الجهاز<input type="checkbox" data-toggle="2"
                                                                               value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">المستخدم<input type="checkbox"
                                                                                 data-toggle="3"
                                                                                 value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">المخزن<input type="checkbox"
                                                                               data-toggle="4"
                                                                               value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">إسم المنتج<input type="checkbox" data-toggle="5"
                                                                                   checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">الكمية<input type="checkbox"
                                                                               data-toggle="6"
                                                                               checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">سعر الإنتاج<input type="checkbox" data-toggle="7"
                                                                                    checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">إجمالى السعر<input type="checkbox" data-toggle="8"
                                                                                     checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="9"
                                                                                 checked value=""></label>

                    <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                    type="checkbox"
                                                                                    checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">إجمالى السعر فى النتيجة<input
                            id="checkSumPriceRowsInTable"
                            type="checkbox"
                            checked value=""></label>
                    <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1"
                            data-placement="bottom" title="طباعة النتيجة">
                        <span class="h3"><i class="fas fa-print"></i></span>
                    </button>
                </div>
                <div class='box-shadow tableFixHead table-responsive text-center '>
                    <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م
                                <span id="countRowsInTable" class="font-en"></span>
                            </th>
                            <th>وقت العملية</th>
                            <th>الجهاز</th>
                            <th>المستخدم</th>
                            <th>المخزن</th>
                            <th>إسم المنتج</th>
                            <th>الكمية</th>
                            <th>سعر الإنتاج</th>
                            <th>
                                إجمالى السعر
                                <span id="span_total_price_in_main_table" class="font-en tooltips"
                                      data-placement='right'
                                      title='مجموع إجمالى سعر الكمية الموجودة لنتيجة البحث بسعر الشراء أو الإنتاج'></span>
                            </th>
                            <th>ملاحظة</th>
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody class="h4">
                        @if (isset($result))
                            <tr class="table-success">
                                <td class="pointer tooltips" data-id="{{$result->id}}"
                                    data-product_name="{{$result->product->name}}"
                                    data-placement="left" title="إضغط لعرض تفاصيل الكمية المستخدمة فى الإنتاج"
                                    data-total_price="{{$result->qte*$result->relation_qte*$result->price_make}}">م
                                </td>
                                <td>{{$result->created_at}}</td>
                                <td>{{$result->device->name}}</td>
                                <td>{{$result->user->name}}</td>
                                <td>{{$result->stoke->name}}</td>
                                <td>{{$result->product->name}}</td>
                                <td>{{$result->qte}} {{$result->productUnit->name}}</td>
                                <td>{{round($result->relation_qte*$result->price_make)}} ج</td>
                                <td>{{round($result->qte*$result->relation_qte*$result->price_make)}} ج</td>
                                <td>{{$result->note}}</td>
                                <td>
                                    @if(Auth::user()->type==1||Auth::user()->allow_delete_make)
                                        <a class='btn btn-sm p-0 tooltips' data-placement='left'
                                           title='سيتم إعادة المكنونات التى تم الإنتاج بها إلى المخزن'
                                           data-delete='{{$result->id}}'><i
                                                class='fas fa-2x text-danger fa-trash-alt'></i></a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        @if(Auth::user()->type==1||Auth::user()->allow_delete_make)
            <form action='{{route('makings.destroy',0)}}' id='form_delete_make' class='d-none'
                  method='post'>
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
        validateByAttr();
        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getSumForTotalAndAndSetIndexAndTotalInMainTable();
            $('#divDetails').addClass('d-none');
            $('#tableDetails tbody').html('');
            design.useToolTip();
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

        $('#checkSumPriceRowsInTable').change(function () {
            if ($('#checkSumPriceRowsInTable').prop('checked')) {
                $('#span_total_price_in_main_table').show();
            } else {
                $('#span_total_price_in_main_table').hide();
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

        function getSumForTotalAndAndSetIndexAndTotalInMainTable() {
            var totalPriceForExistQte = 0, counterRow = 0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                    $(this).children().eq(0).html(counterRow);
                    totalPriceForExistQte -= -$(this).children().eq(0).attr('data-total_price');
                }
            });
            $('#countRowsInTable').html(counterRow);
            $('#span_total_price_in_main_table').html(roundTo(totalPriceForExistQte * 1));
        }


        //get stoke data
        function getData() {
            disableGetProductStokeDetails = false;
            $('#btnGetStokeData').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');
            $('#tableDetails tbody').html('');
            $('#divDetails').addClass('d-none');
            var stoke_id = $('#select_stoke_id').val();
            if (stoke_id == '') {
                design.useSound('error');
                alertify.error('هذا الجهاز غير مصرح له بالوصول إلى إى مخزن !');
                return;
            }
            $.ajax({
                url: '{{route('makings.getDate')}}',
                method: 'POST',
                data: {
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    type: 'getMakingData',
                    stoke_id: stoke_id,
                },
                dataType: 'JSON',
                success: function (data) {
                    disableGetProductStokeDetails = false;
                    $('#divDetails').addClass('d-none');
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var totalPrice = data[i]['qte'] * data[i]['price_make'] ;

                        var delete_row = '';

                        @if(Auth::user()->type==1||Auth::user()->allow_delete_make)
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم إعادة المكنونات التى تم الإنتاج بها إلى المخزن' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        @endif
                        $('#mainTable tbody').append(
                            "<tr class='table-success' >" +
                            "<td class='pointer tooltips' data-id='" + data[i]['id'] + "' data-product_unit='"+roundTo(data[i]['qte'] /data[i]['relation_qte'] ) + data[i]['product_unit']['name'] +"' data-product_name='" + data[i]['product']['name'] + "' data-placement='left' title='إضغط لعرض تفاصيل الكمية المستخدمة فى الإنتاج'  data-total_price='" + totalPrice + "'>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td>" + data[i]['device']['name'] + "</td>" +
                            "<td>" + data[i]['user']['name'] + "</td>" +
                            "<td>" + data[i]['stoke']['name'] + "</td>" +
                            "<td>" + data[i]['product']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['qte'] /data[i]['relation_qte'] ) + data[i]['product_unit']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['price_make'] * data[i]['relation_qte']) + 'ج' + "</td>" +
                            "<td>" + roundTo(totalPrice) + 'ج' + "</td>" +
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
                    $('#txtSearch').trigger('keyup');
                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    design.useSound('success');
                    getSumForTotalAndAndSetIndexAndTotalInMainTable();
                    $('#btnGetStokeData').removeAttr('disabled');
                    design.useToolTip();
                },
                error: function (e) {
                    disableGetProductStokeDetails = false;
                    $('#divDetails').addClass('d-none');
                    alert('error');
                    getSumForTotalAndAndSetIndexAndTotalInMainTable();
                    design.useSound('error');
                    console.log(e);
                    $('#btnGetStokeData').removeAttr('disabled');
                }
            });
        }

        @if (!isset($result))
        getData();
        @else
        design.useSound();
        alertify.success('تم عرض الإنتاج بنجاح');
        @endif

        $('#btnGetStokeData').click(function () {
            getData();
        });
        $('#select_stoke_id').change(function () {
            $('#mainTable tbody').html('');
            alertify.error('برجاء الضغط على زر بحث الإنتاج فى المخزن المحدد فى الفترة المحددة للبحث');
            getSumForTotalAndAndSetIndexAndTotalInMainTable();
            design.useSound('info');
        });

        //attribute to prevent loop get details
        var disableGetProductStokeDetails = false;

        //get stoke details data
        function getDetails(make_id, product_name,product_unit) {
            $('#divDetails').addClass('d-none');
            if (disableGetProductStokeDetails) {
                alertify.success('برجاء الإنتظار جارى عرض التفاصيل');
                design.useSound('info');
                return false;
            }
            disableGetProductStokeDetails = true;
            $('#tableDetails tbody').html('');
            $.ajax({
                url: '{{route('makings.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getMakeDetailsData',
                    make_id: make_id,
                },
                dataType: 'JSON',
                success: function (data) {
                    disableGetProductStokeDetails = false;

                    $('#tableDetails tbody').html('');
                    $('#divDetails').removeClass('d-none');
                    $('#span_details_product_name').html(product_unit+' من المنتج '+ product_name);
                    for (i = 0; i < data.length; i++) {
                        $('#tableDetails tbody').append(
                            "<tr class='table-success'>" +
                            "<td>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['store_with_product']['product']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['qte']) + data[i]['store_with_product']['product']['product_unit']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['store_with_product']['price']) + 'ج' + "</td>" +
                            "<td>" + roundTo(data[i]['qte'] * data[i]['store_with_product']['price']) + "</td>" +
                            "</tr>"
                        );
                    }

                    design.updateNiceScroll();
                    design.useToolTip();
                    alertify.success('تم عرض التفاصيل بنجاح');
                    design.useSound('success');
                },
                error: function (e) {
                    disableGetProductStokeDetails = false;
                    alert('error');
                    design.useSound('error');
                    design.updateNiceScroll();
                    console.log(e);
                }
            });
        }

        //get qte details
        $('#mainTable').on('click', 'tbody td[data-id]', function () {
            $(this).parent().addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
            getDetails($(this).attr('data-id'), $(this).attr('data-product_name'),$(this).attr('data-product_unit'));
        });

        //print main table
        $('#printMainTable').click(function () {
            design.useSound();
            alertify.success('برجاء الإنتظار جارى الطباعة!');
            $('#mainTable').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });
        //print table details
        $('#printDetailsTable').click(function () {
            $(this).addClass('d-none');
            design.useSound();
            alertify.success('برجاء الإنتظار جارى الطباعة!');
            $('#tableDetails').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
            $(this).removeClass('d-none');
        });

        @if(Auth::user()->type==1||Auth::user()->allow_delete_make)
        $('#mainTable').on('click', 'tbody tr a[data-delete]', function (e) {
            var id = $(this).attr('data-delete');
            var parent = $(this).parent().parent();

            parent.addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد العملية المحدده؟",
                title: "حذف عملية",
                confirm: function (button) {
                    var action = $('#form_delete_make').attr('action');
                    action = action.replace(/[0-9]$/, id);
                    $('#form_delete_make').attr('action', action).submit();
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
