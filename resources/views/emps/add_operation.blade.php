<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 23/01/2019
 * Time: 01:52 م
 */
?>
@extends('layouts.app')
@section('title')
    إضافة عملية
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
        input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
        }
        div.error-price,div.error-negative_price{
            width: 25%;
            padding-right: 15px;
        }
        main input{
            font-size: 1.5rem!important;
        }
    </style>
@stop
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='box animated fadeInDown faster container text-right mb-2'>
            <div class='container  pt-3 pb-3'>
                <h1 class='text-center mb-4'>إضافة عملية للموظف
                <span class="text-danger">{{$emp->name}}</span>
                </h1>
                <form id='formAddAccount'
                      action='{{route('emps.post_operation',$emp)}}' class='h5'
                      method='post'>
                    @csrf
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الحـــــــــسـاب الحـــالى</label>
                        <div class="input-group col-sm-9">
                            <input type="text"  value="{{round($emp->account,2)}}"
                                   style="height: 45px"
                                   disabled
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>نـــــــــــــــوع الــعـمـلية</label>
                        <div class='col-sm-9'>
                            <select class="selectpicker  show-tick form-control" id="select_op_type"
                                    name="op_type"
                                    onchange="show_account_after_action();"
                                    data-live-search="true">
                                    <option data-style="padding-bottom: 50px!important;" {{$type==0?'selected':''}} value="0">إضافى</option>
                                    <option data-style="padding-bottom: 50px!important;" {{$type==1?'selected':''}} value="1">خصم</option>
                                    <option data-style="padding-bottom: 50px!important;" data-subtext="(سيتم خصم المبلغ من الدرج)" {{$type==2?'selected':''}} value="2">سلفة</option>
                                    <option data-style="padding-bottom: 50px!important;" data-subtext="(سيتم خصم المبلغ من الدرج)" {{$type==3?'selected':''}} value="3">دفع أجر</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>قــــــــــــيــمة الـعـمـلية</label>
                        <div class="input-group col-sm-9">
                            <input type="text"
                                   id="input_value"
                                   onclick="$(this).select();"
                                   data-validate='price'
                                   data-patternType='price'
                                   style="height: 45px"
                                   onkeyup="show_account_after_action();"
                                   value="0" required
                                   name="price"
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الحــــسـاب بعد العملية</label>
                        <div class="input-group col-sm-9">
                            <input type="text"  value="0" id="input_account_after_action"
                                   data-account="{{$emp->account}}"
                                   style="height: 45px"
                                   disabled
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>مـلاحـظــــــــــــــــــــــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' name='note'></textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit'
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>إضافة</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <a href='{{route('emps.index')}}?show_opertaion=true'
                               class='font-weight-bold mt-2 mb-2 form-control text-white btn btn-success animated bounceInLeft fast'>
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
        design.useNiceScroll();
        design.useSound();

        function show_account_after_action(){
            var price=$('#input_value').val();
            var oldAccount=$('#input_account_after_action').attr('data-account');
            if($('#select_op_type').val()==0){
                $('#input_account_after_action').val(roundTo(oldAccount - - price));
            }else{
                $('#input_account_after_action').val(roundTo(oldAccount - price));
            }
        }
        show_account_after_action();
        $('#formAddAccount').submit(function (e) {
            if($('#input_value').val()==0){
                e.preventDefault();
                alertify.error('برجاء كتابة قيمة العملية ');
                design.useSound('info');
                return;
            }

            design.check_submit($(this), e);
        });
    </script>
@endsection
