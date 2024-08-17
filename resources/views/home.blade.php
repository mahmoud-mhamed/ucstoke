<?php
$permit = \App\Permit::first();
?>
@extends('layouts.app')
@section('css')
    <style>
        section.change-background {
            bottom: 10px;
            left: 10px;
        }

        section.change-background img, #buttonUploadImg {
            width: 25px;
            height: 25px;
        }

        main input[type='checkbox'] {
            transform: scale(2);
            margin-left: 10px;
            display: none;
        }

        div.container_links > div {
            padding-left: 0px !important;
            padding-right: 0px !important;
            width: 220px;
            overflow: hidden;
        }

        div.container_links a {
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        div.container_link h1, div.container_link h2, div.container_link h3, div.container_link h4 {
            text-align: center;

            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media all and (max-width: 569px) {
            div.container_links > div:not(.hide-div), div.container_links > div.opacity-hide {
                display: block !important;
                margin-bottom: 16px !important;
                overflow-x: scroll;
                overflow-y: hidden;
                width: 96%;
            }

            div.container_links h1, div.container_links h2, div.container_links h3, div.container_links h4 {
                font-size: 2.1rem !important;
            }
        }

        @media all and (min-width: 569px) and (max-width: 750px) {
            div.container_links > div:not(.hide-div), div.container_links > div.opacity-hide {
                display: inline-block !important;
                margin-bottom: 16px !important;
                width: 46%;
            }
        }

        @media all and (min-width: 750px) and (max-width: 950px) {
            div.container_links > div:not(.hide-div), div.container_links > div.opacity-hide {
                display: inline-block !important;
                margin-bottom: 16px !important;
                width: 30%;
            }
        }

        main .hide-div {
            display: none !important;
        }

        main .opacity-hide {
            opacity: .55;
            display: inline-block !important;
        }

        #div_container_setting:hover {
            opacity: 1 !important;
        }
    </style>
@endsection

@section('content')
    <main class='px-2 pt-0 text-white h3 text-right' dir='rtl'>
        <div class="container-fluid" id="div_container_setting" style="opacity: 0;height: 30px">
            <input type="checkbox"
                   class="tooltips"
                   id="start_from_here"
                   data-placement="left" title="إبدأ من هنا"
                   style="margin-right:20px;transform: scale(2);padding-right: 5px;display: inline-block!important;"
                   value="">
            <input type="checkbox"
                   class="tooltips"
                   id="check_show_hide_in_home"
                   data-placement="bottom" title="إخفاء , إظهار العناصر فى الصفحة الرئيسية"
                   style="margin-right:20px;padding-right: 5px;display: inline-block!important;"
                   value="">
            <input type="checkbox"
                   class="tooltips"
                   id="hide_links_in_home"
                   data-placement="bottom" title="إخفاء كل العناصر من الصفحة الرئيسية"
                   style="margin-right:20px;transform: scale(2);padding-right: 5px;display: inline-block!important;"
                   value="">
            <input type="checkbox"
                   class="tooltips"
                   id="merge_links_home"
                   data-placement="bottom" title="دمج الأقسام فى الصفحة الرئيسية"
                   style="margin-right:20px;transform: scale(2);padding-right: 5px;display: inline-block!important;"
                   value="">
            <input type="checkbox"
                   class="tooltips"
                   id="open_all_in_new_tab"
                   data-placement="bottom" title="فتح عناصر الصفحة الرئيسية فى نافذة جديدة"
                   style="margin-right:20px;transform: scale(2);padding-right: 5px;display: inline-block!important;"
                   value="">
        </div>
        <section id="container_fast_link" class="text-right">
            <div class="mb-2 container_links">
                @if (Auth::user()->type ==1 ||Auth::user()->allow_add_product)
                    <div class="d-inline-block position-relative btn btn-warning pointer  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إضافة منتج شراء,بيع,إنتاج,عرض جديد">
                        <input type="checkbox" name="hc_add_product" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('products.create')}}" class="btn btn-warning d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-folder-plus"></i>
                                <h1 class="pb-0">إضافة منتج</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_product)
                    <div class="d-inline-block btn btn-warning pointer position-relative mx-2 mb-2 pb-0  tooltips"
                         data-placement="left" title="إدارة المنتجات , تعديل وحذف منتج وطباعة الباركود لمنتج(F3)">
                        <input type="checkbox" name="hc_manage_product" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('products.index')}}" class="btn btn-warning d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-folder-open"></i>
                                <h1 class="pb-0">إدارة المنتجات</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_product_category)
                    <div class="d-inline-block btn btn-warning pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إضافة وتعديل وحذف قسم منتج">
                        <input type="checkbox" name="hc_manage_category" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('products_categories.index')}}" class="btn btn-warning d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-city"></i>
                                <h1 class="pb-0">أقسام المنتجات</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_product_unit)
                    <div class="d-inline-block btn btn-warning pointer position-relative mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إضافة وتعديل وحذف وحدة منتج">
                        <input type="checkbox" name="hc_manage_unit" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('products_units.index')}}" class="btn btn-warning d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-building"></i>
                                <h1 class="pb-0">وحدات المنتجات</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_access_product_move)
                    <div class="d-inline-block btn btn-warning pointer position-relative mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="تقرير بحركة المنتج فى أى فترة">
                        <input type="checkbox" name="hc_product_move" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('product_moves.index')}}" class="btn btn-warning d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-location-arrow"></i>
                                <h1 class="pb-0">حركة المنتجات</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if(Hash::check('use_barcode',$permit->use_barcode))
                    @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_barcode)
                        <div class="d-inline-block btn btn-warning pointer position-relative mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="ضبط خصائص الباركود (لون , حجم , نوع , البيانات الظاهرة)">
                            <input type="checkbox" name="hc_manage_barcode" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('barcodes.index')}}" class="btn btn-warning d-block pb-0">
                                <div class="">
                                    <i class="fas fa-4x fa-barcode"></i>
                                    <h1 class="pb-0">ضبط الباركود</h1>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif
                @if(Hash::check('account_product_move',$permit->account_product_move))
                    @if (Auth::user()->type ==1 ||Auth::user()->allow_access_report_account)
                        <div class="d-inline-block btn btn-warning pointer position-relative mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="تقرير بحركة المنتجات للموردين والعملاء فى أى فترة">
                            <input type="checkbox" name="hc_account_product_move" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('product_moves.account_product_move')}}"
                               class="btn btn-warning d-block pb-0">
                                <div class="">
                                    <i class="fas text-success fa-users"></i>
                                    <i class="fas fa-4x fa-location-arrow"></i>
                                    <h3 class="pb-0 mt-2">حركة المنتج(مورد,عميل)</h3>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif
                <div data-new_row class="my-2"></div>
                @if (Auth::user()->type==1 || Auth::user()->allow_access_product_in_stoke)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left"
                         title="تقرير بالمنتجات فى المخزن وأسعارها وأماكنها وإجمالى سعر البضاعة فى المخزن وإضافة تالف للمخزن (F12)">
                        <input type="checkbox" name="hc_manage_product_in_stoke"
                               class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('stores.index')}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-store"></i>
                                <h1 class="pb-0">المخزن</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_buy)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إنشاء فاتورة شراء(F1)">
                        <input type="checkbox" name="home_create_bill_buy" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('bills.create',0)}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-cart-arrow-down"></i>
                                <h1 class="pb-0">شراء</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إنشاء فاتورة بيع(*)">
                        <input type="checkbox" name="hc_create_bill_sale" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('bills.create',1)}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-cart-plus"></i>
                                <h1 class="pb-0">بيع</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale_show)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إنشاء عرض أسعار بيع(F2)">
                        <input type="checkbox" name="hc_create_bill_sale" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('bills.create',2)}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-shopping-basket"></i>
                                <h1 class="pb-0">عرض أسعار</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_bill_buy)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left"
                         title="(F7) عرض فواتير الشراء , تعديل , حذف , إضافة مرتجع للفاتورة , عرض مرتجعات الفاتورة , طباعة فاتورة">
                        <input type="checkbox" name="hc_manage_bill_buy" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('bills.index',0)}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas text-warning fa-4x  fa-ellipsis-v"></i>
                                <i class="fas fa-4x fa-dolly"></i>
                                {{--                                <i class="fas fa-4x fa-tasks"></i>--}}
                                <h1 class="pb-0">إدارة الفواتير شراء</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_bill_sale)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left"
                         title="عرض فواتير البيع , تعديل , حذف , إضافة مرتجع للفاتورة , عرض مرتجعات الفاتورة , طباعة فاتورة (F8)">
                        <input type="checkbox" name="hc_manage_bill_sale" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('bills.index',1)}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas text-warning fa-4x  fa-ellipsis-v"></i>
                                <i class="fas fa-4x fa-luggage-cart"></i>
                                {{--                                <i class="fas fa-4x fa-tasks"></i>--}}
                                <h1 class="pb-0">إدارة الفواتير بيع</h1>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||(Auth::user()->allow_manage_bill_sale && Auth::user()->allow_manage_bill_buy))
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left"
                         title="عرض فواتير شراء,البيع , تعديل , حذف , إضافة مرتجع للفاتورة , عرض مرتجعات الفاتورة , طباعة فاتورة(Insert) ">
                        <input type="checkbox" name="hc_manage_bill_sale_buy" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('bills.index',2)}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas text-warning fa-4x  fa-ellipsis-v"></i>
                                <i class="fas fa-4x fa-tasks"></i>
                                <h2 class="pb-0">إدارة الفواتير شراء,بيع</h2>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif

                @if(Hash::check('product_make',$permit->product_make))
                    @if (Auth::user()->type ==1 ||Auth::user()->allow_add_make)
                        <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left"
                             title="(F10)إضافة عملية إنتاج ">
                            <input type="checkbox" name="hc_create_making" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('makings.create',1)}}"
                               class="btn btn-success d-block pb-0">
                                <div class="">
                                    <i class="fas fa-2x text-warning fa-briefcase"></i>
                                    <i class="fas fa-4x fa-flask"></i>
                                    <h1 class="pb-0">إضافة إنتاج,عرض</h1>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                    @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_make)
                        <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left"
                             title="إدارة وحذف عمليات الإنتاج أو العروض  ">
                            <input type="checkbox" name="hc_create_making" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('makings.index')}}?show_profit=true" class="btn btn-success d-block pb-0">
                                <div class="">
                                    <i class="fas fa-2x text-warning fa-flask"></i>
                                    <i class="fas fa-4x fa-tasks"></i>
                                    <h2 class="pb-0 font-weight-bold">إدارة الإنتاج,العروض</h2>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif

                @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_bill_sale_with_profit)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left"
                         title="عرض فواتير البيع , تعديل , حذف , إضافة مرتجع للفاتورة , عرض مرتجعات الفاتورة , طباعة فاتورة,أرباح فاتورة بيع ">
                        <input type="checkbox" name="hc_manage_bill_sale" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('bills.index',1)}}?show_profit=true" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-2x text-warning fa-funnel-dollar"></i>
                                <i class="fas fa-4x fa-tasks"></i>
                                <h2 class="pb-0 font-weight-bold">فواتير البيع بالارباح</h2>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_access_product_profit)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left"
                         title="عرض حركة البيع بالأرباح للمنتجات فى فترة">
                        <input type="checkbox" name="hc_show_profit_move" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('product_moves.show_profit')}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-2x text-warning fa-location-arrow"></i>
                                <i class="fas fa-4x text-white  fa-dollar-sign"></i>
                                <h4 class="pb-0 font-weight-bold">حركةالبيع والأرباح للمنتجات</h4>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_add_account)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left"
                         title="إضافة مورد أو عميل {{Hash::check('sup_cust',$permit->sup_cust)?'أو مورد عميل':''}}">
                        <input type="checkbox" name="hc_add_new_account" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('accounts.create')}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-user-plus"></i>
                                <h2 class="pb-0 font-weight-bold">إضافة مورد أو عميل</h2>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 || Auth::user()->allow_access_index_account)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="حسابات الموردين والعملاء - دفع واخذ مال وتعديل مورد أو عميل(F9)">
                        <input type="checkbox" name="hc_manage_account" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('accounts.index')}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-users"></i>
                                <h3 class="pb-0 font-weight-bold">إدارة الموردين والعملاء</h3>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_access_report_account)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="تقارير بحسابات الموردين والعملاء وحذف دفع أو أخذ مال">
                        <input type="checkbox" name="hc_account_calclution" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('account_calculation.index',1)}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-2x text-warning fa-calculator"></i>
                                <i class="fas fa-4x text-white fa-users"></i>
                                <h3 class="pb-0 font-weight-bold">تقارير الموردين والعملاء</h3>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="تقرير مجمع بفواتير شخص">
                        <input type="checkbox" name="hc_account_bill_with_details" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('accounts.account_bill_with_details')}}" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-2x text-warning fa-calculator"></i>
                                <i class="fas fa-4x text-white fa-users"></i>
                                <h4 class="pb-0 font-weight-bold">تقرير مجمع لفواتير شخص</h4>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                <div data-new_row class="my-2"></div>
                @if (Auth::user()->type==1 || Auth::user()->allow_manage_activities)
                    <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="عرض كل نشاطات المستخدمين (End)">
                        <input type="checkbox" name="hc_activity" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('activity.index')}}" class="btn btn-dark d-block pb-0">
                            <div class="">
                                <i class="fas text-success fa-4x fa-chart-line"></i>
                                <h2 class="pb-0 font-weight-bold">حركة المستخدمين</h2>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if(Hash::check('use_emp',$permit->use_emp))
                    @if (Auth::user()->type==1 || Auth::user()->allow_add_emp)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إضافة موظف">
                            <input type="checkbox" name="hc_emp_create" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('emps.create')}}" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="fas text-success fa-4x fa-user-plus"></i>
                                    <h2 class="pb-0 font-weight-bold">إضافة موظف</h2>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                    @if (Auth::user()->type==1 || Auth::user()->allow_manage_emp_operation)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="(PgDn)إضافة( إضافى , خصم , سلفة ,دفع أجر)">
                            <input type="checkbox" name="hc_emp_operation" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('emps.index')}}?show_opertaion=true" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="fas text-success fa-4x fa-bible"></i>
                                    <h2 class="pb-0 font-weight-bold">عمليات الموظفين</h2>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                    @if (Auth::user()->type==1 || Auth::user()->allow_manage_emp_move)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left"
                             title=" الحسابات والحركة التفصيلية للموظفين , حذف إضافى أو خصم أو دفع مال أو سلفة">
                            <input type="checkbox" name="hc_emp_move" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('emps.report')}}" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="fas fa-2x text-info fa-calculator"></i>
                                    <i class="fas text-success fa-4x fa-users"></i>
                                    <h3 class="pb-0">حسابات وحركة الموظفين</h3>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title=" تقارير إجمالية للموظفين">
                            <input type="checkbox" name="hc_emp_move2" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('emps.report2')}}" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="fas fa-2x text-info fa-users"></i>
                                    <i class="fab text-success fa-4x fa-connectdevelop"></i>
                                    <h3 class="pb-0">تقارير الموظفين</h3>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                    @if (Auth::user()->type==1 || Auth::user()->allow_manage_emp_attend)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إدارة حضور الموظفين(PgUp)">
                            <input type="checkbox" name="hc_emp_attend" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('emps.show_emp_attend')}}" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="fas fa-2x text-info fa-book"></i>
                                    <i class="fas text-success fa-4x fa-users"></i>
                                    <h2 class="pb-0 font-weight-bold">إدارة الحضور</h2>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif
                <div data-new_row class="my-2"></div>
                @if(Hash::check('use_expenses',$permit->use_expenses))
                    @if (Auth::user()->type==1 ||Auth::user()->allow_add_expenses_and_expenses_type)
                        <div class="d-inline-block btn btn-info pointer  position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="(F4)إضافة مصروفات جديدة">
                            <input type="checkbox" name="hc_add_expenses" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('expenses.create')}}" class="btn btn-info d-block pb-0">
                                <div class="">
                                    <i class="fas fa-4x fa-plus-circle"></i>
                                    <h2 class="pb-0 font-weight-bold">إضافة مصروفات</h2>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                    @if (Auth::user()->type==1 ||Auth::user()->allow_mange_expenses)
                        <div class="d-inline-block btn btn-info pointer  position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إدارة وتقارير المصروفات وحذف مصروف">
                            <input type="checkbox" name="hc_manage_expenses" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('expenses.index')}}" class="btn btn-info d-block pb-0">
                                <div class="">
                                    <i class="far fa-4x fa-chart-bar"></i>
                                    <h3 class="pb-0 font-weight-bold">إدارة وتقارير المصروفات</h3>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif
                @if(Hash::check('use_exit_deal',$permit->use_exit_deal))
                    @if (Auth::user()->type==1 ||Auth::user()->allow_create_exit_deal)
                        <div class="d-inline-block btn btn-info pointer  position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إضافة أرباح أوخسائر خارجية ">
                            <input type="checkbox" name="hc_create_exist_deal" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('exist_deals.create')}}" class="btn btn-info d-block pb-0">
                                <div class="">
                                    <i class="fas text-warning fa-plus-circle"></i>
                                    <i class="fas fa-4x fa-dollar-sign"></i>
                                    <h3 class="pb-0 font-weight-bold">إضافة تعاملات خارجية</h3>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                    @if (Auth::user()->type==1 ||Auth::user()->allow_manage_exit_deal)
                        <div class="d-inline-block btn btn-info pointer  position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إدارة وتقارير الأرباح والخسائر الخارجية ">
                            <input type="checkbox" name="hc_manage_exist_deal" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('exist_deals.index')}}" class="btn btn-info d-block pb-0">
                                <div class="">
                                    <i class="far text-warning fa-chart-bar"></i>
                                    <i class="fas  fa-4x fa-door-open"></i>
                                    <h3 class="pb-0 font-weight-bold">إدارةالتعاملات الخارجية</h3>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif
                @if (Auth::user()->type==1 ||Auth::user()->allow_access_total_report)
                    <div class="d-inline-block btn btn-info pointer  position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="تقرير شامل بالأرباح والمصروفات ... (F11)">
                        <input type="checkbox" name="hc_users_report" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('users.report')}}" class="btn btn-info d-block pb-0">
                            <div class="">
                                <i class="fas text-warning fa-paper-plane"></i>
                                <i class="fas fa-4x fa-chart-pie"></i>
                                {{--                                <i class="fas fa-4x fa-chart-line"></i>--}}
                                <h3 class="pb-0 font-weight-bold">تقرير شامل</h3>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                <div data-new_row class="my-2"></div>
                <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                     data-placement="left" title="الدرج وأخذ ووضع مال فى الدرج(F6)">
                    <input type="checkbox" name="hc_treasuries" class="tooltips position-absolute"
                           style="right: 5px;top: 5px"
                           title="فتح فى نافذة جديدة" data-placement="bottom">
                    <a href="{{route('treasuries.get_add_or_take_money')}}" class="btn btn-dark d-block pb-0">
                        <div class="">
                            <i class="fas fa-4x fa-dollar-sign"></i>
                            <h2 class="pb-0 font-weight-bold">الدرج</h2>
                        </div>
                    </a>
                    <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                           style="left: -5px;top: 5px"
                           title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                </div>
                @if (Auth::user()->type==1 || Auth::user()->allow_mange_treasury)
                    <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="حركة الدرج وتقارير الدرج وحذف وضع مال أو أخذ مال">
                        <input type="checkbox" name="hc_manage_treasuries" class="tooltips position-absolute"
                               style="right: 5px;top: 5px"
                               title="فتح فى نافذة جديدة" data-placement="bottom">
                        <a href="{{route('treasuries.index')}}" class="btn btn-dark d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-money-check-alt"></i>
                                <h3 class="pb-0 font-weight-bold">حركة وتقارير الدرج</h3>
                            </div>
                        </a>
                        <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                               style="left: -5px;top: 5px"
                               title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                    </div>
                @endif
                @if(Hash::check('use_visit',$permit->use_visit))
                    @if (Auth::user()->type==1||Auth::user()->allow_add_visit)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إضافة مهمة">
                            <input type="checkbox" name="hc_add_visit" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('visits.create')}}" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="text-white fa-4x fas fa-notes-medical"></i>
                                    <h3 class="pb-0 font-weight-bold">إضافة مهمة</h3>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                    @if (Auth::user()->type==1||Auth::user()->allow_manage_visit)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إدارة المهام والزيارات">
                            <input type="checkbox" name="hc_index_visit" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('visits.index')}}" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="text-white fa-4x fas fa-clipboard"></i>
                                    <h3 class="pb-0 font-weight-bold">إدارة المهام,الزيارات</h3>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif
            </div>
        </section>
        <section id="container_start_form_here" class="text-right d-none">
            <div class="container_links">
                @if (Auth::user()->type ==1 ||Auth::user()->allow_add_account)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left"
                         title="إضافة مورد أو عميل {{Hash::check('sup_cust',$permit->sup_cust)?'أو مورد عميل':''}}">
                        <a href="{{route('accounts.create')}}" target="_blank" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-user-plus"></i>
                                <h2 class="pb-0 font-weight-bold">إضافة مورد أو عميل</h2>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_product_category)
                    <div class="d-inline-block btn btn-warning pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إضافة وتعديل وحذف قسم منتج">
                        <a href="{{route('products_categories.index')}}" target="_blank"
                           class="btn btn-warning d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-city"></i>
                                <h1 class="pb-0">أقسام المنتجات</h1>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_product_unit)
                    <div class="d-inline-block btn btn-warning pointer position-relative mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إضافة وتعديل وحذف وحدة منتج">
                        <a href="{{route('products_units.index')}}" target="_blank"
                           class="btn btn-warning d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-building"></i>
                                <h1 class="pb-0">وحدات المنتجات</h1>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_add_product)
                    <div class="d-inline-block position-relative btn btn-warning pointer  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إضافة منتج شراء,بيع,إنتاج,عرض جديد">
                        <a href="{{route('products.create')}}" target="_blank" class="btn btn-warning d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-folder-plus"></i>
                                <h1 class="pb-0">إضافة منتج</h1>
                            </div>
                        </a>
                    </div>
                @endif

                @if (Auth::user()->type==1 || Auth::user()->allow_mange_stoke)
                    <div
                        class="{{Hash::check('mange_stoke',$permit->mange_stoke)?'d-inline-block':'not_permits'}} btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                        data-placement="left"
                        title="إضافة وتعديل وحذف مخزن">
                        <a href="{{route('stokes.index')}}" target="_blank" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-globe"></i>
                                <h1 class="pb-0">إداره المخازن</h1>
                            </div>
                        </a>
                    </div>
                @endif

                @if (Auth::user()->type==1||Auth::user()->allow_mange_device)
                    <div
                        class="{{Hash::check('mange_stoke',$permit->mange_stoke)?'d-inline-block':'not_permits'}} btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                        data-placement="left"
                        title="تحديد المخازن المصرح للأجهزة المتصلة بالوصول إليها">
                        <a href="{{route('devices.index')}}" target="_blank" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-wrench"></i>
                                <h1 class="pb-0">ضبط الأجهزة</h1>
                            </div>
                        </a>
                    </div>
                @endif

                @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_buy)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="(F1)إنشاء فاتورة شراء">
                        <a href="{{route('bills.create',0)}}" target="_blank" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-cart-arrow-down"></i>
                                <h1 class="pb-0">شراء</h1>
                            </div>
                        </a>
                    </div>
                @endif
                @if(Hash::check('product_make',$permit->product_make))
                    @if (Auth::user()->type ==1 ||Auth::user()->allow_add_make)
                        <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left"
                             title="إضافة عملية إنتاج (F10)">
                            <input type="checkbox" name="hc_create_making" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('makings.create',1)}}"
                               class="btn btn-success d-block pb-0">
                                <div class="">
                                    <i class="fas fa-2x text-warning fa-briefcase"></i>
                                    <i class="fas fa-4x fa-flask"></i>
                                    <h1 class="pb-0">إضافة إنتاج,عرض</h1>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إنشاء فاتورة بيع(*)">
                        <a href="{{route('bills.create',1)}}" target="_blank" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-cart-plus"></i>
                                <h1 class="pb-0">بيع</h1>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale_show)
                    <div class="d-inline-block btn btn-success pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إنشاء عرض أسعار بيع(F2)">
                        <a href="{{route('bills.create',2)}}" target="_blank" class="btn btn-success d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-shopping-basket"></i>
                                <h1 class="pb-0">عرض أسعار</h1>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->type==1 ||Auth::user()->allow_add_expenses_and_expenses_type)
                    <div class="d-inline-block btn btn-info pointer  position-relative  mx-2 mb-2 pb-0 tooltips"
                         data-placement="left" title="إضافة مصروفات جديدة(F4)">
                        <a href="{{route('expenses.create')}}" target="_blank" class="btn btn-info d-block pb-0">
                            <div class="">
                                <i class="fas fa-4x fa-plus-circle"></i>
                                <h2 class="pb-0 font-weight-bold">إضافة مصروفات</h2>
                            </div>
                        </a>
                    </div>
                @endif
                @if(Hash::check('use_emp',$permit->use_emp))
                    @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp_jops)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إضافة , تعديل , حذف وظيفة">
                            <a href="{{route('emp_jops.index')}}" target="_blank" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="fas text-success fa-4x fa-cubes"></i>
                                    <h2 class="pb-0 font-weight-bold">إدارة الوظائف</h2>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if (Auth::user()->type==1 || Auth::user()->allow_add_emp)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إضافة موظف">
                            <input type="checkbox" name="hc_emp_create" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('emps.create')}}" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="fas text-success fa-4x fa-user-plus"></i>
                                    <h2 class="pb-0 font-weight-bold">إضافة موظف</h2>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                    @if (Auth::user()->type==1 || Auth::user()->allow_manage_emp_attend)
                        <div class="d-inline-block btn btn-dark pointer position-relative  mx-2 mb-2 pb-0 tooltips"
                             data-placement="left" title="إدارة حضور الموظفين(PgUp)">
                            <input type="checkbox" name="hc_emp_attend" class="tooltips position-absolute"
                                   style="right: 5px;top: 5px"
                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                            <a href="{{route('emps.show_emp_attend')}}" class="btn btn-dark d-block pb-0">
                                <div class="">
                                    <i class="fas fa-2x text-info fa-book"></i>
                                    <i class="fas text-success fa-4x fa-users"></i>
                                    <h2 class="pb-0 font-weight-bold">إدارة الحضور</h2>
                                </div>
                            </a>
                            <input type="checkbox" data-input_state_show class="tooltips position-absolute"
                                   style="left: -5px;top: 5px"
                                   title="أخفاء العنصر من الصفحة الرئيسية" data-placement="left">
                        </div>
                    @endif
                @endif
            </div>
        </section>
    </main>


    @if (Auth::user()->type==1 ||Auth::user()->allow_change_background)
        <form action="{{route('users.uploadImg')}}" class="d-none" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" required id="newImgForUser" name="img" accept="image/*"/>
        </form>

        <section class='change-background position-fixed pointer' style="bottom: 10px;left: 10px;">
            <img src='img/bg2.jpg' alt='bg2'>
            <img src='img/bg3.jpg' alt='bg3'>
            <img src='img/bg4.jpg' alt='bg4'>
            <img src='img/bg5.jpg' alt='bg5'>
            <img src='img/bg6.jpg' alt='bg6'>
            <button class="btn btn-warning position-relative" id="buttonUploadImg">
                <i style="left: 5px;top: 5px" class="fas fa-upload text-dark position-absolute"></i>
            </button>
        </section>
    @endif
@endsection
@section('js')
    <script defer>
        design.useNiceScroll();
        design.useSound();
        @if (Auth::user()->type==1 ||Auth::user()->allow_change_background)
        $(function () {
            $('section.position-fixed img').on('click', function () {
                var bg = $(this).attr('alt');
                $.ajax({
                    url: '{{route('users.editBg')}}',
                    method: 'POST',
                    data: {
                        bg: bg
                    },
                    success: function (data) {
                        window.location.reload(true);
                    },
                    error: function (e) {
                        // alert('error');
                        console.log(e);
                    }
                });
            });
        });
        $('#buttonUploadImg').click(function () {
            alertify.log('برجاء إختيار صورة صغيرة الحجم حتى لا تقلل من سرعة البرنامج هناك مواقع كثيرة لتصغير الصور ومن أفضلها ' +
                "<a href='https://tinypng.com/' style='text-decoration: underline;color: darkblue' target='_blank'>tinypng</a>", 'error', 0);
            $('#newImgForUser').trigger('click');
            design.useSound();
        });
        $('#newImgForUser').change(function () {
            if (hasExtension('newImgForUser', ['.jpg', '.png'])) {
                if (this.files[0].size / 1024 / 1024 > 2) {
                    $('#newImgForUser').val('');
                    alertify.error("برجاء تحديد صورة حجمها أصغر من 2 ميجا ");
                    return;
                }
                $(this).parent().submit();
            } else {
                $('#newImgForUser').val('');
                alertify.error("برجاء تحديد صورة بإمتداد " + '<br/>' + '.jpg أو .png');
            }
        });

        function hasExtension(inputID, exts) {
            var fileName = document.getElementById(inputID).value;
            return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
        }
        @endif

        //create cookie for login success to prevent open two tap and login in one in the same browser
        Cookie.set('succsess_login', '1');


        //hide and show checkbox open in new tab
        $('#container_fast_link .container_links>div').hover(function () {
            if (!$('#check_show_hide_in_home').prop('checked') && !$('#open_all_in_new_tab').prop('checked')) {
                $(this).find('input:checkbox:first-child').css('display', 'inline');
            }
        }, function () {
            $(this).find('input:checkbox:first-child').css('display', 'none');
        });

        //set default value for check box in fast link
        $('#container_fast_link .container_links input:checkbox:first-child').each(function () {
            var name = $(this).attr('name');
            if (Cookie.get(name) != null) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }

            //set default value for check hide and show div
            name = name + '1';
            if (Cookie.get(name) != null) {
                $(this).next().next().prop('checked', true);
                $(this).parent().addClass('hide-div');
            } else {
                $(this).next().next().prop('checked', false);
                $(this).parent().removeClass('hide-div');
            }
        });

        //set default value for open all links in new tap
        if (Cookie.get('stop_open_all_in_new_tab_home') != null) {
            $('#open_all_in_new_tab').prop('checked', true);
        }
        $('#open_all_in_new_tab').change(function () {
            if ($(this).prop('checked')) {
                Cookie.set('stop_open_all_in_new_tab_home', 'true', {expires: 365});
            } else {
                Cookie.remove('stop_open_all_in_new_tab_home');
            }
            design.useSound();
        });
        //open links in container_fast_link in new tab or this tab
        $('#container_fast_link input:checkbox:first-child').change(function () {
            if ($(this).prop('checked')) {
                Cookie.set($(this).attr('name'), $(this).prop('checked'), {expires: 365});
                $(this).next('a').attr('target', '_blank');
            } else {
                Cookie.remove($(this).attr('name'));
                $(this).next('a').attr('target', '_parent');
            }
            design.useSound();
        });

        if ($('#open_all_in_new_tab').prop('checked')) {
            $('#container_fast_link input:checkbox:first-child').trigger('change');
        }

        $('#container_fast_link a').click(function () {
            design.useSound();
            // open in new tab when check open all in new tab is selected
            if ($('#open_all_in_new_tab').prop('checked')) {
                $(this).attr('target', '_blank');
            } else {
                if ($(this).attr('target') == '_parent') {
                    $('#load').css('display', 'block');
                }
            }
        });

        //hide and show div container links
        $('#check_show_hide_in_home').change(function () {
            design.useSound();
            if ($(this).prop('checked')) {
                $('div.container_links div.hide-div').addClass('opacity-hide');
                $('#container_fast_link input:checkbox:last-child').css('display', 'inline');
            } else {
                $('div.container_links div.hide-div').removeClass('opacity-hide');
                $('#container_fast_link input:checkbox:last-child').css('display', 'none');
            }
        });

        $('#check_show_hide_in_home').prop('checked', false);

        $('#container_fast_link input:checkbox:last-child').change(function () {
            if ($(this).prop('checked')) {
                $(this).parent().addClass('hide-div').addClass('opacity-hide');
                Cookie.set(($(this).prev().prev().attr('name') + '1'), $(this).prop('checked'), {expires: 365});
            } else {
                $(this).parent().removeClass('hide-div').removeClass('opacity-hide');
                Cookie.remove(($(this).prev().prev().attr('name') + '1'));
            }
            design.useSound();
        });

        if ($(document).width() < 570) {
            $('#container_fast_link div.container_links div.position-relative,#check_show_hide_in_home').attr('data-placement', 'bottom');
        }

        //set default value for hide links in home
        if (Cookie.get('hide_links_in_home') != null) {
            $('#container_fast_link').addClass('d-none');
            $('#hide_links_in_home').prop('checked', true);
        }

        $('#hide_links_in_home').change(function () {
            design.useSound();
            if ($(this).prop('checked')) {
                $('#container_fast_link').addClass('d-none');
                Cookie.set('hide_links_in_home', $(this).prop('checked'), {expires: 365});
            } else {
                $('#container_fast_link').removeClass('d-none');
                Cookie.remove('hide_links_in_home');
            }
        });

        //set default value merge_links_home
        if (Cookie.get('merge_links_home') != null) {
            $('div[ data-new_row]').addClass('d-none');
            $('#merge_links_home').prop('checked', true);
        }

        $('#merge_links_home').change(function () {
            design.useSound();
            if ($(this).prop('checked')) {
                $('div[ data-new_row]').addClass('d-none');
                Cookie.set('merge_links_home', $(this).prop('checked'), {expires: 365});
            } else {
                $('div[ data-new_row]').removeClass('d-none');
                Cookie.remove('merge_links_home');
            }
        });

        //set default value start_from_here_home
        if (Cookie.get('start_from_here') != null) {
            $('#container_fast_link').addClass('d-none');
            $('#container_start_form_here').removeClass('d-none');
            $('#start_from_here').prop('checked', true);
        }

        $('#start_from_here').change(function () {
            design.useSound();
            if ($(this).prop('checked')) {
                $('#container_fast_link').addClass('d-none');
                $('#container_start_form_here').removeClass('d-none');
                Cookie.set('start_from_here', $(this).prop('checked'), {expires: 365});
            } else {
                $('#container_fast_link').removeClass('d-none');
                $('#container_start_form_here').addClass('d-none');
                Cookie.remove('start_from_here');
            }
        });
    </script>

    @if($state_download_backup && (Auth::user())->type==1 || Auth::user()->allow_download_backup)
        <script defer>
            /*download automatic backup*/
            setTimeout(function () {
                $('#link_download_backup').click();
                window.open('{{route('backups.downloadBackup')}}', '_parent');
            }, 3000);
        </script>
    @endif
    @if($state_create_backup)
        <script defer>
            /*create automatic backup*/
            setTimeout(function () {
                // $('#load').css('display', 'block');
{{--                window.open('{{route('backups.createBackup','createAuto')}}', '_parent');--}}
            }, 3000);
        </script>
    @endif
    <script>
        {{--        script for serial--}}
        Cookie.set('device_serial', '{{$serial}}', {
            expires: 365
        });
    </script>
@stop
