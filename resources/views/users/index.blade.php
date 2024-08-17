<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 20/01/2019
 * Time: 11:20 ص
 */
?>
@extends('layouts.app')
@section('title')
    ادارة المستخدمين
@endsection
@section('content')
    <!--manage user-->
    <main dir='rtl' class='pt-4 px-md-3 pb-2'>
        <section class='animated fadeInDown ml-auto faster'>
            <div class='text-center'>
                <h1 class='font-weight-bold pb-3 mh1 text-white'>إداره المستخدمين</h1>
                <div class='container-fluid'>
                    <div class='table-responsive box-shadow '>
                        <table id="mainTable" class='sorted m-0 table table-hover table-bordered'>
                            <thead class='thead-dark'>
                            <tr class='h3'>
                                <th>م</th>
                                <th>اسم المستخدم</th>
                                <th>اسم تسجيل الدخول</th>
                                <th>الصلاحية</th>
                                <th>الحالة</th>
                                <th>تاريخ الاضافة</th>
                                <th>الادوات</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
    {{--delete User--}}
    <form class='d-inline' id='deleteUser' action='{{route('users.destroy',0)}}' method='post'>
        @method('delete')
        @csrf
    </form>
    {{--changeState User--}}
    <form class='d-inline' id='changeState' action='{{route('users.changeState',0)}}' method='post'>
        @csrf
    </form>
@endsection
@section('js')
    <script defer>
        design.useNiceScroll();
        design.useSound();

        function getData() {
            $('#mainTable tbody').html('');
            $("body").getNiceScroll().resize();
            $.ajax({
                url: '{{route('users.getData')}}',
                method: 'POST',
                data: {
                    type: 'getAllUser'
                },
                success: function (data) {
                    $('#mainTable tbody').html('');
                    for (i = 0; i < data.length; i++) {
                        var state = data[i]['state'] == 0 ? 'غير مفعل' : 'مفعل';
                        var stateClass = data[i]['state'] == 0 ? 'table-danger' : 'table-success';
                        var userType = data[i]['type'] == 1 ? 'VIP Account' : 'مستخدم عادى';
                        var stateButton=data[i]['state']==0?'تفعيل':'الغاء التفعيل';
                        var stateButtonClass=data[i]['state']==1?'btn-warning':'btn-success';
                        $('#mainTable tbody').prepend(
                            "<tr class='" + stateClass + " h4'>" +
                            "<td class='font-en' data-rowId=" + data[i]['id'] + ">" + (i - -1) + "</td>" +
                            "<td class='font-en'>" + data[i]['name'] + "</td>" +
                            "<td>" + data[i]['email'] + "</td>" +
                            "<td>" + userType + "</td>" +
                            "<td>" + state + "</td>" +
                            "<td>" + data[i]['created_at'] + "</td>" +
                            "<td class='text-nowrap'>" +
                            "<button class='btn "+stateButtonClass+"' data-type='changeState'><span class='font-weight-bold text-dark h4'>"+stateButton+" </span></button>" +
                            " <a class='btn btn-primary text-dark' href='users/" + data[i]['id'] + "/edit'><span class='h4'>تعديل </span><i class='fas fa-edit'></i></a>" +
                            " <a class='btn btn-primary text-dark' href='users/" + data[i]['id'] + "'><span class='h4'>عرض </span><i class='fas fa-eye'></i></a>" +
                            " <button class='btn btn-danger tooltips' data-type='delete'  data-placement='left'  title='لا يمكن الحذف إذا قام هذا المستخدم بالتعامل مع النظام ولكن يمكن إلغاء تفعيله أو تعديل الباسورد لمنعه من الدخول ' ><span class='font-weight-bold text-dark h4'>حذف </span><i class='fas fa-trash-alt text-dark'></i></button>" +
                            "</td>" +
                            "</tr>"
                        );
                    }
                    design.updateNiceScroll();
                    design.useToolTip();
                },
                error: function (e) {
                    console.log(data);
                }
            });
        }

        getData();

        /*delete user*/
        $('#mainTable tbody').on('click','button[data-type="delete"]',function (e) {
            var name=$(this).parent().siblings().eq(1).html();
            var id=$(this).parent().siblings().eq(0).attr('data-rowId');
            $(this).parentsUntil('tr').parent().addClass('table-warning').removeClass('table-success').siblings().removeClass('table-warning').addClass('table-success');
            design.useSound('info');
            $(this).confirm({
                text: "هل تريد حذف المستخدم " +  name ,
                title: "حذف مستخدم ؟ ",
                confirm: function(button) {
                    var action=$('#deleteUser').attr('action');
                    action=action.replace(/[0-9]+$/,id);
                    $('#deleteUser').attr('action',action).submit();
                },
                cancel: function(button) {

                },
                post: true,
                confirmButtonClass: "btn-danger",
                cancelButtonClass: "btn-default",
                dialogClass: "modal-dialog modal-lg" // Bootstrap classes for large modal
            });
        });


        /*change officer state*/
        $('#mainTable tbody').on('click','button[data-type="changeState"]',function (e) {
            var id=$(this).parent().siblings().eq(0).attr('data-rowId');
            var action=$('#changeState').attr('action');
            action=action.replace(/[0-9]$/,id);
            $('#changeState').attr('action',action).submit();
        });
    </script>
@endsection
