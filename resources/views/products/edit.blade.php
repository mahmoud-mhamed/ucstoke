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
    تعديل منتج
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
                <h1 class='text-center mb-4'>تعديل المنتج
                    {{$product->name}}</h1>
                <form id='formSave'
                      action='{{route('products.update',$product->id)}}' class='h5'
                      method='post'>
                    @csrf
                    @method('put')
                    <div class='form-group '>
                        <div id="div_container_type_product" class="text-right text-md-center">
                            <label class="pl-2">نـوع المنتــج :</label>
                            <label class="checkbox-inline d-block d-md-inline-block pl-4 mr-2 pointer tooltips"
                                   data-placement='top' title='منتج يمكن شرائة'
                                   dir="ltr">منتـج شـراء
                                <input type="checkbox" id="checkBuy" name="allow_buy"
                                       {{$product->allow_buy?'checked':''}}
                                       value="1">
                            </label>
                            <label class="checkbox-inline d-block d-md-inline-block pl-4 mr-2 pointer tooltips"
                                   data-placement='top' title='منتج يمكن بيعه'
                                   dir="ltr">منتـج بيـــع<input type="checkbox" id="checkSale"
                                                                {{$product->allow_sale?'checked':''}}
                                                                name="allow_sale" value="1"></label>
                            <label
                                class="checkbox-inline pl-4 mr-2 pointer tooltips {{Hash::check('product_make',$permit->product_make)?'d-block d-md-inline-block':'d-none'}}"
                                data-placement='top' title='منتج يمكن إنتاجة أو عرض مكون من عدة منتجات'
                                dir="ltr">منتـج إنتـاج أو عرض<input type="checkbox" id="checkMake" data-filter_type
                                                                    {{$product->allow_make?'checked':''}}       name="allow_make"
                                                                    value="1"></label>
                            <label
                                class="checkbox-inline mr-2 pointer tooltips
                            {{Hash::check('product_no_qte',$permit->product_no_qte)?(!Hash::check('false',$permit->only_product_no_qte)?'d-none':'d-block d-md-inline-block'):'d-none'}}"
                                data-placement='top'
                                title='منتج بدون كمية مثل (مصاريف الشحن-مصاريف صيانة-او منتج ينتج بدون كمية...إلخ) '
                                dir="ltr">منتج بدون كمية<input type="checkbox" id="checkNoQte" data-filter_type
                                                               {{$product->allow_no_qte?'checked':''}}      name="allow_no_qte"
                                                               {{Hash::check('false',$permit->only_product_no_qte)?'':'checked'}}
                                                               value="1"></label>
                        </div>
                    </div>
                    <div class='form-group mb-3 row no-gutters'>
                        <div class="row no-gutters col-12 col-md-6">
                            <label class='col-sm-5 text-md-left pt-2 pl-3'> إســــــــم المنتــج</label>
                            <div class='col-sm-7'>
                                <input type='text'
                                       id="inputName" onclick="$(this).select();"
                                       value="{{$product->name}}"
                                       style="height: 45px"
                                       data-validate='min:3' data-patternType='min' autofocus required
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
                                                data-subtext="({{$p->allow_buy?'شراء':''}} {{$p->allow_make?'- إنتاج':''}})">{{$p->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='form-group mb-3 row'>
                        <label class='col-sm-3 pt-2 text-md-left pl-3'>قـســـــم المنتــــج</label>
                        <div class='col-sm-9'>
                            <select name='product_category_id' id="selectCutName"
                                    class='form-control-lg form-control p-0 text-right selectpicker'
                                    data-live-search="true">
                                @foreach ($cuts as $s)
                                    <option
                                        value='{{$s->id}}' {{$product->product_category_id==$s->id?'selected':''}}>{{$s->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='form-group mb-3 row'>
                        <label class='col-sm-3 pt-2 text-md-left pl-3'>مـلاحـظـــــــــــــــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' name='note'>{{$product->note}}</textarea>
                        </div>
                    </div>
                    <div class='' id="divContainerUnitAndComponent">
                        <div class="show">
                            <h1 data-changeshow class='font-weight-bold text-right mr-2'>الوحدة الأولى
                                <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                   style="top: 10px;right: 10px"></i></span>
                            </h1>
                            <div class='box-shadow p-3 pb-0 bg-white data-container text-dark'
                                 style="padding-bottom: 0.05rem!important;">
                                <div class="h3 " dir="rtl">
                                    <div class='form-group mb-3 row no-gutters'>
                                        <div class="row no-gutters col-12 col-md-6">
                                            <label class="col-sm-5 text-md-left pl-3 pt-2 tooltips"
                                                   data-placement='bottom'
                                                   title='أصغر وحدة فى هذا المنتج'>إســــــم الوحــــــدة</label>
                                            <div class='col-sm-7'>
                                                <select id='selectUnitName' data-select_unit
                                                        class="selectpicker form-control"
                                                        name="product_unit_id"
                                                        data-live-search="true">
                                                    @foreach($units as $c)
                                                        <option data-default_min_qte="{{$c->default_value_for_min_qte}}"
                                                                value="{{$c->id}}"
                                                                {{$product->product_unit_id==$c->id?'selected':''}}
                                                                data-style="padding-bottom: 50px!important;">{{$c->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row no-gutters col-12 col-md-6 mt-3 mt-md-0  tooltips"
                                             data-placement='bottom'
                                             title='إظهار المنتج فى النواقص عندما تقل الكمية عن هذا الرقم ويمكن أن يكون 0'>
                                            <label class='col-sm-5 pt-2 text-md-left pl-2'>أقل عدد من الوحدة</label>
                                            <div class='col-sm-7'>
                                                <input type="number" style="height: 45px" class="form-control" required
                                                       value="{{$product->min_qte}}"
                                                       name="min_qte" onclick="$(this).select();"
                                                       id="inputMinNumberOfProduct" min="0">
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
                                                <input type="text" style="height: 48px" name='barcode1'
                                                       data-input_barcode
                                                       value="{{$product->barcode1}}"
                                                       class="form-control tooltips" data-placement='bottom'
                                                       title='الباركود ' placeholder="الباركود ">
                                            </div>
                                            @if (Hash::check('use_barcode2',$permit->use_barcode2))
                                                <div class="col">
                                                    <input type="text" style="height: 48px"
                                                           name='barcode2'
                                                           value="{{$product->barcode2}}"
                                                           data-input_barcode onclick="$(this).select();"
                                                           class="form-control tooltips" data-placement='bottom'
                                                           title='الباركود الثانى' placeholder="الباركود الثانى">
                                                </div>
                                            @endif
                                            @if (Hash::check('use_barcode3',$permit->use_barcode3))
                                                <div class="col">
                                                    <input type="text" style="height: 48px"
                                                           name='barcode3'
                                                           value="{{$product->barcode3}}"
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
                                                   style="height: 45px"
                                                   name='price_buy' value="{{$product->price_buy}}" required
                                                   class="form-control">
                                            <div class="input-group-append">
                                                <span class="input-group-text"
                                                      style="font-size: 1.2rem!important;">جنية</span>
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
                                                       id="priceSale1"
                                                       name='price_sale1' value="{{$product->price_sale1}}" required
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
                                                           name='price_sale2' value="{{$product->price_sale2}}" required
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
                                                           name='price_sale3' value="{{$product->price_sale3}}" required
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
                                                           name='price_sale4' value="{{$product->price_sale4}}" required
                                                           class="form-control">
                                                    <span class="priceProfit"></span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <span data-total_component
                                          class="d-block text-center mb-0 font-en w-100 text-success h5 font-weight-bold d-none text-center"></span>
                                </div>
                            </div>
                        </div>
                        @foreach ($product['relationProductUnit'] as $u)
                            <div data-old_unit class="{{$loop->index==0?'show':''}}">
                                <h1 data-changeshow class='font-weight-bold d-inline-block text-right mr-2'>وحدة أخرى
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <input type="hidden" name="counter_product_unit[]" value="{{$u->id}}">
                                <button type="button" data-remove_unit class="btn btn-danger mr-5 py-1">
                                    <spane class="h3">حذف</spane>
                                </button>
                                <div class='box-shadow p-3 bg-white data-container text-dark'
                                     style="padding-bottom: 0.05rem!important;">
                                    <div class="h3 mb-0" dir="rtl">
                                        <div class="h3 " dir="rtl">
                                            <div class='form-group mb-3 row no-gutters'>
                                                <div class="row no-gutters col-12 col-md-6">
                                                    <label class="col-sm-5 text-md-left pl-3 pt-2 ">إســــــم
                                                        الوحــــــدة</label>
                                                    <div class='col-sm-7'>
                                                        <select data-select_unit
                                                                name="relation_product_unit_id[]"
                                                                class="selectpicker form-control"
                                                                data-live-search="true">
                                                            @foreach($units as $c)
                                                                <option
                                                                    value="{{$c->id}}"
                                                                    {{$c->id==$u->product_unit_id?'selected':''}}
                                                                    data-style="padding-bottom: 50px!important;">{{$c->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
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
                                                               name="relation_qte[]" onclick="$(this).select();"
                                                               value="{{$u->relation_qte}}"
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
                                                        <input type="text" style="height: 48px" name='relation_barcode1[]'
                                                               data-input_barcode
                                                               value="{{$u->barcode1}}"
                                                               class="form-control tooltips" data-placement='bottom'
                                                               title='الباركود ' placeholder="الباركود ">
                                                    </div>
                                                    @if (Hash::check('use_barcode2',$permit->use_barcode2))
                                                        <div class="col">
                                                            <input type="text" style="height: 48px" name='relation_barcode2[]'
                                                                   onclick="$(this).select();"
                                                                   class="form-control tooltips" data-placement='bottom'
                                                                   data-input_barcode
                                                                   value="{{$u->barcode2}}"
                                                                   title='الباركود الثانى' placeholder="الباركود الثانى">
                                                        </div>
                                                    @endif
                                                    @if (Hash::check('use_barcode3',$permit->use_barcode3))
                                                        <div class="col">
                                                            <input type="text" style="height: 48px" name='relation_barcode3[]'
                                                                   data-input_barcode onclick="$(this).select();"
                                                                   class="form-control tooltips" data-placement='bottom'
                                                                   value="{{$u->barcode3}}"
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
                                                           style="height: 45px" name='relation_price_buy[]'
                                                           value="{{$u->price_buy}}" required
                                                           class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"
                                                              style="font-size: 1.2rem!important;">جنية</span>
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
                                                               name='relation_price_sale1[]' value="{{$u->price_sale1}}" required
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
                                                                   name='relation_price_sale2[]' value="{{$u->price_sale2}}" required
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
                                                                   name='relation_price_sale3[]' value="{{$u->price_sale3}}" required
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
                                                                   name='relation_price_sale4[]' value="{{$u->price_sale4}}" required
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
                        @endforeach
                        <div data-new_unit class="show">
                            <h1 data-changeshow class='font-weight-bold d-inline-block text-right mr-2'>وحدة أخرى
                                <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                   style="top: 10px;right: 10px"></i></span>
                            </h1>
                            <button type="button" data-remove_unit class="btn btn-danger mr-5 py-1">
                                <spane class="h3">حذف</spane>
                            </button>
                            <div class='box-shadow p-3 bg-white data-container text-dark'
                                 style="padding-bottom: 0.05rem!important;">
                                <div class="h3 " dir="rtl">
                                    <div class="h3 " dir="rtl">
                                        <div class='form-group mb-3 row no-gutters'>
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
                        @foreach ($product['relationProductMake'] as $c)
                            <div data-old_component class="mt-3 show">
                                <h1 data-changeshow class='font-weight-bold d-inline-block text-right mr-2'>مكونات
                                    الوحدة
                                    الأولى
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <input type="hidden" name="counter_product_make[]" value="{{$c->id}}">
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
                                                        @foreach($products as $p)
                                                            @if (($p->allow_buy || $p->allow_make )&& $p->id !=$product->id && $p->allow_no_qte==false)
                                                                <option
                                                                    value="{{$p->id}}"
                                                                    data-price_buy="{{$p->price_buy}}"
                                                                    data-unit="{{$p->productUnit->name}}"
                                                                    {{$c->creator_id==$p->id?'selected':''}}
                                                                    data-subtext="{{$p->allow_buy?'(شراء)':''}}{{$p->allow_sale?'(بيع)':''}}{{$p->allow_no_qte?'(بدون كمية)':''}}{{$p->allow_make?'(إنتاج)':''}}"
                                                                    data-style="padding-bottom: 50px!important;">{{$p->name}}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class='form-group col-12 col-md-6 row no-gutters mb-0'>
                                                <label class='col-sm-4 pt-2 text-md-left pl-3 mt-3 mt-md-0'>الكميــــــــــــة</label>
                                                <div class="input-group mb-0 col-sm-8">
                                                    <input type="text"
                                                           data-validate='qte'
                                                           data-patternType='qte'
                                                           data-creator_qte onclick="$(this).select();"
                                                           style="height: 45px" name='relation_qte_creator[]'
                                                           value="{{$c->qte_creator}}" required
                                                           class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"
                                                              style="font-size: 1.2rem!important;">{{$c->productCreator->productUnit->name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
                                                        @if (($p->allow_buy || $p->allow_make )&& $p->id !=$product->id && $p->allow_no_qte==false)
                                                            <option
                                                                value="{{$p->id}}"
                                                                data-price_buy="{{$p->price_buy}}"
                                                                data-unit="{{$p->productUnit->name}}"
                                                                data-subtext="{{$p->allow_buy?'(شراء)':''}}{{$p->allow_sale?'(بيع)':''}}{{$p->allow_no_qte?'(بدون كمية)':''}}{{$p->allow_make?'(إنتاج)':''}}"
                                                                data-style="padding-bottom: 50px!important;">{{$p->name}}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class='form-group col-12 col-md-6 row no-gutters mb-0'>
                                            <label class='col-sm-4 pt-2 text-md-left pl-3 mt-3 mt-md-0'>الكميــــــــــــة</label>
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
                            <button type='submit' onclick="design.useSound();" id="button_save"
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h3 font-weight-bold'>تعديل</span>
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
                $('#divContainerUnitAndComponent input[data-buy]').parent().parent().hide();
            }

            if ($('#checkSale').prop('checked')) {
                $('#divContainerUnitAndComponent input[data-sale]').parent().parent().parent().show();
            } else {
                $('#divContainerUnitAndComponent input[data-sale]').parent().parent().parent().hide();
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
                $('div[data-new_component],div[data-old_component]').remove();
            }
            getComponentPrice();
        });
        $('#checkMake').trigger('change');

        //show and (hide with delete) unit when toggle check noQte
        $('#checkNoQte').change(function () {
            if ($('#checkNoQte').prop('checked')) {
                $('#checkMake').prop('checked', false).trigger('change');
                $('#btnAddNewUnit').hide();
                $('div[data-new_unit],div[data-old_unit]').remove();
                $('#inputMinNumberOfProduct').val('0').parent().parent().hide();
            } else {
                $('#btnAddNewUnit').show();
                $('#inputMinNumberOfProduct').parent().parent().show();
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
            $('#divContainerUnitAndComponent select[data-select_component]').each(function () {
                if ($(this).val() == '') {
                    design.useSound('error');
                    alertify.success('برجاء تحديد إسم المنتج فى المكونات');
                    if (e != '') {
                        e.preventDefault();
                    }
                    return false;
                } else {
                    var resultCheck = tempArray.indexOf($(this).val());
                    if (resultCheck == -1) {
                        tempArray.push($(this).val())
                    } else {
                        design.useSound('error');
                        alertify.success('المكون ' + $(this).find('option:selected').text() + ' مكرر ');
                        return false;
                    }
                }
            });
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
                alertify.error('برجاء تحديد نوع للمنتج من (شراء - بيع - إنتاج)');
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
            /*//check component relation validation
            if($('div.error-gt').length){
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

        //disable submit when enter in barcode
        design.disable_input_submit_when_enter('form input');

        //auto submit when key add
        design.click_when_key_add('#button_save');
    </script>
@endsection
