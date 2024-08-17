<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 02:16 م
 */ ?>
@extends('layouts.app')
@section('title')
    {{$bill->type==0?'تعديل فاتورة شراء':($bill->type==1?'تعديل فاتورة بيع':'')}}
    @if (isset($bill->account->name))
        ({{$bill->account->name}})
    @else
        (بدون)
    @endif

@endsection
@section('css')
    <style>
        label {
            font-size: 1.5rem;
        }

        button, a {
            height: 47px !important;
        }

        button.dropdown-toggle {
            padding-top: 0px;
        }

        input[type='checkbox'], input[type="radio"] {
            transform: scale(2);
            margin-left: 10px;
        }

        div.error-price, div.error-negative_price {
            width: 25%;
            padding-right: 15px;
        }

        ::placeholder {
            font-size: 1.5rem;
        }

        input ::placeholder {
            text-align: center;
        }

        .h0 {
            font-size: 1.5rem;
            height: 47px;
        }

        #divContainerMoney .col {
            min-width: 350px;
        }

        .invalid-feedback {
            position: absolute;
            width: 100% !important;
            z-index: 3;
        }

        #div_container_new_account .invalid-feedback {
            text-align: left;
            padding-left: 5px;
        }

        .overlay {
            position: fixed;
            z-index: 50;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, .7);
        }

        #divSettingForAddExistProductToDetailsWithSameUnit, #divSettingForDefaultValForAccount {
            position: fixed;
            top: 200px;
            background: #eee;
            z-index: 51;
            margin-left: calc((100vw - 800px) / 2);
            width: 800px;
            border-radius: 20px;
        }

        #divEditRowInTable {
            position: fixed;
            top: 200px;
            background: #eee;
            z-index: 51;
            margin-left: calc((100vw - 800px) / 2);
            width: 800px;
            border-radius: 20px;
            border: 25px;
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
            padding-top: 5px
        }
    </style>
    <style>
        #section_bill_container_check input[type='checkbox'], #section_bill_container_check input[type="radio"] {
            transform: scale(2);
            margin-left: 10px;
        }

        #section_bill_type select {
            height: 53px !important;
            font-size: 1.5rem;
        }

        #section_bill_design .b {
            border: 2px black solid;
        }

        #section_bill_design .b-left {
            border-left: 2px black solid;
        }

        #section_bill_design .width-15 {
            width: 15%;
        }

        #section_bill_design th {
            font-weight: 900;
        }

        #section_bill_design input {
            padding-right: 10px;
            width: 100%;
            border: none;
        }

        #section_bill_design span[data-type='billNumber'], span[data-type='date'] {
            cursor: pointer;
        }

        #section_bill_design #tableData input {
            text-align: center;
        }

        #section_bill_design table tr td {
            padding: 5px 15px;
        }

        #section_bill_design table tr * {
            white-space: nowrap;
        }

        #section_bill_design #tableData tbody tr td:nth-child(2) {
            white-space: normal !important;
        }

        #section_bill_design input {
            min-height: 37px;
        }
    </style>
@endsection
@section('content')
    <div id="divContainerSettingForAddExistProductToDetailsWithSameUnit" class="d-none">
        <div class="overlay"
             onclick="$('#divContainerSettingForAddExistProductToDetailsWithSameUnit').addClass('d-none');design.useSound();"></div>
        <div id="divSettingForAddExistProductToDetailsWithSameUnit" class="text-center pt-2 box-shadow" dir="rtl">
            <h1>عند إضافة منتج بنفس الوحدة إلى الفاتورة</h1>
            <div>
                <label class="checkbox-inline pl-4 mr-3 pointer" dir="ltr">
                    إضافة الكمية إلى الكمية الموجودة<input type="radio" value="0" checked
                                                           name="radioSettingAddExistProduct">
                </label>
                <label class="checkbox-inline pl-4 pointer" dir="ltr">عدم الإضافة إلى الفاتورة
                    <input type="radio" value="1" name="radioSettingAddExistProduct">
                </label>
                <label class="checkbox-inline pl-4 pointer" dir="ltr">
                    إظهار رسالة بالإختيار<input type="radio" value="2" name="radioSettingAddExistProduct">
                </label>
            </div>
        </div>
    </div>
    <div id="divContainerEditRowInTable" class="d-none">
        <div class="overlay" onclick="$('#divContainerEditRowInTable').addClass('d-none');design.useSound();"></div>
        <div id="divEditRowInTable" class="text-center pt-2 box-shadow" dir="rtl">
            <h1>تعديل المنتج
                <span id="spanEditProductName" class="text-danger" style="border-bottom: 2px red solid"></span>
                <button type="button" class="btn px-0 bg-transparent text-primary" id="buttonSaveEditRowInTable"><i
                        class="far fa-2x fa-save"></i></button>
            </h1>
            <div class="row no-gutters">
                <div class='col px-0'>
                    <span class='input-group-text h0'>الكمية</span>
                    <div class="input-group">
                        <input id="input_edit_product_qte" type='text'
                               data-validate='qte' data-patternType="qte"
                               value='0' class='form-control h0'>
                        <div class="input-group-prepend">
                            <div class="input-group-prepend">
                                <span class="input-group-text h0" id="spanEditUnitName"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col px-0'>
                    <span class='input-group-text h0'>السعر</span>
                    <div class="input-group">
                        <input value='0' type='text' data-validate='price'
                               {{$setting->use_small_price?'data-small_price':''}}
                               data-patternType="price"
                               id="inputEditProductPrice" data-max_qte="0" class='form-control h0'>
                        <div class="input-group-prepend">
                            <span class="input-group-text h0">ج</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="inputCheckQteBeforeAddToDetails" value="{{$bill->type==0?'false':'true'}}"
           name="checkQteBeforeAddToDetails">
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='text-right  h5'>
            <div class=' container-fluid px-0 px-md-2 ml-auto '>
                <div class='text-center'>
                    <h1 class='font-weight-bold pb-3 text-white'>تعديل
                        {{$bill->type==0?'فاتورة شراء':($bill->type?'فاتورة بيع':'')}}
                        رقم
                        <span class="font-en text-danger text-underline">{{$bill->id}}</span>
                    </h1>
                    <form id="mainForm" action="{{route('bills.update',$bill->id)}}" method="post">
                        @csrf
                        @method('put')
                        <div class='container-fluid box-shadow p-1 text-white' style='background: rgba(12,84,96,.6);'>
                            <div class='row no-gutters font-en'>
                                <div class='col h4'>
                                    <div class="tooltips text-right" data-placement='bottom' title="المخزن">
                                       مــــــــــــخـــزن الـــــــفـاتــورة :
                                        <span class="text-warning">{{$bill->stoke->name}}</span>
                                    </div>
                                    <div class="tooltips position-relative mt-2 text-right" data-placement='bottom'
                                         title="الشخص صاحب الفاتورة">
                                        الشخص صاحب الفاتورة :
                                        <span class="text-warning">
                                        @if (isset($bill->account->name))
                                            {{$bill->account->name}}
                                            ({{$bill->account->tel}})
                                            ({{round($bill->account->account,2)}}
                                            ج
                                            )
                                        @else
                                            بدون
                                        @endif
                                        </span>
                                    </div>
                                </div>
                                <div class='col-4' dir='rtl'>
                                    <select id='selectMessage' onchange="$('#message').val($(this).val());"
                                            class="selectpicker col show-tick form-control">
                                        <option value="" data-subtext="بدون"></option>
                                        @foreach ($bill_messages as $m)
                                            <option>{{$m->name}}</option>
                                        @endforeach
                                    </select>
                                    <textarea class='form-control text-right tooltips' id="message"
                                              style="font-size: 1.22rem;height: 47px" title="رسالة الفاتورة"
                                              data-placement="bottom" rows='1'
                                              placeholder='ملاحظة الفاتورة'>{{$bill->message}}</textarea>
                                   {{-- <textarea class='form-control text-right tooltips' id="message"
                                              style="font-size: 1.22rem;height: 68px" title="رسالة الفاتورة"
                                              data-placement="bottom" rows='1'
                                              placeholder='ملاحظة الفاتورة'>{{$bill->message}}</textarea>--}}
                                </div>
                            </div>
                            <div class='row no-gutters'>
                                <div class='col-12 col-md-1'>
                                    <input type="checkbox" id="input_add_fast_with_barcode"
                                           class="tooltips position-absolute" data-placement="top"
                                           title="الإضافة السريعة عند إستخدام الباركود"
                                           style="left: 10px;top: 10px">
                                    <button type="button" id='addToDetails' class='btn w-100 btn-info h-100 tooltips'
                                            data-placement="bottom" title="إضافة إلى الفاتورة"><span
                                            class="h3">إضافة</span>
                                    </button>
                                    <input type="checkbox" id="checkProductSpecial" class="tooltips position-absolute"
                                           data-placement="bottom" title="قائمة المنتجات الخاصه"
                                           style="left:10px;bottom: 10px">
                                </div>
                                <div class='col-4 '>
                                    <div class="input-group tooltips" data-placement="top" title="طريقة البحث عن منتج">
                                        <label style="/*width: 110px*/ width: 100%"
                                               class="checkbox-inline pb-0 pt-1 d-inline-block  pointer input-group-text h0">
                                            <input type="radio" checked id="radioName" name="billType" class="ml-3"
                                                   value="1">
                                            بالإسم
                                        </label>
                                        <label style="width: calc(100% - 110px);"
                                               class="checkbox-inline py-0 <!-- d-inline-block--> d-none pointer input-group-text h0">
                                            <input type="radio" id="radioBarcode" name="billType" class="ml-3">
                                            بالباركود
                                            <input type="text" id="input_barcode" placeholder=" بحث بالباركود"
                                                   style="width: calc(100% - 110px);max-width: 187px"
                                                   class="border-radius">
                                        </label>
                                    </div>
                                    <div class="input-group">
                                        <div class="w-100" id="divContainerResultBarcodeSearch">
                                            <input type='text' readonly
                                                   class=' form-control h0'>
                                        </div>
                                        <div id="divContainerSelectProduct" class="w-100">
                                            <select data-select='product-name'
                                                    data-container="#divContainerSelectProduct"
                                                    id="selectProduct" class="form-control"
                                                    data-live-search="true">
                                                @foreach ($products as $p)
                                                    <option data-id="{{$p->id}}"
                                                            {{$p->allow_no_qte?'data-no_qte="true"':''}}
                                                            data-special="{{$p->special}}">{{$p->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class='col'>
                                <span class='input-group-text h0'>الكمية
                                <button type="button" class="btn bg-transparent px-0 mr-auto tooltips"
                                        onclick="$('#divContainerSettingForAddExistProductToDetailsWithSameUnit').toggleClass('d-none');design.useSound('info');"
                                        data-placement="bottom"
                                        title="عرض الخيارات عند إضافة منتج موجود فى الفاتورة بنفس الوحدة"
                                        style="color: #38c172;"><i class="fas fa-redo"></i>
                                </button>
                                </span>
                                    <div class="input-group">
                                        <input id="input_product_qte" type='text'
                                               data-validate='qte' data-patternType="qte"
                                               value='0' class='form-control h0'>
                                        <div class="input-group-prepend">
                                            <select class="custom-select h0" id='select_unit'>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class='col '>
                                    <span class='input-group-text h0'>السعر</span>
                                    <div class="input-group">
                                        <input value='0' type='text' data-validate='price'
                                               {{$setting->use_small_price?'data-small_price':''}}
                                               data-patternType="price"
                                               id="inputProductPrice" class='form-control h0'>
                                        <div class="input-group-prepend d-none d-md-inline-block">
                                            <span class="input-group-text h0">ج</span>
                                        </div>
                                    </div>
                                </div>
                                <div class='col '>
                                    <span class='input-group-text h0'>الاجمالي</span>
                                    <div class="input-group">
                                        <input id="inputTotalBeforeAddToTable" value='0' readonly type='text'
                                               name='priceForProduct' class='form-control h0'>
                                        <div class="input-group-prepend d-none d-md-inline-block">
                                            <span class="input-group-text h0">ج</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='table-responsive'>
                                <div class="tableFixHead">
                                    <table id="mainTable" class='m-0 table table-hover table-bordered'>
                                        <thead class='thead-dark h3'>
                                        <tr>
                                            <th>م</th>
                                            <th>إسم المنتج
                                                <div class="input-group d-inline-block" style="max-width: 100px">
                                                    <div class="input-group-prepend bg-transparent">
                                                        <input placeholder='بحث'
                                                               data-filter-col="1"
                                                               type='text'
                                                               id="input_th_product_name_search"
                                                               class='form-control h0 d-none'>
                                                        <span class="input-group-text text-success bg-transparent p-0"
                                                              style="border: none"><i
                                                                onclick="design.useSound();$(this).parent().parent().children().toggleClass('d-none');$('#input_th_product_name_search').focus();"
                                                                class="fas fa-2x fa-search mr-2 tooltips"
                                                                data-placement="left"
                                                                title="بحث فى الفاتورة"></i></span>
                                                        <span
                                                            class="input-group-text text-danger bg-transparent p-0 d-none"
                                                            style="border: none"><i
                                                                onclick="design.useSound();$(this).parent().parent().children().toggleClass('d-none');$('#input_th_product_name_search').val('').trigger('keyup');;"
                                                                class="fas fa-2x fa-times mr-2 tooltips"
                                                                data-placement="left"
                                                                title="إلغاء البحث فى الفاتورة"></i></span>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>الاجمالي
                                                <span class="font-en small tooltips" id="span_total_table"
                                                      data-placement="left" title="جنية"></span>
                                            </th>
                                            <th>العمليات
                                                <button type="button" class="btn bg-transparent p-0  tooltips"
                                                        data-placement="left" title="حذف الكل"
                                                        onclick="$('#mainTable tbody').html('');addIndexAndTotalToTable(false);design.useSound();alertify.error('تم حذف المنتجات من الفاتورة بنجاح ');">
                                                    <i class="fas fa-2x text-danger fa-trash-alt"></i>
                                                </button>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="h4 text-dark">
                                        @foreach ($bill->details as $d)
                                            <?php
                                            $dataUnitId='0';
                                                if($d->product_unit_id==$d->product->product_unit_id){
                                                    $dataUnitId='0';
                                                }else{
//                                                    $dataUnitId=$d->product_unit_id;
//													if(isset($d->relation_product_unit)){
														foreach ($d->relationProductUnit as $r){
															if($r->product_unit_id==$d->product_unit_id){
																$dataUnitId=$r->id;
															}
														}
//													}
                                                }
                                            ?>
                                            <tr class='table-success'>
                                                <td data-relation_qte='{{$d->relation_qte}}' data-price='{{$d->price*$d->relation_qte}}' data-product_id='{{$d->product_id}}'
                                                    data-unit_id='{{$dataUnitId}}' data-qte_val='{{$d->qte/$d->relation_qte}}' data-qte_name='{{$d->productUnit->name}}'
{{--                                                    data-unit_id='{{-1*($d->id)}}' data-qte_val='{{$d->qte/$d->relation_qte}}' data-qte_name='{{$d->productUnit->name}}'--}}
                                                data-qte_add_by_main_unit='{{$d->qte}}'  data-main_unit_name='{{$d->product->productUnit->name}}'>{{$loop->index +1}}</td>
                                                <td> {{$d->product->name}}  </td>
                                                <td> {{$d->qte/$d->relation_qte}}  {{$d->productUnit->name}}  </td>
                                                <td> {{$d->price*$d->relation_qte}}</td>
                                                <td>{{$d->qte * $d->price}}</td>
                                                <td>
                                                     <button type='button' class='btn  bg-transparent mx-2 text-primary px-0 tooltips' data-edit='' data-placement='right' title='تعديل'>
                                                        <i class='fas fa-2x fa-edit'></i>
                                                        </button>
                                                    <button type='button' class='btn  bg-transparent tooltips p-0' data-delete='' data-placement='left' title='حذف'>
                                                        <i class='fas fa-2x fa-trash-alt text-danger'></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class='row no-gutters' id="divContainerMoney">
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0 tooltips'  title="الخصم سابقاً هو {{$bill->discount}}ج " data-placement="bottom">الخـصــــــــــــم</span>
                                        </div>
                                        <input id="input_discount" type='text'
                                               data-validate='price'
                                               required
                                               {{$setting->use_small_price?'data-small_price':''}}
                                               data-patternType="price"
                                               name='priceDiscount' value='{{$bill->discount}}' class='form-control h0'>
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>ج</span>
                                        </div>
                                    </div>
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0 tooltips'  title="الإجمالى بعد الخصم سابقاً هو {{$bill->total_price}}ج " data-placement="bottom">الاجمالي بعد الخصم</span>
                                        </div>
                                        <input id="input_totalPriceAfterDiscount"  type='text' value='0'
                                               readonly='true' class='form-control h0'>
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>ج</span>
                                        </div>
                                    </div>
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend tooltips" title="المبلغ المدفوع سابقاً هو {{$bill->total_paid}}ج " data-placement="bottom">
                                            <span class='input-group-text h0'>المبلغ المدفوع</span>
                                        </div>
                                        <input id="input_totalPaid" type='text'
                                               data-validate='price'
                                               required
                                               {{$setting->use_small_price?'data-small_price':''}}
                                               data-patternType="price"
                                               name='priceBuy' value='{{$bill->total_paid}}' class='form-control h0'>
                                        <div class="input-group-prepend">
                                        <span class='input-group-text h0 tooltips'
                                              style="cursor: pointer!important;"
                                              data-placement="top" title="إضغط لدفع المبلغ بالكامل"
                                              onclick="$('#input_totalPaid').val($('#input_totalPriceAfterDiscount').val());updateTotalAndRentAfterDiscount();$('#input_totalPaid').trigger('keyup');design.useSound();">
                                            ج
                                        </span>
                                        </div>
                                    </div>
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0 tooltips' title="الباقى سابقاً هو {{$bill->total_price-$bill->total_paid}}ج " data-placement="bottom">البــــاقـــــــــــــــــــي</span>
                                        </div>
                                        <input id="input_rent" type='text' value='0' readonly='true'
                                               class='form-control h0'>
                                        <div class="input-group-prepend">
                                            <span class='input-group-text h0'>ج</span>
                                        </div>
                                    </div>
                                </div>
                                <div class='text-center mt-1'>
                                    <input id="check_print_bill" type="checkbox" class="tooltips"
                                           data-placement="right" title="طباعة الفاتورة عند الحفظ">
                                    <button type="submit" class='btn font-weight-bold btn-warning px-4 tooltips'
                                            data-placement="top" title="تعديل الفاتورة">
                                        <input id="check_print_in_new_window"  type="checkbox" class="tooltips d-none"
                                               data-placement="right"  title="تعديل وطباعة فى نافذة جديد">
                                        <span class='h0 font-weight-bold'>تعديل الفاتورة</span>
                                        <i class="fas text-danger fa-2x fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <iframe src="" id="iframe_print_bill" class="d-none" style="width: 100vw;height: 100vh" frameborder="0"></iframe>
@endsection

@section('js')
    <script defer>
        validateByAttr();
        design.useNiceScroll();
        $('#mainTable').filtable({controlPanel: $('#input_th_product_name_search').parent()});

        //use selectPicer in select product
        $('#selectProduct').selectpicker({
            showSubtext: true,
            container: 'body',
            showTick: true
        });
        $('#selectProduct').selectpicker('refresh');


        //toggle between add to bill by barcode or product name
        $('#radioBarcode,#radioName').change(function () {
            if ($('#radioName').prop('checked')) {
                //search by name
                $('#divContainerResultBarcodeSearch,#input_add_fast_with_barcode,#input_barcode').addClass('d-none');
                $('#divContainerSelectProduct,#checkProductSpecial').removeClass('d-none');
            } else {
                //search by barcode
                $('#divContainerResultBarcodeSearch,#input_add_fast_with_barcode,#input_barcode').removeClass('d-none');
                $('#divContainerSelectProduct,#checkProductSpecial').addClass('d-none');
            }
        });
        $('#radioName').trigger('change');

        //toggle select product special
        $('#checkProductSpecial').change(function () {
            if ($(this).prop('checked')) {
                $('#selectProduct option[data-special="0"]').addClass('d-none');
            } else {
                $('#selectProduct option[data-special="0"]').removeClass('d-none');
            }
            $('#selectProduct').selectpicker('refresh');
        });

        //get units and price for product
        function getUnitForProductWithPrice(id) {
            $('#select_unit').html('');
            if (id == null) {
                alertify.error('لا يوجد منتجات برجاء إضافة منتجات لإستخدامها فى الفاتورة ');
                design.useSound('error');
                return;
            }
            $.ajax({
                url: '{{route('products.getDate')}}',
                method: 'POST',
                data: {
                    type: 'findUnitForProduct',
                    product_id: id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#select_unit').html('');
                    $('#select_unit').append(
                        "<option data-unit_id='0' data-price='" + {!!$bill->type==0?"data['price_buy']":"data['price_sale1']"!!}+"' data-relation_qte='1'>" + data['product_unit']['name'] + "</option>"
                    );
                    if (data['relation_product_unit'].length > 0) {
                        for (var i = 0; i < data['relation_product_unit'].length; i++) {
                            $('#select_unit').append(
                                "<option data-unit_id='" + data['relation_product_unit'][i]['id'] +
                                "' data-price='" + {!!$bill->type==0?"data['relation_product_unit'][i]['price_buy']":"data['relation_product_unit'][i]['price_sale1']"!!}+"' " +
                                "data-relation_qte='" + data['relation_product_unit'][i]['relation_qte'] + "'>" + data['relation_product_unit'][i]['product_unit']['name'] + "</option>"
                            );
                        }
                    }
                    updatePrice();
                    design.useSound('success');
                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                }
            });
        }

        //get units and price when select product
        $('#selectProduct').change(function () {
            getUnitForProductWithPrice($('#selectProduct option:selected').attr('data-id'));
            $('#input_product_qte').trigger('change');
            $('#input_product_qte').val(0).focus().select();
        });
        $('#selectProduct').trigger('change');

        //update price when select unit
        function updatePrice() {
            if ($('#select_unit option').length > 0) {
                $('#inputProductPrice').val($('#select_unit option:selected').attr('data-price'));
            } else {
                $('#inputProductPrice').val('0');
            }
        }

        $('#select_unit').change(function () {
            updatePrice();
            $('#input_product_qte').select();
        });
        $('#input_product_qte,#inputProductPrice').click(function () {
            $(this).select();
        });

        //get total for qte and price before add to table
        $('#inputProductPrice,#input_product_qte').on('keyup', function () {
            var qte = $('#input_product_qte').val(), price = $('#inputProductPrice').val();
            if (isNaN(qte) ||
                isNaN(price) ||
                qte == 0 ||
                price == 0) {
                $('#inputTotalBeforeAddToTable').val(0);
            } else {
                $('#inputTotalBeforeAddToTable').val(roundTo(qte * price,{{$setting->use_small_price?'3':'2'}}));
            }
        });

        //add row to table
        function addRowToTable(productId, productName, qteId, qteName, qteVal, price, qteAddByMainUnit = '', existQteInStoke = '', mainUnitName = '', relation_qte = '1') {
            $('#mainTable tbody').prepend(
                "<tr class='table-success'>" +
                "<td data-relation_qte='" + relation_qte + "' data-price='" + price + "' data-product_id='" + productId + "' data-unit_id='" + qteId + "' data-qte_val='" + qteVal + "' data-qte_name='" + qteName + "'" +
                "data-qte_add_by_main_unit='" + qteAddByMainUnit + "' data-qte_in_stoke='" + existQteInStoke + "' data-main_unit_name='" + mainUnitName + "'></td>" +
                "<td>" + productName + "</td>" +
                "<td>" + qteVal + ' ' + qteName + "</td>" +
                "<td>" + price + "</td>" +
                "<td></td>" +
                "<td>" +
                " <button type='button' class='btn  bg-transparent mx-2 text-primary px-0 tooltips' data-edit='' data-placement='right' title='تعديل'>" +
                "<i class='fas fa-2x fa-edit'></i>" +
                "</button>" +
                "<button type='button' class='btn  bg-transparent tooltips p-0' data-delete='' data-placement='left' title='حذف'>" +
                "<i class='fas fa-2x fa-trash-alt text-danger'></i>" +
                "</button>" +
                "</td>" +
                "</tr>"
            );
            design.updateNiceScroll();
            design.useToolTip();
            addIndexAndTotalToTable();
            design.useSound('success');
        }

        //add index and total for row and total for all row in table
        function addIndexAndTotalToTable(select_product_qte=true) {
            var index = $('#mainTable tbody tr').length;
            var total = 0;
            $('#mainTable tbody tr').each(function () {
                var td = $(this).children();
                td.eq(0).html(index);
                index--;
                var tempTotal = td.eq(0).attr('data-qte_val') * td.eq(3).html();
                td.eq(4).html(roundTo(tempTotal,{{$setting->use_small_price?'3':'2'}}));
                total -= -tempTotal;
            });
            $('#span_total_table').html(roundTo(total,{{$setting->use_small_price?'3':'2'}}));
            $('#input_product_qte').val('0');
            if(select_product_qte){
                $('#input_product_qte').select();
            }
            updateTotalAndRentAfterDiscount();
        }

        addIndexAndTotalToTable();

        //checkQteAndPriceBeforeAddToTable
        function checkQteAndPriceBeforeAddToTable() {
            //check if qte not valid
            if ($('#input_product_qte').hasClass('is-invalid') || $('#input_product_qte').val() == 0) {
                alertify.error('برجاء التحقق من الكمية ');
                design.useSound('error');
                $('#input_product_qte').select();
                return false;
            }
            //check if price not valid
            if (($('#inputProductPrice').hasClass('is-invalid') || $('#inputProductPrice').val() == 0) && $('#inputProductPrice').val() != '00') {
                alertify.error('برجاء التحقق من السعر ');
                design.useSound('error');
                $('#inputProductPrice').select();
                return false;
            }
            return true;
        }

        var statePreventLoopCheckBeforeAddToDetails = false;//used if bill type is bill sale
        //add row to table
        $('#addToDetails').click(function () {
            //check if no product exist in select
            if ($('#selectProduct option').length == 0) {
                alertify.error('لا يوجد منتجات برجاء إضافة منتجات لإستخدامها فى الفاتورة ');
                design.useSound('error');
                return;
            }
            if (!checkQteAndPriceBeforeAddToTable()) {
                return;
            }
            if ($('#inputCheckQteBeforeAddToDetails').val() == 'true') {
                if (!checkIfProductExistInTableWithCheckQte()) {
                    return;
                }
            } else {
                if (!checkIfProductExistInTable()) {
                    return;
                }
            }


            var product_id = $('#selectProduct option:selected').attr('data-id');
            var product_name = $('#selectProduct option:selected').html();
            var relation_unit_id = $('#select_unit option:selected').attr('data-unit_id');
            var relation_qte = $('#select_unit option:selected').attr('data-relation_qte');
            var unit_name = $('#select_unit option:selected').html();
            var qte = $('#input_product_qte').val();
            var price = $('#inputProductPrice').val();

            if ($('#inputCheckQteBeforeAddToDetails').val() == 'true') {
                //check if qte exist in store if type bill is sale if product type not no qte
                if ($('#selectProduct option:selected').is('[data-no_qte]')) {
                    addRowToTable(product_id, product_name, relation_unit_id, unit_name, qte, price, relation_qte);
                } else {
                    if (statePreventLoopCheckBeforeAddToDetails) {
                        alertify.error('برجاء الإنتظار جارى الإضافة لتفاصيل الفاتورة');
                        design.useSound('info');
                        return;
                    }
                    statePreventLoopCheckBeforeAddToDetails = true;
                    $.ajax({
                        url: '{{route('stores.getDate')}}',
                        method: 'POST',
                        data: {
                            type: 'getQteForProductInStoke',
                            stoke_id: '{{$bill->stoke_id}}',
                            product_id: $('#selectProduct option:selected').attr('data-id'),
                            relation_unit_id: relation_unit_id,
                        },
                        dataType: 'JSON',
                        success: function (data) {
                            statePreventLoopCheckBeforeAddToDetails = false;
                            if (data.length > 0) {
                                var existQteInStoke = 0;
                                for (var i = 0; i < data.length; i++) {
                                    existQteInStoke -= -data[i]['qte'];
                                }
                                var relation_qte_unit = relation_unit_id == 0 ? 1 : data[0]['relation_product_unit']['relation_qte'];
                                var qteAddByMainUnit = qte * relation_qte_unit;
                                if (qteAddByMainUnit <= existQteInStoke) {
                                    addRowToTable(product_id, product_name, relation_unit_id, unit_name, qte, price, qteAddByMainUnit, existQteInStoke, data[0]['product']['product_unit']['name'], relation_qte);
                                } else {
                                    //if unit used in add to details is main unit
                                    if (relation_unit_id == 0) {
                                        alertify.error("هذه الكمية غير متاحة الكمية المتاحة " + '</br>' + roundTo(existQteInStoke) + " " + unit_name);
                                    } else {
                                        alertify.error("هذه الكمية غير متاحة الكمية المتاحة " + '</br>' + roundTo(existQteInStoke) + " " + data[0]['product']['product_unit']['name'] +
                                            '</br>' + roundTo(existQteInStoke / relation_qte_unit) + " " + unit_name);
                                    }
                                    design.useSound('error');
                                }
                            } else {
                                alertify.error("هذه المنتج غير موجود في المخزن المحدد ");
                                design.useSound('error');
                            }

                        },
                        error: function (e) {
                            statePreventLoopCheckBeforeAddToDetails = false;
                            alertify.error("حصل خطاء في العملية ربما يكون هذا المنتج غير موجود في المخزن حاول مرة اخري في حالة تكرار الخطاء اتصل بالمبرمج");
                            console.log(e);
                        }
                    });

                }

            } else {
                addRowToTable(product_id, product_name, relation_unit_id, unit_name, qte, price);
            }
        });

        //delete row in table
        $('#mainTable').on('click', 'tbody button[data-delete]', function () {
            $(this).parent().parent().remove();
            addIndexAndTotalToTable();
            design.useSound();
        });

        //add row to table when enter in input qte
        $('#input_product_qte,#inputProductPrice').on('keypress', function (e) {
            if (e.which == 13) {
                $('#addToDetails').trigger('click');
            }
        });

        //set default value for radioSettingAddExistProduct
        if (Cookie.get('valueRadioSettingAddExistProduct') != '') {
            var tempValue = Cookie.get('valueRadioSettingAddExistProduct');
            $('#divSettingForAddExistProductToDetailsWithSameUnit input[type="radio"][value="' + tempValue + '"]').prop('checked', true);
        }

        //check if product exist in bill with out check qte
        function checkIfProductExistInTable() {
            var product_id = $('#selectProduct option:selected').attr('data-id');
            var unit_id = $('#select_unit option:selected').attr('data-unit_id');
            var result = true;
            var rowExist = 0;
            var rowPrice = 0;
            $('#mainTable tbody tr').each(function () {
                if (product_id == $(this).children().eq(0).attr('data-product_id') && unit_id == $(this).children().eq(0).attr('data-unit_id')) {
                    rowExist = $(this);
                    rowPrice = $(this).children().eq(0).attr('data-price');
                    result = false;
                    return;
                }
            });
            if (!result) {
                var action = $('#divSettingForAddExistProductToDetailsWithSameUnit input[name="radioSettingAddExistProduct"]:checked').val();
                if (action == 1) {//don't add qte
                    design.useSound('error');
                    alertify.error('هذا المنتج موجود بنفس الوحدة');
                } else if (action == 0) {//add qte to qte
                    //check if price change
                    if (rowPrice != $('#inputProductPrice').val()) {
                        design.useSound('error');
                        alertify.error('هذا المنتج موجود بنفس الوحدة ولكن بسعر مختلف ');
                    } else {
                        var child = rowExist.children();
                        var newQte = child.eq(0).attr('data-qte_val') - -$('#input_product_qte').val();
                        //update qte
                        child.eq(0).attr('data-qte_val', newQte);
                        var tempQteText = newQte + ' ' + child.eq(0).attr('data-qte_name');
                        child.eq(2).html(tempQteText);
                        design.useSound();
                        alertify.success('المنتج موجود بنفس الوحدة وتم إضافة الكمية للكمية الموجودة لتصبح ' + tempQteText);
                        addIndexAndTotalToTable();
                    }
                } else {//ask before add qte
                    //check if price change
                    if (rowPrice != $('#inputProductPrice').val()) {
                        design.useSound('error');
                        alertify.error('هذا المنتج موجود بنفس الوحدة ولكن بسعر مختلف ');
                    } else {
                        design.useSound('info');
                        $(this).confirm({
                            text: "هذا المنتج موجود فى الفاتورة بنفس الوحدة هل تريد الإضافة للكمية الموجودة؟",
                            title: "إضافة كمية لمنتج بنفس الوحدة",
                            confirm: function (button) {
                                var child = rowExist.children();
                                var newQte = child.eq(0).attr('data-qte_val') - -$('#input_product_qte').val();
                                //update qte
                                child.eq(0).attr('data-qte_val', newQte);
                                var tempQteText = newQte + ' ' + child.eq(0).attr('data-qte_name');
                                child.eq(2).html(tempQteText);
                                design.useSound();
                                alertify.success('المنتج موجود بنفس الوحدة وتم إضافة الكمية للكمية الموجودة لتصبح ' + tempQteText);
                                addIndexAndTotalToTable();
                            },
                            cancel: function (button) {
                                alertify.success('تم إلغاء الإضافة ');
                            },
                            post: true,
                            confirmButtonClass: "btn-danger",
                            cancelButtonClass: "btn-default",
                            dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                        });
                    }
                }
            }
            return result;
        }

        //check if product exist in details before with other unit or
        // this unit and check if qteIn store less than total qte for this porduct in store
        function checkIfProductExistInTableWithCheckQte() {
            var product_id = $('#selectProduct option:selected').attr('data-id');
            var unit_id = $('#select_unit option:selected').attr('data-unit_id');
            var addQteByMainUnit = $('#input_product_qte').val() * $('#select_unit option:selected').attr('data-relation_qte');
            var resultExistInDetails = true;
            var rowExist = 0;
            var rowPrice = 0;
            var totalExistQteByMainUnit = 0;
            var qteInStokeByMainUnit = 0;
            var messageCheckQte = '';
            var rowExistWithOtherUnit = '';
            $('#mainTable tbody tr').each(function () {
                if (product_id == $(this).children().eq(0).attr('data-product_id')) {
                    if (unit_id == $(this).children().eq(0).attr('data-unit_id')) {
                        resultExistInDetails = false;
                        rowExist = $(this);
                        rowPrice = $(this).children().eq(0).attr('data-price');
                    }
                    rowExistWithOtherUnit = $(this);
                    totalExistQteByMainUnit -= -$(this).children().eq(0).attr('data-qte_add_by_main_unit');
                    qteInStokeByMainUnit = $(this).children().eq(0).attr('data-qte_in_stoke') * 1;
                }
            });

            //check if total qte in detaisl + qte add less than qte in stoke
            if (!resultExistInDetails || rowExistWithOtherUnit != '') {
                if (addQteByMainUnit - -totalExistQteByMainUnit > qteInStokeByMainUnit) {
                    messageCheckQte = ('هذة الكمية غير متاحة (المنتج موجود فى الفاتورة) والكمية الممكن إضافتها للكمية الموجودة ' + '</br>' +
                        roundTo(qteInStokeByMainUnit - totalExistQteByMainUnit) + ' ' + (rowExist != '' ? rowExist.children().eq(0).attr('data-main_unit_name') : rowExistWithOtherUnit.children().eq(0).attr('data-main_unit_name')));
                    design.useSound('error');
                    alertify.error(messageCheckQte);
                    messageCheckQte = '';
                    resultExistInDetails = false;
                    return false;
                }
            }

            //check if product exist by this unit
            if (!resultExistInDetails) {
                var action = $('#divSettingForAddExistProductToDetailsWithSameUnit input[name="radioSettingAddExistProduct"]:checked').val();
                if (action == 1) {//don't add qte
                    design.useSound('error');
                    alertify.error('هذا المنتج موجود بنفس الوحدة');
                } else if (action == 0) {//add qte to qte
                    //check if price change
                    if (rowPrice != $('#inputProductPrice').val()) {
                        design.useSound('error');
                        alertify.error('هذا المنتج موجود بنفس الوحدة ولكن بسعر مختلف ');
                    } else {
                        //check qte before add qte to qte
                        if (messageCheckQte != '') {
                            design.useSound('error');
                            alertify.error(messageCheckQte);
                            messageCheckQte = '';
                            return false;
                        }

                        var child = rowExist.children();
                        var newQte = child.eq(0).attr('data-qte_val') - -$('#input_product_qte').val();
                        //update qte
                        child.eq(0).attr('data-qte_val', newQte);
                        child.eq(0).attr('data-qte_add_by_main_unit', (child.eq(0).attr('data-qte_add_by_main_unit') - -addQteByMainUnit));
                        var tempQteText = newQte + ' ' + child.eq(0).attr('data-qte_name');
                        child.eq(2).html(tempQteText);
                        design.useSound();
                        alertify.success('المنتج موجود بنفس الوحدة وتم إضافة الكمية للكمية الموجودة لتصبح ' + tempQteText);
                        addIndexAndTotalToTable();
                    }
                } else {//ask before add qte
                    //check if price change
                    if (rowPrice != $('#inputProductPrice').val()) {
                        design.useSound('error');
                        alertify.error('هذا المنتج موجود بنفس الوحدة ولكن بسعر مختلف ');
                    } else {
                        //check qte before add qte to qte
                        if (messageCheckQte != '') {
                            design.useSound('error');
                            alertify.error(messageCheckQte);
                            messageCheckQte = '';
                            return false;
                        }

                        design.useSound('info');
                        $(this).confirm({
                            text: "هذا المنتج موجود فى الفاتورة بنفس الوحدة هل تريد الإضافة للكمية الموجودة؟",
                            title: "إضافة كمية لمنتج بنفس الوحدة",
                            confirm: function (button) {
                                var child = rowExist.children();
                                var newQte = child.eq(0).attr('data-qte_val') - -$('#input_product_qte').val();
                                //update qte
                                child.eq(0).attr('data-qte_val', newQte);
                                child.eq(0).attr('data-qte_add_by_main_unit', (child.eq(0).attr('data-qte_add_by_main_unit') - -addQteByMainUnit));
                                var tempQteText = newQte + ' ' + child.eq(0).attr('data-qte_name');
                                child.eq(2).html(tempQteText);
                                design.useSound();
                                alertify.success('المنتج موجود بنفس الوحدة وتم إضافة الكمية للكمية الموجودة لتصبح ' + tempQteText);
                                addIndexAndTotalToTable();
                            },
                            cancel: function (button) {
                                alertify.success('تم إلغاء الإضافة ');
                            },
                            post: true,
                            confirmButtonClass: "btn-danger",
                            cancelButtonClass: "btn-default",
                            dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                        });
                    }
                }
            }
            return resultExistInDetails;
        }

        //show edit rowInTable
        var tempRowEditInTable = '';
        $('#mainTable').on('click', 'tbody button[data-edit]', function () {
            tempRowEditInTable = $(this).parent().siblings();
            $('#spanEditProductName').html(tempRowEditInTable.eq(1).html());
            $('#input_edit_product_qte').val(tempRowEditInTable.eq(0).attr('data-qte_val'));
            @if($bill->type==1)
            $('#input_edit_product_qte').attr('data-max_qte', (tempRowEditInTable.eq(0).attr('data-qte_in_stoke') / tempRowEditInTable.eq(0).attr('data-relation_qte')));
            @else
            $('#input_edit_product_qte').attr('data-max_qte', 1000000);
            @endif
            $('#spanEditUnitName').html(tempRowEditInTable.eq(0).attr('data-qte_name'));
            $('#inputEditProductPrice').val(tempRowEditInTable.eq(0).attr('data-price'));
            $('#divContainerEditRowInTable').removeClass('d-none');
            design.useSound();
        });
        //save edit rowInTable
        $('#buttonSaveEditRowInTable').click(function () {
            //check if qte not valid
            if ($('#input_edit_product_qte').hasClass('is-invalid') || $('#input_edit_product_qte').val() == 0) {
                alertify.error('برجاء التحقق من الكمية ');
                design.useSound('error');
                $('#input_edit_product_qte').select();
                return;
            }
            //check if price not valid
            if (($('#inputEditProductPrice').hasClass('is-invalid') || $('#inputEditProductPrice').val() == 0)&& $('#inputEditProductPrice').val() != '00') {
                alertify.error('برجاء التحقق من السعر ');
                design.useSound('error');
                $('#inputEditProductPrice').select();
                return;
            }

            //check if qte lesth than qte in stoke (sale )and when type is (bay) max_qte is million
            var tempMaxQteWhenEdit = $('#input_edit_product_qte').attr('data-max_qte') * 1;
            if ($('#input_edit_product_qte').val() * 1 > tempMaxQteWhenEdit) {
                alertify.error("هذه الكمية غير متاحة الكمية المتاحة " + '</br>' + roundTo(tempMaxQteWhenEdit) + " " + $('#spanEditUnitName').html());
                design.useSound('error');
                $('#input_edit_product_qte').select();
                return;
            }

            //update price and qte
            tempRowEditInTable.eq(0).attr('data-qte_val', $('#input_edit_product_qte').val());
            tempRowEditInTable.eq(0).attr('data-qte_add_by_main_unit', $('#input_edit_product_qte').val() * tempRowEditInTable.eq(0).attr('data-relation_qte'));
            var tempQteText = $('#input_edit_product_qte').val() + ' ' + tempRowEditInTable.eq(0).attr('data-qte_name');
            tempRowEditInTable.eq(2).html(tempQteText);
            tempRowEditInTable.eq(0).attr('data-price', $('#inputEditProductPrice').val());
            tempRowEditInTable.eq(3).html($('#inputEditProductPrice').val());
            addIndexAndTotalToTable();
            $('#divContainerEditRowInTable').addClass('d-none');
            alertify.success('تم التعديل بنجاح');
            design.useSound();
        });
        $('#inputEditProductPrice,#input_edit_product_qte').click(function () {
            $(this).select();
        });
        $('#inputEditProductPrice,#input_edit_product_qte').on('keypress', function (e) {
            if (e.which == 13) {
                $('#buttonSaveEditRowInTable').trigger('click');
            }
        });

        updateTotalAndRentAfterDiscount();
        //updateTotalAndRentAfterDiscount
        function updateTotalAndRentAfterDiscount() {
            var totalAfterDis = $('#span_total_table').html() - $('#input_discount').val();
            $('#input_totalPriceAfterDiscount').val((!$('#input_discount').hasClass('is-invalid') ? roundTo(totalAfterDis) : '0'));
            $('#input_rent').val((!$('#input_totalPaid').hasClass('is-invalid') && !$('#input_discount').hasClass('is-invalid') ? roundTo(totalAfterDis - $('#input_totalPaid').val()) : '0'));
        }

        $('#input_discount,#input_totalPaid').keyup(function () {
            updateTotalAndRentAfterDiscount();
        });
        $('#input_discount,#input_totalPaid').click(function () {
            $(this).select();
        });


        //disable submit when enter in input in form
        var stateSubmitByInput = false;
        $('#mainForm input').keydown(function (e) {
            if (e.keyCode == 13) {
                stateSubmitByInput = true;
            }
        });

        var stateCheckIfTotalPaidGreaterThanTotalPrice = false;
        var printLink = "{{route('bills.print')}}/{{$bill->id}}";
        $('#mainForm').submit(function (e) {
            var totalDetailForBill = $('#span_total_table').html() * 1;
            var discount = $('#input_discount').val() * 1;
            var totalPaid = $('#input_totalPaid').val() * 1;
            //check if submit is run buy enter in input to prevent it
            if (stateSubmitByInput) {
                stateSubmitByInput = false;
                e.preventDefault();
                return;
            }

            if ('{{isset($bill->account->account)?'false0':'true1'}}'=='true1') {
                if (roundTo(totalPaid - -discount) != roundTo(totalDetailForBill)) {
                    alertify.error('عند إضافة فاتورة بدون شخص يجب أن يتم دفع إجمالى الفاتورة بعد الخصم ');
                    design.useSound('error');
                    e.preventDefault();
                    return;
                }
            }
            //check if totalPaid greater than total bill - discount
            if('{{isset($bill->account->account)?'true1':'false0'}}'=='true1'){
                if (roundTo(totalPaid - -discount) > roundTo(totalDetailForBill) && !stateCheckIfTotalPaidGreaterThanTotalPrice) {
                    design.useSound('info');
                    $(this).confirm({
                        text: "المبلغ المدفوع أكبر من قيمة الفاتورة هل تريد الحفظ ؟",
                        title: "حفظ فاتورة",
                        confirm: function (button) {
                            stateCheckIfTotalPaidGreaterThanTotalPrice = true;
                            $('#mainForm').trigger('submit');
                        },
                        cancel: function (button) {
                            design.useSound('success');
                            e.preventDefault();
                            return;
                            alertify.success('تم إلغاء الإضافة ');
                        },
                        post: true,
                        confirmButtonClass: "btn-danger",
                        cancelButtonClass: "btn-default",
                        dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                    });
                    e.preventDefault();
                    return;
                }
            }

            stateCheckIfTotalPaidGreaterThanTotalPrice = false;
            //check if no product in bills
            if (totalDetailForBill == 0) {
                alertify.error('برجاء إضافة منتجات للفاتورة ');
                design.useSound('error');
                e.preventDefault();
                return;
            }

            //disable edit before send data
            $('#load').css('display', 'block');

            //send data by ajax
            e.preventDefault();
            var bill_details = [];
            $('#mainTable tbody tr').each(function () {
                var rowData = $(this).children().eq(0);
                bill_details.unshift([rowData.attr('data-product_id'), rowData.attr('data-unit_id'), rowData.attr('data-qte_val'), rowData.attr('data-price')]);
            });

            $.ajax({
                url: '{{route('bills.update',$bill->id)}}',
                method: 'POST',
                data: {
                    _method:'put',
                    //bill details
                    bill_details: bill_details,
                    total_price: (totalDetailForBill - discount),
                    discount: discount,
                    total_paid: totalPaid,
                    message: $('#message').val(),
                },
                // dataType: 'JSON',
                success: function (data) {
                    if (data == 'success') {
                        // design.useSound('success');
                        if ($('#check_print_bill').prop('checked')) {
                            if ($('#check_print_in_new_window').prop('checked')) {
                                window.open('{{route('bills.print')}}/{{$bill->id}}', '_blank');
                                window.location.reload(true);
                            } else {
                                $('#iframe_print_bill').removeClass('d-none').attr('src', printLink);
                                /*setTimeout(function () {
                                    window.location.reload(true);
                                },2000);*/
                                var reloadThisPage=true;
                                setInterval(function () {
                                    // console.log(88);
                                    if (reloadThisPage) {
                                        $('#iframe_print_bill').addClass('d-none');
                                        reloadThisPage=false;
                                        window.location.reload(true);
                                    }
                                }, 2000);
                                /*setInterval(function () {
                                        window.location.reload(true);
                                }, 5000);*/
                            }
                        } else {
                            window.location.reload(true);
                        }
                    } else {
                        console.log(data);
                        $('#load').css('display', 'none');
                        alertify.log(data, 'error', 0);
                        design.useSound('error');
                    }
                    // $('#load').css('display', 'none');
                    // console.log(data);
                },
                error: function (e) {
                    $('#load').css('display', 'none');
                    alert('error');
                    design.useSound('error');
                    console.log(e);

                }
            });
        });
    </script>
    <script defer>
        //set cookie for print
        if (Cookie.get('print_bill{{$bill->type}}') != '') {
            var tempValue = Cookie.get('print_bill{{$bill->type}}');
            if (tempValue == 'true') {
                $('#check_print_bill').prop('checked', true);
                $('#check_print_bill').prop('checked', true);
                // $('#check_print_in_new_window').removeClass('d-none');
            } else {
                $('#check_print_bill').prop('checked', false);
                $('#check_print_in_new_window').addClass('d-none');
            }
        }
        if (Cookie.get('check_print_in_new_window{{$bill->type}}') != '') {
            var tempValue = Cookie.get('check_print_in_new_window{{$bill->type}}');
            if (tempValue == 'true') {
                $('#check_print_in_new_window').prop('checked', true);
            } else {
                $('#check_print_in_new_window').prop('checked', false);
            }
        }
        $('#check_print_in_new_window').prop('checked', true);
        $('#check_print_bill').change(function () {
            design.useSound();
            if ($('#check_print_bill').prop('checked')) {
                Cookie.set('print_bill{{$bill->type}}', $(this).prop('checked'), {expires: 365, path: '/bills/create'});
                // $('#check_print_in_new_window').removeClass('d-none');
            } else {
                Cookie.remove('print_bill{{$bill->type}}', {path: '/bills/create'});
                // $('#check_print_in_new_window').addClass('d-none');
            }
        });
        $('#check_print_in_new_window').change(function () {
            if ($(this).prop('checked')) {
                Cookie.set('check_print_in_new_window{{$bill->type}}', $(this).prop('checked'), {
                    expires: 365,
                    path: '/bills/create'
                });
            } else {
                Cookie.remove('check_print_in_new_window{{$bill->type}}', {path: '/bills/create'});
            }
            design.useSound();
        });

        //set cookie for special product
        if (Cookie.get('checkProductSpecialInAddBill{{$bill->type}}') != '') {
            var tempValue = Cookie.get('checkProductSpecialInAddBill{{$bill->type}}');
            if (tempValue == 'true') {
                $('#checkProductSpecial').prop('checked', true);
                $('#checkProductSpecial').trigger('change');
            } else {
                $('#checkProductSpecial').prop('checked', false);
            }
        }
        $('#checkProductSpecial').change(function () {
            if ($(this).prop('checked')) {
                Cookie.set('checkProductSpecialInAddBill{{$bill->type}}', $(this).prop('checked'), {
                    expires: 365,
                    path: '/bills/create'
                });
            } else {
                Cookie.remove('checkProductSpecialInAddBill{{$bill->type}}', {path: '/bills/create'});
            }
            design.useSound();
        });
    </script>
@endsection
