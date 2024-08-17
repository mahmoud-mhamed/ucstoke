<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    أماكن المنتجات فى المخازن
@endsection
@section('css')
    <style>
        .selectRow {
            color: blueviolet !important;
        }

        main span, main input {
            font-size: 1.5rem !important;
        }

        main input {
            padding: 25px 10px !important;
        }

        main select {
            height: 53px !important;
            font-size: 1.5rem;
        }
        main select,main option{
            /*background: transparent;*/
        }
        main input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
        }

        main #columFilter label {
            cursor: pointer;
        }


        div.input-group {
            flex-wrap: nowrap;
            width: auto;
        }

        #divContainerComponent label, #divContainerUnit label {
            border-bottom: 4px red solid;
            color: darkred;
        }

        table select {
            height: 40px !important;
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
            padding: 5px;
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4  pb-2 position-relative'>
        <section class='animated fadeInDown faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 text-white'>أماكن المنتجات فى المخازن</h1>
                <div class='container-fluid'>
                    <div class='mt-2 table-filters'>
                        <div class="d-flex" style="flex-wrap: wrap">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>حالة المنتج</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="10">
                                        <option value=''>الكل</option>
                                        <option value='1'>مفعل</option>
                                        <option value='0'>غير مفعل</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>نوع المنتج</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="9">
                                        <option value=''>الكل</option>
                                        <option value='0'>شراء</option>
                                        <option value='1'>بيع</option>
                                        <option value='2'>إنتاج</option>
                                        <option value='3'>بدون كمية</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>المنتجات الخاصة</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="11">
                                        <option value=''>الكل</option>
                                        <option value='1'>المنتجات الخاصة</option>
                                        <option value='0'>المنتجات الغير خاصة</option>

                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>القسم</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="2">
                                        <option value=''>الكل</option>
                                        @foreach ($categories as $c)
                                            <option value='{{$c->name}}'>{{$c->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>بحث</span>
                                </div>
                                <input type='text'
                                       data-live-search="true"
                                       data-filter-col="1"
                                       placeholder='بحث بإسم المنتج'
                                       class='form-control selectpicker'>
                            </div>
                        </div>
                    </div>
                    <div class="h3 text-white mt-3" id="columFilter" dir="rtl">
                        <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                              value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإسم<input type="checkbox" data-toggle="1"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">القسم<input type="checkbox" data-toggle="2"
                                                                                  value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">منتج شراء<input type="checkbox" data-toggle="3"
                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">منتج بيع<input type="checkbox" data-toggle="4"
                                                                                     value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">منتج إنتاج<input type="checkbox" data-toggle="5"
                                                                                        value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">منتج بدون كمية<input type="checkbox"
                                                                                           data-toggle="6"
                                                                                           value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="7"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الحالة<input type="checkbox" data-toggle="8"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                        type="checkbox"
                                                                                        checked value=""></label>
                        @foreach ($stokes as $s)
                            <label class="checkbox-inline pl-4" dir="ltr">مخزن
                                {{$s->name}}
                                <input type="checkbox" checked data-toggle="{{$loop->index + 12}}" value=""></label>
                        @endforeach


                    </div>

                    {{--container pordcut--}}
                    <div class='box-shadow tableFixHead table-responsive text-center'>
                        <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م
                                    <span id="countRowsInTable" class="font-en">{{count($products)}}</span>
                                </th>
                                <th>الإسم</th>
                                <th>القسم</th>
                                <th class="tooltips" title="منتج شراء" data-placement="left">شراء</th>
                                <th class="tooltips" title="منتج بيع" data-placement="left">بيع</th>
                                <th class="tooltips" title="منتج إنتاج" data-placement="left">إنتاج</th>
                                <th class="tooltips" title="منتج بدون كمية" data-placement="left">بدون</th>
                                <th>ملاحظة</th>
                                <th>الحالة</th>
                                <th class="d-none">type</th>
                                <th class="d-none">state</th>
                                <th class="d-none">special</th>
                                @foreach ($stokes as $s)
                                    <th>{{$s->name}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody class="h4">
                            @foreach ($products as $p)
                                <tr class="{{$p->state?'table-success':'table-danger text-white'}} pointer">
                                    <td>{{$loop->index +1}}</td>
                                    <td>{{$p->name}}</td>
                                    <td>{{$p->productCategory['name']}}</td>
                                    <td class="{{$p->allow_buy?'':'bg-danger'}}">{!!$p->allow_buy?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td class="{{$p->allow_sale?'':'bg-danger'}}">{!!$p->allow_sale?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td class="{{$p->allow_make?'':'bg-danger'}}">{!!$p->allow_make?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td class="{{$p->allow_no_qte?'':'bg-danger'}}">{!!$p->allow_no_qte?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td>{{$p->note}}</td>
                                    <td class="{{$p->state?'':'bg-danger'}}">{{$p->state?'مفعل':'غير'}}</td>
                                    <td class="d-none">
                                        {{$p->allow_buy?'0':''}}
                                        {{$p->allow_sale?'1':''}}
                                        {{$p->allow_make?'2':''}}
                                        {{$p->allow_no_qte?'3':''}}
                                    </td>
                                    <td class="d-none">{{$p->state?1:0}}</td>
                                    <td class="d-none">{{$p->special?1:0}}</td>
                                    @foreach ($stokes as $s)
                                        <td>
                                            <select class="custom-select custom-select-sm" data-live-search="true">
                                                <option data-stoke_id="{{$s->id}}" data-product_id="{{$p->id}}" value="0">بدون</option>
                                                @foreach ($places as $pl)
                                                    @if (count($p->place)!=0)
                                                        <?php $checkExistPlace=false; ?>
                                                        @foreach ($p->place as $temp)
                                                            @if ($temp->stoke_id==$s->id)
                                                                <?php $checkExistPlace=true; ?>
                                                                <option data-stoke_id="{{$s->id}}" data-product_id="{{$p->id}}" value="{{$pl->id}}" {{($pl->id==$temp->stoke_place_name_id)?'selected':''}}>{{$pl->name}}</option>
                                                            @endif
                                                        @endforeach
                                                        @if (!$checkExistPlace)
                                                            <option data-stoke_id="{{$s->id}}" data-product_id="{{$p->id}}" value="{{$pl->id}}">{{$pl->name}}</option>
                                                        @endif
                                                    @elseif ($pl->state)
                                                        <option data-stoke_id="{{$s->id}}" data-product_id="{{$p->id}}" value="{{$pl->id}}">{{$pl->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        {{--changeState--}}
        <form class='d-inline' id='changeState' action='{{route('stoke_product_places.updateProductPlace')}}' method='post'>
            <input id="input_product_id" type="hidden" name="product_id">
            <input id="input_stoke_id" type="hidden" name="stoke_id">
            <input id="input_place_id" type="hidden" name="place_id">
            @csrf
        </form>
    </main>
@endsection

@section('js')
    <script defer>
        design.useNiceScroll();
        design.useSound();

        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getRowCounter();
            $('#mainTable tbody select').click(function () {
                design.useSound();
            });
            $('#mainTable tbody select').change(function () {
                $('select').attr('disabled', 'disabled');
                $('#input_product_id').val($(this).find('option:selected').attr('data-product_id'));
                $('#input_stoke_id').val($(this).find('option:selected').attr('data-stoke_id'));
                $('#input_place_id').val($(this).val());
                $('#changeState').submit();
            });
            $('#mainTable table tbody select').siblings().remove();
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

        function getRowCounter() {
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
    </script>
@endsection
