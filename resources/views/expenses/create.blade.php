<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 22/01/2019
 * Time: 11:06 م
 */
?>
@extends('layouts.app')
@section('title')
    اضافة مصروفات
@endsection
@section('css')
    <style>
        span, input, select {
            font-size: 1.5rem !important;
        }
        div.error-price,div.error-negative_price{
            width: 25%;
            padding-right: 15px;
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4 px-2 pb-2'>
        <section class='box h5 text-right animated fadeInDown  faster container'>
            <div class='container  pt-3 pb-3'>
                <h1 class='text-center mb-4'>اضافة مصروف جديد</h1>
                <form id="formAddNewExpenses" action='{{route('expenses.store')}}' class="h3" method='post'>
                    @csrf
                    <div class='form-group row'>
                        <label class='col-sm-12 col-md-3 pt-2 text-md-left'>قســم المصروف</label>
                        <div class="col-md-5">
                            <select id="selectCategory" class="selectpicker form-control" name='type'
                                    data-live-search="true" data-filter-col="6">
                                <option value=''>برجاء التحديد</option>
                                @foreach ($types as $type)
                                    <option value="{{$type['id']}}">{{$type['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type='text'
                                   style="height: 51px" id="inputNewCategory"
                                   data-validate='min:5' data-patternType='min' name='newType'
                                   placeholder='قسم مصروفات جديد' title='قسم مصروفات جديد' class="form-control"/>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>المال فى الدرج</label>
                        <div class='col'>
                            <div class='input-group'>
                                <input
                                    style="height: 51px"
                                    value='{{round($device->treasury_value,2)}}'
                                    disabled
                                    class='form-control'>
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>جنية</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @if ($setting->allow_add_expenses_without_subtract_from_treasury &&
                        (Auth::user()->type==1 || Auth::user()->allow_add_expenses_with_out_subtract_form_treasury))
                        <div class='form-group row'>
                            <label class='col-sm-3 pt-2 text-md-left'>المبلغ المصروف</label>
                            <div class='col-sm-6'>
                                <div class='input-group'>
                                    <input
                                        style="height: 51px"
                                        data-validate='number' required type='number' value='' min='1' max='100000'
                                        name='price'
                                        class='form-control'>
                                    <div class="input-group-append">
                                        <span class='input-group-text font-weight-bold'>جنية</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <select id="selectType" style="height: 51px" class="form-control custom-select" name='typePrice'>
                                    <option value=''>برجاء التحديد</option>
                                    <option value='1'>خصم من الدرج</option>
                                    <option value='0'>عدم الخصم من الدرج</option>
                                </select>
                            </div>
                        </div>
                    @else
                        <div class='form-group row'>
                            <label class='col-sm-3 pt-2 text-md-left'>المبلغ المصروف</label>
                            <div class='col-sm-9'>
                                <div class='input-group'>
                                    <input
                                        id="price"
                                        data-placement='left'
                                        title='سيتم خصم المبلغ من الدرج ويجب أن يغطى الدرج المبلغ'
                                        style="height: 51px"
                                        data-validate='price' required type='text' value=''
                                        data-patternType="price"
                                        name='price'
                                        class='form-control tooltips'>
                                    <div class="input-group-append">
                                        <span class='input-group-text font-weight-bold'>جنية</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>ملاحظـــــــــــــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' name='note'></textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit'
                                    style="height: 51px"
                                    id="button_submit" onclick="design.useSound();"
                                    class='mt-3 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>اضافة المصروف</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <a href='{{route('home')}}'
                               style="height: 51px"

                               class='mt-sm-1 mt-md-3 mb-2 form-control btn btn-success animated bounceInLeft fast'>
                                <span class='h4 font-weight-bold'>الغاء</span>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
@section('js')
    <script defer>
        validateByAttr();
        design.useToolTip();
        design.useSound();
        $('#selectCategory').change(function () {
            $('#inputNewCategory').val('');
        });
        $('#inputNewCategory').click(function () {
            $('.selectpicker').selectpicker('val', '');
        });

        design.disable_input_submit_when_enter('#formAddNewExpenses input');
        design.click_when_key_add('#button_submit');

        $('#formAddNewExpenses').submit(function (e) {
            if ($('#selectType').val() == '') {
                e.preventDefault();
                alertify.error("برجاء تحديد طريقة الدفع (خصم أو عدم الخصم من الدرج)");
                design.useSound('error');
                return;
            }
            if ($('#inputNewCategory').val().length == 0 && $('#selectCategory').val() == '') {
                e.preventDefault();
                alertify.error("برجاء إختيار قسم من أقسام المصروفات أو كتابة قسم جديد قبل الإضافة");
                design.useSound('error');
                return;
            }

            if ($('#inputNewCategory').val().length < 5 && $('#selectCategory').val() == '') {
                e.preventDefault();
                alertify.error("برجاء إختيار قسم من أقسام المصروفات أو كتابة قسم جديد قبل الإضافة");
                design.useSound('error');
                return;
            }
            if($('#price').val()<=0){
                e.preventDefault();
                alertify.error("برجاء إدخال مبلغ أكبر من 0");
                design.useSound('error');
                return;
            }

            design.check_submit($(this), e);
        });
    </script>
@stop
