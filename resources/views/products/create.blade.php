<?php
$permit = \App\Permit::first();
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 23/01/2019
 * Time: 01:52 م
 */
?>
@extends('layouts.app')
@section('title')
    إضافة منتج جديد
@endsection
@section('css')
    <style>
        label {
            font-size: 1.5rem;
        }

        a {
            height: 47px;
        }

        ::placeholder {
            font-size: 1.5rem;
        }

        button.dropdown-toggle {
            padding-top: 0px;
        }

        input[type='checkbox'], input[type='radio'] {
            transform: scale(2);
            margin-left: 10px;
        }

        div.error-price, div.error-negative_price {
            width: 25%;
            padding-right: 15px;
        }

        .col div.error-price, .col div.error-negative_price {
            padding-right: 15px;
            width: 100%;
        }

        div.error-qte {
            position: absolute;
            top: -24px;
            left: 32px;
            text-align: center;
            font-size: small;
        }

        div.show > h1 i {
            transform: rotate(-90deg);
            color: green !important;
        }

        div.show > h1 {
            color: green !important;
        }

        #divContainerUnitAndComponent > div > h1 {
            cursor: pointer;
        }

        #divContainerUnitAndComponent div.box-shadow {
            box-shadow: 0 0 3px 0 black;
            margin-bottom: 5px;
        }

        input {
            font-size: 1.5rem !important;
        }

        .priceName {
            color: green;
            font-size: 1.3rem;
            margin-bottom: 0px;
        }

        .priceProfit {
            font-size: 1.3rem;
            color: green;
            font-size: 18px;
            font-family: font-en;
        }
    </style>
@stop
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='box animated fadeInDown faster container text-right mb-2'>
            <div class='container-fluid  pt-3 pb-3'>
                <h1 class='text-center mb-4'>إضافة منتج جديد</h1>
                <form id='formSave'
                      action='{{route('products.store')}}' class='h5'
                      method='post'>
                    @csrf
                    <div class='form-group '>
                        <div id="div_container_type_product" class="text-right text-md-center">
                            <label class="pl-2">نـوع المنتــج :</label>
                            <label class="checkbox-inline d-block d-md-inline-block pl-4 mr-2 pointer tooltips"
                                   data-placement='top' title='منتج يمكن شرائة'
                                   dir="ltr">منتـج شـراء
                                <input type="checkbox" id="checkBuy" name="allow_buy"
                                       value="1">
                            </label>
                            <label class="checkbox-inline d-block d-md-inline-block pl-4 mr-2 pointer tooltips"
                                   data-placement='top' title='منتج يمكن بيعه'
                                   dir="ltr">منتـج بيـــع<input type="checkbox" id="checkSale"
                                                                name="allow_sale" value="1"></label>
                            <label
                                class="checkbox-inline pl-4 mr-2 pointer tooltips {{Hash::check('product_make',$permit->product_make)?'d-block d-md-inline-block':'d-none'}}"
                                data-placement='top' title='منتج يمكن إنتاجة أو عرض مكون من عدة منتجات'
                                dir="ltr">منتـج إنتـاج أو عرض<input type="checkbox" id="checkMake"
                                                                    data-filter_type
                                                                    name="allow_make" value="1"></label>
                            <label
                                class="checkbox-inline mr-2 pointer tooltips
                            {{Hash::check('product_no_qte',$permit->product_no_qte)?(!Hash::check('false',$permit->only_product_no_qte)?'d-none':'d-block d-md-inline-block'):'d-none'}}"
                                data-placement='top'
                                title='منتج بدون كمية مثل (مصاريف الشحن-مصاريف صيانة-او منتج ينتج بدون كمية...إلخ) '
                                dir="ltr">منتج بدون كمية<input type="checkbox" id="checkNoQte" data-filter_type
                                                               {{Hash::check('false',$permit->only_product_no_qte)?'':'checked'}}
                                                               name="allow_no_qte"
                                                               value="1"></label>
                        </div>
                    </div>
                    <div class='form-group mb-3 row no-gutters'>
                        <div class="row no-gutters col-12 col-md-6">
                            <label class='col-sm-5 text-md-left pt-2 pl-3'> إســــــــم المنتــج</label>
                            <div class='col-sm-7'>
                                <input type='text'
                                       id="inputName"
                                       style="height: 45px"
                                       data-validate='min:3' onclick="$(this).select();" data-patternType='min'
                                       autofocus required
                                       name='name'
                                       class='form-control'>
                            </div>
                        </div>
                        <div class="row no-gutters col-12 col-md-6">
                            <label class='col-sm-5 pt-2 text-md-left pl-3 mt-3 mt-md-0'>الأسمـاء الموجـودة</label>
                            <div class='col-sm-7'>
                                <select id='selectExistName' class="selectpicker  show-tick form-control"
                                        data-live-search="true">
                                    @foreach($products as $p)
                                        <option data-style="padding-bottom: 50px!important;"
                                                data-subtext="{{$p->allow_buy?'(شراء)':''}}{{$p->allow_sale?'(بيع)':''}}{{$p->allow_no_qte?'(بدون كمية)':''}}{{$p->allow_make?'(إنتاج)':''}}">{{$p->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='form-group mb-3 row'>
                        <label class='col-sm-3 pt-2 text-md-left pl-3'>قـســـــم المنتــــج</label>
                        <div class='col-sm-9 position-relative'>
                            <select name='product_category_id' id="selectCutName"
                                    class='form-control-lg form-control p-0 text-right selectpicker'
                                    data-live-search="true">
                                <option value=''>برجاء التحديد</option>
                                @foreach ($cuts as $s)
                                    <option value='{{$s->id}}'>{{$s->name}}</option>
                                @endforeach
                            </select>
                            <button type="button"
                                    id="btn_change_default_category"
                                    class="btn bg-transparent position-absolute px-0 mr-auto tooltips"
                                    data-placement="bottom"
                                    title="تعين كقيمة إفتراضية لقسم المنتج عند إضافة منتج جديد , لمدة 5 أيام!"
                                    style="color: #38c172;top: 3px;left: 50px;"><i class="fas fa-magic"></i>
                            </button>
                        </div>
                    </div>
                    <div class='form-group mb-3 row'>
                        <label class='col-sm-3 pt-2 text-md-left pl-3'>مـلاحـظـــــــــــــــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' name='note'></textarea>
                        </div>
                    </div>
                    <div class='' id="divContainerUnitAndComponent">
                        <div class="show">
                            <h1 data-changeshow class='font-weight-bold text-right mr-2'>الوحدة الأولى
                                <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                   style="top: 10px;right: 10px"></i></span>
                            </h1>
                            <div class='box-shadow px-3 pt-3 pb-1 bg-white data-container text-dark'>
                                <div class="h3 mb-0" dir="rtl">
                                    <div class='form-group mb-3 row no-gutters'>
                                        <div class="row no-gutters col-12 col-md-6">
                                            <label class="col-sm-5 text-md-left pl-3 pt-2 tooltips"
                                                   data-placement='bottom'
                                                   title='أصغر وحدة فى هذا المنتج'>إســــــم الوحــــــدة</label>
                                            <div class='col-sm-7 position-relative'>
                                                <select id='selectUnitName' data-select_unit
                                                        class="selectpicker form-control"
                                                        name="product_unit_id"
                                                        data-live-search="true">
                                                    <option data-style="padding-bottom: 50px!important;" value="">
                                                        برجاء التحديد
                                                    </option>
                                                    @foreach($units as $c)
                                                        <option data-default_min_qte="{{$c->default_value_for_min_qte}}"
                                                                value="{{$c->id}}"
                                                                data-style="padding-bottom: 50px!important;">{{$c->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="button"
                                                        id="btn_change_default_unit"
                                                        class="btn bg-transparent position-absolute px-0 mr-auto tooltips"
                                                        data-placement="bottom"
                                                        title="تعين كقيمة إفتراضية لوحدة المنتج عند إضافة منتج جديد , لمدة 5 أيام!"
                                                        style="color: #38c172;top: 3px;left: 50px;"><i
                                                        class="fas fa-magic"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row no-gutters col-12 col-md-6 mt-3 mt-md-0  tooltips"
                                             data-placement='bottom'
                                             title='إظهار المنتج فى النواقص عندما تقل الكمية عن هذا الرقم ويمكن أن يكون 0'>
                                            <label class='col-sm-5 pt-2 text-md-left pl-2'>أقل عدد من الوحدة</label>
                                            <div class='col-sm-7'>
                                                <input type="number" style="height: 45px" class="form-control" required
                                                       value="0"
                                                       onclick="$(this).select();"
                                                       name="min_qte"
                                                       data-validate='qte'
                                                       data-patternType='qte'
                                                       id="inputMinNumberOfProduct" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class='form-group mb-3 row {{Hash::check('use_barcode',$permit->use_barcode)?'':'d-none'}}'>
                                        <label class='col-sm-3 text-md-left pt-0 pt-md-2'>بـــــــاركود هذة
                                            الــوحدة</label>
                                        <div class="col row no-gutters">
                                            <div class="col input-group">
                                                <div class="input-group-append tooltips" style="height: 48px"
                                                     data-placement='bottom'
                                                     title='إنشاء الباركود '>
                                                    <button type="button" class="btn btn-primary px-1"
                                                            data-create_barcode>
                                                        <i
                                                            class="fas fa-marker"></i>
                                                    </button>
                                                </div>
                                                <input type="text" style="height: 48px" name='barcode1'
                                                       data-input_barcode
                                                       class="form-control tooltips" data-placement='bottom'
                                                       title='الباركود ' placeholder="الباركود ">
                                            </div>
                                            @if (Hash::check('use_barcode2',$permit->use_barcode2))
                                                <div class="col">
                                                    <input type="text" style="height: 48px" name='barcode2'
                                                           onclick="$(this).select();"
                                                           class="form-control tooltips" data-placement='bottom'
                                                           data-input_barcode
                                                           title='الباركود الثانى' placeholder="الباركود الثانى">
                                                </div>
                                            @endif
                                            @if (Hash::check('use_barcode3',$permit->use_barcode3))
                                                <div class="col">
                                                    <input type="text" style="height: 48px" name='barcode3'
                                                           data-input_barcode onclick="$(this).select();"
                                                           class="form-control tooltips" data-placement='bottom'
                                                           title='الباركود الثالث' placeholder="الباركود الثالث">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class='form-group mb-3 row'>
                                        <label class='col-sm-3 text-md-left'>سعـــــر شـــراء الوحــدة</label>
                                        <div class="input-group col-sm-9">
                                            <input type="text"
                                                   data-buy onclick="$(this).select();"
                                                   data-validate='price'
                                                   data-patternType='price'
                                                   style="height: 45px"
                                                   {{$setting->use_small_price?'data-small_price':''}}
                                                   name='price_buy' value="0" required
                                                   class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text"
                                                      style="font-size: 1.2rem!important;">جنية</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-group mb-0 row mb-1'>
                                        <label class='col-sm-3 text-md-left pt-0 pt-md-2'>سعـــــــــــــــر
                                            البــيـــــع
                                        </label>
                                        <div class="input-group col-sm-9 row no-gutters">
                                            <div class="col">
                                                <p class="priceName {{Hash::check('use_price2',$permit->use_price2)?'':'d-none'}}">{{$setting->price1_name}}</p>
                                                <input type="text"
                                                       data-sale onclick="$(this).select();"
                                                       data-validate='price'
                                                       {{$setting->use_small_price?'data-small_price':''}}
                                                       data-patternType='price'
                                                       style="height: 45px"
                                                       id="priceSale1"
                                                       name='price_sale1' value="0" required
                                                       class="form-control">
                                                <span class="priceProfit"></span>
                                            </div>
                                            @if (Hash::check('use_price2',$permit->use_price2))
                                                <div class="col">
                                                    <p class="priceName">{{$setting->price2_name}}</p>
                                                    <input type="text"
                                                           data-sale onclick="$(this).select();"
                                                           data-validate='price'
                                                           {{$setting->use_small_price?'data-small_price':''}}
                                                           data-patternType='price'
                                                           style="height: 45px"
                                                           id="priceSale2"
                                                           name='price_sale2' value="0" required
                                                           class="form-control">
                                                    <span class="priceProfit"></span>
                                                </div>
                                            @endif
                                            @if (Hash::check('use_price3',$permit->use_price3))
                                                <div class="col">
                                                    <p class="priceName">{{$setting->price3_name}}</p>
                                                    <input type="text"
                                                           data-sale onclick="$(this).select();"
                                                           data-validate='price'
                                                           {{$setting->use_small_price?'data-small_price':''}}
                                                           data-patternType='price'
                                                           style="height: 45px"
                                                           id="priceSale3"
                                                           name='price_sale3' value="0" required
                                                           class="form-control">
                                                    <span class="priceProfit"></span>
                                                </div>
                                            @endif
                                            @if (Hash::check('use_price4',$permit->use_price4))
                                                <div class="col">
                                                    <p class="priceName">{{$setting->price4_name}}</p>
                                                    <input type="text"
                                                           data-sale onclick="$(this).select();"
                                                           data-validate='price'
                                                           {{$setting->use_small_price?'data-small_price':''}}
                                                           data-patternType='price'
                                                           style="height: 45px"
                                                           id="priceSale4"
                                                           name='price_sale4' value="0" required
                                                           class="form-control">
                                                    <span class="priceProfit"></span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class='form-group mb-3 mt-3 row' id="div_container_stoke">
                                        <label class='col-sm-3 text-md-left tooltips'
                                        data-placement='bottom'
                                        title='القيمة الإفتراضية لكمية المنتج فى المخزن بسعر الشراء'>كمية المنتج فى المخزن</label>
                                        <div class='col position-relative'>
                                            <select id='select_device_stoke'
                                                    class="selectpicker form-control"
                                                    name="device_stoke"
                                                    data-live-search="true">
                                                {{--<option value="0" selected data-style="padding-bottom: 50px!important;">
                                                     بدون
                                                </option>--}}
                                                @foreach ($devise_stokes['allowedStoke'] as $d)                                                    <option data-default_min_qte="{{$c->default_value_for_min_qte}}"
                                                            value="{{$d->stoke->id}}"
                                                            data-style="padding-bottom: 50px!important;">{{$d->stoke->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button"
                                                    id="btn_change_default_stoke"
                                                    class="btn bg-transparent position-absolute px-0 mr-auto tooltips"
                                                    data-placement="bottom"
                                                    title="تعين كقيمة إفتراضية للمخزن عند إضافة منتج جديد , لمدة 5 أيام!"
                                                    style="color: #38c172;top: 3px;left: 50px;"><i
                                                    class="fas fa-magic"></i>
                                            </button>
                                        </div>
                                        <div class="row no-gutters col mt-3 mt-md-0  tooltips"
                                             data-placement='bottom'
                                             title='كمية المنتج فى المخزن بالوحدة الحالية'>
                                            <label class='col-sm-5 pt-2 text-md-left pl-2'>الكمية</label>
                                            <div class='col-sm-7'>
                                                <input type="text" style="height: 45px" class="form-control" required
                                                       value="0"
                                                       onclick="$(this).select();"
                                                       name="qte_stoke"
                                                       id="qte_stoke"
                                                       data-validate='qte'
                                                       data-patternType='qte'
                                                       min="0">
                                            </div>
                                        </div>
                                    </div>

                                    <span data-total_component
                                          class="d-block text-center mb-0 font-en w-100 text-success h5 font-weight-bold d-none text-center"></span>
                                </div>
                            </div>
                        </div>
                        <div data-new_unit class="show">
                            <h1 data-changeshow class='font-weight-bold d-inline-block text-right mr-2'>وحدة أخرى
                                <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                   style="top: 10px;right: 10px"></i></span>
                            </h1>
                            <button type="button" data-remove_unit class="btn btn-danger mr-5 py-1">
                                <spane class="h3">حذف</spane>
                            </button>
                            <div class='box-shadow p-3 bg-white data-container text-dark'
                                 style="padding-bottom: 0.2rem!important;">
                                <div class="h3 " dir="rtl">
                                    <div class="h3 mb-0 " dir="rtl">
                                        <div class='form-group row no-gutters'>
                                            <div class="row no-gutters col-12 col-md-6">
                                                <label class="col-sm-5 text-md-left pl-3 pt-2 ">إســــــم
                                                    الوحــــــدة</label>
                                                <div class='col-sm-7'>
                                                    <select data-select_unit
                                                            name="relation_product_unit_id[]"
                                                            class="selectpicker form-control"
                                                            data-live-search="true">
                                                        <option data-style="padding-bottom: 50px!important;" value="">
                                                            برجاء التحديد
                                                        </option>
                                                        @foreach($units as $c)
                                                            <option
                                                                value="{{$c->id}}"
                                                                data-style="padding-bottom: 50px!important;">{{$c->name}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button"
                                                            data-id="btn_change_default_other_unit"
                                                            class="btn bg-transparent position-absolute px-0 mr-auto tooltips"
                                                            data-placement="bottom"
                                                            title="تعين كقيمة إفتراضية للوحدة الإضافية للمنتج عند إضافة منتج جديد , لمدة 5 أيام!"
                                                            style="color: #38c172;top: 3px;left: 50px;"><i
                                                            class="fas fa-magic"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="row no-gutters col-12 col-md-6 mt-3 mt-md-0  tooltips"
                                                 data-placement='bottom'
                                                 title='كم تحتوى هذه الوحدة من الوحد الأولى ويمكن إدخال رقم أصغر من 1 فى حالة إذا كانت هذة الوحدة أصغر من الوحدة الأولى مثال (0.5)'>
                                                <label class='col-sm-5 pt-2 text-md-left pl-2'>العلاقة بالوحدة
                                                    الأولى</label>
                                                <div class='col-sm-7'>
                                                    <input type="text" style="height: 45px" class="form-control"
                                                           required
                                                           name="relation_qte[]"
                                                           value="" onclick="$(this).select();"
                                                           data-validate='qte'
                                                           data-patternType='qte'
                                                            >
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class='form-group mb-3 row {{Hash::check('use_barcode',$permit->use_barcode)?'':'d-none'}}'>
                                            <label class='col-sm-3 text-md-left pt-0 pt-md-2'>بـــــــاركود هذة
                                                الــوحدة</label>
                                            <div class="row no-gutters col">
                                                <div class="col input-group">
                                                    <div class="input-group-append tooltips" style="height: 48px"
                                                         data-placement='bottom'
                                                         title='إنشاء الباركود '>
                                                        <button type="button" class="btn btn-primary px-1"
                                                                data-create_barcode><i
                                                                class="fas fa-marker"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" style="height: 48px" name='relation_barcode1[]'
                                                           data-input_barcode
                                                           class="form-control tooltips" data-placement='bottom'
                                                           title='الباركود ' placeholder="الباركود ">
                                                </div>
                                                @if (Hash::check('use_barcode2',$permit->use_barcode2))
                                                    <div class="col">
                                                        <input type="text" style="height: 48px"
                                                               name='relation_barcode2[]'
                                                               data-input_barcode onclick="$(this).select();"
                                                               class="form-control tooltips" data-placement='bottom'
                                                               title='الباركود الثانى' placeholder="الباركود الثانى">
                                                    </div>
                                                @endif
                                                @if (Hash::check('use_barcode3',$permit->use_barcode3))
                                                    <div class="col">
                                                        <input type="text" style="height: 48px"
                                                               name='relation_barcode3[]'
                                                               data-input_barcode onclick="$(this).select();"
                                                               class="form-control tooltips" data-placement='bottom'
                                                               title='الباركود الثالث' placeholder="الباركود الثالث">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class='form-group mb-3 row'>
                                            <label class='col-sm-3 text-md-left'>سعـــــر شـــراء الوحــدة</label>
                                            <div class="input-group col-sm-9">
                                                <input type="text"
                                                       data-buy onclick="$(this).select();"
                                                       data-validate='price'
                                                       {{$setting->use_small_price?'data-small_price':''}}
                                                       data-patternType='price'
                                                       style="height: 45px" name='relation_price_buy[]' value="0"
                                                       required
                                                       class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" style="font-size: 1.2rem!important;">جنية</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='form-group mb-3 row'>
                                            <label class='col-sm-3 text-md-left pt-0 pt-md-2'>سعـــــــــــــــر
                                                البــيـــــع
                                            </label>
                                            <div class="input-group col-sm-9 row no-gutters">
                                                <div class="col">
                                                    <p class="priceName {{Hash::check('use_price2',$permit->use_price2)?'':'d-none'}}">{{$setting->price1_name}}</p>
                                                    <input type="text"
                                                           data-sale onclick="$(this).select();"
                                                           data-validate='price'
                                                           {{$setting->use_small_price?'data-small_price':''}}
                                                           data-patternType='price'
                                                           style="height: 45px"
                                                           name='relation_price_sale1[]' value="0" required
                                                           class="form-control">
                                                    <span class="priceProfit"></span>
                                                </div>
                                                @if (Hash::check('use_price2',$permit->use_price2))
                                                    <div class="col">
                                                        <p class="priceName">{{$setting->price2_name}}</p>
                                                        <input type="text"
                                                               data-sale onclick="$(this).select();"
                                                               data-validate='price'
                                                               {{$setting->use_small_price?'data-small_price':''}}
                                                               data-patternType='price'
                                                               style="height: 45px"
                                                               name='relation_price_sale2[]' value="0" required
                                                               class="form-control">
                                                        <span class="priceProfit"></span>
                                                    </div>
                                                @endif
                                                @if (Hash::check('use_price3',$permit->use_price3))
                                                    <div class="col">
                                                        <p class="priceName">{{$setting->price3_name}}</p>
                                                        <input type="text"
                                                               data-sale onclick="$(this).select();"
                                                               data-validate='price'
                                                               {{$setting->use_small_price?'data-small_price':''}}
                                                               data-patternType='price'
                                                               style="height: 45px"
                                                               name='relation_price_sale3[]' value="0" required
                                                               class="form-control">
                                                        <span class="priceProfit"></span>
                                                    </div>
                                                @endif
                                                @if (Hash::check('use_price4',$permit->use_price4))
                                                    <div class="col">
                                                        <p class="priceName">{{$setting->price4_name}}</p>
                                                        <input type="text"
                                                               data-sale onclick="$(this).select();"
                                                               data-validate='price'
                                                               {{$setting->use_small_price?'data-small_price':''}}
                                                               data-patternType='price'
                                                               style="height: 45px"
                                                               name='relation_price_sale4[]' value="0" required
                                                               class="form-control">
                                                        <span class="priceProfit"></span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btnAddNewUnit" class="btn btn-primary w-100" type="button">
                            <span class="h2">إضافــــة وحــدة</span>
                        </button>
                        <div data-new_component class="mt-3 show">
                            <h1 data-changeshow class='font-weight-bold d-inline-block text-right mr-2'>مكونات
                                الوحدة
                                الأولى
                                <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                   style="top: 10px;right: 10px"></i></span>
                            </h1>
                            <button type="button" data-remove_component class="btn btn-danger mr-5 py-1">
                                <spane class="h3">حذف</spane>
                            </button>
                            <div class='box-shadow p-3 mb-0 bg-white data-container text-dark'>
                                <div class="h3 mb-0" dir="rtl">
                                    <div class="row">
                                        <div class="row no-gutters col-12 col-md-6 mb-3 mb-md-0">
                                            <label class='col-sm-4 pt-2 text-md-left pl-3'>إســم المنتـج</label>
                                            <div class='col-sm-8'>
                                                <select data-select_component
                                                        onchange="$(this).parent().parent().parent().next().find('span.input-group-text').html($(this).find('option:selected').attr('data-unit'));"
                                                        name="relation_creator_id[]"
                                                        class="selectpicker  show-tick form-control"
                                                        data-live-search="true">
                                                    <option data-style="padding-bottom: 50px!important;" value="">
                                                        برجاء
                                                        التحديد
                                                    </option>
                                                    @foreach($products as $p)
                                                        @if (($p->allow_buy || $p->allow_make) && $p->allow_no_qte==false)
                                                            <option
                                                                value="{{$p->id}}"
                                                                data-price_buy="{{$p->price_buy}}"
                                                                data-unit="{{$p->productUnit->name}}"
                                                                data-subtext="{{$p->allow_buy?'(شراء)':''}}{{$p->allow_sale?'(بيع)':''}}{{$p->allow_no_qte?'(بدون كمية)':''}}{{$p->allow_make?'(إنتاج)':''}}"
                                                                data-style="padding-bottom: 50px!important;">{{$p->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class='form-group col-12 col-md-6 row mt-3 mt-md-0 no-gutters mb-0'>
                                            <label
                                                class='col-sm-4 pt-2 text-md-left pl-3 mt-0'>الكميــــــــــــة</label>
                                            <div class="input-group mb-0 col-sm-8">
                                                <input type="text"
                                                       data-validate='qte'
                                                       data-patternType='qte'
                                                       data-creator_qte onclick="$(this).select();"
                                                       style="height: 45px" name='relation_qte_creator[]' value="0"
                                                       required
                                                       class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"
                                                          style="font-size: 1.2rem!important;"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btnAddNewComponent" class="btn btn-primary w-100 mt-2" type="button">
                            <span class="h2">إضافــــة مكون</span>
                        </button>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit' id="button_save"
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h3 font-weight-bold'>إضــــــافة</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <a href='{{route('products.index')}}'
                               class='font-weight-bold mt-2 mb-2 form-control text-white btn btn-success animated bounceInLeft fast'>
                                <span class='h3 font-weight-bold'>إلغـــــــاء</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>

    </main>
@endsection
@section('js')
    <script src='{{ asset('js/lib/select_search.js') }}'></script>
    <script defer>
        design.useSound();
        alertify.success('الوحدة الأولى يفضل أن تكون أصغر وحدة فى المنتج');

        var lastBarcode = '{{$barcode->last_barcode+1}}';
        //create barcode
        $('#divContainerUnitAndComponent').on('click', 'button[data-create_barcode]', function () {
            $(this).hide(0);
            $(this).parent().next().val(lastBarcode);
            lastBarcode++;
            design.useSound('success');
            alertify.success('تم الإنشاء بنجاح');
        });

        /*toggle between div*/
        $('#divContainerUnitAndComponent').on('click', 'h1[data-changeshow]', function () {
            $('input[data-attr="check_all"]').addClass('d-none');
            $(this).find('input[data-attr="check_all"]').removeClass('d-none');
            if ($(this).parent().hasClass('show')) {
                $(this).parent().removeClass('show').siblings().removeClass('show');
            } else {
                $(this).parent().addClass('show').siblings().removeClass('show');
            }
            changeShowIndivContainerUnitAndComponent(300);
            design.useSound('success');
        });
        changeShowIndivContainerUnitAndComponent();

        function changeShowIndivContainerUnitAndComponent(time = 300) {
            $('#divContainerUnitAndComponent>div').each(function () {
                if ($(this).hasClass('show')) {
                    $(this).children('div').slideDown(time);
                } else {
                    $(this).children('div').slideUp(time);
                }
            });
            design.updateNiceScroll(700);
        }

        //select type if add has old type
        var type = location.search;
        if (type == '') {
            $('#checkBuy').prop('checked', true);
            $('#checkSale').prop('checked', true);
        } else {
            if (type.indexOf('allow_buy') != -1) {
                $('#checkBuy').prop('checked', true);
            } else {
                $('#checkBuy').prop('checked', false);
            }
            if (type.indexOf('allow_sale') != -1) {
                $('#checkSale').prop('checked', true);
            } else {
                $('#checkSale').prop('checked', false);
            }
            if (type.indexOf('allow_make') != -1) {
                $('#checkMake').prop('checked', true);
            } else {
                $('#checkMake').prop('checked', false);
            }
            if (type.indexOf('allow_no_qte') != -1) {
                $('#checkNoQte').prop('checked', true);
            } else {
                $('#checkNoQte').prop('checked', false);
            }
        }


        $('#inputName').keyup(function () {
            select_search($('#inputName').val(), $('#selectExistName option'), 'لا يوجد منتج بهذا الاسم');
            $('#selectExistName').trigger('change');
        });

        validateByAttr();

        //add new unit
        var newUnit = $('#divContainerUnitAndComponent div[data-new_unit]').clone();
        $('#divContainerUnitAndComponent div[data-new_unit]').remove();
        $('#btnAddNewUnit').click(function () {
            $(this).before(newUnit.clone());
            console.log($('#divContainerUnitAndComponent div[data-new_unit]'));
            if($('#divContainerUnitAndComponent div[data-new_unit]').length==1){
                if (Cookie.get('def_val_other_unit_create_product') != '') {
                    var tempValue = Cookie.get('def_val_other_unit_create_product');
                    $('#divContainerUnitAndComponent div[data-new_unit] select option[value="' + tempValue + '"]').prop('selected', true);
                }
            }

            $('.selectpicker').selectpicker('refresh');
            check_price_show();
            validateByAttr();
            design.disable_input_submit_when_enter('form input');
            design.updateNiceScroll(800);
            design.useSound();
            design.useToolTip();
        });
        //remove unit
        $('#divContainerUnitAndComponent').on('click', 'button[data-remove_unit]', function () {
            $(this).parent().remove();
            design.useSound();
        });

        //add new component
        var newComponent = $('#divContainerUnitAndComponent div[data-new_component]').clone();
        $('#divContainerUnitAndComponent div[data-new_component]').remove();
        $('#btnAddNewComponent').click(function () {
            $(this).before(newComponent.clone());
            $('.selectpicker').selectpicker('refresh');
            validateByAttr();
            design.updateNiceScroll(800);
            design.useSound();
            design.useToolTip();
        });
        //remove component
        $('#divContainerUnitAndComponent').on('click', 'button[data-remove_component]', function () {
            $(this).parent().remove();
            design.useSound();
        });

        //check type of product buy and sale to show price buy and sale
        function check_price_show() {
            if ($('#checkBuy').prop('checked')) {
                $('#divContainerUnitAndComponent input[data-buy]').parent().parent().show();
            } else {
                $('#divContainerUnitAndComponent input[data-buy]').val('0').parent().parent().hide();
            }

            if ($('#checkSale').prop('checked')) {
                $('#divContainerUnitAndComponent input[data-sale]').parent().parent().parent().show();
            } else {
                $('#divContainerUnitAndComponent input[data-sale]').val('0').parent().parent().parent().hide();
            }
            updateProfitMainUnit();
            updateProfitOtherUnit();
            design.updateNiceScroll();
        };
        check_price_show();
        $('#checkBuy,#checkSale').change(function () {
            check_price_show();
        });

        //show and (hide with delete) component when toggle check make
        $('#checkMake').change(function () {
            if ($('#checkMake').prop('checked')) {
                $('#btnAddNewComponent').show();
                $('#checkNoQte').prop('checked', false).trigger('change');
            } else {
                $('#btnAddNewComponent').hide();
                $('#div_container_stoke').show();
                $('div[data-new_component]').remove();
            }
            getComponentPrice();
        });
        $('#checkMake').trigger('change');

        //show and (hide with delete) unit when toggle check noQte
        $('#checkNoQte').change(function () {
            if ($('#checkNoQte').prop('checked')) {
                $('#div_container_stoke').hide();
                $('#qte_stoke').val('0');

                $('#checkMake').prop('checked', false).trigger('change');
                $('#btnAddNewUnit').hide();
                $('div[data-new_unit]').remove();
                $('#inputMinNumberOfProduct').val('0').parent().parent().hide();
            } else {
                $('#div_container_stoke').show();
                $('#btnAddNewUnit').show();
                $('#inputMinNumberOfProduct').parent().parent().show();
            }
            if ($('#checkMake').prop('checked')){
                $('#div_container_stoke').hide();
                $('#qte_stoke').val('0');
            }
        });
        $('#checkNoQte').trigger('change');

        //set default min_qte_for_product
        $('#selectUnitName').change(function () {
            if ($(this).val() != '') {
                $('#inputMinNumberOfProduct').val($('#selectUnitName option:selected').attr('data-default_min_qte'));
            }
        });


        //check if repeat_unit and selected unit
        function check_unit(e = '') {
            var tempArray = [];
            var result = true;
            $('#divContainerUnitAndComponent select[data-select_unit]').each(function () {
                if ($(this).val() == '') {
                    design.useSound('error');
                    alertify.error('برجاء تحديد نوع الوحدة');
                    if (e != '') {
                        e.preventDefault();
                    }
                    result = false;
                    return false;
                } else {
                    var resultCheck = tempArray.indexOf($(this).val());
                    if (resultCheck == -1) {
                        tempArray.push($(this).val())
                    } else {
                        design.useSound('error');
                        alertify.error('الوحدة ' + $(this).find('option:selected').text() + ' مكررة ');
                        if (e != '') {
                            e.preventDefault();
                        }
                        result = false;
                        return false;
                    }
                }
            });
            return result;
        }

        //check if repeat_barcode
        function check_barcode(e = '') {
            var tempArray = [];
            var result = true;
            $('#divContainerUnitAndComponent input[data-input_barcode]').each(function () {
                if ($(this).val() != '') {
                    var resultCheck = tempArray.indexOf($(this).val());

                    if (resultCheck == -1) {
                        tempArray.push($(this).val())
                    } else {
                        design.useSound('error');
                        alertify.error('الباركود ' + $(this).val() + ' مكرر ');
                        if (e != '') {
                            e.preventDefault();
                        }
                        result = false;
                        return false;
                    }
                }
            });
            return result;
        }

        //check if repeat_component and elected commponent
        function check_component(e = '') {
            var tempArray = [];
            var result = true;
            $('#divContainerUnitAndComponent select[data-select_component]').each(function () {
                if ($(this).val() == '') {
                    design.useSound('error');
                    alertify.error('برجاء تحديد إسم المنتج فى المكونات');
                    if (e != '') {
                        e.preventDefault();
                    }
                    result = false;
                    return false;
                } else {
                    var resultCheck = tempArray.indexOf($(this).val());
                    if (resultCheck == -1) {
                        tempArray.push($(this).val())
                    } else {
                        design.useSound('error');
                        alertify.error('المكون ' + $(this).find('option:selected').text() + ' مكرر ');
                        result = false;
                        return false;
                    }
                }
            });
            if (result) {
                $('#divContainerUnitAndComponent input[data-creator_qte]').each(function () {
                    if ($(this).val() <= 0) {
                        design.useSound('error');
                        alertify.error('برجاء إدخال كمية صحيحة للمنتج المستخدم فى الإنتاج');
                        if (e != '') {
                            e.preventDefault();
                        }
                        result = false;
                        return false;
                    }
                });
            }

            return result;
        }

        //check if select cutegory
        function check_category(e = '') {
            if ($('#selectCutName').val() == '') {
                if (e != '') {
                    e.preventDefault();
                }
                design.useSound('error');
                alertify.error('برجاء تحديد قسم المنتج');
                return false;
            }
        }

        $('#formSave').submit(function (e) {

            //check if selected type of product((buy or sale or make) at leset one of this)
            if (!$('#checkMake').prop('checked') && !$('#checkBuy').prop('checked') && !$('#checkSale').prop('checked')) {
                e.preventDefault();
                design.useSound('error');
                alertify.error("برجاء تحديد نوع للمنتج من (شراء - بيع - إنتاج)");
                return;
            }
            if (!$('#checkMake').prop('checked') && $('#checkSale').prop('checked') && !$('#checkMake').prop('checked') && !$('#checkBuy').prop('checked')) {
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء تحديد نوع للمنتج من (شراء - إنتاج) لا يوجد مصدر للمنتج');
                return;
            }

            if (check_category(e) == false) {
                return;
            }
            if (check_unit(e) == false) {
                return;
            }

            //check component relation validation
            /*if($('div.error-gt').length){
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء التحقق من العلاقة بين الوحدات');
                return;
            }*/

            if (check_barcode(e) == false) {
                return;
            }
            if (check_component(e) == false) {
                return;
            }

            //check if type is make and no qte
            if ($('#checkMake').prop('checked')) {
                if ($('#divContainerUnitAndComponent select[data-select_component]').length == 0) {
                    e.preventDefault();
                    design.useSound('error');
                    alertify.error('برجاء إضافة مكونات المنتج');
                    return;
                }
            }
            design.check_submit($(this), e);

        });
        //hide btn add new unit when type of product in noQte
        design.useNiceScroll();

        /*get profit for product*/
        $('#divContainerUnitAndComponent').on('keyup', "input[name='price_buy'],#priceSale1,#priceSale2,#priceSale3,#priceSale4", function () {
            updateProfitMainUnit();
        });
        $('#divContainerUnitAndComponent').on('keyup', "input[name='relation_price_buy[]'],input[name='relation_price_sale1[]'],input[name='relation_price_sale2[]'],input[name='relation_price_sale3[]'],input[name='relation_price_sale4[]']", function () {
            updateProfitOtherUnit();
        });

        function updateProfitMainUnit() {
            $('#divContainerUnitAndComponent>div:first').each(function () {
                var price_buy = $(this).find("input[name='price_buy']").val();
                var price_sale1 = $(this).find("input[name='price_sale1']");
                var price_sale2 = $(this).find("input[name='price_sale2']");
                var price_sale3 = $(this).find("input[name='price_sale3']");
                var price_sale4 = $(this).find("input[name='price_sale4']");
                if ($('#checkBuy').prop('checked') && $('#checkSale').prop('checked') && price_buy != 0) {
                    for (var i = 1; i < 5; i++) {
                        var spanProfit = eval('price_sale' + i).next('span.priceProfit');
                        if (spanProfit.is('span')) {
                            if (eval('price_sale' + i).val() > 0) {
                                var profit = eval('price_sale' + i).val() - price_buy;
                                if (profit < 0) {
                                    spanProfit.removeClass('text-success').addClass('text-danger');
                                } else {
                                    spanProfit.addClass('text-success').removeClass('text-danger');
                                }
                                spanProfit.removeClass('d-none').html(
                                    roundTo(profit) +
                                    'ج ' +
                                    '(' + roundTo(((eval('price_sale' + i).val() - price_buy) / price_buy) * 100) +
                                    '%)');
                            } else {
                                spanProfit.addClass('d-none').html('');
                            }
                        } else {
                            eval('price_sale' + i).next().next('span.priceProfit').addClass('d-none').html('');
                        }

                    }
                } else {
                    $(this).find('span.priceProfit').addClass('d-none').html('');
                }
            });
        }

        function updateProfitOtherUnit() {
            $('#divContainerUnitAndComponent>div:not(:first)').each(function () {
                //get profit for other unit
                var price_buy = $(this).find("input[name='relation_price_buy[]']").val();
                var relation_price_sale1 = $(this).find("input[name='relation_price_sale1[]']");
                var relation_price_sale2 = $(this).find("input[name='relation_price_sale2[]']");
                var relation_price_sale3 = $(this).find("input[name='relation_price_sale3[]']");
                var relation_price_sale4 = $(this).find("input[name='relation_price_sale4[]']");
                // var spanProfit = $(this).find('span[data-profit]');
                if ($('#checkBuy').prop('checked') && $('#checkSale').prop('checked') && price_buy > 0) {
                    for (var i = 1; i < 5; i++) {
                        var spanProfit = eval('relation_price_sale' + i).next('span.priceProfit');
                        if (spanProfit.is('span')) {
                            if (eval('relation_price_sale' + i).val() > 0) {
                                var profit = eval('relation_price_sale' + i).val() - price_buy;
                                if (profit < 0) {
                                    spanProfit.removeClass('text-success').addClass('text-danger');
                                } else {
                                    spanProfit.addClass('text-success').removeClass('text-danger');
                                }
                                spanProfit.removeClass('d-none').html(
                                    roundTo(profit) +
                                    'ج ' +
                                    '(' + roundTo(((eval('relation_price_sale' + i).val() - price_buy) / price_buy) * 100) +
                                    '%)');
                            } else {
                                spanProfit.addClass('d-none').html('');
                            }
                        } else {
                            eval('relation_price_sale' + i).next().next('span.priceProfit').addClass('d-none').html('');
                        }
                    }
                } else {
                    $(this).find('span.priceProfit').addClass('d-none').html('');
                }
            });
        }

        function getComponentPrice() {
            var totalMakePrice = 0;
            $('#divContainerUnitAndComponent select[data-select_component]').each(function () {
                var price = $(this).find('option:selected').attr('data-price_buy');
                var qte = $(this).parent().parent().parent().next().find('input[data-creator_qte]').val();
                if (qte > 0 && price != '') {
                    totalMakePrice -= -price * qte;
                }
            });
            if (totalMakePrice > 0) {
                $('span[data-total_component]').removeClass('d-none').html('إجالى سعر مكونات الوحدة الأولى هو ' + roundTo(totalMakePrice) + ' ج ');
            } else {
                $('span[data-total_component]').addClass('d-none').html('');
            }
            design.updateNiceScroll();
        }

        $('#divContainerUnitAndComponent').on('change keyup', 'select[data-select_component],input[data-creator_qte]', function () {
            getComponentPrice();
        });


        //set default value for category when add
        if (Cookie.get('def_val_category_create_product') != '') {
            var tempValue = Cookie.get('def_val_category_create_product');
            $('#selectCutName option[value="' + tempValue + '"]').prop('selected', true);
            $('#selectCutName').trigger('change');
        }
        $('#btn_change_default_category').click(function () {
            var select_text = $('#selectCutName').val();
            Cookie.set('def_val_category_create_product', select_text, {
                expires: 5
            });
            design.useSound();
            alertify.success('تم ضبط القيمة الإفتراضية لقسم المنتج عند إضافة منتج جديد , لمدة 5 أيام , إلى ' + $('#selectCutName option:selected').html());
        });

        //set default value for category when add
        if (Cookie.get('def_val_unit_create_product') != '') {
            var tempValue = Cookie.get('def_val_unit_create_product');
            $('#selectUnitName option[value="' + tempValue + '"]').prop('selected', true);
            $('#selectUnitName').trigger('change');
        }
        $('#btn_change_default_unit').click(function () {
            var select_text = $('#selectUnitName').val();
            Cookie.set('def_val_unit_create_product', select_text, {
                expires: 5
            });
            design.useSound();
            alertify.success('تم ضبط القيمة الإفتراضية للوحدة الأولى للمنتج عند إضافة منتج جديد , لمدة 5 أيام , إلى ' + $('#selectUnitName option:selected').html());
        });

        $('#divContainerUnitAndComponent').on('click','button[data-id="btn_change_default_other_unit"]',function () {
            var select=$(this).prev().find('select');
            var select_text = select.val();
            Cookie.set('def_val_other_unit_create_product', select_text, {
                expires: 5
            });
            design.useSound();
            alertify.success('تم ضبط القيمة الإفتراضية للوحدة الإضافية للمنتج عند إضافة منتج جديد , لمدة 5 أيام , إلى ' + select.find('option:selected').html());
        });
        //set default value for stoke when add
        if (Cookie.get('def_val_stoke_create_product') != '') {
            var tempValue = Cookie.get('def_val_stoke_create_product');
            $('#select_device_stoke option[value="' + tempValue + '"]').prop('selected', true);
        }
        $('#btn_change_default_stoke').click(function () {
            var select_text = $('#select_device_stoke').val();
            Cookie.set('def_val_stoke_create_product', select_text, {
                expires: 5
            });
            design.useSound();
            alertify.success('تم ضبط القيمة الإفتراضية لمخزن المنتج عند إضافة منتج جديد , لمدة 5 أيام , إلى ' + $('#select_device_stoke option:selected').html());
        });

        //disable submit when enter in barcode
        design.disable_input_submit_when_enter('form input');

        //auto submit when key add
        design.click_when_key_add('#button_save');
    </script>
@endsection
