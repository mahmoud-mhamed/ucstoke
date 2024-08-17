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
    {{$type=='create'?'إضافة':'تعديل'}}

    {{isset($bill)?'زيارة':'مهمة'}}
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

        div.error-price, div.error-negative_price {
            width: 25%;
            padding-right: 15px;
        }

        main input {
            font-size: 1.5rem !important;
        }
    </style>
@stop
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='box animated fadeInDown faster container text-right mb-2'>
            <div class='container  pt-3 pb-3'>
                <form id='formAddAccount'
                      @if($type=='create')
                      action='{{route('visits.store')}}'
                      @else
                      action='{{route('visits.update',$visit->id)}}'
                      @endif
                      class='h5'
                      method='post'>
                    @csrf
                    @if($type!='create')
                        @method('PUT')
                    @endif
                    <h1 class='text-center mb-4'>
                        {{$type=='create'?'إضافة':'تعديل'}}
                        @if(isset($bill))
                            <input type="hidden" name="bill_id" value="{{$bill->id}}">
                            زيارة
                            للفاتورة رقم
                            <span class="font-en text-underline text-danger">{{$bill->id}}</span>
                            بإسم
                            {{$bill->account->name}}
                            <span
                                class="h4">({{$bill->account->is_supplier?'مورد ':''}} {{$bill->account->is_customer?'عميل ':''}})</span>
                        @else
                            مهمة
                        @endif
                        <div class="d-inline-block float-right">
                            <input type="checkbox" name="stateFinish" id="checkBoxFinish"
                                   {{isset($visit->state_visit)?($visit->state_visit==1?'checked':''):''}}
                                   value="1" onclick="$('#div_alarm,#div_date_finish').toggleClass('d-none')">
                            <span class="h2 pointer" onclick="$(this).prev().trigger('click')">منتهى</span>
                        </div>
                    </h1>

                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>الـــــــــــنـوع</label>
                        <div class='col-sm-9'>
                            <select id='select_visit_type' class="selectpicker  show-tick form-control"
                                    name="type"
                                    data-live-search="true">
                                @if (isset($bill))
                                    <option value="" data-style="padding-bottom: 50px!important;">برجاء التحديد</option>
                                    <option value="0"
                                            {{isset($visit->type)?($visit->type==0?'selected':''):''}} data-style="padding-bottom: 50px!important;">
                                        زيارة مجانية
                                    </option>
                                    <option value="1"
                                            {{isset($visit->type)?($visit->type==1?'selected':''):''}} data-style="padding-bottom: 50px!important;">
                                        زيارة مدفوعة فى المكان
                                    </option>
                                    <option value="2"
                                            {{isset($visit->type)?($visit->type==2?'selected':''):''}} data-style="padding-bottom: 50px!important;">
                                        زيارة مدفوعة أون لاين
                                    </option>
                                @else
                                    <option value="3"
                                            {{isset($visit->type)?($visit->type==3?'selected':''):''}} data-style="padding-bottom: 50px!important;">
                                        مهمة
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>قيمة العملية</label>
                        <div class="input-group col-sm-9">
                            <input type="text"
                                   onclick="$(this).select();" data-validate='negative_price' data-patternType='negative_price'
                                   style="height: 45px" value="{{isset($visit->price)?$visit->price:'0'}}" required
                                   name="price"
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div id="div_alarm" class="{{isset($visit->state_visit)?($visit->state_visit==1?'d-none':''):''}}">
                        <div class='form-group row'>
                            <label class='col-sm-3 text-md-left pt-2'>تاريخ الإشعار</label>
                            <div class='col-sm-9'>
                                <input type='text'
                                       id="inputDate"
                                       data-validate='min:3' data-patternType='min' autofocus required
                                       name='date_alarm'
                                       class='form-control'>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <label class='col-sm-3 text-md-left pt-2'>تنـبـية قــبل</label>
                            <div class='col-sm-9 input-group-append'>
                                <input type="number"
                                       onclick="$(this).select();"
                                       id="dayAfterNotification"
                                       min="0" required
                                       name='alarm_before'
                                       value="{{isset($visit->alarm_before)?$visit->alarm_before:'0'}}"
                                       class='form-control'>
                                <span class="input-group-text font-en" style="height: 38px">يوم</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class='form-group {{isset($visit->state_visit)?($visit->state_visit==0?'d-none':''):'d-none'}} row'
                        id="div_date_finish">
                        <label class='col-sm-3 text-md-left pt-2'>تاريخ الإنتهاء</label>
                        <div class='col-sm-9'>
                            <input type='text'
                                   id="inputDate2"
                                   data-validate='min:3' data-patternType='min' required
                                   name='date_finish'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>مـلاحـظـــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' required id="note"
                                      name='note'>{{isset($visit->note)?$visit->note:''}}</textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit' id="button_submit" onclick="design.useSound();"
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>حفظ</span>
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
    <script src='{{ asset('js/lib/select_search.js') }}'></script>
    <script defer>
        design.dateRang($('#inputDate'), 'date', true);
        design.dateRang($('#inputDate2'), 'date2');
        @if(isset($visit))
        $('#inputDate').val('{{$visit->date_alarm}}');
        @if($visit->state_visit==1)
        $('#inputDate2').val('{{$visit->date_finish}}');
        @endif
        @endif
        alertify.log('ملاحظة : عند تحديد منتهى  سيتم إضافة قيمة العملية إلى الدرج يمكن أن تكون القيمة 0 أو رقم أصغر من 0', 'success', 0);

        //select type if add has old type
        design.useSound();
    </script>
    <script defer>
        validateByAttr();
        design.disable_input_submit_when_enter('#formAddAccount input');
        design.click_when_key_add('#button_submit');

        $('#formAddAccount').submit(function (e) {
            if ($('#note').val().length < 3) {
                e.preventDefault();
                alertify.error('برجاء كتابة 3 أحرف على الأقل فى ملاحظة العملية ');
                design.useSound('info');
                return;
            }
            if ($('#select_visit_type').val() == '') {
                e.preventDefault();
                alertify.error('برجاء تحديد نوع للعملية! ');
                design.useSound('info');
                return;
            }
            design.check_submit($(this), e);
        });
        design.useNiceScroll();
    </script>
@endsection
