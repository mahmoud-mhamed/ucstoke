<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 28/01/2019
 * Time: 10:46 ص
 */ ?>
@extends('layouts.app')
@section('title')
    ضبط النسخ الاحتياطي
@endsection
@section('css')
    <style>
       #continerPass *:not(h1) {
            font-size: 1.2rem !important;
        }
        input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
        }
        #continerDevices table tbody *{
            font-size: 1.2rem!important;
        }
        input[type='number']{
            min-width: 100px!important;
            display: inline-block;
        }

    </style>
@stop
@section('content')
    <main dir='rtl' class='pt-4 px-2 pb-2'>
        <section class='animated fadeInDown ml-auto faster container-fluid'>
            <div class='text-center'>
                <h1 class='text-white font-weight-bold pb-3'>ضبط النسخ الاحتياطي</h1>
                <div class='container-fluid box p-2 font-weight-bold'>
                    <section class='data'>
                        <form action='{{route('backups.store')}}' id="formStore" method='post'>
                            @csrf
                            <div id="continerPass">
                                <h1 id="h1_continerPass" class="{{count($backups)==0?'d-none':''}}">مسارات النسخ الإحتياطى على السيرفر</h1>
                                @foreach ($backups as $b)
                                    <div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-append">
                                                <span class="input-group-text">انشاء نسخة احتياطيه كل </span>
                                            </div>
                                            <input type="number" name='createBackUpEvery[]' required
                                                   value='{{$b->createBackUpEvery}}' disabled
                                                   style="height: 54px"
                                                   min='1' class="form-control form-control-lg" placeholder="عدد الايام">
                                            <div class="input-group-append overflow">
                                                <span class="input-group-text">يوم</span>
                                            </div>
                                            <div class="input-group-append overflow tooltips" data-placement="left" title="بارتشن النسخة الإحتياطية">
                                                <span class="input-group-text"> مسار النسخ </span>
                                                {{--<input type="text" name='pass[]' required dir="ltr"
                                                       style="height: 54px"
                                                       value='{{$b->pass}}' disabled
                                                       class="form-control form-control-lg" placeholder="مسار النسخ">--}}
                                                <select name="pass[]" disabled class="custom-select" style="height: 54px">
                                                    <option {{$b->pass=='c:'?'selected':''}} value="c:">C</option>
                                                    <option {{$b->pass=='d:'?'selected':''}} value="d:">D</option>
                                                    <option {{$b->pass=='e:'?'selected':''}} value="e:">E</option>
                                                    <option {{$b->pass=='f:'?'selected':''}} value="f:">F</option>
                                                    <option {{$b->pass=='g:'?'selected':''}} value="g:">G</option>
                                                    <option {{$b->pass=='h:'?'selected':''}} value="h:">H</option>
                                                    <option {{$b->pass=='i:'?'selected':''}} value="i:">I</option>
                                                </select>
                                            </div>
                                            <div class="input-group-append overflow">
                                            <span class="input-group-text"> <area class="d-none d-md-inline-block">ميعاد انشاء النسخة القادمة</area> <label
                                                    data-dayCreate='dateCreate'
                                                    class='font-en px-2 tooltips' data-placement="left" title="ميعاد النسخة القادمة">{{$b['dayCreate']}}</label> </span>
                                            </div>
                                            <div class="input-group-append overflow d-none">
                                                <span class="input-group-text"> نوع النسخة </span>
                                                <select name="type[]" class="form-control form-control-lg custom-select custom-select-lg"
                                                        style="height: 54px"
                                                        disabled >
{{--                                                    <option {{$b['type']==2?'selected':''}} value="2">للملفات وقاعدة البيانات</option>--}}
{{--                                                    <option {{$b['type']==1?'selected':''}} value="1">للملفات فقط</option>--}}
                                                    <option {{$b['type']==0?'selected':''}} value="0">قاعدة البيانات</option>
                                                </select>
                                            </div>
                                            <button class="btn btn-danger d-none" type="button" data-remove="remove">
                                                حذف
                                            </button>
                                            <a class="btn btn-primary" data-createBackup href="backups/createBackup/all/{{$b->id}}">
                                                إنشاء نسخة
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                                <div data-tempDivClone="">
                                    <div class="input-group mb-3">
                                        <div class="input-group-append">
                                            <span class="input-group-text">انشاء نسخة احتياطيه كل </span>
                                        </div>
                                        <input type="number" name='createBackUpEvery[]' required
                                               value='1'
                                               style="height: 54px"
                                               min='1' class="form-control form-control-lg" placeholder="عدد الايام">
                                        <div class="input-group-append overflow">
                                            <span class="input-group-text">يوم</span>
                                        </div>
                                        <div class="input-group-append overflow">
                                            <span class="input-group-text"> مسار النسخ </span>
                                            {{--<input type="text" name='pass[]' required dir="ltr"
                                                   value=''
                                                   style="height: 54px"
                                                   class="form-control form-control-lg" placeholder="مسار النسخ">--}}
                                            <select name="pass[]" class="custom-select  tooltips" data-placement="left" title="بارتشن النسخة الإحتياطية" style="height: 54px">
                                                <option value="c:">C</option>
                                                <option value="d:">D</option>
                                                <option value="e:">E</option>
                                                <option value="f:">F</option>
                                                <option value="g:">G</option>
                                                <option value="h:">H</option>
                                                <option value="i:">I</option>
                                            </select>
                                        </div>
                                        <div class="input-group-append overflow">
                                        <span class="input-group-text"> <area class="d-none d-md-inline-block">ميعاد انشاء النسخة القادمة</area>
                                            <label data-dayCreate='dateCreate'
                                                class='font-en px-2 tooltips' data-placement="left" title="ميعاد النسخة القادمة"></label> </span>
                                        </div>
                                        <div class="input-group-append overflow d-none">
                                            <span class="input-group-text"> نوع النسخة </span>
                                            <select name="type[]" class="form-control form-control-lg custom-select custom-select-lg"
                                                    style="height: 54px">
{{--                                                <option value="2">للملفات وقاعدة البيانات</option>--}}
{{--                                                <option value="1">للملفات فقط</option>--}}
                                                <option value="0">قاعدة البيانات</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-danger" type="button" data-remove="remove">حذف</button>
                                    </div>
                                </div>
                            </div>
                            <div id="continerDevices">
                                <h1>التحميل التلقائى لنسخة من قاعدة البيانات</h1>
                                <div class='text-center'>
                                    <div class='box-shadow table-responsive table-success text-center'>
                                        <table id="mainTable" class='m-0 sorted table table-hover table-bordered'>
                                            <thead class='thead-dark h3'>
                                            <tr>
                                                <th>اسم الجهاز</th>
                                                <th>تحميل نسخة إحتياطية إتوماتيك كل</th>
                                            </tr>
                                            </thead>
                                            <tbody class="h4">
                                            @foreach ($devices as $d)
                                                <tr class="table-success">
                                                    <td>{{$d->name}}
                                                        <input type="checkbox" data-placement="left" title="تحميل نسخة إحتياطية أتوماتيك على هذا الجهاز" disabled class="mr-3 tooltips" {{$d->state_download_backup?'checked':''}}  value="true">
                                                        <input type="hidden" name="device_id[]"  class="mr-3" value="{{$d->id}}">
                                                    </td>
                                                    <td>
                                                        <div class="input-group m-auto {{$d->state_download_backup?'':'d-none'}}" style="max-width: 700px">
                                                            <input type="number" name='downloadBackUpEvery[]' required
                                                                   value='{{$d->state_download_backup?$d->download_backup_every:'0'}}' disabled
                                                                   style="height: 44px"
                                                                   min='0' class="form-control form-control-lg" placeholder="عدد الايام">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">يوم</span>
                                                                <span class="input-group-text d-none d-md-inline-block">ميعاد النسخة القادمة هو</span>
                                                                <span data-dayCreate class="input-group-text tooltips" data-placement="left" title="ميعاد النسخة القادمة">{{$d->state_download_backup?$d->day_download:''}}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <button id='addPass' class='btn btn-success px-3 mt-2' type='button' disabled><span
                                    class="h3">إضافة مسار</span></button>
                            <button type='submit' class='btn btn-success px-3 mt-2' disabled><span class="h3">حفظ</span>
                            </button>
                            <button id='edit' type='button' class='btn btn-primary px-3 mt-2 text-right'><span
                                    class='h3'>تعديل</span></button>
                        </form>
                    </section>
                    <div class=' mt-2 '>
                        <form enctype='multipart/form-data' action='{{route('backups.restore')}}'
                              class='d-inline-block' method='post'>
                            @csrf
                            <div class="btn-group" role="group">
                                <input type='submit' id="restoreDataBase" name='restoreDataBase' disabled
                                       class='btn text-white font-weight-bold btn-primary' value='استعادة نسخة محفوظة'/>
                                <button type='button'
                                        class='btn px-4 border-radius position-relative text-white font-weight-bold btn-primary border-right'
                                        style='z-index: 1'
                                        onclick='$(this).next().trigger("click")'>حدد المسار
                                </button>
                                <input type='file' name='restoreDb'
                                       style='right: -103px;top: 5px;border: none!important;' required
                                       class='position-relative' accept='.sql'>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@section('js')
    <script>
        design.useSound();
        alertify.log('ملاحظة : التحميل التلقائى لنسخة من قاعدة البيانات يعتمد على صلاحية وصول المستخدم لهذه الخاصية','success',0);

        /*add pass*/
        var addPath = $('div[data-tempDivClone]').clone();
        $('div[data-tempDivClone]').remove();
        $('#addPass').click(function () {
            $('#h1_continerPass').removeClass('d-none');
            $('#continerPass').append(
                addPath.clone()
            );
            getDayCreate();
        });
        $('main section.data').on('click', 'button[data-remove]', function () {
            $(this).parent().parent().remove();
        });

        /*edit data*/
        $('#edit').click(function () {
            design.useSound();

            $('section.data button,section.data input,#continerDevices input').removeAttr('disabled');
            $('button[data-remove]').removeClass('d-none');
            $('select').removeAttr('disabled');
            $('a[data-createBackup]').remove();
            $('#continerDevices input:checkbox').each(function () {
                if($(this).prop('checked')){
                    $(this).parent().next().children().removeClass('d-none');
                }else{
                    $(this).parent().next().children().addClass('d-none');
                }
            });

            getDayCreate();
        });

        /*get day create*/
        function getDayCreate() {
            $('#continerPass>div').each(function () {
                var number = $(this).find('input[name="createBackUpEvery[]"]').val()*1;
                var date = new Date();
                date.setDate(date.getDate() + number);
                $(this).find('label[data-dayCreate]').html(date.getFullYear()+'-'+(date.getMonth()- -1)+'-'+date.getDate());
            });
        }
        function getDayCreateForDownload(){
            $('#continerDevices table tbody tr').each(function () {
                var number = $(this).find('input[name="downloadBackUpEvery[]"]').val()*1;
                var date = new Date();
                date.setDate(date.getDate() + number);
                $(this).find('span[data-dayCreate]').html(date.getFullYear()+'-'+(date.getMonth()- -1)+'-'+date.getDate());
            });
        }

        $('form').on('keyup change', 'input[name="createBackUpEvery[]"]', function () {
            getDayCreate();
        });

        $('#continerDevices').on('keyup change','input',function () {
            getDayCreateForDownload()
        });

        //toggle d-none when click in check box
        $('#continerDevices input:checkbox').change(function () {
            design.useSound();
            if($(this).prop('checked')){
                $(this).parent().next().children().removeClass('d-none');
            }else{
                $(this).parent().next().children().addClass('d-none');
                $(this).parent().next().find('input').val('0');
            }
        });

        /*restore Backup*/
        $('input[type="file"]').change(function () {
            $('input[type="submit"]').addClass('btn-success').removeAttr('disabled');
        });

        $('#formStore').submit(function (e) {
            design.useSound();
            $('#load').css('display', 'block');
            design.check_submit($(this),e);
        });

        $('#restoreDataBase').click(function (e) {
            e.preventDefault();
            design.useSound();
            $(this).confirm({
                text: "هل تريد استعادة النسخة المحددة هذة العميلة ستودي لحذف كل البيانات بعد وقت هذة العملية برجاء انشاء نسخة احتياطية قبل الحذف وعدم التعامل مع البرنامج حتي تنتهي عملية الاستعادة هذه العملية قد تستغرق بعض الوقت؟",
                title: "استعادة نسخة محفوظة",
                confirm: function (button) {
                    $('#load').css('display', 'block');
                    $('form').submit();
                },
                cancel: function (button) {

                },
                post: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-default",
                dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
            });
        });
    </script>

@endsection
