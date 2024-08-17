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
     اضافة عملية انتاج أو عرض
@endsection
@section('css')
    <style>
        div.error-qte{
            width: 25%;
            padding-right: 15px;
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='box animated fadeInDown faster container text-right mb-2' style='max-width: 1000px!important;'>
            <div class='container  pt-3 pb-3'>
                <h1 class='text-center mb-4'>اضافة عملية انتاج أو عرض</h1>
                <form class='h3' id="formAddMaking" action='{{route('makings.store')}}' method='post'>
                    @csrf
                    <div class='form-group row'>
                        <label class='col-12 col-md-3 pt-2  text-md-left'>مخزن العملية</label>
                        <div class='col-12 col-md-9 input-group'>
                            <select id="select_stoke_id" name="stoke_id" class="selectpicker col form-control">
                                @if($devise_stokes->default_stoke=='')
                                    <option value="">برجاء التحديد</option>
                                @endif
                                @foreach ($devise_stokes['allowedStoke'] as $d)
                                    @if ($d->stoke->state)
                                        <option
                                            value='{{$d->stoke->id}}' {{$d->stoke->id==$devise_stokes->default_stoke?'selected':''}}>{{$d->stoke->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='form-group row mt-2'>
                        <label class='col-12 col-md-3 pt-2 text-md-left'>إســـم المنتج</label>
                        <div class='col-12 col-md-9'>
                            <select id='selectProductName'
                                    name="product_id"
                                    class="selectpicker  show-tick form-control"
                                    data-live-search="true">
                                <option data-style="padding-bottom: 50px!important;" value="">
                                    برجاء
                                    التحديد
                                </option>
                                @foreach($products as $p)
                                    @if ($p->allow_buy || $p->allow_make)
                                        <option
                                            value="{{$p->id}}"
                                            data-subtext="{{$p->allow_buy?'(شراء)':''}}{{$p->allow_sale?'(بيع)':''}}"
                                            data-style="padding-bottom: 50px!important;">{{$p->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--select-->
                    <div class='form-group row mt-2'>
                        <label class='col-12 col-md-3 pt-2  text-md-left'>الـــــــــكـمـية</label>
                        <div class='col-12 col-md-9 input-group'>
                            <input id="inputQteMaking" type='text' style="height: 52px;font-size: 1.5rem"
                                   data-validate='qte' data-patternType="qte"
                                   name="qteMaking" required
                                   value='' class='form-control font-en'>
                            <div class="input-group-prepend">
                                <select id='select_unit' name="unit_relation_id"  class="form-control custom-select tooltips"
                                        data-placement="left" title="وحدة المنتج"
                                style="height: 52px;font-size: 1.5rem">
                                    <option value="">برجاء التحديد</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row mt-2'>
                        <label class='col-12 col-md-3 text-md-left'>مــــلاحـــــظة</label>
                        <div class='col-12 col-md-9'>
                            <textarea  class='form-control' name='note'></textarea>
                        </div>
                    </div>
                    <!--button-->
                    <div class='form-group row mt-2'>
                        <div class='col-12 col-md-6'>
                            <button  type='submit' id="button_submit" onclick="design.useSound();"
                                     class='font-weight-bold mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold'>حفظ العملية</span>
                            </button>
                        </div>
                        <div class='col-12 col-md-6'>
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
        design.useNiceScroll();
        design.useSound();

        $('#selectProductName').change(function () {
            $('#select_unit').html('');
            if ($('#selectProductName option').length==1) {
                alertify.error('لا يوجد منتجات برجاء إضافة منتجات لإستخدامها فى العملية ');
                design.useSound('error');
                return;
            }
            var product_id=$(this).val();
            if(product_id!=''){
                getUnitForProduct(product_id);
            }
        });
        $('#selectProductName').trigger('change');
        //get units for product
        function getUnitForProduct(id) {
            $('#select_unit').html('');
            if (id == null) {
                alertify.error('لا يوجد منتجات برجاء إضافة منتجات لإستخدامها فى العملية ');
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
                        "<option value='' selected>برجاء التحديد</option>"
                    );
                    $('#select_unit').append(
                        "<option value='0' data-relation_qte='1'>" + data['product_unit']['name'] + "</option>"
                    );
                    if (data['relation_product_unit'].length > 0) {
                        for (var i = 0; i < data['relation_product_unit'].length; i++) {
                            $('#select_unit').append(
                                "<option value='" + data['relation_product_unit'][i]['id'] +
                                "data-relation_qte='" + data['relation_product_unit'][i]['relation_qte'] + "'>" + data['relation_product_unit'][i]['product_unit']['name'] + "</option>"
                            );
                        }
                    }
                    design.useSound('success');
                },
                error: function (e) {
                    alert('error');
                    design.useSound('error');
                    console.log(e);
                }
            });
        }

        design.disable_input_submit_when_enter('#formAddMaking input');
        design.click_when_key_add('#button_submit');

        $('#formAddMaking').submit(function (e) {

            //check if stoke is no stoke
            if ($('#select_stoke_id').val()==''){
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء تحديد مخزن للعملية');
                return;
            }

            //check if product is no product selected
            if ($('#selectProductName').val()==''){
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء تحديد منتج للعملية');
                return;
            }

            //check if product is no product unit
            if ($('#select_unit').val()==''){
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء تحديد وحدة للعملية');
                return;
            }

            //check if product qte
            if ($('#inputQteMaking').val()*1 <= 0){
                e.preventDefault();
                design.useSound('error');
                alertify.error('برجاء إدخال كمية حقيقية للعملية');
                return;
            }

            design.check_submit($(this),e);
        });

    </script>
@endsection
