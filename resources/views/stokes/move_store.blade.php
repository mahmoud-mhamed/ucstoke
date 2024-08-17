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
    نقل منتج من مخزن
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

        div.error-qte {
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
                <h1 class='text-center mb-4'>نقل منتج من المخزن
                    {{$store->stoke->name}}
                </h1>
                <form id='formAddMove'
                      action='{{route('stores.update',$store->id)}}' class='h5'
                      method='post'>
                    @csrf
                    @method('put')
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>إســــــــــــــم الـمـنـتـــج</label>
                        <div class='col-sm-9'>
                            <input type='text'
                                   disabled
                                   value="{{$store->product->name}}"
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الــــــكـمـية الــمـوجــودة</label>
                        <div class='col-sm-9'>
                            <input type='text'
                                   disabled
                                   value="{{round($store->qte,2)}} {{$store->product->productUnit->name}}"
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>المخزن المراد النقل إلية</label>
                        <div class='col-sm-9'>
                            <div class="">
                                <select id="select_stoke_id"
                                        name="stoke_id"
                                        data-counter_stoke="{{count($stokes)}}"
                                        class="selectpicker form-control" data-live-search="true"
                                        data-filter-col="15">
                                    <option value=''>برجاء التحديد</option>
                                    @foreach ($stokes as $s)
                                        @if ($s->stoke_id != $store->stoke_id)
                                            <option
                                                value='{{$s->stoke_id}}' {{\Auth::user()->device->default_stoke==$s->stoke_id?'selected':''}}>{{$s->stoke->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الــــكـمية الـــمراد نقلها</label>
                        <div class="input-group col-sm-9">
                            <input type="text"
                                   id="input_value"
                                   onclick="$(this).select();"
                                   data-validate='qte'
                                   data-patternType='qte'
                                   style="height: 45px"
                                   value="0" required
                                   name="value_move"
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">{{$store->product->productUnit->name}}</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الــــباقى بعد الــــعـملية</label>
                        <div class="input-group col-sm-9">
                            <input type="text"  value="0" id="input_total_after_action"
                                   data-max_qte="{{round($store->qte,2)}}"
                                   style="height: 45px"
                                   disabled
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">{{$store->product->productUnit->name}}</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>مـلاحـظـــــــــــــــــــــــــة</label>
                        <div class='col-sm-9'>
                            <textarea class='form-control' name='note'></textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit'
                                    class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>نقل</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <a href='{{route('stores.index')}}'
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
        $('#input_value').keyup(function () {
            var max_qte=$('#input_total_after_action').attr('data-max_qte');
            $('#input_total_after_action').val(roundTo(max_qte - $(this).val()));
        });
    </script>
    <script defer>
        validateByAttr();
        design.useSound();
        $('#formAddMove').submit(function (e) {
            if ($('#select_stoke_id').val() == '') {
                e.preventDefault();
                alertify.error('برجاء تحديد المخزن المراد النقل إلية! ');
                design.useSound('info');
                return;
            }


            if ($('#input_value').val() == 0) {
                e.preventDefault();
                alertify.error('برجاء تحديد الكمية المراد نقلها! ');
                design.useSound('info');
                return;
            }

            var max_qte=$('#input_total_after_action').attr('data-max_qte')*1;
            if ($('#input_value').val()*1 > max_qte) {
                e.preventDefault();
                alertify.error('برجاء إدخال كمية حقيقية أقل من أو تساوى '+roundTo(max_qte));
                design.useSound('info');
                return;
            }

            design.check_submit($(this), e);
        });
        design.useNiceScroll();
    </script>
@endsection
