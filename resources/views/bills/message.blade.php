<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 22/01/2019
 * Time: 12:15 م
 */
?>
@extends('layouts.app')
@section('title','إدارة رسائل الفواتير')

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
            <h1 class='text-white text-center font-weight-bold pb-3 mh1'>إدارة رسائل الفواتير</h1>
            <div class='px-3'>
                <div class='container text-center box-shadow bg-white pb-2'>
                    <fieldset class="px-1" style="border: 3px solid #164e5b">
                        <legend class="w-auto px-2">إضافة رسالة جديدة</legend>
                        <div class='form-group row h3'>
                            <label class='col-sm-3 pt-2 text-md-left '>الرسالة</label>
                            <div class='col-sm-6'>
                                <input value=''
                                       type='text'
                                       data-filter-col="1,2"
                                       id='name'
                                       data-validate='min:5' data-patternType='min'
                                       placeholder='من فضلك أدخل الرسالة المراد إضافتها'
                                       name='name' class='form-control pr-5'>
                            </div>
                            <br>
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
                               placeholder='ابحث فى الرسائل' class='form-control'>
                    </div>
                </div>
                <div class='box-shadow table-responsive table-success text-center'>
                    <table id="mainTable" class='m-0 sorted table table-hover table-bordered'>
                        <thead class='thead-dark h3'>
                        <tr>
                            <th>م</th>
                            <th>الرسالة</th>
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
                    <form id='editSaves' action='{{route('bill_messages.update',0)}}' method='post'>
                        @csrf
                        @method('put')
                        <fieldset class="px-1" style="border: 3px solid #164e5b">
                            <legend class="w-auto px-2">تعديل رسالة الفواتير
                                <span id='oldName'></span>
                            </legend>
                            <div class='form-group row h3'>
                                <label class='col-sm-2 pt-2 text-md-left '> الرسالة الجديده</label>
                                <div class='col-sm-5'>
                                    <input value='' required
                                           type='text'
                                           id='newName'
                                           data-validate='min:5' data-patternType='min'
                                           placeholder='من فضلك أدخل الرسالة الجديده'
                                           name='name' class='form-control pr-5'>
                                </div>
                                <div class='col-sm-5 mt-2 mt-md-0'>
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
        <form class='d-inline' id='deleteSaves' action='{{route('bill_messages.destroy',0)}}' method='post'>
            @method('delete')
            @csrf
        </form>
        {{--changeState saves--}}
        <form class='d-inline' id='changeStateSaves' action='{{route('bill_messages.changeState',0)}}' method='post'>
            @csrf
        </form>

        {{--set default message--}}
        <form class='d-inline' id='setDefaultMessage' action='{{route('bill_messages.setDefault',0)}}' method='post'>
            <input type="hidden" id="input_default_messsage_type" name="type" value="0">
{{--            type 0 for deault bill buy,1 for default message bill sale--}}
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
                alertify.error("من فضلك أدخل الرسالة المراد إضافتها");
                design.useSound('error');
                return;
            }
            if (name.length < '5') {
                alertify.error("أقل عدد من الحروف هو 5");
                design.useSound('error');
                return;
            }
            $.ajax({
                url: '{{route('bill_messages.store')}}',
                method: 'POST',
                data: {
                    name: name,
                },
                success: function (data) {
                    getData();
                },
                error: function (e) {
                    alertify.error("من فضلك تحقق من الرسالة قد يكون هناك رسالة بنفس المحتوى");
                    console.log(e);
                    design.useSound('error');
                    return;
                }
            });
        }

        $('#add').click(function () {
            addSaves();
        });
        $('#name').on('keypress',function(e) {
            if(e.which == 13) {
                addSaves();
            }
        });
        var idDefaulMessageBuy='{{$setting->bill_message_buy_id}}';
        var idDefaulMessageSale='{{$setting->bill_message_sale_id}}';
        function getData() {
            $('#name').val('');
            $.ajax({
                url: '{{route('bill_messages.getDate')}}',
                method: 'POST',
                success: function (data) {
                    $('section table tbody,#number').html('');
                    for (i = 0; i < data.length; i++) {
                        var state = data[i]['state'] == 0 ? 'غير مفعل' : 'مفعل';
                        var stateButton = data[i]['state'] == 0 ? 'تفعيل' : 'الغاء التفعيل';
                        var stateButtonClass = data[i]['state'] == 1 ? 'btn-danger' : 'btn-primary';
                        var stateClass = data[i]['state'] == 0 ? 'table-danger' : 'table-success';

                        stateClass=(data[i]['id']==idDefaulMessageBuy)||(data[i]['id']==idDefaulMessageSale)?'table-info':stateClass;

                        var defaultBuy="<button data-set_default='0' class='btn px-0 bg-transparent tooltips mx-2' type='button' data-placement='left' title='تعين كرسالة إفتراضية عند الشراء'><i class='fas text-info fa-magic'></i></button>";
                        if(data[i]['id']==idDefaulMessageBuy){
                            state+='-إفتراضى شراء ';
                            defaultBuy='';
                        }
                        var defaultSale="<button data-set_default='1' class='btn px-0 bg-transparent tooltips mx-2' type='button' data-placement='left' title='تعين كرسالة إفتراضية عند البيع'><i class='fas text-info fa-magic'></i></button>";
                        if(data[i]['id']==idDefaulMessageSale){
                            state+='-إفتراضى بيع ';
                            defaultSale='';
                        }

                        $('section table tbody').prepend(
                            "<tr class='" + stateClass + "'>" +
                            "<td class='font-en' data-id=" + data[i]['id'] + ">" + (i - -1) + "</td>" +
                            "<td class='font-en'>" + data[i]['name'] + "</td>" +
                            "<td>" + state + "</td>" +
                            "<td class='text-nowrap'>" +
                            defaultBuy+
                            defaultSale+
                            " <button class='btn " + stateButtonClass + "' data-type='changeState'><span class='font-weight-bold text-dark'>" + stateButton + " </span></button>" +
                            " <button class='btn btn-primary' data-type='edit'><span class='font-weight-bold text-dark'>تعديل </span><i class='fas fa-edit text-dark'></i></button>" +
                            " <button class='btn btn-danger' data-type='delete'><span class='font-weight-bold text-dark'>حذف </span><i class='fas fa-trash-alt text-dark'></i></button>" +
                            "</td>" +
                            "</tr>"
                        );
                    }
                    $('#number').html('( ' + data.length + ' )');
                    design.updateNiceScroll();
                    design.useSound();
                    design.useToolTip();
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
                text: "هل تريد حذف الرسالة المحدده " + name,
                title: "حذف رسالة ؟ ",
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
            $('#load').css('display', 'block');
            var id = $(this).parent().siblings().eq(0).attr('data-id');
            var action = $('#changeStateSaves').attr('action');
            action = action.replace(/[0-9]$/, id);
            $('#changeStateSaves').attr('action', action).submit();
        });

        /*change default message in bill buy and sale*/
        $('table').on('click', 'tbody button[data-set_default]', function (e) {
            $('#load').css('display', 'block');
            var id = $(this).parent().siblings().eq(0).attr('data-id');
            $('#input_default_messsage_type').val($(this).attr('data-set_default'));
            var action = $('#setDefaultMessage').attr('action');
            action = action.replace(/[0-9]$/, id);
            $('#setDefaultMessage').attr('action', action).submit();
        });

        $('#editSaves').submit(function (e) {
            $('#load').css('display', 'block');
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
            design.useSound();
        });
    </script>
@endsection
