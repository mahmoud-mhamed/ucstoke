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
    المنتجات فى المخازن
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
        #section_add_damage div.error-qte{
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
            padding-top: 5px
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
                <h1 class='font-weight-bold pb-3 text-white'>المنتجات فى المخازن</h1>
                <div class=''>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>المخزن</span>
                        </div>
                        <div class="input-group-append">
                            <select id="select_stoke_id" data-counter_stoke="{{count($stokes)}}" class="selectpicker" data-live-search="true"
                                    data-filter-col="16">
                                @foreach ($stokes as $s)
                                    <option value='{{$s->stoke_id}}' {{\Auth::user()->device->default_stoke==$s->stoke_id?'selected':''}}>{{$s->stoke->name}}</option>
                                @endforeach
                                @if (Auth::user()->type==1 || Auth::user()->allow_access_product_in_all_stoke)
                                        <option value='0' data-subtext="المخازن المتاحة">الكل</option>
                                    @endif
                            </select>
                        </div>
                        <button class="btn-success btn font-weight-bold pointer" id="btnGetStokeData"><span
                                class="h5">بحث المنتجات فى المخزن</span></button>
                    </div>
                    <div class="input-group table-filters">
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>قسم المنتج</span>
                        </div>
                        <div class="input-group-append">
                            <select id="select_product_categories" class="selectpicker" data-live-search="true"
                                    data-filter-col="2">
                                <option value=''>الكل</option>
                                @foreach ($categories as $c)
                                    <option value='{{$c->name}}'>{{$c->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>حالة المنتج</span>
                        </div>
                        <div class="input-group-append">
                            <select id="select_product_state" class="selectpicker" data-live-search="true"
                                    data-filter-col="11">
                                <option value=''>الكل</option>
                                <option value='1'>مفعل</option>
                                <option value='0'>غير مفعل</option>
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>المنتجات الخاصة</span>
                        </div>
                        <div class="input-group-append">
                            <select id="select_special_product" class="selectpicker" data-live-search="true"
                                    data-filter-col="12">
                                <option value=''>الكل</option>
                                <option value='1'>المنتجات الخاصة</option>
                                <option value='0'>المنتجات غير الخاصة</option>
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>المنتجات فى المخزن</span>
                        </div>
                        <div class="input-group-append">
                            <select id="selectStateExistInStoke" class="selectpicker" data-live-search="true"
                                    data-filter-col="13">
                                <option value=''>الكل</option>
                                <option selected value='1'>المنتجات المتوفرة فى المخزن</option>
                                <option value='0'>المنتجات الغير متوفرة فى المخزن</option>
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>نواقص المخزن</span>
                        </div>
                        <div class="input-group-append">
                            <select id="selectStateMinQte" class="selectpicker" data-live-search="true"
                                    data-filter-col="14">
                                <option value=''>الكل</option>
                                <option value='0'>المنتجات الناقصة</option>
                                <option value='1'>المنتجات الغير ناقصة</option>
                            </select>
                        </div>
                        <input id="txtSearch" style="min-width: 150px" type='text'
                               data-filter-col="0,1,2,3,4,5,6,7,8,9,10,11,12,13"
                               placeholder='ابحث في المنتجات باى بيانات ' class='form-control'>
                    </div>
                </div>
                <div id='divDetailsDetails' class='mb-2 tableFixHead table-responsive border-radius d-none'>
                    <h3 class='text-white h2'>تفاصيل مصدر المنتج
                        <span id="span_details_details_product_name" class="font-en text-danger text"
                              style="text-decoration: underline"></span>
                    </h3>
                    <table id="tableDetailsDetails" class='main-table sorted m-0 table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م</th>
                            <th>وقت العملية</th>
                            <th>الرقم</th>
                            <th>الكمية</th>
                            <th>ملاحظة</th>
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
                <div id='divDetails' class='mb-2 tableFixHead table-responsive border-radius d-none'>
                    <h3 class='text-white h2'>تفاصيل الكمية فى المنتج
                        <span id="span_details_product_name" class="font-en text-danger text"
                              style="text-decoration: underline"></span>
                        <button id="printDetailsTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1" data-placement="bottom" title="طباعة التفاصيل"><span class="h3"><i class="fas fa-print"></i></span>
                        </button>
                    </h3>
                    <table id="tableDetails" class='main-table sorted m-0 table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م</th>
                            <th>النوع</th>
                            <th>الكمية</th>
                            <th>السعر</th>
                            <th>اجمالي السعر</th>
                            <th>العمليات</th>
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
                <div class="h3 text-white" id="columFilter" dir="rtl">
                    <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                          value=""></label>

                    <label class="checkbox-inline pl-4" dir="ltr">إسم المنتج<input type="checkbox"
                                                                                   checked data-toggle="1"
                                                                                   value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">قسم المنتج<input type="checkbox" data-toggle="2"
                                                                                   value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">حالة المنتج<input type="checkbox"
                                                                                    data-toggle="3"
                                                                                    value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">منتج خاص<input type="checkbox" data-toggle="4"
                                                                                 value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">أقل عدد من المنتج<input type="checkbox"
                                                                                          data-toggle="5"
                                                                                          checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">الكمية<input type="checkbox" data-toggle="6"
                                                                               checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">متوسط السعر<input type="checkbox" data-toggle="7"
                                                                               checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">إجمالى السعر<input type="checkbox" data-toggle="8"
                                                                                     checked value=""></label>
                    <label class="checkbox-inline pl-4 {{Hash::check('place_product',$permit->place_product)?'':'permits_hide'}}" dir="ltr">المكان فى المخزن<input type="checkbox" data-toggle="9"
                                                                                                                                                                   {{Hash::check('place_product',$permit->place_product)?'checked':''}} value=""></label>
                    <label class="checkbox-inline pl-4 d-none" dir="ltr">العمليات<input type="checkbox" data-toggle="10"
                                                                                  value=""></label>

                    <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                    type="checkbox"
                                                                                    checked value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">إجمالى السعر فى النتيجة<input
                            id="checkSumPriceRowsInTable"
                            type="checkbox"
                            checked value=""></label>
                    <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1" data-placement="bottom" title="طباعة النتيجة">
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
                            <th>إسم المنتج</th>
                            <th>قسم المنتج</th>
                            <th>حالة المنتج</th>
                            <th>منتج خاص</th>
                            <th>أقل عدد من المنتج</th>
                            <th>الكمية</th>
                            <th>متوسط السعر</th>
                            <th>
                                إجمالى السعر
                                <span id="span_total_price_in_main_table" class="font-en tooltips"
                                      data-placement='right'
                                      title='مجموع إجمالى سعر الكمية الموجودة لنتيجة البحث بسعر الشراء أو الإنتاج'></span>
                            </th>
                            <th {{Hash::check('place_product',$permit->place_product)?'':'permits_hide'}}>المكان فى المخزن</th>
                            <th>العمليات</th>
                            <th class="d-none">state product_state active</th>
                            <th class="d-none">state product_special</th>
                            <th class="d-none">state exist in stoke</th>
                            <th class="d-none">state min qte</th>
                        </tr>
                        </thead>
                        <tbody class="h4">

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        @if(Auth::user()->type==1||Auth::user()->allow_add_damage)
            <section id="section_add_damage" style="width: 100vw;height: 100vh;top: 0px;left: 0px;z-index: 1" class="position-fixed text-center d-none">
                <div id="div_damage_overlay" class="position-fixed" style="z-index:1;left: 0px;top:0px;width: 100vw;height: 100vh;background: rgba(0,0,0,.7)"></div>
                <div class="container position-relative box mx-aut text-center pt-3 px-2" style="z-index: 2;top:10px;max-width: 800px">
                    <form id="formAddDamage" action="{{route('stores.addDamage')}}" method="post">
                        @csrf
                        <input type="hidden" id="input_damage_store_id" required name="store_id">
                        <h1 class="h1">إضافة تالف للمنتج
                            <span id="span_damage_product_name" class="text-danger" style="text-decoration: underline"></span>
                        </h1>
                        <h2>الكمية المراد إضافة تالف منها هى
                            <span id="span_damage_qte_name" class="text-danger font-en" style="text-decoration: underline"></span>
                            حيث سعر الشراء لها هو
                            <span id="span_damage_qte_price" class="text-danger font-en" style="text-decoration: underline"></span>
                        </h2>
                        <div class='form-group mb-3 h2 mt-4'>
                            <div class="row no-gutters">
                                <label class='col-sm-5 text-md-left pt-2 pl-3'>الكمية التالفة فى المنتج</label>
                                <div class='col-sm-7'>
                                    <div class="input-group">
                                        <input type='text'
                                               id="input_add_damage"
                                               style="height: 45px"
                                               data-max="0"
                                               data-validate='qte' data-patternType='qte'
                                               onclick="$(this).select();" autofocus required
                                               name='qte_damage'
                                               class='form-control pr-5'>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="span_damage_qte_name2">جنية</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row no-gutters mt-2">
                                <label class='col-sm-5 text-md-left pt-2 pl-3'>مـــلاحظة أو سبب التالف</label>
                                <div class='col-sm-7'>
                                    <textarea name="note" id="" class="form-control" style="font-size: 1.5rem!important;" placeholder="سبب التالف أو ملاحظة للعملية" cols="30" rows="2"></textarea>
                                </div>
                            </div>
                            <div class='form-group row mt-3'>
                                <div class='col-sm-6'>
                                    <button type='submit'
                                            class='font-weight-bold mt-2 mb-2 form-control btn btn-success'>
                                        <span class='h3 font-weight-bold'>إضــــــافة التالف</span>
                                    </button>
                                </div>
                                <div class='col-sm-6'>
                                    <button type='button' onclick="$('#section_add_damage').addClass('d-none');design.useSound();alertify.success('تم الإلغاء بنجاح');"
                                            class='font-weight-bold mt-2 mb-2 form-control btn btn-success'>
                                        <span class='h3 font-weight-bold'>إلغاء العملية</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        @endif
        {{--@if(Auth::user()->type==1||Auth::user()->allow_delete_account)
            <form action='{{route('account_calculation.destroy',0)}}' id='form_delete_account' class='d-none'
                  method='post'>
                @csrf
                @method('delete')
            </form>
        @endif--}}
    </main>

@endsection

@section('js')
    <script defer>
        design.useNiceScroll();
        validateByAttr();
        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getSumForTotalAndAndSetIndexAndTotalInMainTable();
            $('#divDetails').addClass('d-none');
            $('#tableDetails tbody').html('');
            design.useToolTip();
            updateAveragePrice();
        });

        function updateAveragePrice(){
            $('#mainTable tbody tr').each(function () {
                var qte=$(this).find('option:selected').attr('value');
                var child_td=$(this).children();
                var price=child_td.eq(8).html();
                if(qte > 0 && price > 0){
                    child_td.eq(7).html(roundTo(price / qte),{{$setting->use_small_price?'3':'2'}});
                }else{
                    child_td.eq(7).html('');
                }
            });
        }

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
            $('#span_total_price_in_main_table').html(roundTo(totalPriceForExistQte * 1,{{$setting->use_small_price?'3':'2'}}));
        }


        //get stoke data
        function getData() {
            disableGetProductStokeDetails = false;
            $('#btnGetStokeData').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');
            $('#tableDetails tbody').html('');
            $('#divDetails,#divDetailsDetails').addClass('d-none');
            var stoke_id = $('#select_stoke_id').val();
            if (stoke_id == '') {
                design.useSound('error');
                alertify.error('هذا الجهاز غير مصرح له بالوصول إلى إى مخزن !');
                $('#btnGetStokeData').removeAttr('disabled');
                return;
            }
            $.ajax({
                url: '{{route('stores.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getStokeData',
                    stoke_id: stoke_id,
                },
                dataType: 'JSON',
                success: function (data) {
                    disableGetProductStokeDetails = false;
                    $('#divDetails').addClass('d-none');
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var totalQte = 0;
                        var totalPrice = 0;
                        var typeProductState = '<i class="fas fa-times"></i>';
                        var typeProdcutStateBg = 'bg-danger';
                        var typeProductSpecial = '<i class="fas fa-times"></i>';
                        var typeProductSpecialBg = 'bg-danger';
                        var typeProductStateNumber = 0;
                        var typeProductSpecialNumber = 0;
                        var product_place_name = '';
                        var product_place_id = '';
                        var var_option_unit = '';
                        if (data[i]['state'] == 1) {
                            typeProductState = '<i class="fas fa-check"></i>';
                            typeProdcutStateBg = '';
                            typeProductStateNumber = 1;
                        }
                        if (data[i]['special'] == 1) {
                            typeProductSpecial = '<i class="fas fa-check"></i>';
                            typeProductSpecialBg = '';
                            typeProductSpecialNumber = 1;
                        }
                        if (data[i]['place'] != '') {
                            for (var j = 0; j < data[i]['place'].length; j++) {
                                if (stoke_id == data[i]['place'][j]['stoke_id']) {
                                    product_place_id = data[i]['place'][j]['id'];
                                    product_place_name = data[i]['place'][j]['place_name']['name'];
                                    break;
                                }
                            }
                        }

                        //get totalQte and total price
                        for (let j = 0; j < data[i]['store'].length; j++) {
                            totalQte -= -data[i]['store'][j]['qte'];
                            totalPrice -= -data[i]['store'][j]['qte'] * data[i]['store'][j]['price'];
                        }

                        //create select to show data with multi unit
                        var_option_unit += "<option value='"+roundTo(totalQte * 1,{{$setting->use_small_price?'3':'2'}})+"'>" + roundTo(totalQte * 1,{{$setting->use_small_price?'3':'2'}}) + ' ' + data[i]['product_unit']['name'] + "</option>";
                        for (let j = 0; j < data[i]['relation_product_unit'].length; j++) {
                            var option_value= roundTo(totalQte / data[i]['relation_product_unit'][j]['relation_qte'],{{$setting->use_small_price?'3':'2'}});
                            var_option_unit += "<option value='"+option_value+"'>" + option_value + ' ' + data[i]['relation_product_unit'][j]['product_unit']['name'] + "</option>";
                        }

                        var delete_row = '';

                        {{--@if(Auth::user()->type==1||Auth::user()->allow_delete_account)
                        if (data[i]['type'] == 1) {
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم خصم المبلغ من الدرج وإضافتة للحساب الحساب' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                        if (data[i]['type'] == 2) {
                            delete_row = " <a class='btn btn-sm p-0 tooltips'  data-placement='left' title='سيتم إضافة المبلغ إلى الدرج وإضافتة للحساب' data-delete='" + data[i]['id'] + "'><i class='fas fa-2x text-danger fa-trash-alt'></i></a>";
                        }
                        @endif--}}
                        $('#mainTable tbody').append(
                            "<tr class='table-success'>" +
                            "<td class='pointer tooltips' data-placement='left' title='"+(totalQte==0?'المنتج غير موجود فى المخزن':'إضغط لعرض تفاصيل الكمية')+"' data-qte='" + totalQte + "' data-product_id='" + data[i]['id'] + "' data-total_price='" + totalPrice + "'>" + (i - -1) + "</td>" +
                            "<td>" + data[i]['name'] + "</td>" +
                            "<td>" + data[i]['product_category']['name'] + "</td>" +
                            "<td class='" + typeProdcutStateBg + "'>" + typeProductState + "</td>" +
                            "<td class='" + typeProductSpecialBg + "'>" + typeProductSpecial + "</td>" +
                            "<td class='" + (totalQte < data[i]['min_qte'] ? 'bg-danger' : '') + "'>" + data[i]['min_qte'] + ' ' + data[i]['product_unit']['name'] + "</td>" +
                            "<td>" +
                            '<select onchange="updateAveragePrice();" class="custom-select" style="min-width: 150px">' + var_option_unit + '</select>' +
                            "</td>" +
                            "<td></td>"+
                            "<td>" + roundTo(totalPrice * 1,{{$setting->use_small_price?'3':'2'}}) + "</td>" +
                            "<td class='{{Hash::check('place_product',$permit->place_product)?'':'permits_hide'}}'>" + product_place_name + "</td>" +
                            "<td class='text-nowrap'>" +
                            // delete_row +
                            "</td>" +
                            "<td class='d-none'>" + typeProductStateNumber + "</td>" +
                            "<td class='d-none'>" + typeProductSpecialNumber + "</td>" +
                            "<td class='d-none'>" + (totalQte == 0 ? 0 : '1') + "</td>" +
                            "<td class='d-none'>" + (totalQte < data[i]['min_qte'] ? 0 : 1) + "</td>" +
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
                    $('#select_product_categories,#select_product_state,#select_special_product,#selectStateExistInStoke,#selectStateMinQte').trigger('change');
                    $('#txtSearch').trigger('keyup');
                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    alertify.success('برجاء الضغط على مسلسل أى سطر لعرض تفاصيل الكمية الموجودة فى هذا السطر أو إضافة تالف !');
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

        getData();

        $('#btnGetStokeData').click(function () {
            getData();
        });
        $('#select_stoke_id').change(function () {
            $('#mainTable tbody').html('');
            $('#divDetails,#divDetailsDetails').addClass('d-none');
            $('#tableDetails tbody').html('');
            alertify.error('برجاء الضغط على زر بحث المنتجات فى المخزن المحدد للبحث');
            getSumForTotalAndAndSetIndexAndTotalInMainTable();
            design.useSound('info');
        });

        //attribute to prevent loop get details
        var disableGetProductStokeDetails = false;

        //get stoke details data
        function getDetails(product_id) {
            $('#divDetails,#divDetailsDetails').addClass('d-none');
            if($('#select_stoke_id').val()==0){
                alertify.error('يجب تحديد مخزن عند عرض تفاصيل الكمية فى المخزن!');
                design.useSound('error');
                return ;
            }
            if (disableGetProductStokeDetails) {
                alertify.success('برجاء الإنتظار جارى عرض التفاصيل');
                design.useSound('info');
                return false;
            }
            disableGetProductStokeDetails = true;
            $('#tableDetails tbody').html('');
            var stoke_id = $('#select_stoke_id').val();

            $.ajax({
                url: '{{route('stores.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getStokeDetailsData',
                    stoke_id: stoke_id,
                    product_id: product_id,
                },
                dataType: 'JSON',
                success: function (data) {
                    disableGetProductStokeDetails = false;
                    //if product not exist in store
                    if (data[0]['store'].length == 0) {
                        return;
                    }
                    $('#tableDetails tbody').html('');
                    $('#divDetails').removeClass('d-none');

                    $('#span_details_product_name').html(data[0]['name']);
                    $('#span_details_details_product_name').html(data[0]['name']);
                    var counterRow = 0;
                    for (i = 0; i < data[0]['store'].length; i++) {
                        var qteByMainUnit = data[0]['store'][i]['qte'];
                        if (roundTo(qteByMainUnit) == 0 ) {
                            continue;
                        }
                        counterRow -= -1;
                        var priceByMainUnit = data[0]['store'][i]['price'];
                        var typeQte = data[0]['store'][i]['type'] == 0 ? 'شراء' : 'إنتاج';
                        var var_option_unit = '';
                        //create select to show data with multi unit
                        var_option_unit += "<option data-option_price='" + priceByMainUnit + "' data-option_qte='" + qteByMainUnit + "'>" + roundTo(qteByMainUnit * 1,{{$setting->use_small_price?'3':'2'}}) + ' ' + data[0]['product_unit']['name'] + "</option>";
                        for (let j = 0; j < data[0]['relation_product_unit'].length; j++) {
                            var tempQte = qteByMainUnit / data[0]['relation_product_unit'][j]['relation_qte'];
                            var tempPrice = priceByMainUnit * data[0]['relation_product_unit'][j]['relation_qte'];
                            var_option_unit += "<option data-option_price='" + tempPrice + "'>" + roundTo(tempQte,{{$setting->use_small_price?'3':'2'}}) + ' ' + data[0]['relation_product_unit'][j]['product_unit']['name'] + "</option>";
                        }

                        var damaged='';
                        @if(Auth::user()->type==1||Auth::user()->allow_add_damage)
                            damaged="<button class='btn btn-danger' data-add_damage " +
                            "data-store_id='"+ data[0]['store'][i]['id'] +"' data-product_name='"+data[0]['name']+"'" +
                            " data-main_unit_name='"+data[0]['product_unit']['name']+"' " +
                            "data-main_qte='"+roundTo(qteByMainUnit * 1,{{$setting->use_small_price?'3':'2'}})+"' data-main_price='"+data[0]['store'][i]['price']+"' type='button'><span class='h2'>إضافة تالف</span> <i class='fas fa-exclamation-triangle'></i></button>";
                        @endif
                            var move='';
                            @if(Auth::user()->type==1 || Auth::user()->allow_move_product_in_stoke)
                            if($('#select_stoke_id').attr('data-counter_stoke')>1){
                                move="<a href='stores/" + data[0]['store'][i]['id'] + "/edit' class='btn btn-success ml-2 tooltips' data-placement='right' title='نقل الكمية أو جزء منها إلى مخزن أخر' " +
                                "data-store_id='"+ data[0]['store'][i]['id'] +"'><span class='h2'>نقل إلى مخزن أخر</span> <i class=\"fas fa-shipping-fast\"></i></a>";
                            }
                            @endif
                        $('#tableDetails tbody').append(
                            "<tr class='table-success'>" +
                            "<td data-id_store='" + data[0]['store'][i]['id'] + "' class='pointer tooltips' data-placement='left' title='إضغط لعرض أخر 5 مصادر للكمية المحددة (5 شراء - 5 إنتاج)' >" + counterRow + "</td>" +
                            "<td>" + typeQte + "</td>" +
                            "<td>" +
                            '<select class="custom-select" style="min-width: 150px">' + var_option_unit + '</select>' +
                            "</td>" +
                            "<td></td>" +
                            "<td>" + roundTo(priceByMainUnit * qteByMainUnit,{{$setting->use_small_price?'3':'2'}}) + "</td>" +
                            "<td class='py-0'>"+move+damaged+"</td>" +
                            "</tr>"
                        );
                    }

                    $('#tableDetails tbody select').trigger('change');
                    design.updateNiceScroll();
                    design.useToolTip();
                    alertify.success('تم عرض التفاصيل بنجاح');
                    alertify.success('برجاء الضغط على مسلسل أى سطر لعرض فواتير الكمية!');

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
        $('#mainTable').on('click', 'tbody td[data-product_id]', function () {
            if ($(this).attr('data-qte') > 0){
                $(this).parent().addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
                getDetails($(this).attr('data-product_id'));

            }
        });

        //get qte detailsDetails
        $('#tableDetails').on('click', 'tbody td[data-id_store]', function () {
            $(this).parent().addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
            getDetailsDetails($(this).attr('data-id_store'));
        });

        //attribute to prevent loop get detailsDetails
        var disableGetProductStokeDetailsDetails = false;
        //get qte details bill or makeing data
        function getDetailsDetails(store_id) {
            $('#divDetailsDetails').addClass('d-none');
            if (disableGetProductStokeDetailsDetails) {
                alertify.success('برجاء الإنتظار جارى عرض مصدر الكمية');
                design.useSound('info');
                return false;
            }
            disableGetProductStokeDetailsDetails = true;
            $('#divDetailsDetails tbody').html('');

            $.ajax({
                url: '{{route('stores.getDate')}}',
                method: 'POST',
                data: {
                    type: 'getSourceForStore',
                    store_id: store_id,
                },
                dataType: 'JSON',
                success: function (data) {
                    disableGetProductStokeDetailsDetails = false;
                    $('#tableDetailsDetails tbody').html('');
                    $('#divDetailsDetails').removeClass('d-none');

                    //for bills
                    counterRow=0;
                    for (i = 0; i < data[0].length; i++) {
                        counterRow++;
                        var billId='<a class="font-en pointer btn btn-primary tooltips" data-placement="left" title="عرض فاتورة الشراء"  href="{{route("bills.index",0)}}?bill_id='+data[0][i]['bill']['id']+'">'+data[0][i]['bill']['id']+'</a>';

                        $('#tableDetailsDetails tbody').append(
                            "<tr class='table-success'>" +
                            "<td>" + counterRow + "</td>" +
                            "<td>" + data[0][i]['created_at'] + "</td>" +
                            "<td>"+billId+"</td>" +
                            "<td>" + data[0][i]['qte']+' ' +data[0][i]['product_unit']['name']+ "</td>" +
                            "<td>" + data[0][i]['bill']['message'] + "</td>" +
                            "</tr>"
                        );
                    }

                    //for moving
                    for (i = 0; i < data[1].length; i++) {
                        counterRow++;
                        $('#tableDetailsDetails tbody').append(
                            "<tr class='table-success'>" +
                            "<td>" + counterRow + "</td>" +
                            "<td>" + data[1][i]['created_at'] + "</td>" +
                            "<td>نقل</td>" +
                            "<td>" + data[1][i]['qte']+' '+data[1][i]['product_unit']['name'] + "</td>" +
                            "<td>" + data[1][i]['note'] + "</td>" +
                            "</tr>"
                        );
                    }

                    //for making
                    for (i = 0; i < data[2].length; i++) {
                        counterRow++;
                        $('#tableDetailsDetails tbody').append(
                            "<tr class='table-success'>" +
                            "<td>" + counterRow + "</td>" +
                            "<td>" + data[2][i]['created_at'] + "</td>" +
                            "<td>إنتاج</td>" +
                            "<td>" + roundTo(data[2][i]['qte']/ data[2][i]['relation_qte'],{{$setting->use_small_price?'3':'2'}})+' '+data[2][i]['product_unit']['name'] + "</td>" +
                            "<td>" + data[2][i]['note'] + "</td>" +
                            "</tr>"
                        );
                    }

                    design.updateNiceScroll();
                    design.useToolTip();
                    alertify.success('تم عرض أخر 5 مصادر للكمية المحددة بنجاح');
                    design.useSound('success');
                },
                error: function (e) {
                    disableGetProductStokeDetailsDetails = false;
                    alert('error');
                    design.useSound('error');
                    design.updateNiceScroll();
                    console.log(e);
                }
            });
        }

        //get price in table details for unit
        $('#tableDetails').on('change', 'tbody select', function () {
            $(this).parent().parent().children().eq(3).html(roundTo($(this).find('option:selected').attr('data-option_price'),{{$setting->use_small_price?'3':'2'}}));
        });

        @if(Auth::user()->type==1||Auth::user()->allow_add_damage)
        //add damage for qte in details table
        $('#tableDetails').on('click', 'tbody tr button[data-add_damage]', function (e) {
            $('#input_damage_store_id').val($(this).attr('data-store_id'));
            $('#span_damage_product_name').html($(this).attr('data-product_name'));
            $('#span_damage_qte_name').html($(this).attr('data-main_qte')+' '+$(this).attr('data-main_unit_name'));
            $('#span_damage_qte_name2').html($(this).attr('data-main_unit_name'));
            $('#span_damage_qte_price').html(roundTo($(this).attr('data-main_price')*1,{{$setting->use_small_price?'3':'2'}})+'ج');
            $('#input_add_damage').val('0').attr('data-max',$(this).attr('data-main_qte'));
            $('#section_add_damage').removeClass('d-none');
            alertify.error('يتم حساب التالف بال'+$(this).attr('data-main_unit_name'));
            design.useSound();
        });

        //disable submit in input qte damage
        $('#input_add_damage').on('keypress', function (e) {
            if (e.which == 13) {
                e.preventDefault();
            }
        });
        //submit add damage
        $('#formAddDamage').submit(function (e) {
            var input=$('#input_add_damage');
            var max_qte=input.attr('data-max');
            if(input.val()*1 > max_qte*1){
                e.preventDefault();
                alertify.error('برجاء إدخال كمية أقل من '+$('#span_damage_qte_name').html());
                design.useSound('error');
                return;
            }
            if(input.val()*1 <= 0){
                e.preventDefault();
                alertify.error('برجاء إدخال كمية أكبر من 0');
                design.useSound('error');
                return;
            }
            $('#load').css('display', 'block');
            design.check_submit($(this),e);
        });
        @endif

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

        {{--@if(Auth::user()->type==1||Auth::user()->allow_delete_account)
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
        @endif--}}
    </script>
@endsection
