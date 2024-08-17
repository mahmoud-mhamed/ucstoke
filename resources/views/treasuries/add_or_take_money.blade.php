<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 23/03/2019
 * Time: 10:46 م
 */?>
@extends('layouts.app')
@section('title')
    اخذ ووضع مال في الدرج
@endsection
@section('css')
    <style>
        .h3,.h3 *{
            font-size: 1.575rem!important;
        }
        input[type='text']{
            height: 54px;
        }
        div.error-price,div.error-negative_price{
            width: 25%;
            padding-right: 15px;
        }
    </style>
@stop
@section('content')
    <div class='text-center container' dir='rtl'>
        <h1 class='text-white font-weight-bold pb-2 mt-5'>درج
            الجهاز
            {{$device->name}}</h1>
        <div class='box-shadow h3'>
            <div class="input-group">
                <div class="input-group-append">
                    <span type="text" class="input-group-text">
                        المال في الدرج
                    </span>
                </div>
                <input value='{{round($treasury,2)}}' id='money' type="text" disabled class="form-control">
                <div class="input-group-append" style="z-index: 0">
                    <span type="text" class="input-group-text">جنية</span>
                </div>
                <div class="input-group-append" style="z-index: 0">
                    <button id='setMoney' class="btn btn-success">وضع مال في الدرج</button>
                    <button id='takMoney' class="btn btn-primary">اخذ مال</button>
                </div>
            </div>
        </div>
    </div>
    <div class='takeMoney h3 position-fixed d-none'
         style='top: 0;left: 0;width: 100vw;height: 100vh;background: rgba(0,0,0,.8);z-index: 2'>
        <div class='container py-3 mt-4 box text-dark text-center' style='max-width: 800px'>
            <form action='{{route('treasuries.post_add_or_take_money',1)}}' id="form_take_money" method='post'>
                @csrf
                <h2>ادخل المبلغ المراد اخذة</h2>
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text" >جنية</span>
                    </div>
                    <input type="text" name='money' data-validate='price' required id="input_take_money"
                           data-patternType='price' value='' class="form-control text-center" >
                    <div class="input-group-append">
                        <span class="input-group-text" >المبلغ</span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class='col-sm-10  mt-2'>
                        <textarea  name='note'  class='form-control text-right'></textarea>
                    </div>
                    <label class='col-sm-2 mt-3 '>ملاحظة</label>
                </div>
                <button type='submit' class='btn btn-primary save'>حفظ</button>
                <button type='button' class='btn btn-danger cancel'
                        onclick="$('div.takeMoney').addClass('d-none');design.useSound('success');">الغاء</button>
            </form>
        </div>
    </div>
    <div class='setMoney h3 position-fixed d-none'
         style='top: 0;left: 0;width: 100vw;height: 100vh;background: rgba(0,0,0,.8);z-index: 2'>
        <div class='container py-3 mt-4 box text-dark text-center' style='max-width: 800px'>
            <form id="form_add_money" action='{{route('treasuries.post_add_or_take_money',0)}}' method='post'>
                @csrf
                <h2>ادخل المبلغ المراد وضعة في الدرج</h2>
                <div class="input-group mb-3">
                    <div class="input-group-append">
                        <span class="input-group-text" >جنية</span>
                    </div>
                    <input type="text" id="input_add_money" name='money' data-validate='price' required
                           data-patternType='price' class="form-control text-center" >
                    <div class="input-group-append">
                        <span class="input-group-text" >المبلغ</span>
                    </div>
                </div>
                <div class="form-group row" dir="rtl">
                    <label class='col-sm-2 mt-3 '>ملاحظة</label>
                    <div class='col-sm-10  mt-2'>
                        <textarea  name='note'  class='form-control text-right'></textarea>
                    </div>
                </div>
                <button type='submit' class='btn btn-primary save'>حفظ</button>
                <button type='button' class='btn btn-danger cancel'
                        onclick="$('div.setMoney').addClass('d-none');design.useSound('success');">الغاء</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script defer>
        validateByAttr();
        design.useSound();
        $(function () {
            $('#takMoney').click(function () {
                if ($('#money').val()!='0'){
                    design.useSound('info');
                    $('div.takeMoney').removeClass('d-none');
                }else{
                    alertify.error('لا يوجد مال فى الدرج ');
                    design.useSound('error');
                }
            });
            $('#setMoney').click(function () {
                design.useSound('info');
                $('div.setMoney').removeClass('d-none');
            });
        });

        $('#form_take_money').submit(function (e) {
            if($('#input_take_money').val()*1==0){
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء كتابة رقم أكبر من 0');
                return;
            }
            if($('#money').val()*1 < $('#input_take_money').val()*1){
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء كتابة رقم أقل من أو يساوى '+$('#money').val()+'ج');
                return;
            }else{
                design.check_submit($(this),e);
            }
        });
        $('#form_add_money').submit(function (e) {
            if($('#input_add_money').val() <= 0){
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء كتابة رقم أكبر من 0 ');
            }else{
                design.check_submit($(this),e);
            }
        });
    </script>
@endsection
