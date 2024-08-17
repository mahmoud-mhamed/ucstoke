<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 22/01/2019
 * Time: 12:15 م
 */
?>
@extends('layouts.app')
@section('title')
    نشاطات المستخدمين
@endsection
@section('css')
    <style>
        span, input {
            font-size: 1.5rem !important;
        }

        input {
            padding: 25px 10px !important;
        }

        select {
            height: 53px !important;
            font-size: 1.5rem;
        }

        input[type="checkbox"] {
            transform: scale(2);
            margin: 10px 10px 0 10px;
            cursor: pointer;
        }

        label {
            font-size: 1.5rem;
        }
    </style>
    <style>
        .tableFixHead {
            overflow-y: auto;
            max-height: 50vh !important;
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
    <main dir='rtl' class='pt-4 px-md-3 pb-2'>
        <section class='animated fadeInDown ml-auto faster '>
            <h1 class='text-white text-center font-weight-bold pb-3 mh1'>نشاطات المستخدمين</h1>
            <div class='container-fluid text-center'>
                <div data-date='all' class='row no-gutters overflow-hidden'>
                    <!--from date-->
                    <div class='col-sm-12 col-md-6'>
                        <div class='input-group-prepend text-center '>
                            <span class='input-group-text font-weight-bold' style='min-width: 150px'>من الثلاثاء</span>
                            <input type='text' data-date='from' class='font-weight-bold text-center form-control'>
                        </div>
                    </div>
                    <!--to date-->
                    <div class='col-sm-12 col-md-6 mt-1 mt-md-0'>
                        <div class='input-group-prepend text-center '>
                            <span class='input-group-text font-weight-bold' style='min-width: 150px'>الي الخميس</span>
                            <input type='text' data-date='to' class='font-weight-bold text-center form-control'>
                        </div>
                    </div>
                </div>
                <div class='mt-2 '>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>اسم المستخدم</span>
                        </div>
                        <div class="input-group-append">
                            <select id="userName" class="selectpicker" data-live-search="true">
                                <option value='all'>الكل</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>بحث</span>
                        </div>
                        <input type='text' id="searchText" placeholder='البحث من خلال نص' class='form-control'>
                        <div class="input-group-append">
                            <button id="search" class="btn-success btn font-weight-bold pointer tooltips"
                                    data-placement="bottom" title="بحث فى الفتره المحددة للمستخدم المحدد بالنص المكتوب">
                                <span class="h5">بحث</span></button>
                            <form id="deleteAll" action='{{route('activity.destroy',-1)}}' method='post'>
                                @csrf
                                @method('delete')
                                <button data-type="deleteAll"
                                        style='font-size: 1.5rem'
                                        type='button' class='btn border-white btn-info font-weight-bold'>
                                    تعين كل الإشعارات كمقروئة
                                </button>
                            </form>
                            <form id='destroyAll' action='{{route('activity.truncate')}}' method='post'>
                                @csrf
                                @method('delete')
                                <button data-type="delete"
                                        style='font-size: 1.5rem'
                                        type='button' class='btn border-white btn-warning font-weight-bold'>
                                    مسح الكل
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="input-group table-filters">
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>نوع النشاط</span>
                        </div>
                        <div class="input-group-append">
                            <select id="activityType" class="selectpicker" data-live-search="true" data-filter-col="4">
                                <option value=''>الكل</option>
                                <option value='0'>المستخدمين</option>
                                <option value='1'>المخازن وحركة المنتجات بين المخازن</option>
                                <option value='2'>أماكن الحفظ في المخازن</option>
                                <option value='3'>النسخ الإحتياطى</option>
                                <option value='4'>ضبط إعدادات البرنامج</option>
                                <option value='5'>الموردين</option>
                                <option value='6'>العملاء</option>
                                <option value='7'>الموردين العملاء</option>
                                <option value='9'>الأجهزة المتصلة</option>
                                <option value='10'>أخذ ووضع مال فى الدرج</option>
                                <option value='11'>المصروفات</option>
                                <option value='12'>المنتجات(وحدات - أقسام - باركود)</option>
                                <option value='13'>فواتير شراء</option>
                                <option value='14'>فواتير بيع</option>
                                <option value='15'>رسائل وديزاين الفواتير</option>
                                <option value='8'>توالف منتجات</option>
                                <option value='16'>إنتاج منتجات</option>
                                <option value='17'>الموظفين (أنواع الوظائق - إضافة موظف - حركة الموظفين)</option>
                                <option value='18'>الأرباح والخسائر الخارجية</option>
                                <option value='19'>زيارة أو مهمة</option>

                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>حالة التنبيهات</span>
                        </div>
                        <div class="input-group-append">
                            <select class="selectpicker" data-live-search="true" data-filter-col="5">
                                <option value=''>الكل</option>
                                <option value='1'>بتنبية</option>
                                <option value='0'>بدون تنبية</option>
                                <option value='2'>كان بتنبية</option>
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>العلاقة بالدرج</span>
                        </div>
                        <div class="input-group-append">
                            <select class="selectpicker" data-live-search="true" data-filter-col="6">
                                <option value=''>الكل</option>
                                <option value='0'>ليس له علاقة</option>
                                <option value='.'>له علاقة</option>
                                <option value='1'>أضاف للدرج</option>
                                <option value='2'>أنقص من الدرج</option>
                            </select>
                        </div>
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>الجهاز</span>
                        </div>
                        <div class="input-group-append">
                            <select class="selectpicker" data-live-search="true" data-filter-col="6">
                                <option value=''>الكل</option>
                                @foreach ($devices as $d)
                                    <option value='{{$d->id}}'>{{$d->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input id="txtSearch" type='text' data-filter-col="0,1,2,3"
                               placeholder='ابحث في نتيجة البحث من خلال أى بيانات (م,إسم المستخدم,التاريخ,التفاصيل) '
                               class='form-control'>
                    </div>
                </div>
                <div class="h3 text-white" id="columFilter" dir="rtl">
                    <label class="checkbox-inline pl-4" dir="ltr">م<input type="checkbox" data-toggle="0" checked
                                                                          value=""></label>

                    <label class="checkbox-inline pl-4" dir="ltr">إسم المستخدم<input type="checkbox" checked
                                                                                     data-toggle="1"
                                                                                     value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">التاريخ<input type="checkbox" data-toggle="2" checked
                                                                                value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">تفاصيل النشاط<input type="checkbox" checked
                                                                                      data-toggle="3"
                                                                                      value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">الجهاز الذى تمت عليه العملية<input type="checkbox"
                                                                                                     data-toggle="8"
                                                                                                     value=""></label>
                    <label class="checkbox-inline pl-4" dir="ltr">القيمة المضافة للدرج<input type="checkbox"
                                                                                             data-toggle="9"
                                                                                             value=""></label>
                </div>

                <div class='box-shadow tableFixHead table-responsive table-success text-center'>
                    <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م</th>
                            <th>اسم المستخدم</th>
                            <th>التاريخ</th>
                            <th>تفاصيل النشاط</th>
                            <th style="display: none">النوع</th>
                            <th style="display: none">حالة التنبية</th>
                            <th style="display: none">العلاقة بالدرج</th>
                            <th style="display: none">device Id</th>
                            <th style="display: none">الجهاز</th>
                            <th style="display: none">القيمة المضافة للدرج</th>
                        </tr>
                        </thead>
                        <tbody class="h4">
                        @if (isset($activity))
                            <tr class="{{$activity->notification!='0'?'table-danger':'table-success'}}">
                                <td data-rowId="{{$activity->id}}">1</td>
                                <td>{{isset($activity->user)?$activity->user->name:''}}</td>
                                <td class="text-nowrap">{{$activity->created_at}}</td>
                                <td>
                                    <p class='d-inline-block mb-0 pb-1'
                                       style='overflow: auto;max-height: 70px;'>
                                        @if ($activity->notification==1)
                                            <button class='btn btn-danger ml-2' data-deleteOne='{{$activity->id}}'><span
                                                    class='h3 font-weight-bold'>تعين كمقروء</span></button>
                                        @endif
                                        {{$activity->data}}
                                    </p>
                                </td>
                                <td style='display: none'>{{$activity->type}}</td>
                                <td style='display: none'>{{$activity->notification}}</td>
                                <td style='display: none'>{{$activity->relation_treasury}}</td>
                                <td style='display: none'>{{$activity->device_id}}</td>
                                <td style='display: none'>{{$activity->device==null?'':$activity->device->name}}</td>
                                <td style='display: none'>{{$activity->treasury_value == null ? '' :
                                 (($activity->relation_treasury==2?'<i class="fas text-warning ml-2 fa-minus"></i>':'<i class="fas text-primary ml-2 fa-plus"></i>')
                                 .$activity->treasury_value)}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <form id='deleteOne' action='{{route('activity.destroy',0)}}' data-type='delete' class='d-none' method='post'>
            @csrf
            @method('delete')
        </form>
    </main>
@endsection

@section('js')
    <script defer>
        design.useNiceScroll();
        $('#mainTable').filtable({controlPanel: $('.table-filters')});


        $('#activityType option').each(function () {
            if ($(this).val() != '')
                $(this).attr('value', ($(this).val() - -100));
        });

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

        $('#searchText').val('');
        design.dateRangFromTo('input[data-date="from"]', 'input[data-date="to"]',
            '[data-date=all]', 'DateActivity');

        $('div.DateActivity').click(function () {
            $('#mainTable tbody').html('');
            alertify.error('برجاء الضغط على زر البحث');
            design.useSound('info');
        });
        $('#userName').change(function () {
            $('#mainTable tbody').html('');
            alertify.error('برجاء الضغط على زر البحث');
            design.useSound('info');
        });
        $('div[data-date=all] input').keyup(function () {
            $('#mainTable tbody').html('');
            alertify.error('برجاء الضغط على زر البحث');
            design.useSound('info');
        });
        $('#searchText').click(function () {
            $('#mainTable tbody').html('');
            alertify.error('برجاء الضغط على زر البحث');
            design.useSound('info');
        });

        function getData() {
            $('#search').attr('disabled', 'disabled');
            $('#mainTable tbody').html('');
            design.updateNiceScroll();
            $('input[type="search"]').val('');
            $('#txtSearch').val('');

            $.ajax({
                url: '{{route('activity.search')}}',
                method: 'POST',
                data: {
                    dateFrom: $('input[data-date="from"]').val(),
                    dateTo: $('input[data-date="to"]').val(),
                    search: $('#searchText').val(),
                    user_id: $('select option:selected').val()
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#txtSearch').val('');
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var user = '';
                        if (data[i]['user'])
                            user = data[i]['user']['name'];
                        var tableType = (data[i]['notification'] != 0) ? 'table-danger' : 'table-success';
                        var deleteRow = '';
                        if (data[i]['notification'] == 1) {
                            deleteRow = "<button class='btn btn-danger ml-2' data-deleteOne='" + data[i]['id'] + "'> <span class='h3 font-weight-bold'>تعين كمقروء</span>  </button>"
                        }
                        $('#mainTable tbody').append(
                            "<tr class='" + tableType + "'>" +
                            "<td data-rowId='" + data[i]['id'] + "'>" + (i - -1) + "</td>" +
                            "<td>" + user + "</td>" +
                            "<td class='text-nowrap'>" + data[i]['created_at'] + "</td>" +
                            "<td>" +
                            "<p class='d-inline-block mb-0 pb-1' style='overflow: auto;max-height: 70px;'>" + deleteRow + data[i]['data'] + "</p>" +
                            "</td>" +
                            "<td style='display: none'>" + (data[i]['type'] - -100) + "</td>" +
                            "<td style='display: none'>" + data[i]['notification'] + "</td>" +
                            "<td style='display: none'>" + ((data[i]['relation_treasury'] == 0 || data[i]['treasury_value'] == 0) ? 0 : data[i]['relation_treasury'] + '.') + "</td>" +/*column 7*/
                            "<td style='display: none'>" + data[i]['device_id'] + "</td>" +/*column 8*/
                            "<td style='display: none'>" + (data[i]['device'] == null ? '' : data[i]['device']['name']) + "</td>" +/*column 9*/
                            "<td style='display: none'>" + ((data[i]['treasury_value'] == null||data[i]['treasury_value'] == '0') ? '' : ((data[i]['relation_treasury'] == 2 ? '<i class="fas text-warning ml-2 fa-minus"></i>' : '<i class="fas text-primary ml-2 fa-plus"></i>')) + roundTo(data[i]['treasury_value'])) + "</td>" +/*column 9*/
                            "</tr>"
                        );
                    }

                    design.hide_option_not_exist_in_table_in_select($('#activityType'),
                        $('#mainTable tbody tr'),4,true);

                    design.updateNiceScroll();
                    alertify.success('تم البحث بنجاح');
                    design.useSound('success');
                    $('.table-filters select,.table-filters input').trigger('change');
                    $('#search').removeAttr('disabled');
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
                    design.useToolTip();
                },
                error: function (e) {
                    design.useToolTip();
                    alert('error');
                    design.updateNiceScroll();
                    design.useSound('error');
                    $('#search').removeAttr('disabled');
                    console.log(e);
                }
            });
        }

        $('#search').click(function () {
            getData();
        });
        @if(!isset($activity))
        getData();
        @else
        design.useSound();
        @endif


        $('button[data-type="delete"]').click(function (e) {
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد حذف كل النشاطات  هذة العملية لن تقوم بحذف الاحداث فلا تقلق برجاء اخذ نسخة احتياطية للاحتفاظ بالنشاطات في حالة الرجوع لها؟",
                title: "حذف النشاطات",
                confirm: function (button) {
                    $('#destroyAll').submit();
                },
                cancel: function (button) {

                },
                post: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-default",
                dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
            });
        });

        $('button[data-type="deleteAll"]').click(function (e) {
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد تعين كل الإشعارات كمقروئة؟",
                title: "تعين الإشعارات كمقروئة",
                confirm: function (button) {
                    $('#deleteAll').submit();
                },
                cancel: function (button) {

                },
                post: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-default",
                dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
            });
        });
        $('#mainTable').on('click', 'tbody button[data-deleteOne]', function (e) {
            var id = $(this).attr('data-deleteOne');
            var action = $('#deleteOne').attr('action');
            action = action.replace(/[0-9]$/, id);
            $('#deleteOne').attr('action', action).submit();
        });

        $('#deleteOne,#deleteAll').submit(function (e) {
            design.useSound();
            $('#load').css('display', 'block');
            design.check_submit($(this), e);
        });

    </script>
@endsection
