<?php
//the scope for this parameter in header.blade.php only
$tempDevice = Auth::user()->device;
$tempSetting = \App\Setting::first();
$permit = \App\Permit::first();
?>
<style>
    #section_notification .tableFixHead {
        overflow-y: auto;
        max-height: 85vh !important;
    }

    #section_notification .tableFixHead thead th {
        position: sticky;
        top: 0;
    }

    #navUl div.dropdown-menu a input[type=checkbox] {
        position: absolute;
        right: 7px;
        margin-top: 9px;
    }

    @media screen and (max-width: 991px) {
        #navUl div.dropdown-menu {
            position: relative;
        }
    }

    #div_hide_in_top_header:hover {
        opacity: 1 !important;
    }
</style>
<header id='topHeader'>
    <div>
        <div class='container-fluid pt-3 text-white head' dir='rtl'>
            <div class='row no-gutters animated slideInDown' style='z-index: 1'>
                <div class='col-12 col-lg-6  text-right pr-2'>
                    <button class="btn bg-transparent p-0 tooltips" data-placement="left"
                            title="قائمة الوصول السريع"
                            id="button_toggle_show_right_side">
                        <i class="fas fa-3x fa-caret-left" style="color:rgba(255, 237, 74,.5);"></i>
                    </button>
                    <a class="py-1 text-success tooltips"
                       data-placement="right" title="الصفحة الرئيسية(Home)"
                       onclick="design.useSound();$('#load').css('display', 'block');"
                       href="{{route('home')}}">
                        <i class="fas fa-2x fa-home"></i>
                    </a>
                    <button class='font-weight-bold bg-transparent border-0 h2 text-white' id="a_header_home_page"
                            data-placement='left'
                            data-html="true"
                            title=' الجهاز الحالي هو {{$tempDevice->name}} {{$tempSetting->show_treasury_value_in_header?('<br/>'.'والمال فى الدرج هو '.round($tempDevice->treasury_value ,2).'ج'):''}}'>
                        <label class='pointer position-relative'>
                            مرحباً بكم فى عالم
                            <span class="text-nowrap tooltips"
                                  title="إعادة التحميل(F5)"
                                  data-placement="bottom"
                                  onclick="design.useSound();$('#load').css('display', 'block');window.location.reload(true);">
                                <small
                                    class="text-nowrap position-absolute text-success font-weight-bold font-en tooltips"
                                    style="display: none;left:-9px;bottom: -4px "
                                    id="version" data-placement="bottom"
                                    title="الإصدار الثانى"><sub>V3.3.1</sub></small>Ultimate Code
                            </span>
                        </label>
                    </button>
                    <div class="d-inline-block" id="div_hide_in_top_header" style="opacity: 0">
                        <input type="range" class='d-inline pointer p-0 tooltips'
                               data-placement="left" title="ضبط مستوى الصوت فى البرنامج" id="input_sound_value"
                               style="top:10px;width: 100px" min='0' value="10" max="10"/>
                    </div>
                </div>
                <div class='col-12 col-lg-5 h4 d-none d-lg-block'>
                    <span>المستخدم الحالي :</span>
                    <div class="<!--dropdown--> d-inline" style='z-index: 1'>
                        <button class="bg-transparent pointer text-white border-0 <!--dropdown-toggle-->"
                                style='outline: 0' type="button" id="dropdownLogout" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            {{Auth()->user()->name}}
                        </button>
                        <button
                            class="pointer p-0 border-0 bg-transparent text-center hvr-icon-wobble-horizontal tooltips"
                            data-placement="right" title="تسجيل الخروج" type="button"
                            onclick='Cookie.remove("succsess_login");$("#logout").submit();'>
                            <i class="fas fa-sign-out-alt pr-2 text-danger hvr-icon"></i>
                        </button>
                        {{--<div class="dropdown-menu" aria-labelledby="dropdownLogout">
                            <button class="dropdown-item pointer text-center hvr-icon-wobble-horizontal" type="button"
                                    onclick='Cookie.remove("succsess_login");$("#logout").submit();'>
                                تسجيل الخروج
                                <i class="fas fa-sign-out-alt pr-2 text-danger hvr-icon"></i>
                            </button>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
        @if (Route::currentRouteAction()=='App\Http\Controllers\HomeController@index')
            <i id="i_show_notification"
               class="fas fa-bell {{count($activities)>0 || count($little_product)>0 ||count($visits)>0?'text-danger':'text-white'}} position-absolute pointer"
               style="z-index: 2;top: 14px;left: 3%;font-size: 28px"></i>
            <section id="section_notification" class="text-center d-none" dir="rtl">
                <div id="div_notification_header_overlay" class="position-fixed pointer"
                     style="z-index:1;left: 0px;top:0px;width: 100vw;height: 100vh;background: rgba(0,0,0,.5)"
                     onclick="$('#section_notification').addClass('d-none');design.useSound();design.updateNiceScroll();"></div>
                <div class="container position-fixed box mx-aut text-center p-0"
                     style="z-index: 2;top:60px;left: 0">
                    <div class='table-responsive'>
                        <div class="tableFixHead">
                            <table id="table_notification" dir="rtl"
                                   class='m-0 text-center table table-hover table-bordered'>
                                <thead class='thead-dark h3'>
                                <tr>
                                    <th>م</th>
                                    <th>تفاصيل الإشعار
                                        <div class="input-group d-inline-block" style="max-width: 100px">
                                            <div class="input-group-prepend bg-transparent">
                                                <input placeholder='بحث'
                                                       data-filter-col="1"
                                                       type='text'
                                                       id="input_th_notification_search"
                                                       class='form-control h0 d-none'>
                                                <span class="input-group-text text-success bg-transparent p-0"
                                                      style="border: none"><i
                                                        onclick="$(this).parent().parent().children().toggleClass('d-none');$('#input_th_notification_search').focus();"
                                                        class="fas fa-2x fa-search mr-2 tooltips"
                                                        data-placement="left"
                                                        title="بحث فى الإشعارت"></i></span>
                                                <span
                                                    class="input-group-text text-danger bg-transparent p-0 d-none"
                                                    style="border: none"><i
                                                        onclick="$(this).parent().parent().children().toggleClass('d-none');$('#input_th_notification_search').val('').trigger('keyup');"
                                                        class="fas fa-2x fa-times mr-2 tooltips"
                                                        data-placement="left"
                                                        title="إلغاء البحث فى الإشعارات"></i></span>
                                            </div>
                                        </div>
                                        <select class="custom-select mr-2 pt-0 tooltips float-left"
                                                id="select_filter_table_header"
                                                data-placement="left" title="نوع الإشعار" style="width: 120px;"
                                                data-filter-col="3">
                                            <option value="" selected>الكل</option>
                                            @if ($activities !='')
                                                <option value="0">نشاطات</option>
                                            @endif
                                            @if ($little_product !='')
                                                <option value="1">نواقص المنتجات</option>
                                                <option value="2">نواقص شراء</option>
                                                <option value="3">نواقص بيع</option>
                                                <option value="4">نواقص إنتاج</option>
                                            @endif
                                            @if ($visits!='')
                                                <option value="5">المهام والزيارات</option>
                                            @endif
                                        </select>
                                        <button class="btn mr-5 bg-transparent p-0 text-success tooltips"
                                                data-placement="bottom" title="طباعة الإشعارات"
                                                onclick="design.useSound();alertify.success('برجاء الإنتظار جارى الطباعة!');
                                                    $('#table_notification i,#table_notification select,#table_notification table td:eq(2),#table_notification table th:eq(2)').addClass('d-none');
                                                    $('#table_notification').parent().printArea({
                                                    extraCss: '<?php echo e(asset('css/print.css')); ?>'
                                                    });
                                                    $('#table_notification i,#table_notification select,#table_notification table td:eq(2),#table_notification table th:eq(2)').removeClass('d-none')">
                                            <i class="fas fa-2x fa-print"></i>
                                        </button>
                                    </th>
                                    <th></th>
                                    <th class="d-none">type</th>
                                </tr>
                                </thead>
                                <tbody class="h4 text-dark">
                                @foreach ($activities as $a)
                                    <tr class="table-success">
                                        <td class="pointer tooltips" data-placement="left"
                                            title="وقت إنشاء الإشعار {{$a->created_at}}"><span>{{$loop->index+1}}</span>
                                        </td>
                                        <td>{{$a->data}}</td>
                                        <td>
                                            <button
                                                class='btn btn-danger p-0 bg-transparent border-0 text-danger tooltips'
                                                data-placement="left" title="تعين كمقروء"
                                                data-deleteOne='{{$a->id}}'><i class="fas fa-2x fa-times"></i>
                                            </button>
                                            <a href='{{route('activity.index',['id'=>$a->id])}}'
                                               onclick="design.useSound();$('#load').css('display', 'block');"
                                               class='btn  p-0 bg-transparent border-0 text-info tooltips'
                                               data-placement="left" title="عرض الإشعار"><i
                                                    class="fas fa-2x fa-eye"></i>
                                            </a>
                                        </td>
                                        <td class="d-none">0</td>
                                    </tr>
                                @endforeach
                                @foreach ($little_product as $l)
                                    <tr class="table-success">
                                        <td>{{$loop->index+1}}</td>
                                        <td class="tooltips" data-placement="left" title="نوع المنتج
                                        ({{$l['allow_buy']?'شراء-':''}} {{$l['allow_sale']?'-بيع-':''}} {{$l['allow_make']?'إنتاج':''}})
                                    -يتم حساب الكميات فى كل المخازن المصرح لهذا الجهاز بالتعامل معها">
                                            المنتج
                                            {{$l['product_name']}}
                                            يتوفر منه
                                            {{round($l['qte_exist'],2)}} {{$l['product_unit']}}
                                            واقل عدد محدد من المنتج
                                            {{round($l['min_qte'],2)}} {{$l['product_unit']}}
                                        </td>
                                        <td>
                                            <a href='{{route('products.edit',['id'=>$l['product_id']])}}'
                                               class='btn  p-0 bg-transparent border-0 text-info tooltips'
                                               data-placement="left"
                                               title="تعديل المنتج (لمنع ظهور المنتج فى النواقص قم بجعل أقل كمية فى المنتج = 0)"><i
                                                    class="fas fa-2x fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="d-none">
                                            1{{$l['allow_buy']?'2':''}}{{$l['allow_sale']?'3':''}}{{$l['allow_make']?'4':''}}</td>
                                    </tr>
                                @endforeach
                                @foreach ($visits as $v)
                                    <tr class="table-success">
                                        <td class="pointer"><span>{{$loop->index+1}}</span>
                                        </td>
                                        <td>
                                            {{$v->type==3?'مهمة ':'زيارة'}}
                                            بتاريخ
                                            {{$v->date_alarm}}
                                            @if ($v->bill_id!=null)
                                                لفاتورة رقم
                                                {{$v->bill_id}}
                                                بإسم
                                                {{\App\Account::findOrFail($v->account_id)->name}}
                                            @endif
                                            بقيمة
                                            {{$v->price}}
                                            ج
                                            بملاحظة
                                            {{$v->note}}</td>
                                        <td>
                                            <a href='{{route('visits.edit',['id'=>$v->id])}}'
                                               onclick="design.useSound();$('#load').css('display', 'block');"
                                               class='btn  p-0 bg-transparent border-0 text-info tooltips'
                                               data-placement="left" title="تعديل"><i
                                                    class="fas fa-2x fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="d-none">5</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <form id='formDeleteNotification' action='{{route('activity.destroy',0)}}' class='d-none' method='post'>
                @csrf
                @method('delete')
            </form>
            <script defer>
                //search in notification
                $('#table_notification').filtable({controlPanel: $('#table_notification thead')});
                $('#table_notification').on('aftertablefilter', function (event) {
                    addAndUpdateIndexInTableNotification();
                    design.useToolTip();
                });
                $('#table_notification').on('click', 'tbody button[data-deleteOne]', function (e) {
                    design.useSound();
                    $('#load').css('display', 'block');
                    var id = $(this).attr('data-deleteOne');
                    var action = $('#formDeleteNotification').attr('action');
                    action = action.replace(/[0-9]$/, id);
                    $('#formDeleteNotification').attr('action', action).submit();
                });
                $('#formDeleteNotification').submit(function (e) {
                    design.check_submit($(this), e);
                });

                //add and update index to table notification
                function addAndUpdateIndexInTableNotification() {
                    var index = $('#table_notification tbody tr:not(.hidden)').length;
                    $('#table_notification tbody tr').each(function () {
                        if (!$(this).hasClass('hidden')) {
                            var td = $(this).children();
                            td.eq(0).html(index);
                            index--;
                        }
                    });
                }

                {{--show notification if exist notification--}}
                $('#i_show_notification').click(function () {
                    //if exist notification
                    if ($('#table_notification tbody tr').length > 0) {
                        $('#section_notification').toggleClass('d-none');
                        design.useSound();
                        design.updateNiceScroll();
                        design.useToolTip();
                    } else {//if no notification
                        alertify.error('لا يوجد إشعارات!');
                        design.useSound('info');
                        design.updateNiceScroll();
                    }
                });

                design.hide_option_not_exist_in_table_in_select($('#select_filter_table_header'),
                    $('#table_notification tbody tr'), 3, false);

            </script>
        @endif
        <nav class="navbar navbar-expand-lg navbar-light bg-light py-0 px-0">
            <button class="navbar-toggler ml-4" type="button"
                    data-toggle="collapse" data-target="#navUl" aria-controls="navUl"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class='h4 pt-2 pr-2 d-inline-block ml-auto text-right d-lg-none' dir='rtl'>
                {{--                <span>المستخدم الحالي :</span>--}}
                <div class="dropdown d-inline" style='z-index: 1'>
                    <button class="bg-transparent  pointer text-dark border-0 dropdown-toggle"
                            style='outline: 0;' type="button" id="dropdownLogout2" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        {{Auth()->user()->name}}
                    </button>
                    <button class="pointer p-0 border-0 bg-transparent text-center hvr-icon-wobble-horizontal tooltips"
                            data-placement="right" title="تسجيل الخروج" type="button"
                            onclick='Cookie.remove("succsess_login");$("#logout").submit();'>
                        <i class="fas fa-sign-out-alt pr-2 text-danger hvr-icon"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownLogout2">
                        <button class="dropdown-item pointer text-center hvr-icon-wobble-horizontal" type="button"
                                onclick='Cookie.remove("succsess_login");$("#logout").submit();'>
                            تسجيل الخروج
                            <i class="fas fa-sign-out-alt pr-2 text-danger hvr-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="collapse navbar-collapse bg-light pointer" id="navUl">
                <ul class="navbar-nav ml-auto text-right position-relative" dir='rtl'>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navnavbarDropdownUsers" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            المستخدمين
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navnavbarDropdownUsers">
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_edit_my_account)
                                <a class="dropdown-item"
                                   href='{{route('users.edit',Auth::user()->id)}}'>
                                    <i class="fas text-info fa-user-edit"></i>
                                    <input type="checkbox" name="header_edit_my_account" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    تعديل حسابك</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1)
                                <a class="dropdown-item"
                                   href='{{route('users.index')}}'>
                                    <i class="fas text-info fa-users"></i>
                                    <input type="checkbox" name="header_manage_users" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>اداره المستخدمين</span></a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item"
                                   href='{{route('users.create')}}'>
                                    <i class="fas text-info fa-user-plus"></i>
                                    <input type="checkbox" name="header_add_user" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    اضافة مستخدم</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_activities)
                                <a class="dropdown-item"
                                   href='{{route('activity.index')}}'>
                                    <i class="fas text-info fa-chart-line"></i>
                                    <input type="checkbox" name="header_manage_activity" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    نشاطات المستخدمين
                                    <span class="font-en text-success">(End)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            <form id='logout' onsubmit="$('#load').css('display', 'block');"
                                  action='{{route('logout')}}' class='d-none' method='post'>
                                @csrf
                                <button type='submit'>logout</button>
                            </form>
                            <a class="dropdown-item" onclick='Cookie.remove("succsess_login");$("#logout").submit();'>
                                <i
                                    class="fas fa-sign-out-alt text-danger"></i>
                                تسجيل الخروج</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navnavbarDropdownTreasury" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            الدرج
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navnavbarDropdownTreasury">
                            @if (Auth::user()->type==1)
                                <a class="dropdown-item"
                                   href='{{route('treasuries.get_add_or_take_money')}}'>
                                    <i class="fas text-dark fa-dollar-sign"></i>
                                    <input type="checkbox" name="header_treasury_add_or_take" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    وضع وأخذ مال من الدرج
                                    <span class="text-success font-en">(F6)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type==1 || Auth::user()->allow_mange_treasury)
                                <a class="dropdown-item"
                                   href='{{route('treasuries.index')}}'>
                                    <i class="fas text-dark fa-money-check-alt"></i>
                                    <input type="checkbox" name="header_treasury_move" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    حركة أخذ ووضع مال فى الدرج</a>
                                <div class="dropdown-divider"></div>
                            @endif
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navnavbarDropdownStoke" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            المخازن
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navnavbarDropdownStoke">
                            @if (Auth::user()->type==1 || Auth::user()->allow_mange_stoke)
                                <a class="dropdown-item {{Hash::check('mange_stoke',$permit->mange_stoke)?'':'not_permits'}}"
                                   href='{{route('stokes.index')}}'>
                                    <i class="fas text-success fa-globe"></i>
                                    <input type="checkbox" name="header_manage_stokes" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>اداره المخازن المتاحة</span></a>
                                <div
                                    class="dropdown-divider {{Hash::check('mange_stoke',$permit->mange_stoke)?'':'not_permits'}}"></div>
                            @endif
                            @if (Auth::user()->type==1 || Auth::user()->allow_mange_place_in_stoke)
                                <a class="dropdown-item {{Hash::check('place_product',$permit->place_product)?'':'not_permits'}}"
                                   href='{{route('stoke_product_places.index')}}'>
                                    <i class="far text-success fa-map"></i>
                                    <input type="checkbox" name="header_manage_product_places" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>أسماء أماكن الحفظ فى المخازن</span></a>
                                <div
                                    class="dropdown-divider {{Hash::check('place_product',$permit->place_product)?'':'not_permits'}}"></div>
                            @endif
                            @if (Auth::user()->type==1 || Auth::user()->allow_mange_product_place_in_stoke)
                                <a class="dropdown-item {{Hash::check('place_product',$permit->place_product)?'':'not_permits'}}"
                                   href='{{route('stoke_product_places.showProductPlace')}}'>
                                    <i class="fas text-success fa-map-marker-alt"></i>
                                    <input type="checkbox" name="header_manage_place_product_in_stoke" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>أماكن المنتجات فى المخازن</span>
                                </a>
                                <div
                                    class="dropdown-divider {{Hash::check('place_product',$permit->place_product)?'':'not_permits'}}"></div>
                            @endif
                            @if (Auth::user()->type==1 || Auth::user()->allow_access_product_in_stoke)
                                <a class="dropdown-item"
                                   href='{{route('stores.index')}}'>
                                    <i class="fas text-success fa-store"></i>
                                    <input type="checkbox" name="header_product_in_stoke" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    المنتجات فى المخازن
                                    <span class="font-en text-success">(F12)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navnavbarDropdownPeople" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            الموردين والعملاء
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navnavbarDropdownPeople">
                            @if (Auth::user()->type ==1 || Auth::user()->allow_access_index_account)
                                <a class="dropdown-item"
                                   href='{{route('accounts.index')}}'>
                                    <i class="fas text-success fa-users"></i>
                                    <input type="checkbox" name="header_manage_account" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>اداره الموردين والعملاء</span>
                                    <span class="font-en text-success">(F9)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_add_account)
                                <a class="dropdown-item"
                                   href='{{route('accounts.create')}}'>
                                    <i class="fas text-success fa-user-plus"></i>
                                    <input type="checkbox" name="header_create_account" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>إضافة مورد أو عميل
                                        <span class="{{Hash::check('sup_cust',$permit->sup_cust)?'':'not_permits'}}">أو مورد عميل</span>
                                    </span></a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_access_report_account)
                                <a class="dropdown-item"
                                   href='{{route('account_calculation.index')}}'>
                                    <i class="fas text-success fa-calculator"></i>
                                    <i class="fas text-success fa-users"></i>
                                    <input type="checkbox" name="header_account_calcluation" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>حسابات الموردين والعملاء
                                         <span class="{{Hash::check('sup_cust',$permit->sup_cust)?'':'not_permits'}}">والموردين العملاء</span></span></a>
                                <div class="dropdown-divider"></div>
                                @if(Hash::check('account_product_move',$permit->account_product_move))
                                    <a class="dropdown-item"
                                       href='{{route('product_moves.account_product_move')}}'>
                                        <i class="fas text-warning fa-location-arrow"></i>
                                        <i class="fas text-success fa-users"></i>
                                        <input type="checkbox" name="header_account_product_move" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        <span>حركة المنتجات الموردين والعملاء
                                         <span class="{{Hash::check('sup_cust',$permit->sup_cust)?'':'not_permits'}}">والموردين العملاء</span></span></a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                <a class="dropdown-item"
                                   href='{{route('accounts.account_bill_with_details')}}'>
                                    <i class="fas text-warning fa-calculator"></i>
                                    <i class="fas text-success fa-users"></i>
                                    <input type="checkbox" name="header_account_bill_with_details" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>تقرير مجمع لفواتير شخص
                                         </span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navnavbarDropdownProduct" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            المنتجات
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navnavbarDropdownProduct">
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_product)
                                <a class="dropdown-item"
                                   href='{{route('products.index')}}'>
                                    <i class="fas text-warning fa-folder-open"></i>
                                    <input type="checkbox" name="header_manage_product" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    اداره المنتجات
                                    <span class="font-en text-success">(F3)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_add_product)
                                <a class="dropdown-item"
                                   href='{{route('products.create')}}'>
                                    <i class="fas text-warning fa-folder-plus"></i>
                                    <input type="checkbox" name="header_create_product" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    اضافة منتج جديد</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_product_category)
                                <a class="dropdown-item"
                                   href='{{route('products_categories.index')}}'>
                                    <i class="fas text-warning fa-city"></i>
                                    <input type="checkbox" name="header_manage_product_categories" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إدارة أقسام المنتجات</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_product_unit)
                                <a class="dropdown-item"
                                   href='{{route('products_units.index')}}'>
                                    <i class="fas text-warning fa-building"></i>
                                    <input type="checkbox" name="header_manage_product_units" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إدارة وحدات المنتجات</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if(Hash::check('use_barcode',$permit->use_barcode))
                                @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_barcode)
                                    <a class="dropdown-item"
                                       href='{{route('barcodes.index')}}'>
                                        <i class="fas text-warning fa-barcode"></i>
                                        <input type="checkbox" name="header_manage_barcode" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        ضبط الباركود</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_access_product_move)
                                <a class="dropdown-item"
                                   href='{{route('product_moves.index')}}'>
                                    <i class="fas text-warning fa-location-arrow"></i>
                                    <input type="checkbox" name="header_product_move" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    حركة المنتجات</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type==1||Auth::user()->allow_access_product_profit)
                                <a class="dropdown-item"
                                   href='{{route('product_moves.show_profit')}}'>
                                    <i class="fas text-primary fa-dollar-sign"></i>
                                    <i class="fas text-warning fa-location-arrow"></i>
                                    <input type="checkbox" name="header_show_product_profit" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>حركة البيع والأرباح للمنتجات</span></a>
                                <div class="dropdown-divider"></div>
                            @endif
                                @if(Hash::check('product_make',$permit->product_make))
                                    @if (Auth::user()->type ==1 ||Auth::user()->allow_add_make)
                                        <a class="dropdown-item"
                                           href='{{route('makings.create')}}'>
                                            <i class="fas text-warning fa-briefcase"></i>
                                            <i class="fas text-success fa-flask"></i>
                                            <input type="checkbox" name="header_create_make" class="tooltips"
                                                   style="transform: scale(1.5);margin-left: 10px;display: none"
                                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                                            إضافة عملية إنتاج أو عرض
                                            <span class="font-en text-success">(F10)</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_make)
                                        <a class="dropdown-item"
                                           href='{{route('makings.index')}}'>
                                            <i class="fas text-warning fa-flask"></i>
                                            <i class="fas text-success fa-tasks"></i>
                                            <input type="checkbox" name="header_manage_make" class="tooltips"
                                                   style="transform: scale(1.5);margin-left: 10px;display: none"
                                                   title="فتح فى نافذة جديدة" data-placement="bottom">
                                            إدارة عمليات الإنتاج</a>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                @endif

                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="DropdownBills" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            الفواتير
                        </a>
                        <div class="dropdown-menu" aria-labelledby="DropdownBills">
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_buy)
                                <a class="dropdown-item"
                                   href='{{route('bills.create',0)}}'>
                                    <i class="fas text-success fa-cart-arrow-down"></i>
                                    <input type="checkbox" name="header_create_bill_buy" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إنشاء فاتورة شراء
                                    <span class="font-en text-success">(F1)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_bill_buy)
                                <a class="dropdown-item"
                                   href='{{route('bills.index',0)}}'>
                                    <i class="fas text-warning fa-ellipsis-v"></i>
                                    {{--                                    <i class="fas text-success fa-tasks"></i>--}}
                                    <i class="fas text-success fa-dolly"></i>
                                    <input type="checkbox" name="header_manage_bill_buy" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إدارة فواتير الشراء
                                    <span class="font-en text-success">(F7)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale)
                                <a class="dropdown-item"
                                   href='{{route('bills.create',1)}}'>
                                    <i class="fas text-success fa-cart-plus"></i>
                                    <input type="checkbox" name="header_create_bill_sale" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إنشاء فاتورة بيع
                                    <span class="font-en text-success">(*)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_bill_sale)
                                <a class="dropdown-item"
                                   href='{{route('bills.index',1)}}'>
                                    <i class="fas text-warning  fa-ellipsis-v"></i>
                                    <i class="fas text-success fa-luggage-cart"></i>
                                    <input type="checkbox" name="header_manage_bill_sale" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إدارة فواتير البيع
                                    <span class="font-en text-success">(F8)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_manage_bill_sale_with_profit)
                                <a class="dropdown-item"
                                   href='{{route('bills.index',1)}}?show_profit=true'>
                                    <i class="fas text-warning fa-funnel-dollar"></i>
                                    <i class="fas text-success fa-tasks"></i>
                                    {{--                                    <i class="fas text-success fa-luggage-cart"></i>--}}
                                    <input type="checkbox" name="header_manage_bill_sale_with_profit" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إدارة فواتير البيع مع أرباح
                                    الفواتير</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 || (Auth::user()->allow_manage_bill_sale)&& Auth::user()->allow_manage_bill_buy)
                                <a class="dropdown-item"
                                   href='{{route('bills.index',2)}}'>
                                    <i class="fas text-warning fa-ellipsis-v"></i>
                                    <i class="fas text-success fa-tasks"></i>
                                    <i class="fas text-success fa-cart-arrow-down"></i>
                                    <i class="fas text-success fa-cart-plus"></i>
                                    <input type="checkbox" name="header_manage_bill_sale_with_profit" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إدارة فواتير شراء وبيع
                                    <span class="font-en text-success">(Insert)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale_show)
                                <a class="dropdown-item"
                                   href='{{route('bills.create',2)}}'>
                                    <i class="fas text-success fa-shopping-basket"></i>
                                    <input type="checkbox" name="header_create_bill_show" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إنشاء عرض أسعار بيع
                                    <span class="font-en text-success">(F2)</span>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_bill_message)
                                <a class="dropdown-item"
                                   href='{{route('bill_messages.index')}}'>
                                    <i class="fas text-success fa-comment-dots"></i>
                                    <input type="checkbox" name="header_manage_bill_message" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    إدارة رسائل الفواتير</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if(Hash::check('bill_design',$permit->bill_design))
                                @if (Auth::user()->type ==1 ||Auth::user()->allow_mange_print_setting)
                                    <a class="dropdown-item"
                                       href='{{route('bill_prints.index')}}'>
                                        <i class="fas text-success fa-print"></i>
                                        <input type="checkbox" name="header_manage_print_bill_design" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        تصميم الفاتورة</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                            @endif
                        </div>
                    </li>
                    @if(Hash::check('use_expenses',$permit->use_expenses))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="DropdownEXPENSES" role="button"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                المصروفات
                            </a>
                            <div class="dropdown-menu" aria-labelledby="DropdownEXPENSES">
                                @if (Auth::user()->type==1 ||Auth::user()->allow_mange_expenses)
                                    <a class="dropdown-item"
                                       href='{{route('expenses.index')}}'>
                                        <i class="far text-info fa-chart-bar"></i>
                                        <input type="checkbox" name="header_report_expenses" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        اداره وتقارير المصروفات</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1 ||Auth::user()->allow_mange_expenses_type)
                                    <a class="dropdown-item"
                                       href='{{route('expenses_types.index')}}'>
                                        <i class="fas text-info fa-cubes"></i>
                                        <input type="checkbox" name="header_manage_expenses_type" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        أقسام المصروفات</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1 ||Auth::user()->allow_add_expenses_and_expenses_type)
                                    <a class="dropdown-item"
                                       href='{{route('expenses.create')}}'>
                                        <i class="fas text-info fa-plus-circle"></i>
                                        <input type="checkbox" name="header_create_expenses" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        اضافة مصروفات
                                        <span class="text-success font-en">(F4)</span>
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endif
                    @if(Hash::check('use_emp',$permit->use_emp))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="DropdownEmps" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                                الموظفين
                            </a>
                            <div class="dropdown-menu" aria-labelledby="DropdownEmps">
                                @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp_jops)
                                    <a class="dropdown-item"
                                       href='{{route('emp_jops.index')}}'>
                                        <i class="fas text-info fa-cubes"></i>
                                        <input type="checkbox" name="header_emp_jops_index" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        إدارة الوظائق</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1 ||Auth::user()->allow_add_emp)
                                    <a class="dropdown-item"
                                       href='{{route('emps.create')}}'>
                                        <i class="fas text-info fa-user-plus"></i>
                                        <input type="checkbox" name="header_create_emp" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        إضافة موظف أو عامل</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp)
                                    <a class="dropdown-item"
                                       href='{{route('emps.index')}}'>
                                        <i class="fas text-info fa-users"></i>
                                        <input type="checkbox" name="header_create_manage" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        إدارة الموظفين</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp_operation)
                                    <a class="dropdown-item"
                                       href='{{route('emps.index')}}?show_opertaion=true'>
                                        <i class="fas text-info fa-bible"></i>
                                        <input type="checkbox" name="header_create_opertaion" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        عمليات الموظفين
                                        <span class="font-en text-success">(PgDn)</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp_move)
                                    <a class="dropdown-item"
                                       href='{{route('emps.report')}}'>
                                        <i class="fas text-info fa-calculator"></i>
                                        <i class="fas text-success fa-users"></i>
                                        <input type="checkbox" name="header_emp_report" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        الحسابات والحركة التفصيلية للموظفين</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                       href='{{route('emps.report2')}}'>
                                        <i class="fas text-info  fa-users"></i>
                                        <i class="fab text-success fa-connectdevelop"></i>
                                        <input type="checkbox" name="header_emp_report2" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        تقارير إجمالية للموظفين</a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp_attend)
                                    <a class="dropdown-item"
                                       href='{{route('emps.show_emp_attend')}}'>
                                        <i class="fas text-info fa-book"></i>
                                        <i class="fas text-success fa-users"></i>
                                        <input type="checkbox" name="header_emps.show_emp_attend" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        إدارة الحضور
                                        <span class="font-en text-success">(PgUp)</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @endif
                            </div>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navnavbarDropdownMore" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            المزيد
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navnavbarDropdownMore">
                            @if (Auth::user()->type==1||Auth::user()->allow_mange_setting)
                                <a class="dropdown-item"
                                   href='{{route('settings.index')}}'>
                                    <i class="fas text-primary fa-cogs"></i>
                                    <input type="checkbox" name="header_manage_setting" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>ضبط خصائص البرنامج</span></a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type==1||Auth::user()->allow_mange_device)
                                <a class="dropdown-item"
                                   href='{{route('devices.index')}}'>
                                    <i class="fas text-primary fa-wrench"></i>
                                    <input type="checkbox" name="header_manage_device" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>ضبط الأجهزة المتصلة</span></a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if(Hash::check('use_visit',$permit->use_visit))
                                @if (Auth::user()->type==1||Auth::user()->allow_add_visit)
                                    <a class="dropdown-item"
                                       href='{{route('visits.create')}}'>
                                        <i class="text-warning fas fa-notes-medical"></i>
                                        <input type="checkbox" name="header_index_visit" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        <span>إضافة مهمة</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1||Auth::user()->allow_manage_visit)
                                    <a class="dropdown-item"
                                       href='{{route('visits.index')}}'>
                                        <i class="text-warning fas fa-clipboard"></i>
                                        <input type="checkbox" name="header_index_visit" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        <span>إدارة المهام والزيارات</span></a>
                                    <div class="dropdown-divider"></div>
                                @endif
                            @endif
                            @if(Hash::check('use_exit_deal',$permit->use_exit_deal))
                                @if (Auth::user()->type==1||Auth::user()->allow_create_exit_deal)
                                    <a class="dropdown-item"
                                       href='{{route('exist_deals.create')}}'>
                                        <i class="fas text-primary fa-dollar-sign"></i>
                                        <i class="fas text-warning fa-door-open"></i>
                                        <input type="checkbox" name="header_add_exist_deal" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        <span>إضافة أرباح أو خسائر خارجية</span></a>
                                    <div class="dropdown-divider"></div>
                                @endif
                                @if (Auth::user()->type==1||Auth::user()->allow_manage_exit_deal)
                                    <a class="dropdown-item"
                                       href='{{route('exist_deals.index')}}'>
                                        <i class="fas text-primary fa-dollar-sign"></i>
                                        <i class="fas text-primary fa-tasks"></i>
                                        <i class="fas text-warning fa-door-open"></i>
                                        <input type="checkbox" name="header_manage_exist_deal" class="tooltips"
                                               style="transform: scale(1.5);margin-left: 10px;display: none"
                                               title="فتح فى نافذة جديدة" data-placement="bottom">
                                        <span>إدارة الأرباح و الخسائر خارجية</span></a>
                                    <div class="dropdown-divider"></div>
                                @endif
                            @endif
                            @if (Auth::user()->type==1||Auth::user()->allow_access_total_report)
                                <a class="dropdown-item"
                                   href='{{route('users.report')}}'>
                                    <i class="fas text-primary fa-pencil-ruler"></i>
                                    <i class="fas text-warning fa-paper-plane"></i>
                                    <i class="fas text-warning fa-chart-pie"></i>
                                    {{--                                    <i class="fas text-warning fa-chart-line"></i>--}}
                                    <input type="checkbox" name="header_program_reprot" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    <span>تقرير شامل </span>
                                    <span class="font-en text-success">(F11)</span>
                                </a>
                            @endif
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="DropdownBACKUP" role="button" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            نسخ احتياطي
                        </a>
                        <div class="dropdown-menu dropdown-menu-left" aria-labelledby="DropdownBACKUP">
                            @if (Auth::user()->type==1||Auth::user()->allow_mange_backup)
                                <a class="dropdown-item"
                                   href='{{route('backups.index')}}'>
                                    <i class="fas text-success fa-wrench"></i>
                                    <input type="checkbox" name="header_manage_backup" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    ضبط النسخ الاحتياطي</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            @if (Auth::user()->type==1||Auth::user()->allow_download_backup)
                                <a href='{{route('backups.downloadBackup')}}'
                                   id="link_download_backup"
                                   onclick="$('#load').fadeIn(500).fadeOut(300)"
                                   class="dropdown-item">
                                    <i class="fas text-success fa-download"></i>
                                    <input type="checkbox" name="header_download_backup" class="tooltips"
                                           style="transform: scale(1.5);margin-left: 10px;display: none"
                                           title="فتح فى نافذة جديدة" data-placement="bottom">
                                    تحميل نسخة احتياطية من قاعدة البيانات</a>
                                <div class="dropdown-divider"></div>
                            @endif
                            {{--<a href='{{route('backups.createBackup','all')}}'
                               onclick="$('#load').css('display','block');"
                               class="dropdown-item">انشاء نسخة احتياطية لكل المسارات</a>
                            <div class="dropdown-divider"></div>--}}
                            <a href='{{route('backups.createBackup',0)}}' onclick="$('#load').css('display','block');"
                               class="dropdown-item">
                                <i class="fas text-success fa-copy"></i>
                                <input type="checkbox" name="header_create_backup_db" class="tooltips"
                                       style="transform: scale(1.5);margin-left: 10px;display: none"
                                       title="فتح فى نافذة جديدة" data-placement="bottom">
                                انشاء نسخة احتياطية </a>
                            {{--<div class="dropdown-divider"></div>
                            <a href='{{route('backups.createBackup',1)}}' onclick="$('#load').css('display','block');"
                               class="dropdown-item">انشاء نسخة احتياطية للملفات</a>--}}
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<aside id="asidContainerIconRight" style="width: 50px" dir="rtl">
    <div style="background:rgba(0,0,0,0.4);height: 100vh;position: relative;right: 0px;">
        <div style="height: 100vh" id="div_container_icon_right">
            <a class="py-1 tooltips" onclick="design.useSound();$('#load').css('display', 'block');"
               data-placement="right" title="الصفحة الرئيسية(Home)"
               href="{{route('home')}}">
                <i class="fas fa-2x fa-home"></i>
            </a>
            <a class="py-1 tooltips"
               onclick="design.useSound();design.toggleFullscreen();"
               data-placement="right" title="شاشة كاملة (كليك يمين)">
                <i class="fas fa-2x fa-compress"></i>
            </a>
            <a class="py-1 tooltips"
               onclick="design.useSound();$('#load').css('display', 'block');window.location.reload(true);"
               data-placement="right" title="إعادة التحميل!(F5)"
               href="">
                <i class="fas fa-2x fa-sync"></i>
            </a>
            @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale)
                <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right" title="بيع(*)"
                   target="_blank"
                   href="{{route('bills.create',1)}}">
                    <i class="fas fa-2x fa-cart-plus"></i>
                </a>
            @endif
            @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale_show)
                <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right" title="شراء(F1)"
                   target="_blank"
                   href="{{route('bills.create',0)}}">
                    <i class="fas fa-2x fa-cart-arrow-down"></i>
                </a>
            @endif
            @if (Auth::user()->type ==1 ||Auth::user()->allow_create_bill_sale_show)
                <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right" title="عرض أسعار(F2)"
                   target="_blank"
                   href="{{route('bills.create',2)}}">
                    <i class="fas fa-2x fa-shopping-basket"></i>
                </a>
            @endif
            @if (Auth::user()->type ==1 ||(Auth::user()->allow_manage_bill_sale && Auth::user()->allow_manage_bill_buy))
                <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right"
                   title="إدارة الفواتير شراء , بيع(Insert)"
                   target="_blank"
                   href="{{route('bills.index',2)}}">
                    <i class="fas fa-2x fa-tasks"></i>
                </a>
            @endif
            @if(Hash::check('use_expenses',$permit->use_expenses))
                @if (Auth::user()->type==1 ||Auth::user()->allow_add_expenses_and_expenses_type)
                    <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right"
                       title="إضافة مصروفات (F4)"
                       target="_blank"
                       href="{{route('expenses.create',0)}}">
                        <i class="fas fa-2x fa-plus-circle"></i>
                    </a>
                @endif
            @endif
            @if(Hash::check('product_make',$permit->product_make))
                @if (Auth::user()->type==1 ||Auth::user()->allow_add_make)
                    <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right"
                       title="إضافة إنتاج أو عرض (F10)"
                       target="_blank"
                       href="{{route('makings.create',1)}}">
                        <i class="fas fa-2x fa-flask"></i>
                    </a>
                @endif
            @endif
            @if(Hash::check('use_visit',$permit->use_visit))
                @if (Auth::user()->type==1||Auth::user()->allow_add_visit)
                    <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right" title="إضافة مهمة"
                       target="_blank"
                       href="{{route('visits.create',0)}}">
                        <i class="text-white fa-2x fas fa-notes-medical"></i>
                    </a>
                @endif
            @endif
            @if(Hash::check('use_emp',$permit->use_emp))
                @if (Auth::user()->type==1 ||Auth::user()->allow_manage_emp_attend)
                    <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right"
                       title="إدارة الحضور(PgUp)"
                       target="_blank"
                       href="{{route('emps.show_emp_attend',0)}}">
                        <i class="fas fa-2x fa-users"></i>
                    </a>
                @endif
            @endif
            @if (Auth::user()->type==1 ||Auth::user()->allow_access_product_in_stoke)
                <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right" title="المخزن(F12)"
                   target="_blank"
                   href="{{route('stores.index')}}">
                    <i class="fas fa-2x fa-store"></i>
                </a>
            @endif
            <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right" title="الدرج(F6)"
               target="_blank"
               href="{{route('treasuries.get_add_or_take_money')}}">
                <i class="fas fa-2x fa-dollar-sign"></i>
            </a>
            @if (Auth::user()->type==1 ||Auth::user()->allow_manage_activities)
                <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right"
                   title="نشاطات المستخدمين(End)"
                   target="_blank"
                   href="{{route('activity.index')}}">
                    <i class="fas fa-2x fa-chart-line"></i>
                </a>
            @endif
            @if (Auth::user()->type==1 ||Auth::user()->allow_access_total_report)
                <a class="py-1 tooltips" onclick="design.useSound();" data-placement="right"
                   title="تقرير شامل بالأرباح والمصروفات... (F11)"
                   target="_blank"
                   href="{{route('users.report')}}">
                    <i class="fas fa-2x fa-chart-pie"></i>
                </a>
            @endif
        </div>
    </div>
</aside>


<script defer>
    $('#asidContainerIconRight').niceScroll({
        cursorcolor: "#08526D",
        cursorwidth: "8px",
        cursorminheight: 100,
        cursorborder: "1px solid #08526D"

    });
    //remove empty links in header
    $('#navUl ul li').each(function () {
        if ($(this).find('div.dropdown-menu').children().length == 0) {
            $(this).remove();
        }
    });

    //hide and show checkbox open in new tab
    $('#navUl div.dropdown-menu a').hover(function () {
        $(this).find('input:checkbox').css('display', 'inline');
    }, function () {
        $(this).find('input:checkbox').css('display', 'none');
    });


    //set default value for sound
    if (Cookie.get('sound_value') != '') {
        $('#input_sound_value').val(Cookie.get('sound_value'));
    }

    $('#input_sound_value').change(function () {
        Cookie.set('sound_value', $(this).val(), {expires: 365});
        design.useSound();
        alertify.success('تم ضبط مستوى الصوت !');
    });


    //set default value for right link  0=>hide ,1 => active, 2 =>not active
    var default_value_for_right_link = Cookie.get('right_link');
    if (default_value_for_right_link == '0') {
        $('#asidContainerIconRight,#button_toggle_show_right_side').removeClass('active').addClass('d-none');
        $('body').removeClass('active-right-link');
    } else if (default_value_for_right_link == '1') {
        $('body').addClass('active-right-link');
        $('#asidContainerIconRight,#button_toggle_show_right_side').addClass('active');
    } else /*if (default_value_for_right_link == '2')*/ {
        $('body').removeClass('active-right-link');
        $('#asidContainerIconRight,#button_toggle_show_right_side').removeClass('active');
    }


    //set default value for check box in fast link
    $('#navUl div.dropdown-menu a input:checkbox').each(function () {
        var name = $(this).attr('name');
        if (Cookie.get(name) != null) {
            $(this).prop('checked', true);
        } else {
            $(this).prop('checked', false);
        }
    });

    //open links in container_fast_link in new tab or this tab
    $('#navUl div.dropdown-menu a input:checkbox').change(function () {
        if ($(this).prop('checked')) {
            $(this).parent('a').attr('target', '_blank');
            Cookie.set($(this).attr('name'), $(this).prop('checked'), {expires: 365});
        } else {
            $(this).parent('a').attr('target', '_parent');
            Cookie.remove($(this).attr('name'));
        }
        design.useSound();
    });
    $('#navUl div.dropdown-menu a').click(function () {
        design.useSound();
        if ($(this).find('input').prop('checked')) {
            $(this).attr('target', '_blank');
        } else {
            $('#load').css('display', 'block');
            $(this).attr('target', '_parent');
        }
    });
</script>
<script defer>
    $('#a_header_home_page').tooltip({
        container: 'body',
    });

    @if($tempSetting->show_treasury_value_in_header)
    $('#a_header_home_page').hover(function () {
        var newText = ' الجهاز الحالي هو ' + '{{$tempDevice->name}}';
        $('#a_header_home_page').attr('data-original-title', newText);
        $('#a_header_home_page').tooltip('hide').tooltip('show');

        design.useToolTip();
        $.ajax({
            url: '{{route('users.getData')}}',
            method: 'POST',
            data: {
                type: 'getDevice',
            },
            success: function (data) {
                if ($('#a_header_home_page').is(":hover")) {
                    var newText = ' الجهاز الحالي هو ' + '{{$tempDevice->name}}';
                    newText += '</br>';
                    newText += 'والمال فى الدرج هو ';
                    newText += roundTo(data['treasury_value']) + 'ج';
                    $('#a_header_home_page').attr('data-original-title', newText);
                    $('#a_header_home_page').tooltip('hide').tooltip('show');
                } else {
                    $('#a_header_home_page').tooltip('hide');
                }
            },
            error: function (e) {
                $('#load').css('display', 'block');
                window.location.reload(true);
            }
        });
    }, function () {
        $('#a_header_home_page').tooltip('hide');
    });
    @endif


    $('#button_toggle_show_right_side').click(function () {
        $('#asidContainerIconRight,#button_toggle_show_right_side').toggleClass('active');
        $('body').toggleClass('active-right-link');
        Cookie.set('right_link', ($('body').hasClass('active-right-link') ? 1 : 2), {expires: 365});
        design.useSound();
        design.updateNiceScroll();
    });
</script>

