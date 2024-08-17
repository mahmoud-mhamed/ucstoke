<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 24/01/2019
 * Time: 12:54 م
 */ ?>
@extends('layouts.app')
@section('title')
    {{isset($op)?'عمليات':'ادارة'}}
    الموظفين
@endsection
@section('css')
    <style>
        main span, main input {
            font-size: 1.5rem !important;
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

        #mainTable td {
            padding: 0px;
            padding-top: 5px;
            padding-bottom: 5px
        }
    </style>
@endsection
@section('content')
    <main dir='rtl' class='pt-4  pb-2 position-relative'>
        <section class='animated fadeInDown faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 text-white'>{{isset($op)?'عمليات الموظفين للجهاز الحالى':'ادارة الموظفين'}}
                </h1>
                <div class='container-fluid'>
                    <div class="h3 text-white" id="columFilter" dir="rtl">
                        <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                              value=""></label>

                        <label class="checkbox-inline pl-4 {{isset($op)?'d-none':''}}" dir="ltr">المستخدم الذى قام
                            بإضافتة<input type="checkbox"
                                          data-toggle="1"
                                          value=""></label>
                        <label class="checkbox-inline pl-4 {{isset($op)?'d-none':''}}" dir="ltr">وقت الإضافة<input
                                type="checkbox" data-toggle="2"
                                value=""></label>
                        <label class="checkbox-inline pl-4 {{isset($op)?'d-none':''}}" dir="ltr">وقت أخر تعديل<input
                                type="checkbox"
                                data-toggle="3"
                                value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الإسم<input type="checkbox" data-toggle="4"
                                                                                  checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الوظيفة<input type="checkbox" data-toggle="5"
                                                                                    checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">رقم الهاتف<input type="checkbox" data-toggle="6"
                                                                                       checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العنوان<input type="checkbox" data-toggle="7"
                                                                                    value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">ملاحظة<input type="checkbox" data-toggle="8"
                                                                                   value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">المرتب باليوم<input type="checkbox"
                                                                                          data-toggle="9"
                                                                                          checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">الحساب الحالى<input type="checkbox"
                                                                                          data-toggle="10"
                                                                                          checked value=""></label>
                        <label class="checkbox-inline pl-4 " dir="ltr">الحالة<input
                                type="checkbox" data-toggle="11"
                                {{isset($op)?'':'checked'}} value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">العمليات<input type="checkbox" data-toggle="12"
                                                                                     checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">عدد النتيجة<input id="checkCountRowsInTable"
                                                                                        type="checkbox"
                                                                                        checked value=""></label>
                        <label class="checkbox-inline pl-4" dir="ltr">إجمالى الحساب<input
                                id="checkTotalAccountRowsInTable"
                                type="checkbox"
                                checked value=""></label>
                        <button id="printMainTable" class="btn border-0 text-success bg-transparent p-0 tooltips mt-1"
                                data-placement="bottom" title="طباعة النتيجة">
                            <span class="h3"><i class="fas fa-print"></i></span>
                        </button>
                    </div>
                    <div class='mt-2 table-filters'>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class='input-group-text font-weight-bold'>الوظيفة</span>
                            </div>
                            <div class="input-group-append">
                                <select id="activityType" class="selectpicker" data-live-search="true"
                                        data-filter-col="5">
                                    <option value=''>الكل</option>
                                    @foreach ($jops as $j)
                                        <option value="{{$j->name}}">{{$j->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if (!isset($op))
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>الحالة</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="15">
                                        <option value=''>الكل</option>
                                        <option selected value='1'>مفعل</option>
                                        <option value='0'>غير مفعل</option>
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>الحساب</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="14">
                                        <option value=''>الكل</option>
                                        <option value='1'>يوجد حساب</option>
                                        <option value='0'>بدون حساب</option>
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <span class='input-group-text font-weight-bold'>الجهاز</span>
                                </div>
                                <div class="input-group-append">
                                    <select id="activityType" class="selectpicker" data-live-search="true"
                                            data-filter-col="16">
                                        <option value=''>الكل</option>
                                        <option value='0'>بدون</option>
                                        @foreach ($devices as $d)
                                            <option value='{{$d->id}}'>{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <input id="txtSearch" type='text' data-filter-col="0,1,2,3,4,5,6,7,8,9,10,11"
                                   placeholder='ابحث في النتيجة باى بيانات ' class='form-control'>
                        </div>
                    </div>
                    <div class='box-shadow tableFixHead table-responsive text-center'>
                        <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م
                                    <span id="countRowsInTable" class="font-en"></span>
                                </th>
                                <th class="{{isset($op)?'d-none':''}}">المستخدم الذى قام بإضافتة</th>
                                <th class="{{isset($op)?'d-none':''}}">وقت الإضافة</th>
                                <th class="{{isset($op)?'d-none':''}}">وقت أخر تعديل</th>
                                <th>الإسم</th>
                                <th>الوظيفة</th>
                                <th>رقم الهاتف</th>
                                <th>العنوان</th>
                                <th>ملاحظة</th>
                                <th>المرتب باليوم</th>
                                <th>
                                    الحساب الحالى
                                    <span id="span_total_account" class="font-en tooltips" data-placement='right'
                                          title='إجمالى الحساب لنتيجة البحث'></span>
                                </th>
                                <th>الحالة</th>
                                <th class="tooltips {{isset($op)?'d-none':''}}" data-placement="left"
                                    title="الجهاز المصرح له بالتعامل مع الموظف">
                                    الجهاز
                                </th>
                                <th>العمليات</th>
                                <th class="d-none">has_account</th>
                                <th class="d-none">active_state</th>
                                <th class="d-none">device_id</th>
                            </tr>
                            </thead>
                            <tbody class="h4">
                            @foreach($emps as $emp)
                                <tr data-id="{{$emp->id}}" data-account="{{$emp->account}}"
                                    class='{{$emp->state==0?'table-danger':'table-success'}}'>
                                    <td></td>
                                    <td class="{{isset($op)?'d-none':''}}">{{$emp->user->name}}</td>
                                    <td class="{{isset($op)?'d-none':''}}">{{$emp->created_at}}</td>
                                    <td class="{{isset($op)?'d-none':''}}">{{$emp->updated_at}}</td>
                                    <td>{{$emp->name}}</td>
                                    <td> {{$emp->empJop->name}}</td>
                                    <td> {{$emp->tel}}</td>
                                    <td> {{$emp->address}}</td>
                                    <td> {{$emp->note}}</td>
                                    <td> {{$emp->day_salary}}ج</td>
                                    <td> {{round($emp->account,2)}}ج</td>
                                    <td class="{{$emp->state?'':'bg-danger'}}">{!!$emp->state?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>'!!}</td>
                                    <td class="{{isset($op)?'d-none':''}}">
                                        @if (!isset($op))
                                            <form action="{{route('emps.changeState',$emp->id)}}" method="post">
                                                @csrf
                                                <select name="select_device_id" style="max-height: 40px;"
                                                        class="custom-select py-0">
                                                    @if($emp->device_id==null)
                                                        <option selected value="">بدون</option>
                                                    @endif
                                                    @foreach ($devices as $d)
                                                        <option {{$d->id==$emp->device_id?'selected':''}}
                                                                value="{{$d->id}}">{{$d->name}}</option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endif
                                    </td>
                                    <td class='text-nowrap'>
                                        @if (isset($op))
                                            <a class='btn text-primary px-0 tooltips'
                                               data-placement="right" title="إضافة إضافى للموظف"
                                               href='emps/create_operation/{{$emp->id}}/0'><span
                                                    class='h4'> </span><i class="fas fa-2x fa-plus"></i></a>
                                            <a class='btn mr-2 text-danger px-0 tooltips'
                                               data-placement="right" title="إضافة خصم للموظف"
                                               href='emps/create_operation/{{$emp->id}}/1'><span
                                                    class='h4'> </span><i class="fas fa-2x fa-minus"></i></a>
                                            <a class='btn mr-2 text-white px-0 tooltips'
                                               data-placement="right" title="إضافة سلفة للموظف"
                                               href='emps/create_operation/{{$emp->id}}/2'><span
                                                    class='h4'> </span><i class="fas fa-2x fa-hand-holding-usd"></i></a>
                                            <a class='btn mr-2 text-warning px-0 tooltips'
                                               data-placement="right" title="دفع أجر للموظف"
                                               href='emps/create_operation/{{$emp->id}}/3'><span
                                                    class='h4'> </span><i class="fas fa-2x fa-dollar-sign"></i></a>
                                        @else
                                            <button class='btn px-0 bg-transparent tooltips'
                                                    title="{{$emp->state?'إلغاء تفعيل الشخص بحيث لا يظهر فى الحضور':'تفعيل الشخص'}}"
                                                    data-placement="right"
                                                    data-type='changeState'><span
                                                    class='font-weight-bold text-dark h4'>{!!$emp->state?'<i class="fas text-danger fa-eye-slash"></i>':'<i class="fas text-success fa-eye"></i>'!!} </span>
                                            </button>
                                            <a class='btn  mx-2 text-primary px-0 tooltips'
                                               data-placement="right" title="تعديل"
                                               href='emps/{{$emp->id}}/edit'><span
                                                    class='h4'> </span><i class='fas fa-2x fa-edit'></i></a>
                                            @if ($emp->account==0)
                                                <button class='btn  bg-transparent tooltips p-0'
                                                        data-delete='{{$emp->id}}' data-placement='right'
                                                        title='لا يمكن الحذف إذا كان هذا الموظف تعامل مع النظام ولكن يجوز إلغاء تفعيلة لمنع ظهورة فى الحضور '>
                                                    <span class='font-weight-bold text-danger h4'> </span><i
                                                        class='fas fa-2x fa-trash-alt text-danger'></i></button>
                                            @endif
                                        @endif
                                    </td>
                                    <td class='d-none'>{{round($emp->account,2)==0?0:1}}</td>
                                    <td class='d-none'>{{$emp->state?1:0}}</td>
                                    <td class='d-none'>{{$emp->device_id?$emp->device_id:0}}</td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        @if (!isset($op))
            {{--changeState--}}
            <form class='d-inline' id='changeState' action='{{route('emps.changeState',0)}}' method='post'>
                <input id="input_change_state_type" type="hidden" name="type">
                @csrf
            </form>
            <form action='{{route('emps.destroy',0)}}' id='form_delete_account' class='d-none' method='post'>
                @csrf
                @method('delete')
            </form>
        @endif
    </main>

@endsection

@section('js')
    <script defer>
        design.useNiceScroll();
        design.useSound();
        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        $('#mainTable').on('aftertablefilter', function (event) {
            getRowCounterAndTotalAccount();

            @if(!isset($op))
            //change emp state active
            $('#mainTable tbody button[data-type="changeState"]').click(function () {
                $('button').attr('disabled', 'disabled');
                $('#load').css('display', 'block');
                var id = $(this).parent().parent().attr('data-id');
                var action = $('#changeState').attr('action');
                $('#input_change_state_type').val('changeState');
                action = action.replace(/[0-9]$/, id);
                $('#changeState').attr('action', action).submit();
            });


            /*change default device*/
            $('select[name="select_device_id"]').change(function (e) {
                $('#load').css('display', 'block');
                $(this).parent().submit();
            });


            $('#mainTable').on('click', 'tbody tr button[data-delete]', function (e) {
                var id = $(this).attr('data-delete');
                var parent = $(this).parent().parent();
                parent.addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
                design.useSound('info');
                $(this).confirm({
                    text: "هل تريد حذف الشخص المحدده؟",
                    title: "حذف شخص ",
                    confirm: function (button) {
                        var action = $('#form_delete_account').attr('action');
                        action = action.replace(/[0-9]$/, id);
                        $('#form_delete_account').attr('action', action).submit();
                    },
                    cancel: function (button) {

                    },
                    post: true,
                    confirmButtonClass: "btn-danger",
                    cancelButtonClass: "btn-default",
                    dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
                });
            });

            @endif
        });

        /*hide and show colum in table*/
        $('#columFilter input').each(function () {
            var index = $(this).attr('data-toggle');
            if ($(this).prop('checked')) {
                $('#mainTable tr').each(function () {
                    $(this).children().eq(index).show();
                });
            } else {
                $('#mainTable tr').each(function () {
                    $(this).children().eq(index).hide();
                });
            }

        });
        if ($('#checkCountRowsInTable').prop('checked')) {
            $('#countRowsInTable').show();
        } else {
            $('#countRowsInTable').hide();
        }
        $('#checkCountRowsInTable').change(function () {
            if ($('#checkCountRowsInTable').prop('checked')) {
                $('#countRowsInTable').show();
            } else {
                $('#countRowsInTable').hide();
            }
        });

        $('#checkTotalAccountRowsInTable').change(function () {
            if ($('#checkTotalAccountRowsInTable').prop('checked')) {
                $('#span_total_account').show();
            } else {
                $('#span_total_account').hide();
            }
        });

        function getRowCounterAndTotalAccount() {
            var counterRow = 0;
            var totalAccount = 0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                    $(this).children().eq(0).html(counterRow);
                    totalAccount -= -($(this).attr('data-account'));
                }
            });
            $('#countRowsInTable').html(counterRow);
            totalAccount = totalAccount == 0 ? '' : totalAccount + 'ج';
            $('#span_total_account').html(roundTo(totalAccount));

            design.useToolTip(); //to update tooltip after filter
            design.updateNiceScroll();
        }

        /*hide and show colum in table*/
        $('#columFilter input').click(function () {
            var index = $(this).attr('data-toggle');
            if ($(this).prop('checked')) {
                $('#mainTable tr').each(function () {
                    $(this).children().eq(index).show();
                });
            } else {
                $('#mainTable tr').each(function () {
                    $(this).children().eq(index).hide();
                });
            }
        });


        //print main table
        $('#printMainTable').click(function () {
            design.useSound();
            alertify.success('برجاء الإنتظار جارى الطباعة!');
            $('#mainTable').parent().printArea({
                extraCss: '{{asset('css/print1.css')}}'
            });
        });

    </script>
@endsection
