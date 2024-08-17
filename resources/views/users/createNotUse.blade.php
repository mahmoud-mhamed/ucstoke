<?php
/**
 * Created by PhpStorm.
 * User: mahmoud mohamed
 * Date: 20/01/2019
 * Time: 12:11 م
 */ ?>
@extends('layouts.app')
@section('title')
    اضافة مستخدم
@endsection
@section('css')
    <style>
        input[type="checkbox"] {
            transform: scale(2);
            margin: 10px 10px 0 10px;
            cursor: pointer;
        }

        label {
            font-size: 1.5rem;
        }

        button, a {
            height: 47px !important;
        }
    </style>
    <style>
        input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
        }

        .data-container label {
            cursor: pointer;
            margin: 10px 20px;
        }

        #normalAccountSetting div > h1 i, #normalAccountSetting div > h1 {
            transition: all ease-in-out 0.3s;
            color: black;;
        }

        div.show > h1 i {
            transform: rotate(-90deg);
            color: green!important;
        }

        div.show > h1 {
            color: green!important;
        }

        #contentAllData > div > h1 {
            cursor: pointer;
        }

        #normalAccountSetting div.box-shadow{
            box-shadow: 0 0 3px 0 black;
        }
    </style>
@stop
@section('content')
    <!--add new user-->
    <main dir='rtl' class='pt-4 px-3 pb-2'>
        <section class='box animated fadeInDown faster container-fluid text-right mb-2'>
            <div class='container-fluid  pt-3 pb-3'>
                <h1 class='text-center mb-4'>اضافة مستخدم جديد</h1>
                <form action='{{route('users.store')}}' method='post'>
                @csrf
                <!--fullName-->
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2 text-md-left'>اســــم المسـتـخـدم</label>
                        <div class='col-sm-9'>
                            <input data-validate='min:3' data-patternType='min' type='text' required name='name'
                                   class='form-control'>
                        </div>
                    </div>
                    <!--userName-->
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2  text-md-left'>اسم تسجيل الدخول</label>
                        <div class='col-sm-9'>
                            <input data-validate='min:3' data-patternType='min' type='text' required name='email'
                                   class='form-control'>
                        </div>
                    </div>
                    <!--password-->
                    <div class='form-group row'>
                        <label class='col-sm-3 text-md-left pt-2'>كلمـــــــة المـــــــرور</label>
                        <div class='col-sm-9'>
                            <div class='input-group-prepend password'>
                                <input data-validate='min:3' data-patternType='min' required name='password'
                                       data-type='password' type='password' autocomplete='off' value=''
                                       class='form-control'>
                                <span class='input-group-text pointer'><i class='fa fa-eye '></i></span>
                            </div>
                        </div>
                    </div>
                    <!--select-->
                    <div class='form-group row'>
                        <label class='col-sm-3 pt-2  text-md-left'>نــــوع المـسـتـخـدم</label>
                        <div class='col-sm-9'>
                            <select name='type' id="typeUser" class='form-control-lg custom-select-lg pr-5 custom-select'>
                                <option value='2'>مستخدم عادي</option>
                                <option value='1'>مدير النظام</option>
                            </select>
                        </div>
                    </div>
                    <div id="normalAccountSetting">
                        <div class='' id="contentAllData">
                            <div class="show">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>المستخدمين
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                            تسجيل الخروج عند محاولة فتح صفحة غير مصرح بفتحها
                                            <input type="checkbox"  name="log_out_security"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بتعديل حسابك
                                            <input type="checkbox" data-parent="edit_my_account" name="allow_edit_my_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عند التعديل السماح بتغير إسم المستخدم
                                            <input type="checkbox" data-child="edit_my_account" name="allow_edit_my_account_name"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عند التعديل السماح بتغير إسم تسجيل الدخول
                                            <input type="checkbox" data-child="edit_my_account" name="allow_edit_account_email"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بتغير الخلفية
                                            <input type="checkbox" checked name="allow_change_background"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدارة النشاطات
                                            <input type="checkbox" checked name="allow_manage_activities"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>
                                    الموردين والعملاء والموردين العملاء
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline tooltips" dir="ltr"  data-placement='top'  title='السماح بإضافة مورد أو عميل أو مورد عميل '>
                                            السماح بإضافة شخص
                                            <input type="checkbox" checked data-parent="add_account" name="allow_add_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار عند إضافة شخص بحساب سابق
                                            <input type="checkbox" data-child="add_account" name="create_notification_when_add_account_with_old_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بالدخول إلى صفحة إدارة الموردين والعملاء
                                            <input type="checkbox"  data-parent="index-account"  name="allow_access_index_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بتعديل بيانات شخص
                                            <input type="checkbox"  data-parent="edit-account" data-child="index-account"  name="allow_edit_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بتغير النوع
                                            <input type="checkbox"  data-child="edit-account"  name="allow_edit_account_type"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بتعديل الإسم
                                            <input type="checkbox"  data-child="edit-account"  name="allow_edit_account_name"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بتعديل رقم الهاتف
                                            <input type="checkbox"  data-child="edit-account"  name="allow_edit_account_tel"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بحذف مورد أو عميل
                                            <input type="checkbox"  data-child="index-account"  name="allow_delete_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline"
                                               data-placement='top'  title='هذة العميلية لا تؤثر فى الدرج ' dir="ltr">
                                            السماح بإجبار الحساب على قيمة معينة
                                            <input type="checkbox" data-parent="adjust_account"  name="allow_adjust_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار عند إجبار الحساب على قيمة
                                            <input type="checkbox"  data-child="adjust_account" name="create_notification_when_adjust_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بالدخول إلى صفحة حسابات الموردين والعملاء
                                            <input type="checkbox"  data-parent="report-account"  name="allow_access_report_account"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بحذف أخذ مال أو وضع مال للموردين والعملاء
                                            <input type="checkbox"  data-parent="delete_account_buy_or_take_money" data-child="report-account"  name="allow_delete_account_buy_take_money"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار عند حذف أخذ مال أو وضع مال للموردين والعملاء
                                            <input type="checkbox"  data-child="delete_account_buy_or_take_money" name="notification_when_delete_account_buy_take_money"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>المخازن والتوالف وحركة المنتجات
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                            إضافة منتج جديد
                                            <input type="checkbox"  name="allow_add_product"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            إدارة المنتجات (تعديل وحذف وتفعيل منتج)
                                            <input type="checkbox"  name="allow_manage_product"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدارة المخازن من إضافة مخزن جديد وتعديل و تفعيل وإلغاء تفعيل مخزن
                                            <input type="checkbox"  name="allow_mange_stoke"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدارة أسماء أماكن الحفظ فى المخازن من إضافة مكان جديد وتعديل و تفعيل وإلغاء تفعيل مكان
                                            <input type="checkbox" name="allow_mange_place_in_stoke"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدارة أماكن المنتجات فى المخازن
                                            <input type="checkbox" name="allow_mange_product_place_in_stoke"  value="1">
                                        </label>

                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بعرض المنتجات فى المخزن
                                            <input type="checkbox" data-parent="access_product_in_stoke" name="allow_access_product_in_stoke"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإضافة تالف للمنتجات
                                            <input type="checkbox" data-parent="add_damage" data-child="access_product_in_stoke" name="allow_add_damage"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار عند إضافة تالف
                                            <input type="checkbox" data-child="add_damage" name="notification_when_add_damage"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بعرض حركة المنتجات
                                            <input type="checkbox" data-parent="allow_access_product_move" name="allow_access_product_move"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بحذف تالف للمنتجات
                                            <input type="checkbox" data-parent="delete_damage" data-child="allow_access_product_move" name="allow_delete_damage"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار عند حذف تالف
                                            <input type="checkbox" data-child="delete_damage" name="notification_when_delete_damage"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>الإنتاج والعروض
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                           إضافة عملية إنتاج أو عرض
                                            <input type="checkbox"  name="allow_add_make"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            إدارة عمليات الإنتاج أو العروض
                                            <input type="checkbox" data-parent='allow_manage_make' name="allow_manage_make"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بحذف إنتاج أو عرض
                                            <input type="checkbox" data-parent="allow_delete_make" data-child="allow_manage_make" name="allow_delete_make"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار عند حذف إنتاج أو عرض
                                            <input type="checkbox" data-child="allow_delete_make" name="notification_delete_make"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>النسخ الإحتياطى
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدارة النسخ الإحتياطى
                                            <input type="checkbox"  name="allow_mange_backup"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بتحميل نسخة إحتياطية
                                            <input type="checkbox" name="allow_download_backup"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>الدرج
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بعرض حركة أخذ ووضع مال فى الدرج
                                            <input type="checkbox" data-parent="treasury"  name="allow_mange_treasury"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بحذف وضع مال أو أخذ مال
                                            <input type="checkbox" data-child="treasury" data-parent="notification_delete_treasury" name="allow_delete_treasury"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار بحذف وضع مال أو أخذ مال
                                            <input type="checkbox" data-child="notification_delete_treasury" name="create_notification_when_delete_treasury"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>المصروفات
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإضافة مصروفات وأقسام مصروفات
                                            <input type="checkbox" data-parent="expenses1" name="allow_add_expenses_and_expenses_type"  value="1">
                                        </label>
                                        <label class="checkbox-inline tooltips"  data-placement='left'
                                               title="للسماح بإضافة مصروفات لا تخصم من الدرج يجب أن تكون هذة الخاصية مفعلة فى ضبط خصائص البرنامج"
                                               dir="ltr">
                                            السماح بإضافة مصروفات لا تخصم من الدرج
                                            <input type="checkbox"
                                                   data-message="للسماح بإضافة مصروفات لا تخصم من الدرج يجب أن تكون هذة الخاصية مفعلة فى ضبط خصائص البرنامج"
                                                   data-child="expenses1" data-parent="expenses11" name="allow_add_expenses_with_out_subtract_form_treasury"  value="1">
                                        </label>
                                        <label class="checkbox-inline"  dir="ltr">
                                            عمل إشعار عند إضافة مصروفات لا تخصم من الدرج
                                            <input type="checkbox"
                                                   data-child="expenses11" name="notification_when_add_expenses_with_out_subtract_form_treasury"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدراة أقسام المصروفات
                                            <input type="checkbox" name="allow_mange_expenses_type"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدراةالمصروفات
                                            <input type="checkbox" data-parent="expenses" name="allow_mange_expenses"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بحذف مصروفات
                                            <input type="checkbox" data-child="expenses" data-parent="expenses111" name="allow_delete_expenses"  value="1">
                                        </label>
                                        <label class="checkbox-inline"  dir="ltr">
                                            عمل إشعار عند حذف مصروفات
                                            <input type="checkbox"
                                                   data-child="expenses111" name="notification_when_delete_expenses"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>المنتجات
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بضبط الباركود
                                            <input type="checkbox" name="allow_mange_barcode"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدارة أقسام المنتجات
                                            <input type="checkbox" name="allow_mange_product_category"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدارة وحدات المنتجات
                                            <input type="checkbox" name="allow_mange_product_unit"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>الفواتير
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بضبط خصائص الطباعة
                                            <input type="checkbox" name="allow_mange_print_setting"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدارة رسائل الفواتير
                                            <input type="checkbox" name="allow_mange_bill_message"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            إضافة فاتورة شراء
                                            <input type="checkbox" name="allow_create_bill_buy"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            إنشاء عرض أسعار بيع
                                            <input type="checkbox" name="allow_create_bill_sale_show"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            إدارة فواتير الشراء
                                            <input type="checkbox" data-parent="allow_manage_bill_buy" name="allow_manage_bill_buy"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            تعديل فاتورة شراء
                                            <input type="checkbox" data-child="allow_manage_bill_buy" name="allow_edit_bill_buy"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            حذف فاتورة شراء
                                            <input type="checkbox" data-paretn="notification_delete_bill_buy" data-child="allow_manage_bill_buy" name="allow_delete_bill_buy"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار عند حذف فاتورة شراء
                                            <input type="checkbox" data-child="notification_delete_bill_buy" name="notification_delete_bill_buy"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            إضافة فاتورة بيع
                                            <input type="checkbox" name="allow_create_bill_sale"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            إدارة فواتير البيع بدون عرض الأرباح
                                            <input type="checkbox" data-parent="allow_manage_bill_sale" name="allow_manage_bill_sale"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            إدارة فواتير البيع مع عرض الأرباح
                                            <input type="checkbox" data-child="allow_manage_bill_sale" name="allow_manage_bill_sale_with_profit"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            تعديل فاتورة بيع
                                            <input type="checkbox" data-child="allow_manage_bill_sale" name="allow_edit_bill_sale"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            حذف فاتورة بيع
                                            <input type="checkbox" data-paretn="notification_delete_bill_sale" data-child="allow_manage_bill_sale" name="allow_delete_bill_sale"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            عمل إشعار عند حذف فاتورة بيع
                                            <input type="checkbox" data-child="notification_delete_bill_sale" name="notification_delete_bill_sale"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <h1 data-changeshow class='font-weight-bold text-right mr-2'>المزيد
                                    <span class="position-relative"><i class="fas fa-angle-left position-absolute"
                                                                       style="top: 10px;right: 10px"></i></span>
                                </h1>
                                <div class='box-shadow bg-white data-container text-dark'>
                                    <div class="h3 " dir="rtl">
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بضبط خصائص البرنامج
                                            <input type="checkbox"  name="allow_mange_setting"  value="1">
                                        </label>
                                        <label class="checkbox-inline" dir="ltr">
                                            السماح بإدراة الأجهزة المتصلة بالبرنامج
                                            <input type="checkbox" name="allow_mange_device"  value="1">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='form-group row'>
                        <div class='col-sm-6'>
                            <button type='submit'
                                    class='font-weight-bold  mt-2 mb-2 form-control btn btn-success animated bounceInRight fast'>
                                <span class='h4 font-weight-bold h3'>اضافة مستخدم</span>
                            </button>
                        </div>
                        <div class='col-sm-6'>
                            <a href='{{route('users.index')}}'
                               class='font-weight-bold mt-2  mb-2 form-control text-white btn btn-success animated bounceInLeft fast'>
                                <span class='h4 font-weight-bold h3'>الغاء</span>
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
        design.show_password();
        design.useNiceScroll();
        design.useToolTip();
        $('input[type="checkbox"]').prop('checked',false);

        validateByAttr();

        $('#typeUser').change(function () {
            design.useSound('info');
           if($(this).val()==2){
               $('input[type="checkbox"]').prop('checked',false);
               $('#normalAccountSetting').slideDown();
               design.updateNiceScroll();
           }else{
               $('#normalAccountSetting').slideUp();
               design.updateNiceScroll();
           }
        });

        /*toggle between div*/
        $('#contentAllData [data-changeshow]').click(function () {
            $('input[data-attr="check_all"]').addClass('d-none');
            $(this).find('input[data-attr="check_all"]').removeClass('d-none');
            if($(this).parent().hasClass('show')){
                $(this).parent().removeClass('show').siblings().removeClass('show');
            }else{
                $(this).parent().addClass('show').siblings().removeClass('show');
            }
            changeShow(300);
            design.updateNiceScroll();
            design.useSound('success');
        });
        changeShow();

        function changeShow(time = 300) {
            $('#contentAllData>div').each(function () {
                if ($(this).hasClass('show')) {
                    $(this).children('div').slideDown(time);
                } else {
                    $(this).children('div').slideUp(time);
                }
            });
        }

        //hide check box with relation
        $('#normalAccountSetting input[data-parent]').change(function () {
            var state=$(this).prop('checked');
            design.useSound('info');
            if(state){
                $('#normalAccountSetting input[data-child="'+$(this).data('parent')+'"]').prop('checked',false).parent().slideDown(0);
            }else{
                $('#normalAccountSetting input[data-child="'+$(this).data('parent')+'"]').prop('checked',false).parent().slideUp(0);
            }
        });
        $('#normalAccountSetting input[data-parent]').trigger('change');

        //show message when check box with data-message
        $('#normalAccountSetting input[data-message]').change(function () {
            var state=$(this).prop('checked');
            if(state){
                design.useSound('info');
                alertify.success($(this).attr('data-message'));
            }
        });
    </script>
@endsection


