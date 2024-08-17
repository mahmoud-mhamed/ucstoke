<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 06/02/2019
 * Time: 11:24 ص
 */ ?>
@extends('layouts.app')
@section('title')
    اضافة مرتجع لفاتورة
    @if (isset($type))
        {{$type==0?' شراء':($type==1?' بيع':'')}}
    @endif
@endsection
@section('css')
    <style>
        main span, main input {
            font-size: 1.5rem !important;
        }

        .error-qte {
            position: absolute;
            top: 0;
            left: 0;
            text-align: left;
            max-width: 150px;
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
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='text-right  h5'>
            <div class='animated fadeInDown ml-auto faster '>
                <div class='text-center h2'>
                    <h1 class='font-weight-bold pb-3 text-white'>اضافة مرتجع لفاتورة
                        @if (isset($type))
                            {{$type==0?' شراء':($type==1?' بيع':'')}}
                        @endif
                    </h1>
                    <form id="form_back" action="{{route('bills.store_bill_back',$bill->id)}}" method="post">
                        @csrf
                        <div class='container-fluid box-shadow p-1 text-white' style='background: rgba(12,84,96,.6);'>
                            <div class='row no-gutters pb-5'>
                                <div class='col-6'>
                                    <h1 class='text-right pr-2'>
                                        فاتورة رقم
                                        :
                                        <span class='font-en pl-3 text-danger'
                                              style="text-decoration: underline">{{$bill->id}}</span>
                                        العميل
                                        :
                                        <span
                                            class="text-danger">{!!isset($bill->account)?($bill->account->name.'<span class="tooltips mx-2 font-en" data-placement="bottom" title="رقم الهاتف">'.$bill->account->tell.'</span><label class="tooltips mx-2 font-en" data-placement="bottom" title="الحساب الحالى"> '.round($bill->account->account,2) .'ج'.'</label>'):'بدون'!!}</span>
                                        المخزن
                                        :
                                        <span class='font-en pl-3 text-danger'>{{$bill->stoke->name}}</span>
                                    </h1>
                                    <div class="input-group ">
                                        <div class="input-group-append">
                                            <label class="input-group-text"><span>نوع المرتجع</span></label>
                                        </div>
                                        <select id="select_back_type" name="bill_back_type" class="selectpicker" data-live-search="true">
                                            <option value="">برجاء التحديد</option>
                                            <option value="0">استبدال</option>
                                            <option value="1">اخذ مال</option>
                                            @if ($bill->account!='')
                                                <option value="2">خصم من الحساب</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class='col-6' dir='ltr'>
                                    <textarea class='form-control w-75 text-right' style="font-size: 1.5rem" name="note"
                                              placeholder='ملاحظة المرتجع'></textarea>
                                </div>
                            </div>
                            <div class='table-responsive tableFixHead small'>
                                <table id="mainTable" class='m-0 table table-hover table-bordered'>
                                    <thead class='thead-dark h3'>
                                    <tr>
                                        <th>م</th>
                                        <th>اسم المنتج
                                            {{--<div class="input-group d-inline-block" style="max-width: 100px">
                                                <div class="input-group-prepend bg-transparent">
                                                    <input placeholder='بحث'
                                                           data-filter-col="2"
                                                           type='text'
                                                           id="input_th_product_name_search"
                                                           class='form-control h0 d-none'>
                                                    <span class="input-group-text text-success bg-transparent p-0"
                                                          style="border: none"><i
                                                            onclick="design.useSound();$(this).parent().parent().children().toggleClass('d-none');$('#input_th_product_name_search').focus();"
                                                            class="fas fa-2x fa-search mr-2 tooltips"
                                                            data-placement="left"
                                                            style="font-size: 1.5rem"
                                                            title="بحث فى الفاتورة"></i></span>
                                                    <span
                                                        class="input-group-text text-danger bg-transparent p-0 d-none"
                                                        style="border: none"><i
                                                            onclick="design.useSound();$(this).parent().parent().children().toggleClass('d-none');$('#input_th_product_name_search').val('').trigger('keyup');;"
                                                            class="fas fa-2x fa-times mr-2 tooltips"
                                                            data-placement="left"
                                                            style="font-size: 1.5rem"
                                                            title="إلغاء البحث فى الفاتورة"></i></span>
                                                </div>
                                            </div>--}}
                                        </th>
                                        <th>الكمية في الفاتورة</th>
                                        <th>الكمية المراد ارجاعها</th>
                                        <th>السعر</th>
                                        <th>الاجمالي</th>
                                    </tr>
                                    </thead>
                                    <tbody class="h4">
                                    @foreach($bill->details as $d)
                                        <tr class='table-success text-dark'
                                            data-max_qte="{{$d->qte / $d->relation_qte}}"
                                            data-price="{{$d->price * $d->relation_qte}}"
                                            data-total_price="0"
                                        >
                                            <td>{{$loop->index+1}}</td>
                                            <td class="d-none"><input type="text" name="details_id[]"
                                                                      value="{{$d['id']}}"></td>
                                            <td>{{$d->product->name}}</td>
                                            <td>{{round($d->qte / $d->relation_qte,$setting->use_small_price?'3':'2')}} {{$d->productUnit->name}}</td>
                                            <td class="py-2">
                                                <div class="position-relative">
                                                    <input type='text' name="qte_back[]" onclick="$(this).select();"
                                                           style="width: 150px;"
                                                           {{$d->product->allow_no_qte?'readonly':''}}
                                                           data-validate='qte' required data-patternType="qte"
                                                           value='0' class='form-control py-0 pr-4 d-inline h0'>
                                                    {{$d->productUnit->name}}
                                                </div>
                                            </td>
                                            <td>
                                                {{round($d->price * $d->relation_qte,$setting->use_small_price?'3':'2')}}ج
                                            </td>
                                            <td><span data-total_price class="font-en">0</span> ج</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class='row no-gutters'>
                                    <div class="input-group col input-group-prepend">
                                        <div class="input-group-prepend">
                                            <span class='input-group-text'>اجمالي حساب المرتجع</span>
                                        </div>
                                        <input id="input_total_back" name="total_price" type='text' value='0'
                                               readonly='true' style="height: 53px" class='form-control'>
                                        <div class="input-group-prepend">
                                            <span class='input-group-text'>ج</span>
                                        </div>
                                    </div>
                                    <button data-type='save' type="submit" class='col btn font-weight-bold btn-warning'>
                                        <span class="h2">اضافة المرتجع</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
@endsection

@section('js')
    <script defer>
        validateByAttr();
        design.useNiceScroll();
        design.useSound();

        //check if qte back less than qte max and validate
        $('#mainTable tbody tr input[name="qte_back[]"]').keyup(function () {
            var row = $(this).parent().parent().parent();
            var max_qte = row.attr('data-max_qte');
            if ($(this).next('.error-qte').length != 0) {
                row.attr('data-total_price', 0);
                row.find('span[data-total_price]').html(0);
                getTotalForBack();
                return;
            }
            if ($(this).val() * 1 > max_qte * 1) {
                row.attr('data-total_price', 0);
                row.find('span[data-total_price]').html(0);
                alertify.error('برجاء إدخال كمية صحيحة أقل من أو تساوى ' + roundTo(max_qte));
                design.useSound('error');
            } else {
                var totalPrice = $(this).val() * row.attr('data-price');
                row.attr('data-total_price', totalPrice);
                row.find('span[data-total_price]').html(roundTo(totalPrice));
            }
            getTotalForBack();
        });

        function getTotalForBack() {
            var result = 0;
            $('#mainTable tbody tr').each(function () {
                result -= -$(this).attr('data-total_price');
            });
            $('#input_total_back').val(roundTo(result));
        }

        //function submit
        $('#form_back').submit(function (e) {
            //check back type
            if($('#select_back_type').val()==''){
                e.preventDefault();
                alertify.error('برجاء تحديد نوع للمرتجع ');
                design.useSound('error');
                return;
            }
            //check qte
            var checkIfBillHasBack=false;
            $('#mainTable tbody tr').each(function(){
                if ($(this).find('.error-qte').length != 0 || $(this).find('input[name="qte_back[]"]').val() > roundTo($(this).attr('data-max_qte'))) {
                    e.preventDefault();
                    alertify.error('برجاء إدخال كمية صحيحة أقل من أو تساوى ' + roundTo($(this).attr('data-max_qte')));
                    design.useSound('error');
                    return;
                }
                if($(this).find('input[name="qte_back[]"]').val() > 0){
                    checkIfBillHasBack=true;
                }
            });
            if(!checkIfBillHasBack){
                e.preventDefault();
                alertify.error('برجاء تحديد كميات المرتجع');
                design.useSound('error');
                return;
            }
            $('#load').css('display','block');
            design.check_submit($(this),e);
        });

        design.disable_input_submit_when_enter('#form_back input')

    </script>
@endsection
