<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 22/01/2019
 * Time: 12:15 م
 */
?>
@extends('layouts.app')
@section('title','إدارة وحدات المنتجات')

@section('css')
    <style>
        span, input, button {
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
            <h1 class='text-white text-center font-weight-bold pb-3 mh1'>إدارة وحدات المنتجات</h1>
            <div class='px-3'>
                <div class='container text-center box-shadow bg-white pb-2'>
                    <fieldset class="px-1" style="border: 3px solid #164e5b">
                        <legend class="w-auto px-2">إضافة وحدة جديد</legend>
                        <div class='form-group row h3'>
                            <label class='col-sm-2 pt-2 text-md-left '>إســم الوحدة</label>
                            <div class='col-sm-4'>
                                <input value=''
                                       type='text'
                                       data-filter-col="1,2"
                                       id='name'
                                       data-validate='min:5' data-patternType='min'
                                       placeholder='من فضلك أدخل إسم الوحدة المراد إضافتها'
                                       name='name' class='form-control pr-5'>
                            </div>
                            <div class='col-sm-3'>
                                <input value='0'
                                       type='number'
                                       required
                                       min="0"
                                       data-placement='top' title='القيمة الإفتراضية لاقل عدد من المنتج عند إضافة منتج بهذة الوحدة'
                                       style="height: 51px"
                                       id='inputDefaultValue'
                                       placeholder='القيمة الإفتراضية عند إضافة منتج بهذة الوحدة'
                                       name='default_value' class='form-control py-0 pr-5 tooltips'>
                            </div>
                            <div class='col-sm-3 mt-2 mt-md-0'>
                                <button type='button'
                                        id='add'
                                        class='font-weight-bold form-control btn-lg  btn btn-success animated bounceInRight fast'
                                        style='padding-bottom: 42px!important;'>
                                    <span>اضافة</span>
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class='container text-center'>
                <div class='mt-2'>
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class='input-group-text font-weight-bold'>بحث</span>
                        </div>
                        <input type='text' id="search"
                               data-filter-col="1,2"
                               placeholder='ابحث عن وحدة' class='form-control'>
                    </div>
                </div>
                <div class='box-shadow table-responsive table-success text-center'>
                    <table id="mainTable" class='m-0 sorted table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م</th>
                            <th>اسم الوحدة</th>
                            <th class="small tooltips" data-placement='left' title='القيمة الإفتراضية لأقل كمية عند إضافة منتج بهذة الوحدة'>القيمة الإفتراضية لأقل كمية</th>
                            <th>الحالة</th>
                            <th>العمليات
                                <span id='number' class='font-en text-nowrap'></span>
                            </th>
                        </tr>
                        </thead>
                        <tbody class="h4">

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <section class='edit d-none'>
            <div class='w-100 h-100 position-fixed' style='left:0;top: 0;z-index: 2;background: rgba(0, 0, 0, 0.7);'>
                <div id='formEdit' class='container text-center box-shadow  bg-white pb-2'>
                    <form id='editSaves' action='{{route('products_units.update',0)}}' method='post'>
                        @csrf
                        @method('put')
                        <fieldset class="px-1" style="border: 3px solid #164e5b">
                            <legend class="w-auto px-2">تعديل وحدة
                                <span id='oldName'></span>
                            </legend>
                            <div class='form-group row h3'>
                                <label class='col-sm-2 pt-2 text-md-left '> إسم الوحدة الجديد</label>
                                <div class='col-sm-4'>
                                    <input value='' required
                                           type='text'
                                           id='newName'
                                           data-validate='min:5' data-patternType='min'
                                           placeholder='من فضلك أدخل إسم الوحدة الجديد'
                                           name='name' class='form-control pr-5'>
                                </div>
                                <div class='col-sm-2 px-0'>
                                    <input value=''
                                           type='number' min="0"
                                           required
                                           id='newDefaultValue'
                                           style="height: 51px"
                                           placeholder='القيمة الإفتراضية الجديدة'
                                           name='default_value' class='form-control  py-0 pr-5'>
                                </div>
                                <div class='col-sm-4 mt-2 mt-md-0'>
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
        {{--delete saves--}}
        <form class='d-inline' id='deleteSaves' action='{{route('products_units.destroy',0)}}' method='post'>
            @method('delete')
            @csrf
        </form>
        {{--changeState saves--}}
        <form class='d-inline' id='changeStateSaves' action='{{route('products_units.changeState',0)}}' method='post'>
            @csrf
        </form>
    </main>
@endsection

@section('js')
    <script defer>
        $('#search').val('');
        $('#name').val('');
        validateByAttr();
        $('#formEdit').animate({
            marginTop: 200
        }, 300);
        $('#mainTable').filtable({controlPanel: $('.table-filters')});
        design.useNiceScroll();

        function addSaves() {
            var name = $('#name').val();
            if (name.trim() == '') {
                alertify.error("من فضلك إدخل إسم الوحدة المراد إضافتها");
                design.useSound('error');
                return;
            }
            if (name.length < '5') {
                alertify.error("أقل عدد من الحروف هو 5");
                design.useSound('error');
                return;
            }
            var defaultValue=$('#inputDefaultValue').val();
            if (defaultValue == '') {
                alertify.error("من فضلك أدخل القيمة الإفتراضية لاقل عدد من المنتج عند إضافة منتج بهذة الوحدة");
                design.useSound('error');
                return;
            }
            $.ajax({
                url: '{{route('products_units.store')}}',
                method: 'POST',
                data: {
                    name: name,
                    default_value:defaultValue
                },
                success: function (data) {
                    getData();
                },
                error: function (e) {
                    alertify.error("من فضلك تحقق من إسم الوحدة قد يكون هناك وحده بنفس الإسم");
                    console.log(e);
                    design.useSound('error');
                    return;
                }
            });
        }

        $('#add').click(function () {
            addSaves();
        });
        $('#name,#inputDefaultValue').on('keypress',function(e) {
            if(e.which == 13) {
                addSaves();
            }
        });

        function getData() {
            $('#name').val('');
            $.ajax({
                url: '{{route('products_units.getDate')}}',
                method: 'POST',
                success: function (data) {
                    $('section table tbody,#number').html('');
                    for (i = 0; i < data.length; i++) {
                        var state = data[i]['state'] == 0 ? 'غير مفعل' : 'مفعل';
                        var stateButton = data[i]['state'] == 0 ? 'تفعيل' : 'الغاء التفعيل';
                        var stateButtonClass = data[i]['state'] == 1 ? 'btn-danger' : 'btn-primary';
                        var stateClass = data[i]['state'] == 0 ? 'table-danger' : 'table-success';
                        $('section table tbody').prepend(
                            "<tr class='" + stateClass + "'>" +
                            "<td data-id=" + data[i]['id'] + ">" + (i - -1) + "</td>" +
                            "<td>" + data[i]['name'] + "</td>" +
                            "<td>" + data[i]['default_value_for_min_qte'] + "</td>" +
                            "<td>" + state + "</td>" +
                            "<td class='text-nowrap'>" +
                            "<button class='btn " + stateButtonClass + "' data-type='changeState'><span class='font-weight-bold text-dark'>" + stateButton + " </span></button>" +
                            " <button class='btn btn-primary' data-type='edit'><span class='font-weight-bold text-dark'>تعديل </span><i class='fas fa-edit text-dark'></i></button>" +
                            " <button class='btn btn-danger' data-type='delete'><span class='font-weight-bold text-dark'>حذف </span><i class='fas fa-trash-alt text-dark'></i></button>" +
                            "</td>" +
                            "</tr>"
                        );
                    }
                    $('#number').html('( ' + data.length + ' )');
                    design.updateNiceScroll();
                    design.useSound();
                },
                error: function (e) {
                    console.log(e);
                    design.updateNiceScroll();
                    design.useSound('error');
                }
            });
        }

        getData();

        /*delete saves*/
        $('table').on('click', 'tbody button[data-type="delete"]', function (e) {
            var name = $(this).parent().siblings().eq(1).html();
            var id = $(this).parent().siblings().eq(0).attr('data-id');
            $(this).parent().parent().addClass('table-danger').removeClass('table-success').siblings().addClass('table-success').removeClass('table-danger');
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد حذف الوحدة المحددة " + name,
                title: "حذف وحدة منتجات ؟ ",
                confirm: function (button) {
                    var action = $('#deleteSaves').attr('action');
                    action = action.replace(/[0-9]$/, id);
                    $('#deleteSaves').attr('action', action).submit();
                },
                cancel: function (button) {

                },
                post: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-default",
                dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
            });
        });


        /*change saves state*/
        $('table').on('click', 'tbody button[data-type="changeState"]', function (e) {
            var id = $(this).parent().siblings().eq(0).attr('data-id');
            var action = $('#changeStateSaves').attr('action');
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
            $('#newDefaultValue').val($(this).parent().siblings().eq(2).text());
            var action = $('#editSaves').attr('action');
            action = action.replace(/[0-9]$/, id);
            $('#editSaves').attr('action', action);
        });

        $('#cancelEdit').click(function () {
            $('section.edit').addClass('d-none');
            design.useSound();
        });
    </script>
@endsection
