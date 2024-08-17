<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 22/01/2019
 * Time: 12:15 م
 */
?>
@extends('layouts.app')
@section('title','َضبط الأجهزة التصلة')

@section('css')
    <style>
        main span,main input,main button {
            font-size: 1.5rem !important;
        }

        input {
            padding: 25px 10px !important;
        }

        select {
            height: 53px !important;
            font-size: 1.5rem;
        }

        .form-check-inline {
            margin-right: 40px;
        }

        .form-check-inline * {
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4 px-md-3 pb-2'>
        <section class='animated fadeInDown ml-auto faster table-filters'>
            <h1 class='text-white text-center font-weight-bold pb-3 mh1'>ضبط الأجهزة المتصلة</h1>
            <div class='container-fluid text-center'>
                <div class='box-shadow table-responsive table-success text-center'>
                    <table id="mainTable" class='m-0 sorted table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م</th>
                            <th>اسم الجهاز</th>
                            <th>المال في الخزينة</th>
                            <th class="small">ديزاين الطباعة فى الفواتير</th>
                            <th>المخزن الرئيسى</th>
                            @foreach($stokes as $s)
                                <th>{{$s->name}}</th>
                                @endforeach
                            <th>العمليات</th>
                        </tr>
                        </thead>
                        <tbody class="h4">
                            @foreach ($devices as $d)
                                <tr class="table-success">
                                    <td data-id="{{$d->id}}">{{$loop->index + 1}}</td>
                                    <td>{{$d->name}}</td>
                                    <td>{{round($d->treasury_value, 2)}} ج</td>
                                    <td>
                                        <form action="{{route('devices.changeDefaultBillPrint',$d->id)}}" method="post">
                                            @csrf
                                            <select name="design_print_id" class="custom-select">
                                                @foreach ($prints as $p)
                                                    <option value="{{$p->id}}" {{$d->design_bill_print==$p->id?'selected':''}}>{{$p->name}}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td>{{$d->mainStoke?$d->mainStoke['name']:'بدون'}}</td>
                                    @foreach($stokes as $s)
                                        <?php $stateStoke=false; ?>
                                        @foreach ($d->allowedStoke as $a)
                                            <?php if ($a->stoke_id==$s->id){
                                                    $stateStoke=true;
                                                } ?>
                                        @endforeach
                                    <td class="{{$stateStoke?'':'bg-danger'}}">
                                        @if ($stateStoke && ($d->default_stoke=='' || $d->default_stoke!=$s->id))
                                            <button data-default="{{$s->id}}"
                                                class="btn px-0 bg-transparent tooltips" type="button" data-placement="bottom" title="تعين كمخزن إفتراضى"><i class="fas text-info fa-magic"></i></button>
                                        @endif
                                        <button class='btn px-0 bg-transparent tooltips'
                                                data-change_access="{{$stateStoke?0:1}}"
                                                data-stoke_id="{{$s->id}}"
                                                title="{{$stateStoke?'إضغط لمنع الوصول لهذا المخزن':'إضغط لتمكين الوصول لهذا المخزن'}}"
                                                data-placemen="left"
                                                type="button"><span
                                                class='font-weight-bold text-dark h4'>{!!$stateStoke?'<i class="fas text-danger fa-eye-slash"></i>':'<i class="fas text-success fa-eye"></i>'!!} </span>
                                        </button>
                                    </td>
                                    @endforeach
                                    <td>
                                        <button class='btn btn-primary tooltips' data-placement="bottom" title="تعديل إسم الجهاز" data-type='edit'><span class='font-weight-bold text-dark'>تعديل </span><i class='fas fa-edit text-dark'></i></button>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <section class='edit d-none'>
            <div class='w-100 h-100 position-fixed' style='left:0;top: 0;z-index: 2;background: rgba(113, 107, 107, 0.5);'>
                <div id='formEdit' class='text-center box-shadow  bg-white pb-2'>
                    <form id='editSaves' action='{{route('devices.update',0)}}' method='post'>
                        @csrf
                        @method('put')
                        <fieldset class="px-1" style="border: 3px solid #164e5b">
                            <legend class="w-auto px-2">تعديل إسم جهاز
                                <span id='oldName'></span>
                            </legend>
                            <div class='form-group row h3'>
                                <label class='col-sm-2 pt-2 text-md-left '> إسم الجهاز الجديد</label>
                                <div class='col-sm-5'>
                                    <input value='' required
                                           type='text'
                                           id='newName'
                                           data-validate='min:5' data-patternType='min'
                                           placeholder='من فضلك أدخل إسم الجهاز الجديد'
                                           name='name' class='form-control pr-5'>
                                </div>
                                <div class='col-sm-5'>
                                    <button type='submit'
                                            id='edit'
                                            class='font-weight-bold form-control btn-lg w-auto px-5 d-inline  btn btn-success animated bounceInRight fast'
                                            style='padding-bottom: 42px!important;'>
                                        <span>حفظ</span>
                                    </button>
                                    <button type='button'
                                            id='cancelEdit'
                                            class='font-weight-bold form-control btn-lg w-auto px-5 d-inline  btn btn-success animated bounceInRight fast'
                                            style='padding-bottom: 42px!important;'>
                                        <span>الغاء</span>
                                    </button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </section>
        {{--changeState saves--}}
        <form class='d-inline' id='changeStateSaves' action='{{route('devices.changeState',0)}}' method='post'>
            <input type="hidden" id="input_change_state_type" name="type" value="1">{{--1 for change default stoke,2 change state allow access to stoke--}}
            <input type="hidden" id="input_stoke_id" name="stoke_id" value="">
            <input type="hidden" id="input_stoke_state_access" name="state_access" value="">
            @csrf
        </form>
    </main>
@endsection

@section('js')
    <script defer>
        alertify.log('ملاحظة : الوصول إلى مخزن يتوقف على حالة المخزن فى إدارة المخازن (مفعل أو غير مفعل)','success',0);
        design.useSound();

        validateByAttr();
        $('#formEdit').animate({
            marginTop: 200
        }, 300);
        design.useNiceScroll();


        /*change default stoke*/
        $('table').on('click', 'tbody button[data-default]', function (e) {
            $('#load').css('display', 'block');
            $('table button').attr('disabled','disabled');
            var id = $(this).parent().siblings().eq(0).attr('data-id');
            var action = $('#changeStateSaves').attr('action');
            $('#input_change_state_type').val('1');
            $('#input_stoke_id').val($(this).attr('data-default'));
            action = action.replace(/[0-9]$/, id);
            $('#changeStateSaves').attr('action', action).submit();
        });

        /*change state allow access to stoke*/
        $('table').on('click', 'tbody button[data-change_access]', function (e) {
            $('#load').css('display', 'block');
            $('table button').attr('disabled','disabled');
            var id = $(this).parent().siblings().eq(0).attr('data-id');
            var action = $('#changeStateSaves').attr('action');
            $('#input_change_state_type').val('2');
            $('#input_stoke_id').val($(this).attr('data-stoke_id'));
            $('#input_stoke_state_access').val($(this).attr('data-change_access'));
            action = action.replace(/[0-9]$/, id);
            $('#changeStateSaves').attr('action', action).submit();
        });

        $('#editSaves').submit(function (e) {
            design.check_submit($(this),e);
        });

        /*edit saves*/
        $('table').on('click', 'tbody button[data-type="edit"]', function (e) {
            var name = $(this).parent().siblings().eq(1).html();
            var id = $(this).parent().siblings().eq(0).attr('data-id');

            design.useSound('info');

            $('section.edit').removeClass('d-none');
            $('#oldName').html(name);
            $('#newName').val(name);
            var action = $('#editSaves').attr('action');
            action = action.replace(/[0-9]$/, id);
            $('#editSaves').attr('action', action);
        });

        $('#cancelEdit').click(function () {
            $('section.edit').addClass('d-none');
            design.useSound('success');
        });


        /*change default printBillDesign*/
        $('select[name="design_print_id"]').change(function (e) {
            $('#load').css('display', 'block');
            $(this).parent().submit();
        });
    </script>
@endsection
