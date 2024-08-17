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
    إضافة أرباح أو خسائر خارجية
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
        span.input-group-text{
            font-size: 1.2rem!important;
        }
    </style>
@stop
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='box animated fadeInDown faster container text-right mb-2'>
            <div class='container  pt-3 pb-3'>
                <h1 class='text-center mb-4'>إضافة أرباح أو خسائر خارجية</h1>
                <form id='formAddAccount'
                      action='{{route('exist_deals.store')}}' class='h5'
                      method='post'>
                    @csrf
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>نـــــــــــــوع الــــعـمـليـة</label>
                        <div class='col-sm-9'>
                            <select id='select_type' class="selectpicker  show-tick form-control"
                                    name="type"
                                    data-live-search="true">
                                <option value="" data-style="padding-bottom: 50px!important;">برجاء التحديد</option>
                                <option value="0" data-style="padding-bottom: 50px!important;">أرباح خارجية</option>
                                <option value="1" data-style="padding-bottom: 50px!important;">خسائر خارجية</option>
                            </select>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>الشخص صاحب العملية</label>
                        <div class='col-sm-9'>
                            <select id='selectAccountName' class="selectpicker  show-tick form-control"
                                    name="account_id"
                                    data-live-search="true">
                                <option value="" data-style="padding-bottom: 50px!important;">برجاء التحديد</option>
                                <option value="0" data-style="padding-bottom: 50px!important;">بدون</option>
                                @foreach ($accounts as $c)
                                    <option value="{{$c->id}}"
                                            data-subtext="({{$c->tel}}) ({{round($c->account,2).'ج'}}) ({{$c->is_supplier?'مورد ':''}} {{$c->is_customer?'عميل ':''}})"
                                            data-style="padding-bottom: 50px!important;">{{$c->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>قــــــــــيـمـة الـــعـمـلية</label>
                        <div class="input-group col-sm-9">
                            <input type="text"  onclick="$(this).select();"
                                   id="input_value"
                                   data-validate='price' data-patternType='price'
                                   style="height: 45px"
                                   name='value'
                                   value="0" required
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row' id="div_paid">
                        <label class='col-sm-3 text-md-left pt-2'>الــــــمـبلغ الـــمـدفــوع</label>
                        <div class="input-group col-sm-9">
                            <input type="text"
                                   id="input_paid"
                                   onclick="$(this).select();"
                                   data-validate='price'
                                   data-patternType='price'
                                   style="height: 45px"
                                   value="0" required
                                   name="paid"
                                   class="form-control">
                            <div class="input-group-append tooltips pointer" onclick="$('#input_paid').val($('#input_value').val());design.useSound();" data-placement="left"  title="إضغط لدفع المبلغ كامل">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row' id="div_rent" >
                        <label class='col-sm-3 text-md-left pt-2'>الــــــــــــــــــــــــــبـاقـى</label>
                        <div class="input-group col-sm-9">
                            <input type="text"
                                   id="input_rent"
                                   disabled
                                   style="height: 45px"
                                   value="0"
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>مـلاحـظــــــــــــــــــــــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' id="note" required name='note'></textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit' id="button_submit" onclick="design.useSound();"
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>إضافة</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <a href='{{route('home')}}'
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
        design.useSound();

        changeShow();
        //chow paid and rent if select account
        function changeShow(){
            if($('#selectAccountName').val() !='0' && $('#selectAccountName').val()!=''){
                $('#div_paid,#div_rent').removeClass('d-none');
                design.updateNiceScroll();
            }else{
                $('#div_paid,#div_rent').addClass('d-none');
                $('#input_paid').val(0);
            }
        }

        $('#selectAccountName').change(function () {
            changeShow();
        });

        var stop_submit=false;

        $('#input_value,#input_paid').keyup(function () {
            $('#input_rent').val(roundTo($('#input_value').val() - $('#input_paid').val()));
            if($('#input_rent').val()*1<0){
                stop_submit=true;
            }else{
                stop_submit=false;
            }
        });

        design.disable_input_submit_when_enter('#formAddAccount input');
        design.click_when_key_add('#button_submit');

        $('#formAddAccount').submit(function (e) {
            if ($('#select_type').val()=='' ) {
                e.preventDefault();
                alertify.error('برجاء تحديد نوع العملية ');
                design.useSound('info');
                return;
            }

            if($('#selectAccountName').val()==''){
                e.preventDefault();
                alertify.error('برجاء تحديد شخص للعملية ');
                design.useSound('info');
                return;
            }

            if ($('#input_value').val()== 0) {
                e.preventDefault();
                alertify.error('برجاء كتابة قيمة العملية ');
                design.useSound('info');
                return;
            }

            if ($('#note').val().length <3) {
                e.preventDefault();
                alertify.error('برجاء كتابة 3 أحرف على الأقل فى ملاحظة العملية ');
                design.useSound('info');
                return;
            }

            if($('#input_value').val()*1 < $('#input_paid').val()*1){
                design.useSound('info');
                $(this).confirm({
                    text: "المبلغ المدفوع أكبر من قيمة العملية هل تريد الإضافة وتعديل الحساب بالباقى؟",
                    title: "التحقق قبل الإضافة ؟",
                    confirm: function (button) {
                        stop_submit=false;
                        $('#formAddAccount').trigger('submit');
                    },
                    cancel: function (button) {
                        alertify.success('تم إلغاء الإضافة ');
                        return;
                    },
                    post: true,
                    confirmButtonClass: "btn-danger",
                    cancelButtonClass: "btn-default",
                    dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                });
            }
            if(!stop_submit){
                design.check_submit($(this), e);
            }else{
                e.preventDefault();
            }
        });
        design.useNiceScroll();

        alertify.log('الأرباح الخارجية تضاف للدرج , الخسائر الخارجية تخصم من الدج (مع مراعات الأجل فى حالة تحديد شخص للعملية)', 'success', 0);
    </script>
@endsection
