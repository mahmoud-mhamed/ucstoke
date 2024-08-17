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
    ادارة المنتجات
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
                <h1 class='font-weight-bold pb-3 text-white'>ادارة المنتجات</h1>
                <div class='container-fluid'>
                    <div class='mt-2 table-filters'>
                        <div class="d-flex" style="flex-wrap: wrap">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>الحالة</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="23">
                                        <option value=''>الكل</option>
                                        <option value='1' selected>مفعل</option>
                                        <option value='0'>غير مفعل</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>النوع</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="22">
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
                                            data-filter-col="24">
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
                                            data-filter-col="4">
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
                                       data-filter-col="0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20"
                                       placeholder='بحث بأى بيانات (الإسم , الوحدة , أقل عدد , ملاحظة , الباركود إلخ)'
                                       class='form-control selectpicker'>
                            </div>
                        </div>
                    </div>
                    <div class="h3 text-white mt-3" id="columFilter" dir="rtl">
                        <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                              value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">وقت الإضافة<input type="checkbox" data-toggle="1"
                                                                                        value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">وقت أخر تعديل<input type="checkbox"
                                                                                          data-toggle="2"
                                                                                          value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإسم<input type="checkbox" data-toggle="3"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">القسم<input type="checkbox" data-toggle="4"
                                                                                  value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">منتج شراء<input type="checkbox" data-toggle="5"
                                                                                      checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">منتج بيع<input type="checkbox" data-toggle="6"
                                                                                     checked value=""></label>
                        <label
                            class="checkbox-inline pl-4 {{Hash::check('product_make',$permit->product_make)?'':'d-none'}}"
                            dir="ltr">منتج إنتاج<input type="checkbox" data-toggle="7"
                                                       {{Hash::check('product_make',$permit->product_make)?'checked':''}}   value=""></label>
                        <label
                            class="checkbox-inline pl-4 {{Hash::check('product_no_qte',$permit->product_no_qte)?'':'d-none'}}"
                            dir="ltr">منتج بدون كمية<input type="checkbox"
                                                           data-toggle="8"
                                                           {{Hash::check('product_no_qte',$permit->product_no_qte)?'checked':''}} value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الوحدة الأولى<input type="checkbox"
                                                                                          data-toggle="9"
                                                                                          checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">أقل عدد من الوحدة الأولى<input type="checkbox"
                                                                                                     data-toggle="10"
                                                                                                     checked
                                                                                                     value=""></label>
                        <label
                            class="checkbox-inline pl-4 font-en {{Hash::check('use_barcode',$permit->use_barcode)?'':'d-none'}}"
                            dir="ltr">الباركود 1<input type="checkbox" data-toggle="11"
                                                       {{Hash::check('use_barcode',$permit->use_barcode)?'checked':''}}  value=""></label>
                        <label
                            class="checkbox-inline pl-4 font-en {{Hash::check('use_barcode2',$permit->use_barcode2)?'':'d-none'}}"
                            dir="ltr">الباركود 2<input type="checkbox" data-toggle="12"
                                                       value=""></label>
                        <label
                            class="checkbox-inline pl-4 font-en {{Hash::check('use_barcode3',$permit->use_barcode3)?'':'d-none'}}"
                            dir="ltr">الباركود 3<input type="checkbox" data-toggle="13"
                                                       value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">سعر الشراء<input type="checkbox" data-toggle="14"
                                                                                       value=""></label>
                        <label class="checkbox-inline pl-4"
                               dir="ltr">{{Hash::check('use_price2',$permit->use_price2)?'سعر '.$setting->price1_name:'سعر البيع'}}
                            <input type="checkbox"
                                   data-toggle="15"
                                   value=""></label>
                        <label class="checkbox-inline pl-4 {{Hash::check('use_price2',$permit->use_price2)?'':'d-none'}}" dir="ltr">{{$setting->price2_name}}<input type="checkbox"
                                                                             data-toggle="16"
                                                                             value=""></label>
                        <label class="checkbox-inline pl-4 {{Hash::check('use_price3',$permit->use_price3)?'':'d-none'}}" dir="ltr">{{$setting->price3_name}}<input type="checkbox"
                                                                                                      data-toggle="17"
                                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4 {{Hash::check('use_price4',$permit->use_price4)?'':'d-none'}}" dir="ltr">{{$setting->price4_name}}<input type="checkbox"
                                                                                                      data-toggle="18"
                                                                                                      value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="19"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الحالة<input type="checkbox" data-toggle="20"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                        type="checkbox"
                                                                                        checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="21"
                                                                                     checked value=""></label>
                        <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1"
                                data-placement="bottom" title="طباعة النتيجة">
                            <span class="h3"><i class="fas fa-print"></i></span>
                        </button>
                    </div>
                    {{--container units--}}
                    <div class='table-responsive box d-none text-center mb-3' id="divContainerUnit">
                        <h1>وحدات المنتج
                            <label data-text="product_name"></label>
                            حيث الوحدة الأولى هى
                            <label data-text="product_unit"></label>
                        </h1>
                        <table id="unitTable" class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م</th>
                                <th>إسم الوحدة</th>
                                <th class="{{Hash::check('use_barcode',$permit->use_barcode)?'':'d-none'}}">الباكود 1</th>
                                <th class="{{Hash::check('use_barcode2',$permit->use_barcode2)?'':'d-none'}}">الباكود 2</th>
                                <th class="{{Hash::check('use_barcode3',$permit->use_barcode3)?'':'d-none'}}">الباكود 3</th>
                                <th class="small">سعر الشراء</th>
                                <th class="small"> {{Hash::check('use_price2',$permit->use_price2)?'سعر '.$setting->price1_name:'سعر البيع'}}</th>
                                <th class="{{Hash::check('use_price2',$permit->use_price2)?'':'d-none'}}">{{$setting->price2_name}}</th>
                                <th class="{{Hash::check('use_price3',$permit->use_price3)?'':'d-none'}}">{{$setting->price3_name}}</th>
                                <th class="{{Hash::check('use_price4',$permit->use_price4)?'':'d-none'}}">{{$setting->price4_name}}</th>
                                <th>العلاقة بالوحدة الأولى</th>
                            </tr>
                            </thead>
                            <tbody class="h4">
                            <tr class="table-success'">

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    {{--container component--}}
                    <div class='table-responsive box d-none text-center mb-3' id="divContainerComponent">
                        <h1>المكونات الأزمة لإنتاج
                            <label data-text="product_unit"></label>
                            من المنتج
                            <label data-text="product_name"></label>
                        </h1>
                        <table class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م</th>
                                <th>إسم المنتج المكون</th>
                                <th>الكمية الأزمة لإنتاج
                                    <label class="h3 text-white" data-text="product_unit"></label>
                                </th>
                                <th>سعر الشراء</th>
                                <th>الإجمالى
                                    <span class="tooltips" data-placement="left" title="إجمالى سعر المكونات"
                                          id="span_table_making_total"></span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="h4">
                            <tr class="table-success'">

                            </tr>
                            </tbody>
                        </table>
                    </div>

                    {{--container pordcut--}}
                    <div class='box-shadow tableFixHead table-responsive text-center'>
                        <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م
                                    <span id="countRowsInTable" class="font-en">{{count($products)}}</span>
                                </th>
                                <th>وقت الإضافة</th>
                                <th>وقت أخر تعديل</th>
                                <th>الإسم</th>
                                <th>القسم</th>
                                <th class="tooltips" title="منتج شراء" data-placement="left">شراء</th>
                                <th class="tooltips" title="منتج بيع" data-placement="left">بيع</th>
                                <th class="tooltips" title="منتج إنتاج" data-placement="left">إنتاج</th>
                                <th class="tooltips" title="منتج بدون كمية" data-placement="left">بدون</th>
                                <th class="small">الوحدة الأولى</th>
                                <th class="small tooltips" data-placement='left'
                                    title="إظهار المنتج فى النواقص عندما تقل كميته عن">أقل عدد
                                </th>
                                <th class="font-en small">الباركود 1</th>
                                <th class="font-en small">الباركود 2</th>
                                <th class="font-en small">الباركود 3</th>
                                <th class="small">سعر الشراء</th>
                                <th class="small">
                                    {{Hash::check('use_price2',$permit->use_price2)?'سعر '.$setting->price1_name:'سعر البيع'}}</th>
                                <th class="small">سعر
                                    {{$setting->price2_name}}</th>
                                <th class="small">سعر
                                    {{$setting->price3_name}}</th>
                                <th class="small">سعر
                                    {{$setting->price4_name}}</th>
                                <th>ملاحظة</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                                <th class="d-none">type</th>
                                <th class="d-none">state</th>
                            </tr>
                            </thead>
                            <tbody class="h4">
                            @foreach ($products as $p)
                                <tr class="{{$p->state?'table-success':'table-danger text-white'}} pointer">
                                    <td data-rowId="{{$p->id}}">{{$loop->index +1}}</td>
                                    <td>{{$p->created_at}}</td>
                                    <td>{{$p->updated_at}}</td>
                                    <td>{{$p->name}}</td>
                                    <td>{{$p->productCategory['name']}}</td>
                                    <td class="{{$p->allow_buy?'':'bg-danger'}}">{!!$p->allow_buy?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td class="{{$p->allow_sale?'':'bg-danger'}}">{!!$p->allow_sale?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td class="{{$p->allow_make?'':'bg-danger'}}">{!!$p->allow_make?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td class="{{$p->allow_no_qte?'':'bg-danger'}}">{!!$p->allow_no_qte?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td>{{$p->productUnit['name']}}</td>
                                    <td class="small">{{$p->min_qte}}</td>
                                    <td data-stop_show_details>{{$p->barcode1}}
                                        @if ($p->barcode1!='')
                                            <button class="btn p-0 bg-transparent tooltips" data-placement="left"
                                                    title="طباعة" data-barcode="{{$p->barcode1}}"><i
                                                    class="fas text-warning fa-print"></i></button>
                                        @endif
                                    </td>
                                    <td data-stop_show_details>
                                        {{$p->barcode2}}
                                        @if ($p->barcode2!='')
                                            <button class="btn p-0 bg-transparent tooltips" data-placement="left"
                                                    title="طباعة" data-barcode="{{$p->barcode2}}"><i
                                                    class="fas text-warning fa-print"></i></button>
                                        @endif
                                    </td>
                                    <td data-stop_show_details>{{$p->barcode3}}
                                        @if ($p->barcode3!='')
                                            <button class="btn p-0 bg-transparent tooltips" data-placement="left"
                                                    title="طباعة" data-barcode="{{$p->barcode3}}"><i
                                                    class="fas text-warning fa-print"></i></button>
                                        @endif
                                    </td>
                                    <td>{{$p->price_buy}} ج<br>
                                    </td>
                                    <td data-price="{{$p->price_sale1}}">{{$p->price_sale1}} ج
                                        <span class=" tooltips small text-warning " style="font-size: 1.2rem!important;"
                                              data-placement="left"
                                              title="الربح">({{($p->price_buy > 0 && $p->price_sale1>0)?(round($p->price_sale1-$p->price_buy,2)).'ج'.','.round((($p->price_sale1-$p->price_buy)/$p->price_sale1)*100).'%':''}})</span>
                                    </td>
                                    <td>{{$p->price_sale2}} ج
                                        <span class=" tooltips small text-warning" style="font-size: 1.2rem!important;"
                                              data-placement="left"
                                              title="الربح">({{($p->price_buy > 0 && $p->price_sale2>0)?(round($p->price_sale2-$p->price_buy,2)).'ج'.','.round((($p->price_sale2-$p->price_buy)/$p->price_sale2)*100).'%':''}})</span>
                                    </td>
                                    <td>{{$p->price_sale3}} ج
                                        <span class=" tooltips small text-warning" style="font-size: 1.2rem!important;"
                                              data-placement="left"
                                              title="الربح">({{($p->price_buy > 0 && $p->price_sale3>0)?(round($p->price_sale3-$p->price_buy,2)).'ج'.','.round((($p->price_sale3-$p->price_buy)/$p->price_sale3)*100).'%':''}})</span>
                                    </td>
                                    <td>{{$p->price_sale4}} ج
                                        <span class=" tooltips small text-warning" style="font-size: 1.2rem!important;"
                                              data-placement="left"
                                              title="الربح">({{($p->price_buy > 0 && $p->price_sale4>0)?(round($p->price_sale4-$p->price_buy,2)).'ج'.','.round((($p->price_sale4-$p->price_buy)/$p->price_sale4)*100).'%':''}})</span>
                                    </td>
                                    <td>{{$p->note}}</td>
                                    <td class="{{$p->state?'':'bg-danger'}}">{{$p->state?'مفعل':'غير'}}</td>
                                    <td data-stop_show_details>
                                        <button class='btn px-0 bg-transparent tooltips ml-2'
                                                title="{{$p->special?'حذف المنتج من قائمة المنتجات الخاصة':'إضافة المنتج لقائمة المنتجات الخاصة (يجب أن يكون المنتج مفعل ليظهر فى المنتجات الخاصة فى الفواتير)'}}"
                                                data-placement="right"
                                                data-change_favorit=''>
                                            <span class='font-weight-bold text-dark h4'>
                                                {!!$p->special?'<i class="fas text-danger fa-minus"></i>':'<i class="fas text-primary fa-plus"></i>'!!}
                                            </span>
                                        </button>
                                        <button class='btn px-0 bg-transparent tooltips'
                                                title="{{$p->state?'إلغاء تفعيل المنتج بحيث لا يظهر فى الفواتير':'تفعيل المنتج'}}"
                                                data-placement="right"
                                                data-type='changeState'><span
                                                class='font-weight-bold text-dark h4'>{!!$p->state?'<i class="fas text-danger fa-eye-slash"></i>':'<i class="fas text-success fa-eye"></i>'!!} </span>
                                        </button>
                                        <a class='btn  mx-2 text-primary px-0 tooltips'
                                           data-placement="right" title="تعديل"
                                           href='products/{{$p->id}}/edit'><span
                                                class='h4'> </span><i class='fas fa-2x fa-edit'></i></a>
                                        <button class='btn  bg-transparent tooltips p-0' data-delete='{{$p->id}}'
                                                data-placement='right'
                                                title='لا يمكن الحذف إذا كان هذا المنتج مستخدم ولكن يجوز إلغاء تفعيلة لمنع ظهورة فى الفواتير '>
                                            <span class='font-weight-bold text-danger h4'> </span><i
                                                class='fas fa-2x fa-trash-alt text-danger'></i></button>
                                    </td>
                                    <td class="d-none">
                                        {{$p->allow_buy?'0':''}}
                                        {{$p->allow_sale?'1':''}}
                                        {{$p->allow_make?'2':''}}
                                        {{$p->allow_no_qte?'3':''}}
                                    </td>
                                    <td class="d-none">{{$p->state?1:0}}</td>
                                    <td class="d-none">{{$p->special?1:0}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        @if(Auth::user()->type==1||Auth::user()->allow_delete_account)
            <form action='{{route('products.destroy',0)}}' id='form_delete_product' class='d-none' method='post'>
                @csrf
                @method('delete')
            </form>
        @endif
        {{--changeState--}}
        <form class='d-inline' id='changeState' action='{{route('products.changeState',0)}}' method='post'>
            <input id="input_change_state_type" type="hidden" name="type">
            @csrf
        </form>
    </main>
    <div id="div_container_barcode" class="d-none">
        <div class="font-en" dir="rtl"
             style="background: white;width: 150px;height: 150px;margin:auto">
            <p id="p_barcode_company" style="margin: 0px;text-align: center">إسم الشركة</p>
            <img id="barcode1" style="object-fit: fill{{--contain--}};display: block;margin: auto"/>
            <div id="barcodeNumber" style="text-align: center;margin: auto"></div>
            <div class="div_container_barcode_product"
                 style="display: flex;justify-content: space-between;flex-wrap: wrap">
                <span id="span_barcode_product_name">إسم المنتج</span>
                <span id="span_barcode_product_price">سعر المنتج</span>
                <span id="span_barcode_date">وقت الطباعة</span>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script defer>
        design.useSound();
        design.useNiceScroll();
        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getRowCounter();

            $('#mainTable tbody button[data-type="changeState"]').click(function () {
                $('button').attr('disabled', 'disabled');
                $('#load').css('display', 'block');
                design.useSound();

                var id = $(this).parent().siblings().eq(0).attr('data-rowId');
                var action = $('#changeState').attr('action');
                $('#input_change_state_type').val('changeState');
                action = action.replace(/[0-9]$/, id);
                $('#changeState').attr('action', action).submit();
            });

            $('#mainTable tbody button[data-change_favorit]').click(function () {
                $('button').attr('disabled', 'disabled');
                $('#load').css('display', 'block');
                design.useSound();

                var id = $(this).parent().siblings().eq(0).attr('data-rowId');
                var action = $('#changeState').attr('action');
                $('#input_change_state_type').val('change_favorit');
                action = action.replace(/[0-9]$/, id);
                $('#changeState').attr('action', action).submit();
            });

            $('#mainTable tbody td:not([data-stop_show_details])').click(function () {
                getDetailsData($(this).parent().children().eq(0).attr('data-rowId'));
                $(this).parent().addClass('selectRow').siblings().removeClass('selectRow');
            });


            $('#mainTable').on('click', 'button[data-barcode]', function () {
                var parent = $(this).parent().siblings();
                drow_barcode($(this).attr('data-barcode'), parent.eq(3).html(), parent.eq(14).attr('data-price')+'ج');
            });
            $('#unitTable').on('click', 'button[data-barcode]', function () {
                var parent = $(this).parent().siblings();
                drow_barcode($(this).attr('data-barcode'), parent.eq(0).attr('data-productName'), parent.eq(5).attr('data-price')+'ج');
            });


            @if(Auth::user()->type==1||Auth::user()->allow_delete_product)
            $('#mainTable').on('click', 'tbody tr button[data-delete]', function (e) {
                var id = $(this).attr('data-delete');
                $(this).parent().parent().addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
                /*var parent = $(this).parent().parent();
                $('#mainTable tbody tr').each(function () {
                    $(this).removeClass('table-danger').addClass($(this).attr('data-main_class'));
                });
                parent.addClass('table-danger').removeClass(parent.attr('data-main_class'));*/
                design.useSound('info');
                $(this).confirm({
                    text: "هل تريد حذف المنتج المحدد؟",
                    title: "حذف منتج",
                    confirm: function (button) {
                        var action = $('#form_delete_product').attr('action');
                        action = action.replace(/[0-9]$/, id);
                        $('#form_delete_product').attr('action', action).submit();
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

        var stateGetData = true;

        function getDetailsData(id) {
            $('#divContainerComponent,#divContainerUnit').addClass('d-none');
            $('#divContainerComponent tbody,#divContainerUnit tbody').html('');
            if (stateGetData) {
                stateGetData = false;
                $.ajax({
                    url: '{{route('products.getDate')}}',
                    method: 'POST',
                    data: {
                        type: 'findProductWithUnitWithComponent',
                        product_id: id
                    },
                    dataType: 'JSON',
                    success: function (data) {
                        $('#divContainerComponent,#divContainerUnit').addClass('d-none');
                        $('#divContainerComponent tbody,#divContainerUnit tbody').html('');
                        if (data['relation_product_make'].length > 0) {
                            $('#divContainerComponent').removeClass('d-none');

                            var priceMake = 0;
                            for (var i = 0; i < data['relation_product_make'].length; i++) {
                                priceMake -= -(data['relation_product_make'][i]['product_creator']['price_buy'] * data['relation_product_make'][i]['qte_creator']);
                                $('#divContainerComponent tbody').append(
                                    "<tr class='table-success'>" +
                                    "<td>" + (i - -1) + "</td>" +
                                    "<td>" + data['relation_product_make'][i]['product_creator']['name'] + "</td>" +
                                    "<td>" + roundTo(data['relation_product_make'][i]['qte_creator']) + ' ' + data['relation_product_make'][i]['product_creator']['product_unit']['name'] + "</td>" +
                                    "<td>" + roundTo(data['relation_product_make'][i]['product_creator']['price_buy']) + ' ج ' + "</td>" +
                                    "<td>" + roundTo(data['relation_product_make'][i]['product_creator']['price_buy'] * data['relation_product_make'][i]['qte_creator']) + ' ج ' + "</td>" +
                                    "</tr>"
                                );
                            }

                            $('#divContainerComponent label[data-text="product_name"]').html(data['name']);
                            $('#divContainerComponent label[data-text="product_unit"]').html(data['product_unit']['name']);
                            $('#span_table_making_total').html(roundTo(priceMake) + ' ج ');
                        }

                        if (data['relation_product_unit'].length > 0) {
                            $('#divContainerUnit').removeClass('d-none');
                            $('#divContainerUnit label[data-text="product_name"]').html(data['name']);
                            $('#divContainerUnit label[data-text="product_unit"]').html(data['product_unit']['name']);

                            for (var i = 0; i < data['relation_product_unit'].length; i++) {
                                var print = '';
                                if (data['relation_product_unit'][i]['barcode1'] != '') {
                                    print = '<button class="btn p-0 bg-transparent tooltips" data-placement="left" title="طباعة" data-barcode="' + data['relation_product_unit'][i]['barcode1'] + '"><i class="fas text-warning fa-print"></i></button>';
                                }
                                var print2 = '';
                                if (data['relation_product_unit'][i]['barcode2'] != '') {
                                    print2 = '<button class="btn p-0 bg-transparent tooltips" data-placement="left" title="طباعة" data-barcode="' + data['relation_product_unit'][i]['barcode2'] + '"><i class="fas text-warning fa-print"></i></button>';
                                }
                                var print3 = '';
                                if (data['relation_product_unit'][i]['barcode3'] != '') {
                                    print3 = '<button class="btn p-0 bg-transparent tooltips" data-placement="left" title="طباعة" data-barcode="' + data['relation_product_unit'][i]['barcode3'] + '"><i class="fas text-warning fa-print"></i></button>';
                                }

                                $('#divContainerUnit tbody').append(
                                    "<tr class='table-success'>" +
                                    "<td data-productName='" + data['name'] + "'>" + (i - -1) + "</td>" +
                                    "<td>" + data['relation_product_unit'][i]['product_unit']['name'] + "</td>" +
                                    "<td class='{{Hash::check('use_barcode',$permit->use_barcode)?'':'d-none'}}'>" + (data['relation_product_unit'][i]['barcode1'] != null ? data['relation_product_unit'][i]['barcode1'] + print : '') + "</td>" +
                                    "<td class='{{Hash::check('use_barcode2',$permit->use_barcode2)?'':'d-none'}}'>" + (data['relation_product_unit'][i]['barcode2'] != null ? data['relation_product_unit'][i]['barcode2'] + print2 : '') + "</td>" +
                                    "<td class='{{Hash::check('use_barcode3',$permit->use_barcode3)?'':'d-none'}}'>" + (data['relation_product_unit'][i]['barcode3'] != null ? data['relation_product_unit'][i]['barcode3'] + print3 : '') + "</td>" +
                                    "<td>" + data['relation_product_unit'][i]['price_buy'] + 'ج' + "</td>" +
                                    "<td data-price='"+data['relation_product_unit'][i]['price_sale1']+"'>" + data['relation_product_unit'][i]['price_sale1'] + 'ج' +
                                    "<span class=' tooltips small text-warning' style='font-size: 1.2rem!important;' data-placement='left' title='الربح'>("+
                                    ((data['relation_product_unit'][i]['price_sale1'] > 0 && data['relation_product_unit'][i]['price_buy'] > 0) ? (data['relation_product_unit'][i]['price_sale1'] - data['relation_product_unit'][i]['price_buy']) + 'ج' + ',' + roundTo(((data['relation_product_unit'][i]['price_sale1'] - data['relation_product_unit'][i]['price_buy']) / data['relation_product_unit'][i]['price_buy']) * 100) + '%' : '')+
                                    ")</span>"+
                                    "</td>" +
                                    "<td class='{{Hash::check('use_price2',$permit->use_price2)?'':'d-none'}}'>" + data['relation_product_unit'][i]['price_sale2'] + 'ج' +
                                    "<span class=' tooltips small text-warning' style='font-size: 1.2rem!important;' data-placement='left' title='الربح'>("+
                                    ((data['relation_product_unit'][i]['price_sale2'] > 0 && data['relation_product_unit'][i]['price_buy'] > 0) ? (data['relation_product_unit'][i]['price_sale2'] - data['relation_product_unit'][i]['price_buy']) + 'ج' + ',' + roundTo(((data['relation_product_unit'][i]['price_sale2'] - data['relation_product_unit'][i]['price_buy']) / data['relation_product_unit'][i]['price_buy']) * 100) + '%' : '')+
                                    ")</span>"+
                                    "</td>" +
                                    "<td class='{{Hash::check('use_price3',$permit->use_price3)?'':'d-none'}}'>" + data['relation_product_unit'][i]['price_sale3'] + 'ج' +
                                    "<span class=' tooltips small text-warning' style='font-size: 1.2rem!important;' data-placement='left' title='الربح'>("+
                                    ((data['relation_product_unit'][i]['price_sale3'] > 0 && data['relation_product_unit'][i]['price_buy'] > 0) ? (data['relation_product_unit'][i]['price_sale3'] - data['relation_product_unit'][i]['price_buy']) + 'ج' + ',' + roundTo(((data['relation_product_unit'][i]['price_sale3'] - data['relation_product_unit'][i]['price_buy']) / data['relation_product_unit'][i]['price_buy']) * 100) + '%' : '')+
                                    ")</span>"+
                                    "</td>" +
                                    "<td class='{{Hash::check('use_price4',$permit->use_price4)?'':'d-none'}}'>" + data['relation_product_unit'][i]['price_sale4'] + 'ج' +
                                    "<span class=' tooltips small text-warning' style='font-size: 1.2rem!important;' data-placement='left' title='الربح'>("+
                                    ((data['relation_product_unit'][i]['price_sale4'] > 0 && data['relation_product_unit'][i]['price_buy'] > 0) ? (data['relation_product_unit'][i]['price_sale4'] - data['relation_product_unit'][i]['price_buy']) + 'ج' + ',' + roundTo(((data['relation_product_unit'][i]['price_sale4'] - data['relation_product_unit'][i]['price_buy']) / data['relation_product_unit'][i]['price_buy']) * 100) + '%' : '')+
                                    ")</span>"+
                                    "</td>" +
                                    "<td>" + data['relation_product_unit'][i]['product_unit']['name'] + ' = '
                                    + data['relation_product_unit'][i]['relation_qte'] + ' ' + data['product_unit']['name'] +
                                    "</td>" +
                                    "</tr>"
                                );
                            }
                            design.useToolTip();
                        }

                        if (data['relation_product_unit'].length > 0 || data['relation_product_make'].length > 0) {
                            alertify.success('تم عرض التفاصيل بنجاح');
                        } else {
                            alertify.success('لا يوجد تفاصيل عن هذا المنتج لعرضها');
                        }
                        design.updateNiceScroll();
                        design.useSound('success');
                        stateGetData = true;
                    },
                    error: function (e) {
                        alert('error');
                        design.useSound('error');
                        stateGetData = true;
                        console.log(e);
                        $('#continerGetData button').removeAttr('disabled');
                    }
                });
            } else {
                alertify.error('برجاء الإنتظار جارى البحث عن البيانات');
                design.useSound('info');
            }
        }

        //print main table
        $('#printMainTable').click(function () {
            design.useSound();
            alertify.success('برجاء الإنتظار جارى الطباعة!');
            $('#mainTable').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });

        function drow_barcode(input_barcode_val, productName, productPrice, company_name = '{{$barcode->company_name}}', company_color = '{{$barcode->company_name_color}}',
                              barcode_type = '{{$barcode->barcode_type}}',
                              barcode_width = {{$barcode->barcode_width}}, barcode_height = {{$barcode->barcode_height}},
                              company_font = {{$barcode->company_name_font_size}}, code_font = {{$barcode->barcode_font_size}},
                              product_font = {{$barcode->product_font_size}}, product_color = '{{$barcode->product_color}}',
                              price_font = {{$barcode->price_font_size}}, price_color = '{{$barcode->price_color}}',
                              date_font = {{$barcode->time_font_size}}, date_color = '{{$barcode->time_color}}', barcode_color = '{{$barcode->barcode_color}}',
                              padding_top = {{$barcode->padding_top}}, padding_right = {{$barcode->padding_right}},
                              padding_bottom = {{$barcode->padding_bottom}}, padding_left = {{$barcode->padding_left}}) {
            $('#div_container_barcode').removeClass('d-none');
            $('#p_barcode_company').html(company_name).css({
                fontSize: company_font + 'px',
                maxWidth: barcode_width + 'mm',
                color: company_color,
            });
            $('#div_container_barcode>div').css({
                paddingTop: padding_top + 'mm',
                paddingBottom: padding_bottom + 'mm',
                paddingLeft: padding_left + 'mm',
                paddingRight: padding_right + 'mm',
                width: barcode_width + 'mm',
                height: barcode_height - padding_top - padding_bottom + 'mm'
            });
            $('#span_barcode_product_name').html(productName).css({
                fontSize: product_font + 'px',
                color: product_color
            });
            $('#span_barcode_product_price').html(productPrice).css({
                fontSize: price_font + 'px',
                color: price_color
            });
            $('#span_barcode_date').html(moment().format('hh:mm') + moment().format('a') + ' , ' + moment().format('YYYY.MM.D')).css({
                fontSize: date_font + 'px',
                color: date_color
            });
            try {
                JsBarcode("#barcode1", input_barcode_val, {
                    format: barcode_type,
                    width: 3,
                    {{--                    flat: true,--}}
                    textAlign: 'center',
                    textPosition: 'bottom',
                    fontSize: 0,
                    // fontSize: code_font,
                    margin: 0,
                    background: 'white',
                    lineColor: barcode_color,
                    textMargin: 0
                });
                $('#barcodeNumber').html(input_barcode_val).css('fontSize', code_font + 'px');
                $('#barcode1').css({
                    width: (barcode_width - padding_right - padding_left) + 'mm',
                    height: (barcode_height - ($('#p_barcode_company').height() * (25.4 / 96)) - ($('#barcodeNumber').height() * (25.4 / 96)) -
                        ($('#span_barcode_product_name').parent().height() * (25.4 / 96)) - padding_top - padding_bottom) + 'mm'
                });
                $('#div_container_barcode').printArea({
                    extraCss: "{{asset('css/barcode.css')}}",
                    stopHeader: true,
                    // mode:'popup',
                });
                $('#div_container_barcode').addClass('d-none');
                design.useSound();
                alertify.success('تمت العملية بنجاح');
            } catch (e) {
                $('#div_container_barcode').addClass('d-none');
                design.useSound('error');
                alertify.error('الباركود غير مناسب للنوع المحدد من طباعة الباركود');
            }
            design.updateNiceScroll();

        }
    </script>
@endsection
