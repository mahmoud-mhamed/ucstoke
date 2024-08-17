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
    إضافة موظف جديد
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
                <h1 class='text-center mb-4'>إضافة موظف جديد</h1>
                <form id='formAddAccount'
                      action='{{route('emps.store')}}' class='h5'
                      method='post'>
                    @csrf
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>اسم العامل أو الموظف</label>
                        <div class='col-sm-9'>
                            <input type='text'
                                   id="inputName"
                                   data-validate='min:3' data-patternType='min' autofocus required name='name'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>الأسمــــاء المــوجــــــودة</label>
                        <div class='col-sm-9'>
                            <select id='selectAccountName' class="selectpicker  show-tick form-control"
                                    data-live-search="true">
                                @foreach($emps as $a)
                                    <option data-style="padding-bottom: 50px!important;">{{$a->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class='form-group mb-3 row'>
                        <label class='col-sm-3 pt-2 text-md-left pl-3'>الــــــــــــــــــــوظــــيـفـة</label>
                        <div class='col-sm-9 position-relative'>
                            <select name='emp_jop_id' id="select_emp_jop"
                                    class='form-control-lg form-control p-0 text-right selectpicker'
                                    data-live-search="true">
                                <option value=''>برجاء التحديد</option>
                                @foreach ($jops as $s)
                                    <option value='{{$s->id}}'>{{$s->name}}</option>
                                @endforeach
                            </select>
                            <button type="button"
                                    id="btn_change_default_jop"
                                    class="btn bg-transparent position-absolute px-0 mr-auto tooltips"
                                    data-placement="bottom"
                                    title="تعين كقيمة إفتراضية للوظيفة عند إضافة موظف جديد , لمدة 5 أيام!"
                                    style="color: #38c172;top: 3px;left: 50px;"><i class="fas fa-magic"></i>
                            </button>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الحــــــسـاب الســـــابـق</label>
                        <div class="input-group col-sm-9">
                            <input type="text"  onclick="$(this).select();" data-validate='price' data-patternType='price' style="height: 45px" name='account' value="0" required
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الـــــــمـرتـب للـيــــــوم</label>
                        <div class="input-group col-sm-9">
                            <input type="text"
                                   id="input_salary_by_day"
                                   onclick="$(this).select();"
                                   data-validate='price'
                                   data-patternType='price'
                                   style="height: 45px"
                                   value="0" required
                                   name="salary_by_day"
                                   class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">جنية</span>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>رقـــــــــــــم الـهــــاتــف</label>
                        <div class='col-sm-9'>
                            <input type='text' data-validate='tel' data-patternType='tell'
                                   name='tel'
                                   class='form-control'>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>الـعـنـــــــــــــــــــــــــوان</label>
                        <div class='col-sm-9'>
                            <input type='text' name='address' data-validate='name'
                                   class='form-control'>
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
    <script src='{{ asset('js/lib/select_search.js') }}'></script>
    <script defer>
        $('#inputName').keyup(function () {
            select_search($('#inputName').val(), $('#selectAccountName option'), 'لا يوجد شخص بهذا الاسم');
            $('#selectAccountName').trigger('change');
        });
    </script>
    <script defer>
        validateByAttr();
        design.useSound();

        design.disable_input_submit_when_enter('#formAddAccount input');
        design.click_when_key_add('#button_submit');

        $('#formAddAccount').submit(function (e) {
            if ($('#select_emp_jop').val()=='' ) {
                e.preventDefault();
                alertify.error('برجاء تحديد وظيفة ');
                design.useSound('info');
                return;
            }

            if($('#input_salary_by_day').val()==0){
                e.preventDefault();
                alertify.error('برجاء تحديد المرتب باليوم ');
                design.useSound('info');
                $('#input_salary_by_day').select();
                return;
            }

            if ($('#inputName').val().length < 3) {
                e.preventDefault();
                alertify.error('برجاء كتابة 3 أحرف على الأقل فى إسم الشخص ');
                design.useSound('info');
                return;
            }
            design.check_submit($(this), e);
        });
        design.useNiceScroll();

        //set default value for jop when add
        if (Cookie.get('def_val_jop_create_emp') != '') {
            var tempValue = Cookie.get('def_val_jop_create_emp');
            $('#select_emp_jop option[value="' + tempValue + '"]').prop('selected', true);
            $('#select_emp_jop').trigger('change');
        }
        $('#btn_change_default_jop').click(function () {
            var select_text=$('#select_emp_jop').val();
            Cookie.set('def_val_jop_create_emp', select_text, {
                expires: 5
            });
            design.useSound();
            alertify.success('تم ضبط القيمة الإفتراضية للوظيفة عند إضافة موظف جديد , لمدة 5 أيام , إلى ' + $('#select_emp_jop option:selected').html());
        });
    </script>
@endsection
