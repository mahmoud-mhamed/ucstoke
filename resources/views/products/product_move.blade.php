<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    حركة المنتجات
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
                <h1 class='font-weight-bold pb-3 text-white'>حركة المنتجات</h1>
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
                                <span class='input-group-text font-weight-bold w-100 d-block'>إسم المنتج</span>
                            </div>
                            <div class="input-group-append col-7" style="direction: rtl;text-align: right" >
                                <select id="select_account_name"  style="direction: rtl;text-align: right" class="selectpicker form-control show-tick" data-live-search="true"
                                        data-filter-col="11">
                                    <option data-style="padding-bottom: 50px!important;" value="">الكل</option>
                                    @foreach($products as $p)
                                        <option data-style="padding-bottom: 50px!important;" value="{{$p->id}}"
                                                data-subtext="{{$p->allow_buy?'(شراء)':''}}{{$p->allow_sale?'(بيع)':''}}{{$p->allow_no_qte?'(بدون كمية)':''}}{{$p->allow_make?'(إنتاج)':''}}">{{$p->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="continerGetData" class="input-group-append col">
                                <button class="btn-primary btn font-weight-bold pointer" id="btnGetByDateCreate"><span
                                        class="h5">بحث بوقت الحركة للمنتج المحدد</span></button>
                            </div>
                        </div>
                        <div class="input-group  table-filters">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>المخزن</span>
                            </div>
                            <div class="input-group-append">
                                <select class="selectpicker" data-live-search="true"
                                        data-filter-col="2">
                                    <option value=''>الكل</option>
                                    @foreach ($stokes as $s)
                                        <option value='{{$s->name}}'>{{$s->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>الجهاز</span>
                            </div>
                            <div class="input-group-append">
                                <select  class="selectpicker" data-live-search="true"
                                        data-filter-col="3">
                                    <option value=''>الكل</option>
                                    @foreach ($devices as $d)
                                        <option value='{{$d->name}}'>{{$d->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>نوع العملية</span>
                            </div>
                            <div class="input-group-append">
                                <select id="select_type" class="selectpicker" data-live-search="true"
                                        data-filter-col="9">
                                    <option value=''>الكل</option>
                                    <option value='فاتورة شراء'>فاتورة شراء</option>
                                    <option value='فاتورة بيع'>فاتورة بيع</option>
                                    <option value='إنتاج'>عملية إنتاج</option>
                                    <option value='تالف شراء'>تالف شراء</option>
                                    <option value='تالف إنتاج'>تالف عرض أو إنتاج</option>
                                    <option value='إستخدام فى الإنتاج'>إستخدام فى الإنتاج</option>
                                    <option value='مرتجع شراء' data-subtext="(إخذ مال , خصم من الحساب)">مرتجع شراء</option>
                                    <option value='مرتجع بيع' data-subtext="(إخذ مال , خصم من الحساب)">مرتجع بيع</option>
                                    <option value='مرتجع إستبدال شراء' data-subtext="(إستبدال)">مرتجع شراء إستبدال</option>
                                    <option value='مرتجع إستبدال بيع' data-subtext="(إستبدال)">مرتجع بيع إستبدال</option>
                                    <option value='نقل من المخزن'>نقل من المخزن</option>
                                    <option value='نقل إلى المخزن'>نقل إلى المخزن</option>
                                    <option value='عند إنشاء المنتج'>عند إنشاء المنتج</option>
                                    {{--<option value='(فاتورةشراء)الكميةقبل تعديل'>(فاتورةشراء)الكميةقبل تعديل</option>
                                    <option value='(فاتورةبيع)الكميةقبل تعديل'>(فاتورةبيع)الكميةقبل تعديل</option>
                                    <option value='(فاتورةشراء)الكميةبعدالتعديل'>(فاتورةشراء)الكميةبعدالتعديل</option>
                                    <option value='(فاتورةبيع)الكميةبعدالتعديل'>(فاتورةبيع)الكميةبعدالتعديل</option>--}}
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>العلاقة بالمخزن</span>
                            </div>
                            <div class="input-group-append">
                                <select class="selectpicker" data-live-search="true"
                                        data-filter-col="13">
                                    <option value=''>الكل</option>
                                    <option value='1'>إضافة للمخزن</option>
                                    <option value='2'>نقص من المخزن</option>
                                    <option value='3'>لم توثر فى المخزن</option>
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
                        <label class="checkbox-inline pl-4" dir="ltr">المخزن<input type="checkbox" data-toggle="2"
                                                                                        value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الجهاز<input type="checkbox" data-toggle="3"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">وقت العملية<input type="checkbox"
                                                                                          data-toggle="4"
                                                                                          value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإسم<input type="checkbox" data-toggle="5"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الكمية<input type="checkbox" data-toggle="6"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">السعر<input type="checkbox" data-toggle="7"
                                                                                       checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإجمالى<input type="checkbox" data-toggle="8"
                                                                                    checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">نوع العملية<input type="checkbox" data-toggle="9"
                                                                                   checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">رقم العملية<input type="checkbox" data-toggle="10"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="11"
                                                                                     checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="12"
                                                                                               checked value=""></label>
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
                    <div class='box-shadow tableFixHead table-responsive text-center'>
                        <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م
                                    <span id="countRowsInTable" class="font-en"></span>
                                </th>
                                <th>المستخدم</th>
                                <th>المخزن</th>
                                <th>الجهاز</th>
                                <th>وقت العملية</th>
                                <th>إسم المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالى
                                    <span id="span_total_val" class="font-en tooltips" data-placement='right'
                                          title='إجمالى المبلغ لنتيجة البحث'></span>
                                </th>
                                <th>نوع العملية</th>
                                <th>رقم العملية</th>
                                <th>ملاحظة</th>
                                <th>العمليات</th>
                                <th class="d-none">relation to stoke</th>
                            </tr>
                            </thead>
                            <tbody class="h4">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        @if(Auth::user()->type==1||Auth::user()->allow_delete_damage)
            <form action='{{route('stores.destroy',0)}}' id='form_delete_damage' class='d-none' method='post'>
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
                    totalValue -= -$(this).attr('data-value');
                }
            });
            $('#span_total_val').html(roundTo(totalValue * 1,{{$setting->use_small_price?'3':'2'}})+'ج');
            $('#countRowsInTable').html(counterRow);
        }

        function getData() {
            $('#continerGetData button').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');

            $.ajax({
                url: '{{route('product_moves.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getProductMove',
                    dateFrom: $('#dateFrom').val(),
                    dateTo: $('#dateTo').val(),
                    product_id: $('#select_account_name').val(),
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var typeOpration='';
                        //set typeOpertation
                        if (data[i]['type'] == 0){
                            typeOpration='فاتورة شراء';
                        }else if(data[i]['type'] == 1){
                            typeOpration='فاتورة بيع';
                        }else if(data[i]['type'] == 2){
                            typeOpration='تالف شراء';
                        }else if(data[i]['type'] == 3){
                            typeOpration='تالف إنتاج';
                        }else if(data[i]['type'] == 4){
                            typeOpration='إنتاج';
                        }else if(data[i]['type'] == 5){
                            typeOpration='إستخدام فى الإنتاج';
                        }else if(data[i]['type']==6){
                            typeOpration='مرتجع شراء';
                        }else if(data[i]['type']==7){
                            typeOpration='مرتجع بيع';
                        }else if(data[i]['type']==8){
                            typeOpration='مرتجع إستبدال شراء';
                        }else if(data[i]['type']==9){
                            typeOpration='مرتجع إستبدال بيع';
                        }else if(data[i]['type']==10){
                            typeOpration='نقل من المخزن';
                        }else if(data[i]['type']==11){
                            typeOpration='نقل إلى المخزن';
                        }else if(data[i]['type']==12){
                            typeOpration='(فاتورة شراء)الكميةقبل تعديل';
                        }else if(data[i]['type']==13){
                            typeOpration='(فاتورة بيع)الكميةقبل تعديل';
                        }else if(data[i]['type']==14){
                            typeOpration='(فاتورة شراء)الكميةبعدالتعديل';
                        }else if(data[i]['type']==15){
                            typeOpration='(فاتورة بيع)الكميةبعدالتعديل';
                        }else if(data[i]['type']==16){
                            typeOpration='عند إنشاء المنتج';
                        }


                        var opertation='',opertationIndex='',opertationText='';//opertationIndex 1 => for add qte to stoke,2 for subtract qte from stoke,3 for don't update stoke
                        if(data[i]['type']==0||data[i]['type']==4||data[i]['type']==7||data[i]['type']==11||data[i]['type']==14){
                            opertation='<i class="fas text-primary ml-2 fa-plus"></i>';
                            opertationIndex=1;
                            opertationText='أضاف للمخزن';
                        }else if(data[i]['type']==1||data[i]['type']==2||data[i]['type']==3||data[i]['type']==5||data[i]['type']==6||data[i]['type']==10 ||data[i]['type']==15){
                            opertation='<i class="fas text-warning ml-2 fa-minus"></i>';
                            opertationIndex=2;
                            opertationText='أنقص من للمخزن';
                        }else if(data[i]['type']==8||data[i]['type']==9 ||data[i]['type']==12 ||data[i]['type']==13){
                            opertation='<i class="fas ml-2 text-white fa-circle"></i>';
                            opertationIndex=3;
                            opertationText='لم يؤثر فى المخزن';
                        }

                        var qteByRelationUnit=data[i]['qte']/data[i]['relation_qte'];
                        var priceByRelationUnit=data[i]['price']*data[i]['relation_qte'];

                        var showBill='';
                        if(data[i]['bill_id']!=null){
                            if(data[i]['type']==0){
                                showBill='<a class="font-en pointer btn btn-primary tooltips" data-placement="left" title="عرض الفاتورة"  href="{{route("bills.index",0)}}?bill_id='+data[i]['bill_id']+'">'+data[i]['bill_id']+'</a>';
                            }else{
                                showBill='<a class="font-en pointer btn btn-primary tooltips" data-placement="left" title="عرض الفاتورة"  href="{{route("bills.index",1)}}?bill_id='+data[i]['bill_id']+'">'+data[i]['bill_id']+'</a>';
                            }
                        }
                        var showMake='';
                        if(data[i]['make_id']!=null){
                            showMake='<a class="font-en pointer btn btn-primary tooltips" data-placement="left" title="عرض الإنتاج"  href="{{route("makings.index")}}?make_id='+data[i]['make_id']+'">'+data[i]['make_id']+'</a>';
                        }

                        var  delete_damage = '';

                        @if(Auth::user()->type==1||Auth::user()->allow_delete_damage)
                            if(data[i]['type']==2 || data[i]['type']==3){
                            delete_damage = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم إرجاع الكمية للمخزن المحزوفة منه' data-delete_damage='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                        @endif
                        $('#mainTable tbody').append(
                            "<tr class='table-success' data-value='"+(priceByRelationUnit*qteByRelationUnit)+"'>" +
                            "<td data-id='" + data[i]['id'] + "' data-qte_main_unit='"+data[i]['qte']+"' data-total_price_by_relation_unit='"+(priceByRelationUnit*qteByRelationUnit)+"'>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['user']['name'] + "</td>" +
                            "<td>" + (data[i]['stoke']!=null?data[i]['stoke']['name']:'') + "</td>" +
                            "<td>" + data[i]['device']['name'] + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td>" + data[i]['product']['name'] + "</td>" +
                            "<td class='tooltips' data-placement='left' title='"+opertationText+"'>" +opertation+ roundTo(qteByRelationUnit,{{$setting->use_small_price?'3':'2'}})+data[i]['product_unit']['name'] + "</td>" +
                            "<td>" + roundTo(priceByRelationUnit,{{$setting->use_small_price?'3':'2'}})+"</td>" +
                            "<td>" + roundTo(priceByRelationUnit*qteByRelationUnit,{{$setting->use_small_price?'3':'2'}})+"</td>" +
                            "<td>" + typeOpration+"</td>" +
                            "<td>" + showBill+showMake+"</td>" +
                            "<td>" + (data[i]['note']==null?'':data[i]['note'])+"</td>" +
                            "<td class='text-nowrap'>" +
                            delete_damage +
                            "</td>" +
                            "<td class='d-none'>" + opertationIndex + "</td>" +
                            "</tr>"
                        );
                    }
                    design.hide_option_not_exist_in_table_in_select($('#select_type'),
                        $('#mainTable tbody tr'),9,true);
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
                    $('.table-filters input,.table-filters select').trigger('change');
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

        @if(Auth::user()->type==1||Auth::user()->allow_delete_damage)
        $('#mainTable').on('click', 'tbody tr a[data-delete_damage]', function (e) {
            var id = $(this).attr('data-delete_damage');
            var parent = $(this).parent().parent();
            parent.addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد حذف التالف المحدد سيتم إرجاع الكمية للمخزن ؟",
                title: "حذف تالف",
                confirm: function (button) {
                    var action = $('#form_delete_damage').attr('action');
                    action = action.replace(/[0-9]$/, id);
                    $('#form_delete_damage').attr('action', action).submit();
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
