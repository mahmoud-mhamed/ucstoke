<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 14/03/2019
 * Time: 02:56 م
 */ ?>

@extends('layouts.app')
@section('title')
    ادارة الحضور
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
    <main dir="rtl">
        <div class='container-fluid animated fadeInDown  faster'>
            <div class='text-center'>
                <h1 class='text-white font-weight-bold pb-3'>اداره الحضور</h1>
                <div class=''>
                    <div class='input-group-prepend text-center tooltips' data-placement="bottom" title="يوم الحضور">
                        <span class='input-group-text font-weight-bold'></span>
                        <input type='text' id="input_date" class='font-weight-bold text-center form-control' style="height: 53px">
                        <button class="btn btn-primary" id="button_get_data"><span class="h2">بحث لليوم المحدد</span></button>
                    </div>
                    <div class='table-filters'>
                       <div class="input-group">
                           <div class="input-group-append">
                               <span class='input-group-text font-weight-bold'>الوظيفة</span>
                           </div>
                           <div class="input-group-append">
                               <select id="activityType" class="selectpicker" data-live-search="true"
                                       data-filter-col="2">
                                   <option value=''>الكل</option>
                                   @foreach ($jops as $j)
                                       <option value="{{$j->name}}">{{$j->name}}</option>
                                   @endforeach
                               </select>
                           </div>
                           <div class="input-group-append">
                               <input type='text'
                                      data-filter-col="0,1,2,3,4,5"
                                      style="height: 53px" placeholder='بحث بأى بيانات' class='form-control'>
                           </div>
                       </div>
                    </div>
                    <div class='table-responsive'>
                        <table id="mainTable" class='m-0 sorted table table-hover table-bordered'>
                            <thead class='thead-dark h3'>
                            <tr>
                                <th>م</th>
                                <th>اسم الموظف</th>
                                <th>الوظيفة</th>
                                <th>الحساب الحالى</th>
                                <th>المرتب لليوم</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                            <tbody class="h4">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('js')
    <script defer>
        design.dateRang('#input_date', 'dateAttend');
        design.updateNiceScroll();

        $('#mainTable').filtable({controlPanel: $('.table-filters')});

        $('#mainTable').on('aftertablefilter', function (event) {
            getRowCounterAndTotalAccount();
        });
        function getRowCounterAndTotalAccount() {
            var counterRow = 0;
            $('#mainTable tbody tr').each(function () {
                if ($(this).hasClass('hidden') == false) {
                    counterRow -= -1;
                   $(this).children().eq(0).html(counterRow);
                }
            });
            design.useToolTip(); //to update tooltip after filter
        }

        function getAttend() {
            $('#button_get_data').attr('disabled','disabled');
            var date = $('#input_date').val();
            $('#mainTable tbody').html('');
            $.ajax({
                url: '{{route('emps.getData')}}',
                method: 'POST',
                data: {
                    type: 'getAttendForDay',
                    date: date
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var stateAttend = data[i]['emp_move'].length > 0 ? (data[i]['emp_move'][0]['type'] == 5 ? true : false) : false;
                        $('#mainTable tbody').append(
                            "<tr class='" + (stateAttend ? 'table-success' : 'table-danger') + "' data-id='" + data[i]['id'] + "'>" +
                            "<td></td>" +
                            "<td>" + data[i]['name'] + "</td>" +
                            "<td>" + data[i]['emp_jop']['name'] + "</td>" +
                            "<td>" + roundTo(data[i]['account']) + 'ج' + "</td>" +
                            "<td>" + (stateAttend?data[i]['emp_move'][0]['value']:data[i]['day_salary']) + ' جنية ' + "</td>" +
                            "<td class='tooltips' data-placement='left' title='"+(!stateAttend?'غياب':'حضور')+"'>" + (stateAttend ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>') + "</td>" +
                            "<td class='text-nowrap'>" +
                            "<button class='btn px-0 bg-transparent tooltips'" +
                            "title='" + (stateAttend ? 'تسجيل غياب' : 'تسجيل حضور') + "'" +
                            "data-placement='right'" +
                            "onclick='changeEmpAttend("+data[i]['id']+","+(stateAttend?1:0)+")'"+
                            "data-type='changeState'><span" +
                            "class='font-weight-bold text-dark h4'>" + (stateAttend ? '<i class=\"fas fa-2x text-danger fa-times\"></i>' : '<i class=\"fa-2x text-success fas fa-check\"></i>') + " </span>" +
                            "</button>" +
                            "</td>" +
                            "</tr>"
                        );
                    }
                    design.updateNiceScroll();
                    design.useSound();
                    design.useToolTip();
                    $('#button_get_data').removeAttr('disabled');
                    $('#mainTable').filtable({controlPanel: $('.table-filters')});
                },
                error: function (e) {
                    alert('error');
                    $('#button_get_data').removeAttr('disabled');
                    console.log(e);
                }
            });
        }

        getAttend();
        $('#input_date').keyup(function () {
            $('#mainTable tbody').html('');
            getRowCounterAndTotalAccount();
            alertify.error('برجاء الضغط على زر بحث لليوم المحدد للبحث');
            design.useSound('info');
        });
        $('div.dateAttend').click(function () {
            $('#mainTable tbody').html('');
            getRowCounterAndTotalAccount();
            alertify.error('برجاء الضغط على زر بحث لليوم المحدد للبحث');
            design.useSound('info');
        });

        $('#button_get_data').click(function () {
            getAttend();
        });


        function changeEmpAttend(emp_id,state_now) {
            $('div.tooltip-inner').parent().hide();

            $('#mainTable button').attr('disabled','disabled');
            var date = $('#input_date').val();
            $.ajax({
                url: '{{route('emps.change_emp_attend')}}',
                method: 'POST',
                data: {
                    emp_id: emp_id,
                    date: date,
                    state_now:state_now
                },
                success: function (data) {
                    if (data == 'success') {
                        Swal.fire(
                            'تمت العملية بنجاح',
                            '',
                            'success'
                        );
                        getAttend();
                    } else if(data=='error'){
                        Swal.fire(
                            'غير مصرح لك بالتعامل مع هذا الموظف',
                            '',
                            'error'
                        );
                        design.useSound('error');
                    }else{
                        alertify.log(data, 'error', 0);
                        design.useSound('error');
                    }
                    $('#mainTable button').removeAttr('disabled');

                },
                error: function (e) {
                    $('#mainTable button').removeAttr('disabled');
                    alert('error');
                    console.log(e);
                }
            });
        }

    </script>
@stop
