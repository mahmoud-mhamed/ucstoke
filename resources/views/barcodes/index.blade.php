<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 22/01/2019
 * Time: 10:56 م
 */ ?>
@extends('layouts.app')
@section('title')
    ضبط خصائص الباركود
@endsection
@section('css')
    <style>
        input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
        }

        .data-container label {
            cursor: pointer;
            margin: 10px 20px;
        }

        div > h1 i, div > h1 {
            transition: all ease-in-out 0.3s;
            color: #7c7c7d;;
            text-shadow: 1px 1px #1e4858, 1px 1px 4px #5624e4;
        }

        div.show > h1 i {
            transform: rotate(-90deg);
            color: white;
        }

        div.show > h1 {
            color: white;
        }

        input[type=number], span.input-group-text {
            height: 40px;
        }

        select {
            height: 45px !important;
        }

        #contentAllData > div > h1 {
            cursor: pointer;
        }

        #formSetting .row {
            margin-left: 0px !important;
            margin-right: 0px !important;
            padding-left: 0px !important;
            padding-right: 0px !important;
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4 px-2 pb-2'>
        <form method="post" id="formSetting" action="{{route('barcodes.update',1)}}">
            @csrf
            @method('put')
            <section class='animated text-center fadeInDown ml-auto faster px-1 px-md-4'>
                <h1 class='text-white font-weight-bold pb-3'>ضبط خصائص الباركود</h1>
                <div class="container">
                    <div id="div_container_barcode">
                        <div class="font-en" dir="rtl"
                             style="background: white;width: 150px;height: 150px;margin:auto;overflow: hidden">
                            <p id="p_barcode_company" style="margin: 0px;text-align: center">إسم الشركة</p>
                            <img id="barcode1" class="font-en" style="object-fit: fill{{--contain--}};display: block;margin: auto"/>
                            <div id="barcodeNumber" style="text-align: center;margin: auto"></div>
                            <div class="div_container_barcode_product"
                                 style="display: flex;justify-content: space-between;flex-wrap: wrap">
                                <span id="span_barcode_product_name">إسم المنتج</span>
                                <span id="span_barcode_product_price">سعر المنتج</span>
                                <span id="span_barcode_date">وقت الطباعة</span>
                            </div>
                        </div>
                    </div>
                    <div id="divSettingBarcode" class="box py-3 mt-3 h4  pt-2 px-2 text-right text-md-center">
                        <div class="row">
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6  pt-2'>إســـــــــــــم الشـــركـة</label>
                                <div class='col-12 col-md-6'>
                                    <input type="text" value="{{$b->company_name}}" id="input_company_name"
                                           name="company_name"
                                           data-default="UltimateCode"
                                           data-placement='top' title='إسم الشركة فى الباركود ويمكن أن لا يكتب'
                                           class='form-control tooltips'>
                                </div>
                            </div>
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>حجــم خـــــط الكـــــود</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->barcode_font_size}}" required
                                           id="input_code_font"
                                           data-default="6"
                                           name="barcode_font_size"
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">PX</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6  pt-2'>بــــــــــــاركـود الإختـبـــار</label>
                                <div class='col-12 col-md-6'>
                                    <input type="text" value="1" id="input_barcode_val"
                                           data-placement='top' title='قيمة الباركود المراد طباعتة'
                                           data-default="1"
                                           class='form-control tooltips'>
                                </div>
                            </div>
                            <div class='form-group font-en row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>نـــــــــوع البـــــاركـود</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <select class="custom-select pb-2" readonly name="barcode_type" id="select_type">
                                        <option {{$b->barcode_type=='CODE128'?'selected':''}} value="CODE128">CODE128</option>
                                        <option {{$b->barcode_type=='ean8'?'selected':''}} value="ean8">EAN8 / UPC</option>
                                        <option {{$b->barcode_type=='CODE39'?'selected':''}} value="CODE39">CODE39</option>
                                        <option {{$b->barcode_type=='ITF14'?'selected':''}} value="ITF14">ITF-14</option>
                                        <option {{$b->barcode_type=='MSI'?'selected':''}} value="MSI">MSI</option>
                                        <option {{$b->barcode_type=='pharmacode'?'selected':''}} value="pharmacode">pharmacode</option>
                                        <option {{$b->barcode_type=='codabar'?'selected':''}} value="codabar">codabar</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6  pt-2'>عـــــــــرض المـــلـصـق</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="10" value="{{$b->barcode_width}}" required
                                           id="input_width"
                                           name="barcode_width"
                                           data-default="38"
                                           class='form-control'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">ملم</span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>طـــــــــــول الـمـلـصـق</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="10" value="{{$b->barcode_height}}" required
                                           id="input_height"
                                           name="barcode_height"
                                           data-default="25"
                                           class='form-control'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">ملم</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6  pt-2'>حجم خط إسم الشركة</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->company_name_font_size}}" required
                                           id="input_company_font"
                                           name="company_name_font_size"
                                           data-default="8"
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">PX</span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group font-en row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>لــون إســم الشركة</label>
                                <div class='col-12 col-md-6 '>
                                    <input type="color" data-default="#000000"
                                           name="company_name_color"
                                           id="input_company_color" value="{{$b->company_name_color}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6  pt-2'>حجم خط إسـم المنتج</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->product_font_size}}" required
                                            name='product_font_size'
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           id="input_product_font"
                                           data-default="6"
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">PX</span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group font-en row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>لــون إســـم المنتج</label>
                                <div class='col-12 col-md-6 '>
                                    <input type="color" name='product_color' id="input_product_color" data-default="#000000" value="{{$b->product_color}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>حجم خـــــط الســــعــر</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->price_font_size}}" required
                                           name="price_font_size"
                                           id="input_price_font" data-default="6"
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">PX</span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group font-en row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>لـــــــــــون السـعـــــر</label>
                                <div class='col-12 col-md-6 '>
                                    <input type="color" name="price_color" id="input_price_color" data-default="#000000" value="{{$b->price_color}}" class="form-control">
                                </div>
                            </div>
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6  pt-2'>حجم خط وقت الإنشاء</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->time_font_size}}" required
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           id="input_date_font"
                                           name="time_font_size"
                                           data-default="6"
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">PX</span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group font-en row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>لــون وقـت الإنشاء</label>
                                <div class='col-12 col-md-6 '>
                                    <input type="color" id="input_date_color" name="time_color" data-default="#000000" value="{{$b->time_color}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6  pt-2'>الهـامش مــــن الأعلى</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->padding_top}}" required
                                           name="padding_top"
                                           id="input_margin_top"
                                           data-default="1"
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">ملم</span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>الهامش مـــن الأسـفل</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->padding_bottom}}" required
                                           name="padding_bottom"
                                           data-default="1"
                                           id="input_margin_bottom"
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">ملم</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6  pt-2'>الهـامش مــن اليمين</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->padding_right}}" required
                                           data-default="1"
                                           name="padding_right"
                                           id="input_margin_right"
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">ملم</span>
                                    </div>
                                </div>
                            </div>
                            <div class='form-group row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>الهامش مـــن اليـسار</label>
                                <div class='col-12 col-md-6 input-group'>
                                    <input type="number" min="0" value="{{$b->padding_left}}" required
                                           data-default="1"
                                           name="padding_left"
                                           id="input_margin_left"
                                           data-placement='top' title='يمكن أن يكون 0 عند إخفائة'
                                           class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class="input-group-text">ملم</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class='form-group font-en row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>لـــــــــون البــــاركـود</label>
                                <div class='col-12 col-md-6 '>
                                    <input type="color" id="input_barcode_color" name="barcode_color" data-default="#000000" value="{{$b->barcode_color}}" class="form-control">
                                </div>
                            </div>
                            <div class='form-group font-en row col-12 col-md-6'>
                                <label class='col-12 col-md-6 pt-2'>أخر باركود تم عملة</label>
                                <div class='col-12 col-md-6 '>
                                    <input type="number" min="0"  name="last_barcode" data-default="{{$b->last_barcode}}" value="{{$b->last_barcode}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <div class='col-sm-12 col-md-2'>
                                <button type='button' id="btn_test"
                                        class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                    <span class='h4 font-weight-bold'>معاينة</span>
                                </button>
                            </div>
                            <div class='col-sm-12 col-md-3'>
                                <button type='button' id="btn_print"
                                        class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                    <span class='h4 font-weight-bold'>معاينة و طباعة</span>
                                </button>
                            </div>
                            <div class='col-sm-12 col-md-5'>
                                <button type='button' id="btn_reset"
                                        class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                    <span class='h4 font-weight-bold'>إستعادة الإعدادات الإفتراضية</span>
                                </button>
                            </div>
                            <div class='col-sm-12 col-md-2'>
                                <button type="submit"
                                   class='font-weight-bold mt-2 mb-2 form-control text-white btn btn-success animated bounceInLeft fast'>
                                    <span class='h4 font-weight-bold'>حفظ</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </form>
    </main>
@endsection
@section('js')
    <script defer>
        $('#formSetting').submit(function (e) {
            design.check_submit($(this), e);
        });

        function drow_barcode(input_barcode_val, company_name = '', company_color = 'red', barcode_type = 'auto',
                              barcode_width = 38, barcode_height = 25,
                              company_font = 8, code_font = 6,
                              product_font = 6, product_color = 'black',
                              price_font = 6, price_color = 'black',
                              date_font = 6, date_color = 'black',barcode_color='black',
                              padding_top = 1, padding_right = 1, padding_bottom = 1, padding_left = 1) {
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
                height: barcode_height-padding_top-padding_bottom + 'mm'
            });
            $('#span_barcode_product_name').css({
                fontSize: product_font + 'px',
                color: product_color
            });
            $('#span_barcode_product_price').css({
                fontSize: price_font + 'px',
                color: price_color
            });
            $('#span_barcode_date').html( moment().format('hh:mm')+moment().format('a')+' , '+moment().format('YYYY.MM.D')).css({
                fontSize: date_font + 'px',
                color: date_color
            });
            try{
                JsBarcode("#barcode1", input_barcode_val, {
                    format: barcode_type,
                    width: 3,
{{--                    flat: true,--}}
                    textAlign: 'center',
                    textPosition: 'bottom',
                    // fontSize: code_font,
                    fontSize: 0,
                    margin: 0,
                    background: 'white',
                    lineColor: barcode_color,
                    textMargin: 0
                });
                $('#barcodeNumber').html(input_barcode_val).css('fontSize',code_font+'px');
                $('#barcode1').css({
                    width: (barcode_width - padding_right - padding_left) + 'mm',
                    height: (barcode_height - ($('#p_barcode_company').height() * (25.4 / 96)) -($('#barcodeNumber').height()* (25.4 / 96))-
                        ($('#span_barcode_product_name').parent().height() * (25.4 / 96)) - padding_top - padding_bottom) + 'mm'
                });
                design.useSound();
                alertify.success('تمت العملية بنجاح');
            }catch (e) {
                design.useSound('error');
                alertify.error('باركود الإختبار غير مناسب للنوع المحدد من الباركود');
            }
           {{-- $('#barcode1').css({
                maxWidth: (barcode_width - padding_right - padding_left) + 'mm',
                maxHeight: (barcode_height - ($('#p_barcode_company').height() * (25.4 / 96)) -
                    ($('#span_barcode_product_name').parent().height() * (25.4 / 96)) - padding_top - padding_bottom) + 'mm'
            });--}}
            design.updateNiceScroll();
        }

        $('#btn_test').click(function () {
            drow_barcode($('#input_barcode_val').val(), $('#input_company_name').val(), $('#input_company_color').val(), $('#select_type').val(),
                $('#input_width').val(), $('#input_height').val(),
                $('#input_company_font').val(), $('#input_code_font').val(),
                $('#input_product_font').val(), $('#input_product_color').val(),
                $('#input_price_font').val(), $('#input_price_color').val(),
                $('#input_date_font').val(), $('#input_date_color').val(),$('#input_barcode_color').val(),
                $('#input_margin_top').val(),
                $('#input_margin_right').val(),
                $('#input_margin_bottom').val(),
                $('#input_margin_left').val(),
            );
        });
        $('#btn_test').trigger('click');

        $('#btn_print').click(function () {
            $('#btn_test').trigger('click');
            $('#div_container_barcode').printArea({
                // mode:'popup',
                extraCss:"{{asset('css/barcode.css')}}",
                stopHeader:true,
            });
        });
        $('#btn_reset').click(function () {
            $('#divSettingBarcode input').each(function () {
                $(this).val($(this).attr('data-default'));
            });
            $('#select_type option:first').prop('selected',true);
            $('#btn_test').trigger('click');
        });
        $('#divSettingBarcode input').click(function () {
            design.useSound('info');
            alertify.success('برجاء الضغط على زر معاينة أو معاينة وطباعة لعرض التعديلات');
        });
        $('#divSettingBarcode select').change(function () {
            design.useSound('info');
            alertify.success('برجاء الضغط على زر معاينة أو معاينة وطباعة لعرض التعديلات');
        });

        design.useNiceScroll();

    </script>
@endsection
