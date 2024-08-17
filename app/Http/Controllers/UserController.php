<?php

namespace App\Http\Controllers;

use App\Account;
use App\Activity;
use App\Bill;
use App\BillBack;
use App\Device;
use App\DeviceStoke;
use App\Emp;
use App\EmpMove;
use App\ExistDeal;
use App\Expense;
use App\Product;
use App\ProductMove;
use App\Store;
use App\Treasury;
use App\User;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('users.index', ['users' => User::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
//        return view('users.create');
        return view('users.create_edit_show');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate(['name' => 'required|max:50|unique:users,name',
            'email' => 'required|min:3|max:50|unique:users,email',
            'password' => 'required|min:3|max:20',
            'type' => 'required|regex:/^[1-2]$/',

            'log_out_security' => 'boolean',

            'allow_edit_my_account' => 'boolean',
            'allow_edit_my_account_name' => 'boolean',
            'allow_edit_account_email' => 'boolean',
            'allow_change_background' => 'boolean',
            'allow_manage_activities' => 'boolean',

            //accounts
            'allow_add_account' => 'boolean',
            'create_notification_when_add_account_with_old_account' => 'boolean',
            'allow_access_index_account' => 'boolean',
            'allow_edit_account' => 'boolean',
            'allow_edit_account_type' => 'boolean',
            'allow_edit_account_name' => 'boolean',
            'allow_edit_account_tel' => 'boolean',
            'allow_delete_account' => 'boolean',
            'allow_adjust_account' => 'boolean',
            'create_notification_when_adjust_account' => 'boolean',
            'allow_access_report_account' => 'boolean',
            'allow_delete_account_buy_take_money' => 'boolean',
            'notification_when_delete_account_buy_take_money' => 'boolean',

            //stoke
            'allow_mange_stoke' => 'boolean',
            'allow_mange_place_in_stoke' => 'boolean',
            'allow_mange_product_place_in_stoke' => 'boolean',

            //damage and store and product move
            'allow_access_product_in_stoke' => 'boolean',
            'allow_add_damage' => 'boolean',
            'notification_when_add_damage' => 'boolean',
            'allow_access_product_move' => 'boolean',
            'allow_delete_damage' => 'boolean',
            'notification_when_delete_damage' => 'boolean',
            'allow_access_product_profit' => 'boolean',

            'allow_access_product_in_all_stoke' => 'boolean',
            'allow_move_product_in_stoke' => 'boolean',
            'notification_when_move_product' => 'boolean',


            /*backups*/
            'allow_mange_backup' => 'boolean',
            'allow_download_backup' => 'boolean',

            //treasury
            'allow_mange_treasury' => 'boolean',
            'allow_delete_treasury' => 'boolean',
            'create_notification_when_delete_treasury' => 'boolean',


            //other
            'allow_mange_setting' => 'boolean',
            'allow_mange_device' => 'boolean',
            'allow_access_total_report' => 'boolean',

            //product
            'allow_add_product' => 'boolean',
            'allow_manage_product' => 'boolean',
            'allow_mange_barcode' => 'boolean',
            'allow_mange_product_category' => 'boolean',
            'allow_mange_product_unit' => 'boolean',


            //expenses
            'allow_add_expenses_and_expenses_type' => 'boolean',
            'allow_mange_expenses_type' => 'boolean',
            'allow_mange_expenses' => 'boolean',
            'allow_delete_expenses' => 'boolean',
            'allow_add_expenses_with_out_subtract_form_treasury' => 'boolean',
            'notification_when_add_expenses_with_out_subtract_form_treasury' => 'boolean',
            'notification_when_delete_expenses' => 'boolean',

            //bill
            'allow_mange_print_setting' => 'boolean',
            'allow_mange_bill_message' => 'boolean',
            'allow_create_bill_buy' => 'boolean',
            'allow_create_bill_sale_show' => 'boolean',
            'allow_manage_bill_buy' => 'boolean',
            'allow_edit_bill_buy' => 'boolean',
            'allow_delete_bill_buy' => 'boolean',
            'notification_delete_bill_buy' => 'boolean',
            'allow_create_bill_sale' => 'boolean',
            'allow_manage_bill_sale' => 'boolean',
            'allow_manage_bill_sale_with_profit' => 'boolean',
            'allow_edit_bill_sale' => 'boolean',
            'allow_delete_bill_sale' => 'boolean',
            'notification_delete_bill_sale' => 'boolean',

            //making
            'allow_add_make' => 'boolean',
            'allow_manage_make' => 'boolean',
            'allow_delete_make' => 'boolean',
            'notification_delete_make' => 'boolean',

            //emps
            'allow_add_emp' => 'boolean',
            'allow_manage_emp_jops' => 'boolean',
            'allow_manage_emp' => 'boolean',
            'allow_manage_emp_operation' => 'boolean',
            'allow_manage_emp_move' => 'boolean',
            'allow_manage_emp_attend' => 'boolean',


            //exit deal
            'allow_create_exit_deal' => 'boolean',
            'allow_manage_exit_deal' => 'boolean',
            'allow_delete_exit_deal' => 'boolean',
            'notification_when_delete_exit_deal' => 'boolean',

            //visits and mission
            'allow_add_visit' => 'boolean',
            'allow_manage_visit' => 'boolean',
            'allow_delete_visit' => 'boolean',
            'notification_when_delete_visit' => 'boolean',
            'show_notification_visit' => 'boolean',


        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->type = $request->type;
        if ($user->type != 1) {
            $user->log_out_security = isset($request->log_out_security) ? 1 : 0;

            //users
            $user->allow_edit_my_account = isset($request->allow_edit_my_account) ? 1 : 0;
            $user->allow_edit_my_account_name = isset($request->allow_edit_my_account_name) ? 1 : 0;
            $user->allow_edit_account_email = isset($request->allow_edit_account_email) ? 1 : 0;
            $user->allow_change_background = isset($request->allow_change_background) ? 1 : 0;
            $user->allow_manage_activities = isset($request->allow_manage_activities) ? 1 : 0;

            //accounts
            $user->allow_add_account = isset($request->allow_add_account) ? 1 : 0;
            $user->create_notification_when_add_account_with_old_account = isset($request->create_notification_when_add_account_with_old_account) ? 1 : 0;
            $user->allow_access_index_account = isset($request->allow_access_index_account) ? 1 : 0;
            $user->allow_edit_account = isset($request->allow_edit_account) ? 1 : 0;
            $user->allow_edit_account_type = isset($request->allow_edit_account_type) ? 1 : 0;
            $user->allow_edit_account_name = isset($request->allow_edit_account_name) ? 1 : 0;
            $user->allow_edit_account_tel = isset($request->allow_edit_account_tel) ? 1 : 0;
            $user->allow_delete_account = isset($request->allow_delete_account) ? 1 : 0;
            $user->allow_adjust_account = isset($request->allow_adjust_account) ? 1 : 0;
            $user->create_notification_when_adjust_account = isset($request->create_notification_when_adjust_account) ? 1 : 0;
            $user->allow_access_report_account = isset($request->allow_access_report_account) ? 1 : 0;
            $user->allow_delete_account_buy_take_money = isset($request->allow_delete_account_buy_take_money) ? 1 : 0;
            $user->notification_when_delete_account_buy_take_money = isset($request->notification_when_delete_account_buy_take_money) ? 1 : 0;

            //stoke
            $user->allow_mange_stoke = isset($request->allow_mange_stoke) ? 1 : 0;
            $user->allow_mange_place_in_stoke = isset($request->allow_mange_place_in_stoke) ? 1 : 0;
            $user->allow_mange_product_place_in_stoke = isset($request->allow_mange_product_place_in_stoke) ? 1 : 0;

            //damage and store and product move
            $user->allow_access_product_in_stoke = isset($request->allow_access_product_in_stoke) ? 1 : 0;
            $user->allow_add_damage = isset($request->allow_add_damage) ? 1 : 0;
            $user->notification_when_add_damage = isset($request->notification_when_add_damage) ? 1 : 0;
            $user->allow_access_product_move = isset($request->allow_access_product_move) ? 1 : 0;
            $user->allow_delete_damage = isset($request->allow_delete_damage) ? 1 : 0;
            $user->notification_when_delete_damage = isset($request->notification_when_delete_damage) ? 1 : 0;
            $user->allow_access_product_profit = isset($request->allow_access_product_profit) ? 1 : 0;
            $user->allow_move_product_in_stoke = isset($request->allow_move_product_in_stoke) ? 1 : 0;
            $user->notification_when_move_product = isset($request->notification_when_move_product) ? 1 : 0;

//            backups
            $user->allow_mange_backup = isset($request->allow_mange_backup) ? 1 : 0;
            $user->allow_download_backup = isset($request->allow_download_backup) ? 1 : 0;

            //treasury
            $user->allow_mange_treasury = isset($request->allow_mange_treasury) ? 1 : 0;
            $user->allow_delete_treasury = isset($request->allow_delete_treasury) ? 1 : 0;
            $user->create_notification_when_delete_treasury = isset($request->create_notification_when_delete_treasury) ? 1 : 0;

            //other
            $user->allow_mange_setting = isset($request->allow_mange_setting) ? 1 : 0;
            $user->allow_mange_device = isset($request->allow_mange_device) ? 1 : 0;
            $user->allow_access_total_report = isset($request->allow_access_total_report) ? 1 : 0;

            //expenses
            $user->allow_add_expenses_and_expenses_type = isset($request->allow_add_expenses_and_expenses_type) ? 1 : 0;
            $user->allow_mange_expenses_type = isset($request->allow_mange_expenses_type) ? 1 : 0;
            $user->allow_mange_expenses = isset($request->allow_mange_expenses) ? 1 : 0;
            $user->allow_delete_expenses = isset($request->allow_delete_expenses) ? 1 : 0;
            $user->allow_add_expenses_with_out_subtract_form_treasury = isset($request->allow_add_expenses_with_out_subtract_form_treasury) ? 1 : 0;
            $user->notification_when_add_expenses_with_out_subtract_form_treasury = isset($request->notification_when_add_expenses_with_out_subtract_form_treasury) ? 1 : 0;
            $user->notification_when_delete_expenses = isset($request->notification_when_delete_expenses) ? 1 : 0;

            //products
            $user->allow_add_product = isset($request->allow_add_product) ? 1 : 0;
            $user->allow_manage_product = isset($request->allow_manage_product) ? 1 : 0;
            $user->allow_mange_barcode = isset($request->allow_mange_barcode) ? 1 : 0;
            $user->allow_mange_product_category = isset($request->allow_mange_barcode) ? 1 : 0;
            $user->allow_mange_product_unit = isset($request->allow_mange_barcode) ? 1 : 0;

            //bill
            $user->allow_mange_print_setting = isset($request->allow_mange_print_setting) ? 1 : 0;
            $user->allow_mange_bill_message = isset($request->allow_mange_bill_message) ? 1 : 0;
            $user->allow_create_bill_buy = isset($request->allow_create_bill_buy) ? 1 : 0;
            $user->allow_create_bill_sale_show = isset($request->allow_create_bill_sale_show) ? 1 : 0;
            $user->allow_manage_bill_buy = isset($request->allow_manage_bill_buy) ? 1 : 0;
            $user->allow_edit_bill_buy = isset($request->allow_edit_bill_buy) ? 1 : 0;
            $user->allow_delete_bill_buy = isset($request->allow_delete_bill_buy) ? 1 : 0;
            $user->notification_delete_bill_buy = isset($request->notification_delete_bill_buy) ? 1 : 0;
            $user->allow_create_bill_sale = isset($request->allow_create_bill_sale) ? 1 : 0;
            $user->allow_manage_bill_sale = isset($request->allow_manage_bill_sale) ? 1 : 0;
            $user->allow_manage_bill_sale_with_profit = isset($request->allow_manage_bill_sale_with_profit) ? 1 : 0;
            $user->allow_edit_bill_sale = isset($request->allow_edit_bill_sale) ? 1 : 0;
            $user->allow_delete_bill_sale = isset($request->allow_delete_bill_sale) ? 1 : 0;
            $user->notification_delete_bill_sale = isset($request->notification_delete_bill_sale) ? 1 : 0;


            //making
            $user->allow_add_make = isset($request->allow_add_make) ? 1 : 0;
            $user->allow_manage_make = isset($request->allow_manage_make) ? 1 : 0;
            $user->allow_delete_make = isset($request->allow_delete_make) ? 1 : 0;
            $user->notification_delete_make = isset($request->notification_delete_make) ? 1 : 0;


            //emps
            $user->allow_add_emp = isset($request->allow_add_emp) ? 1 : 0;
            $user->allow_manage_emp_jops = isset($request->allow_manage_emp_jops) ? 1 : 0;
            $user->allow_manage_emp = isset($request->allow_manage_emp) ? 1 : 0;
            $user->allow_manage_emp_operation = isset($request->allow_manage_emp_operation) ? 1 : 0;
            $user->allow_manage_emp_move = isset($request->allow_manage_emp_move) ? 1 : 0;
            $user->allow_manage_emp_attend = isset($request->allow_manage_emp_attend) ? 1 : 0;

            //exit deal
            $user->allow_create_exit_deal = isset($request->allow_create_exit_deal) ? 1 : 0;
            $user->allow_manage_exit_deal = isset($request->allow_manage_exit_deal) ? 1 : 0;
            $user->allow_delete_exit_deal = isset($request->allow_delete_exit_deal) ? 1 : 0;
            $user->notification_when_delete_exit_deal = isset($request->notification_when_delete_exit_deal) ? 1 : 0;


            //visits and mission
            $user->allow_add_visit = isset($request->allow_add_visit) ? 1 : 0;
            $user->allow_manage_visit = isset($request->allow_manage_visit) ? 1 : 0;
            $user->allow_delete_visit = isset($request->allow_delete_visit) ? 1 : 0;
            $user->notification_when_delete_visit = isset($request->notification_when_delete_visit) ? 1 : 0;
            $user->show_notification_visit = isset($request->show_notification_visit) ? 1 : 0;

        }
        $user->save();

        Session::flash('success', 'اضافة مستخدم');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'اضافة مستخدم جديد باسم ' . $user->name . ' ونوعة ' . ($user->type == 1 ? 'VIP Account' : 'مستخدم عادى');
        $activty->type = 0;
        $activty->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::findOrFail($id);
        return view('users.create_edit_show', [
            'user' => $user,
            'show' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        if (Auth::user()->id != $user->id && Auth::user()->type != 1) {
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = ' تحذير حاول المستخدم ' . Auth::user()->name . ' تعديل المستخدم ' . $user->name . ' وتم تسجيل الخروج ';
            $activty->type = 0;
            $activty->notification = 1;
            $activty->save();
            \auth()->logout();
            return redirect(route('login'));
        }
        if (Auth::user()->type == 1) {
            return view('users.create_edit_show', ['user' => $user]);
        }
        if (Auth::user()->type != 1) {
            return view('users.edit', ['user' => $user]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = User::findOrFail($id);

        $uniqueName = $user->name == $request->name ? '' : '|unique:users,name';
        $uniqueEmail = $user->email == $request->email ? '' : '|unique:users,email';
        $request->validate([
            'name' => "required|max:50$uniqueName",
            'email' => "required|max:50$uniqueEmail",
            'password' => 'max:20',
            'type' => 'regex:/^[1-2]$/',
            'log_out_security' => 'boolean',

            'allow_edit_my_account' => 'boolean',
            'allow_edit_my_account_name' => 'boolean',
            'allow_edit_account_email' => 'boolean',
            'allow_change_background' => 'boolean',
            'allow_manage_activities' => 'boolean',

            //accounts
            'allow_add_account' => 'boolean',
            'create_notification_when_add_account_with_old_account' => 'boolean',
            'allow_access_index_account' => 'boolean',
            'allow_edit_account' => 'boolean',
            'allow_edit_account_type' => 'boolean',
            'allow_edit_account_name' => 'boolean',
            'allow_edit_account_tel' => 'boolean',
            'allow_delete_account' => 'boolean',
            'allow_adjust_account' => 'boolean',
            'create_notification_when_adjust_account' => 'boolean',
            'allow_access_report_account' => 'boolean',
            'allow_delete_account_buy_take_money' => 'boolean',
            'notification_when_delete_account_buy_take_money' => 'boolean',

            //stoke
            'allow_mange_stoke' => 'boolean',
            'allow_mange_place_in_stoke' => 'boolean',
            'allow_mange_product_place_in_stoke' => 'boolean',

            //damage and store and product move
            'allow_access_product_in_stoke' => 'boolean',
            'allow_add_damage' => 'boolean',
            'notification_when_add_damage' => 'boolean',
            'allow_access_product_move' => 'boolean',
            'allow_delete_damage' => 'boolean',
            'notification_when_delete_damage' => 'boolean',
            'allow_access_product_profit' => 'boolean',

            'allow_access_product_in_all_stoke' => 'boolean',
            'allow_move_product_in_stoke' => 'boolean',
            'notification_when_move_product' => 'boolean',


            /*backups*/
            'allow_mange_backup' => 'boolean',
            'allow_download_backup' => 'boolean',

            //treasury
            'allow_mange_treasury' => 'boolean',
            'allow_delete_treasury' => 'boolean',
            'create_notification_when_delete_treasury' => 'boolean',


            //other
            'allow_mange_setting' => 'boolean',
            'allow_mange_device' => 'boolean',
            'allow_access_total_report' => 'boolean',

            //product
            'allow_add_product' => 'boolean',
            'allow_manage_product' => 'boolean',
            'allow_mange_barcode' => 'boolean',
            'allow_mange_product_category' => 'boolean',
            'allow_mange_product_unit' => 'boolean',


            //expenses
            'allow_add_expenses_and_expenses_type' => 'boolean',
            'allow_mange_expenses_type' => 'boolean',
            'allow_mange_expenses' => 'boolean',
            'allow_delete_expenses' => 'boolean',
            'allow_add_expenses_with_out_subtract_form_treasury' => 'boolean',
            'notification_when_add_expenses_with_out_subtract_form_treasury' => 'boolean',
            'notification_when_delete_expenses' => 'boolean',

            //bill
            'allow_mange_print_setting' => 'boolean',
            'allow_mange_bill_message' => 'boolean',
            'allow_create_bill_buy' => 'boolean',
            'allow_create_bill_sale_show' => 'boolean',
            'allow_manage_bill_buy' => 'boolean',
            'allow_edit_bill_buy' => 'boolean',
            'allow_delete_bill_buy' => 'boolean',
            'notification_delete_bill_buy' => 'boolean',
            'allow_create_bill_sale' => 'boolean',
            'allow_manage_bill_sale' => 'boolean',
            'allow_manage_bill_sale_with_profit' => 'boolean',
            'allow_edit_bill_sale' => 'boolean',
            'allow_delete_bill_sale' => 'boolean',
            'notification_delete_bill_sale' => 'boolean',

            //making
            'allow_add_make' => 'boolean',
            'allow_manage_make' => 'boolean',
            'allow_delete_make' => 'boolean',
            'notification_delete_make' => 'boolean',

            //emps
            'allow_add_emp' => 'boolean',
            'allow_manage_emp_jops' => 'boolean',
            'allow_manage_emp' => 'boolean',
            'allow_manage_emp_operation' => 'boolean',
            'allow_manage_emp_move' => 'boolean',
            'allow_manage_emp_attend' => 'boolean',


            //exit deal
            'allow_create_exit_deal' => 'boolean',
            'allow_manage_exit_deal' => 'boolean',
            'allow_delete_exit_deal' => 'boolean',
            'notification_when_delete_exit_deal' => 'boolean',
        ]);

        if (Auth::user()->type != 1 && $user->id != $id) {
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = ' تحذير حاول المستخدم ' . Auth::user()->name . ' تعديل المستخدم ' . $user->name . ' وتم تسجيل الخروج ';
            $activty->type = 0;
            $activty->notification = 1;
            $activty->save();
            \auth()->logout();
            return redirect(route('login'));
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password != '') {
            $user->password = \Hash::make($request->password);
        }

        if (Auth::user()->type == 1) {
            $user->type = $request->type;

//            return $request;
            //like store
            if ($user->type != 1) {
                $user->log_out_security = isset($request->log_out_security) ? 1 : 0;

                //users
                $user->allow_edit_my_account = isset($request->allow_edit_my_account) ? 1 : 0;
                $user->allow_edit_my_account_name = isset($request->allow_edit_my_account_name) ? 1 : 0;
                $user->allow_edit_account_email = isset($request->allow_edit_account_email) ? 1 : 0;
                $user->allow_change_background = isset($request->allow_change_background) ? 1 : 0;
                $user->allow_manage_activities = isset($request->allow_manage_activities) ? 1 : 0;

                //accounts
                $user->allow_add_account = isset($request->allow_add_account) ? 1 : 0;
                $user->create_notification_when_add_account_with_old_account = isset($request->create_notification_when_add_account_with_old_account) ? 1 : 0;
                $user->allow_access_index_account = isset($request->allow_access_index_account) ? 1 : 0;
                $user->allow_edit_account = isset($request->allow_edit_account) ? 1 : 0;
                $user->allow_edit_account_type = isset($request->allow_edit_account_type) ? 1 : 0;
                $user->allow_edit_account_name = isset($request->allow_edit_account_name) ? 1 : 0;
                $user->allow_edit_account_tel = isset($request->allow_edit_account_tel) ? 1 : 0;
                $user->allow_delete_account = isset($request->allow_delete_account) ? 1 : 0;
                $user->allow_adjust_account = isset($request->allow_adjust_account) ? 1 : 0;
                $user->create_notification_when_adjust_account = isset($request->create_notification_when_adjust_account) ? 1 : 0;
                $user->allow_access_report_account = isset($request->allow_access_report_account) ? 1 : 0;
                $user->allow_delete_account_buy_take_money = isset($request->allow_delete_account_buy_take_money) ? 1 : 0;
                $user->notification_when_delete_account_buy_take_money = isset($request->notification_when_delete_account_buy_take_money) ? 1 : 0;

                //stoke
                $user->allow_mange_stoke = isset($request->allow_mange_stoke) ? 1 : 0;
                $user->allow_mange_place_in_stoke = isset($request->allow_mange_place_in_stoke) ? 1 : 0;
                $user->allow_mange_product_place_in_stoke = isset($request->allow_mange_product_place_in_stoke) ? 1 : 0;

                //damage and store and product move
                $user->allow_access_product_in_stoke = isset($request->allow_access_product_in_stoke) ? 1 : 0;
                $user->allow_add_damage = isset($request->allow_add_damage) ? 1 : 0;
                $user->notification_when_add_damage = isset($request->notification_when_add_damage) ? 1 : 0;
                $user->allow_access_product_move = isset($request->allow_access_product_move) ? 1 : 0;
                $user->allow_delete_damage = isset($request->allow_delete_damage) ? 1 : 0;
                $user->notification_when_delete_damage = isset($request->notification_when_delete_damage) ? 1 : 0;
                $user->allow_access_product_profit = isset($request->allow_access_product_profit) ? 1 : 0;
                $user->allow_move_product_in_stoke = isset($request->allow_move_product_in_stoke) ? 1 : 0;
                $user->notification_when_move_product = isset($request->notification_when_move_product) ? 1 : 0;

//            backups
                $user->allow_mange_backup = isset($request->allow_mange_backup) ? 1 : 0;
                $user->allow_download_backup = isset($request->allow_download_backup) ? 1 : 0;

                //treasury
                $user->allow_mange_treasury = isset($request->allow_mange_treasury) ? 1 : 0;
                $user->allow_delete_treasury = isset($request->allow_delete_treasury) ? 1 : 0;
                $user->create_notification_when_delete_treasury = isset($request->create_notification_when_delete_treasury) ? 1 : 0;

                //other
                $user->allow_mange_setting = isset($request->allow_mange_setting) ? 1 : 0;
                $user->allow_mange_device = isset($request->allow_mange_device) ? 1 : 0;
                $user->allow_access_total_report = isset($request->allow_access_total_report) ? 1 : 0;

                //expenses
                $user->allow_add_expenses_and_expenses_type = isset($request->allow_add_expenses_and_expenses_type) ? 1 : 0;
                $user->allow_mange_expenses_type = isset($request->allow_mange_expenses_type) ? 1 : 0;
                $user->allow_mange_expenses = isset($request->allow_mange_expenses) ? 1 : 0;
                $user->allow_delete_expenses = isset($request->allow_delete_expenses) ? 1 : 0;
                $user->allow_add_expenses_with_out_subtract_form_treasury = isset($request->allow_add_expenses_with_out_subtract_form_treasury) ? 1 : 0;
                $user->notification_when_add_expenses_with_out_subtract_form_treasury = isset($request->notification_when_add_expenses_with_out_subtract_form_treasury) ? 1 : 0;
                $user->notification_when_delete_expenses = isset($request->notification_when_delete_expenses) ? 1 : 0;

                //products
                $user->allow_add_product = isset($request->allow_add_product) ? 1 : 0;
                $user->allow_manage_product = isset($request->allow_manage_product) ? 1 : 0;
                $user->allow_mange_barcode = isset($request->allow_mange_barcode) ? 1 : 0;
                $user->allow_mange_product_category = isset($request->allow_mange_barcode) ? 1 : 0;
                $user->allow_mange_product_unit = isset($request->allow_mange_barcode) ? 1 : 0;

                //bill
                $user->allow_mange_print_setting = isset($request->allow_mange_print_setting) ? 1 : 0;
                $user->allow_mange_bill_message = isset($request->allow_mange_bill_message) ? 1 : 0;
                $user->allow_create_bill_buy = isset($request->allow_create_bill_buy) ? 1 : 0;
                $user->allow_create_bill_sale_show = isset($request->allow_create_bill_sale_show) ? 1 : 0;
                $user->allow_manage_bill_buy = isset($request->allow_manage_bill_buy) ? 1 : 0;
                $user->allow_edit_bill_buy = isset($request->allow_edit_bill_buy) ? 1 : 0;
                $user->allow_delete_bill_buy = isset($request->allow_delete_bill_buy) ? 1 : 0;
                $user->notification_delete_bill_buy = isset($request->notification_delete_bill_buy) ? 1 : 0;
                $user->allow_create_bill_sale = isset($request->allow_create_bill_sale) ? 1 : 0;
                $user->allow_manage_bill_sale = isset($request->allow_manage_bill_sale) ? 1 : 0;
                $user->allow_manage_bill_sale_with_profit = isset($request->allow_manage_bill_sale_with_profit) ? 1 : 0;
                $user->allow_edit_bill_sale = isset($request->allow_edit_bill_sale) ? 1 : 0;
                $user->allow_delete_bill_sale = isset($request->allow_delete_bill_sale) ? 1 : 0;
                $user->notification_delete_bill_sale = isset($request->notification_delete_bill_sale) ? 1 : 0;


                //making
                $user->allow_add_make = isset($request->allow_add_make) ? 1 : 0;
                $user->allow_manage_make = isset($request->allow_manage_make) ? 1 : 0;
                $user->allow_delete_make = isset($request->allow_delete_make) ? 1 : 0;
                $user->notification_delete_make = isset($request->notification_delete_make) ? 1 : 0;


                //emps
                $user->allow_add_emp = isset($request->allow_add_emp) ? 1 : 0;
                $user->allow_manage_emp_jops = isset($request->allow_manage_emp_jops) ? 1 : 0;
                $user->allow_manage_emp = isset($request->allow_manage_emp) ? 1 : 0;
                $user->allow_manage_emp_operation = isset($request->allow_manage_emp_operation) ? 1 : 0;
                $user->allow_manage_emp_move = isset($request->allow_manage_emp_move) ? 1 : 0;
                $user->allow_manage_emp_attend = isset($request->allow_manage_emp_attend) ? 1 : 0;

                //exit deal
                $user->allow_create_exit_deal = isset($request->allow_create_exit_deal) ? 1 : 0;
                $user->allow_manage_exit_deal = isset($request->allow_manage_exit_deal) ? 1 : 0;
                $user->allow_delete_exit_deal = isset($request->allow_delete_exit_deal) ? 1 : 0;
                $user->notification_when_delete_exit_deal = isset($request->notification_when_delete_exit_deal) ? 1 : 0;


                //visits and mission
                $user->allow_add_visit = isset($request->allow_add_visit) ? 1 : 0;
                $user->allow_manage_visit = isset($request->allow_manage_visit) ? 1 : 0;
                $user->allow_delete_visit = isset($request->allow_delete_visit) ? 1 : 0;
                $user->notification_when_delete_visit = isset($request->notification_when_delete_visit) ? 1 : 0;
                $user->show_notification_visit = isset($request->show_notification_visit) ? 1 : 0;

            }
        }

        $user->save();

        Session::flash('success', 'تعديل مستخدم');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تعديل المستخدم ' . $user->name;
        $activty->type = 0;
        $activty->save();

        return back();
        if (Auth::user()->type == 1) {
            return redirect(route('users.index'));
        } else
            return redirect(route('home'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::findOrFail($id);
        if (Auth::user() == $user) {
            Session::flash('fault', 'لا يجوز حذف المستخدم الحالي !');
            return back();
        }
        try {
            $user->delete();
            Session::flash('success', 'حذف مستخدم');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف المستخدم ' . $user->name;
            $activty->type = 0;
            $activty->save();
            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذا المستخدم لتعاملة مع النظام');
            return back();
        }
    }

    public function getData(Request $r)
    {
        if (isset($r->type)) {
            //get all users
            /*used in users.index*/
            if ($r->type == 'getAllUser') {
                return User::orderby('state')->get();
            }

            //used in header.blad.php
            if ($r->type == 'getDevice') {
                return Device::find(Auth::user()->device_id);
            }
        }
    }

    public function changeState($id)
    {
        //
        $o = User::findOrFail($id);
        if ($o->state == 0) {
            $o->state = 1;
        } else {
            $o->state = 0;
        }
        $o->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->type = 0;
        $activty->data = 'تغير حالة المستخدم ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
        $activty->save();

        Session::flash('success', 'تمت العملية بنجاح');
        return back();

    }

    public function editBg(Request $r)
    {
        $r->validate([
            'bg' => "required|max:20"
        ]);
        $id = Auth::user()->id;
        $user = User::find($id);
        $user->bg = $r->bg;
        $user->bg_img = null;
        $user->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->type = 0;
        $activty->data = 'تغير خلفية المستخدم ' . $user->name . ' إلى ' . $user->bg;
        $activty->save();

        Session::flash('success', 'تمت العملية بنجاح');
        return back();
    }

    public function uploadImg(Request $r)
    {
        try {
            if ($r->file('img')->getSize() > 2070804) {//=1M
                Session::flash('fault', 'برجاء إختيار صورة أصغر من 2 ميجا حتى لا تقلل من سرعة البرنامج هناك مواقع كثيرة لتصغير الصور ومن أفضلها ' . '<a href="https://tinypng.com/" target="_blank">tinypng</a>');
                return back();
            }
            $imgType = $r->file('img')->getClientOriginalExtension();
            if ($imgType != 'jpg' && $imgType != 'png') {
                Session::flash('fault', 'برجاء إختيار صورة نوعها ' . '<br/>' . 'jpg أو png');
                return back();
            }
            $image_base64 = base64_encode(file_get_contents($r->file('img')->getRealPath()));
            $image = 'data:image/' . $imgType . ';base64,' . $image_base64;
            $id = Auth::user()->id;
            $user = User::find($id);
            $user->bg_img = $image;
            $user->save();

            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type = 0;
            $activty->data = 'تغير خلفية المستخدم ' . $user->name . ' إلى صورة خاصة';
            $activty->save();
            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        } catch (\Exception $e) {
//            return  $e->getMessage();
            Session::flash('fault', 'حصل خطاء في العملية الصورة غير صالحة');
            return back();
//            throw $e;
        }
    }

    public function report()
    {
        return view('users.report', [
            'devices' => Device::orderBy('name')->get(),
        ]);
    }

    public function getReport(Request $r)
    {
        if ($r->type = 'getReport') {
            $device_id = [];
            $result = [
                'exist_deal_profit' => 0,
                'exist_deal_loses' => 0,

                'expenses' => 0,
                'expenses_count' => 0,

                'supplier_account' => 0,
                'supplier_counter' => 0,
                'supplier_has_account_counter' => 0,
                'customer_account' => 0,
                'customer_counter' => 0,
                'customer_has_account_counter' => 0,


                'treasury_add' => 0,
                'treasury_take' => 0,

                'stoke_buy' => 0,
                'stoke_make' => 0,

                'damage_stoke_buy' => 0,
                'damage_stoke_make' => 0,


                'bill_buy' => 0,//after discount
                'bill_buy_count' => 0,
                'bill_buy_paid'=>0,
                'bill_sale' => 0,//after discount
                'bill_sale_count' => 0,
                'bill_sale_paid' => 0,
                'profit_sale_has_qte_without_discount' => 0,
                'profit_sale_has_no_qte_without_discount' => 0,
                'profit_buy_has_no_qte_without_discount' => 0,
                'bill_discount_buy' => 0,
                'bill_discount_sale' => 0,

                'buy_back_replace'=>0,
                'buy_back_take_money'=>0,
                'buy_back_discount'=>0,

                'sale_back_replace'=>0,
                'sale_back_take_money'=>0,
                'sale_back_discount'=>0,

                'emp_account' => 0,
                'emp_paid_and_borrow' => 0,

                'total_visit'=>0,
                'total_mission'=>0,

            ];

            if ($r->device_id == '') {
                $device = Device::all();
                foreach ($device as $d) {
                    array_push($device_id, $d->id);
                }
            } else {
                array_push($device_id, $r->device_id);
            }

            //get total for exist deal type profit
            $exist_deals_profit = ExistDeal::where('type', '0')->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('value');

            $result['exist_deal_profit'] = $exist_deals_profit;
            $exist_deals_loses = ExistDeal::where('type', '1')->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('value');
            $result['exist_deal_loses'] = $exist_deals_loses;

            //get total for expenses
            $expenses = Expense::whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('price');
            $result['expenses'] = $expenses;

            $result['expenses_count'] = Expense::whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->count();

            //get total for account
            $supplier_account = Account::where('is_supplier', 1)->sum('account');
            $result['supplier_account'] = $supplier_account;
            $result['supplier_counter'] = Account::where('is_supplier', 1)->count();
            $result['supplier_has_account_counter'] = Account::where('is_supplier', 1)->where('account','!=','0')->count();

            $customer_account = Account::where('is_supplier','!=', 1)->where('is_customer', 1)->sum('account');
            $result['customer_account'] = $customer_account;
            $result['customer_counter'] = Account::where('is_supplier', 0)->where('is_customer', 1)->count();
//            return $result['customer_counter'];
            $result['customer_has_account_counter'] = Account::where('is_supplier','!=', 1)->where('is_customer', 1)->where('account','!=','0')->count();

            //get total for treasury
            $treasury_add = Treasury::where('type', 0)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('val');
            $result['treasury_add'] = $treasury_add;

            $treasury_take = Treasury::where('type', 1)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('val');
            $result['treasury_take'] = $treasury_take;

            //get total for stoke data
            $stoke_allowed = DeviceStoke::whereIn('device_id', $device_id)->get();
            $stoke_allowed_result = [];
            foreach ($stoke_allowed as $d) {
                array_push($stoke_allowed_result, $d->stoke_id);
            }
            $stoke_buy = Store::select(DB::raw('sum(qte * price) as total'))->where('type', 0)->whereIn('stoke_id', $stoke_allowed_result)->first();
            $result['stoke_buy'] = $stoke_buy->total == null ? 0 : $stoke_buy->total;

            $stoke_make = Store::select(DB::raw('sum(qte * price) as total'))->where('type', 1)->whereIn('stoke_id', $stoke_allowed_result)->first();
            $result['stoke_make'] = $stoke_make->total == null ? 0 : $stoke_make->total;

            //get total Damaged qte in stoke
            $damage_stoke_buy = ProductMove::select(DB::raw('sum(qte * price) as total'))->
            where('type', 2)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->first();
            $result['damage_stoke_buy'] = $damage_stoke_buy->total == null ? 0 : $damage_stoke_buy->total;

            $damage_stoke_make = ProductMove::select(DB::raw('sum(qte * price) as total'))->
            where('type', 3)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->first();
            $result['damage_stoke_make'] = $damage_stoke_make->total == null ? 0 : $damage_stoke_make->total;


            //get total for bill
            $bill_buy = Bill::where('type', 0)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('total_price');
            $result['bill_buy'] = $bill_buy;

            $result['bill_buy_count']=Bill::where('type',0)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->count();

            $result['bill_buy_paid']=Bill::where('type', 0)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('total_paid');

            $bill_sale = Bill::where('type', 1)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('total_price');
            $result['bill_sale'] = $bill_sale;

            $result['bill_sale_count']=Bill::where('type',1)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->count();

            $result['bill_sale_paid']=Bill::where('type', 1)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('total_paid');

            //get bill profit
            $totalProfitHasQte = 0;
            $totalProfitNoQte = 0;
            $bill = Bill::with('detail')->where('type', 1)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            for ($i = 0; $i < count($bill); $i++) {
                for ($d = 0; $d < count($bill[$i]['detail']); $d++) {
                    $price_sale = $bill[$i]['detail'][$d]['price'];
                    $qte_sale = $bill[$i]['detail'][$d]['qte'];

                    if (count($bill[$i]['detail'][$d]['saleMakeQteDetail']) == 0) {//product type is no qte
                        $totalProfitNoQte += $qte_sale * $price_sale;
                    } else {//product type not is no qte
                        //get qte profit
                        for ($q = 0; $q < count($bill[$i]['detail'][$d]['saleMakeQteDetail']); $q++) {
                            $qte = $bill[$i]['detail'][$d]['saleMakeQteDetail'][$q]['qte'];
                            $buyPrice = $bill[$i]['detail'][$d]['saleMakeQteDetail'][$q]['store']['price'];
                            $totalProfitHasQte += $qte * $price_sale - $qte * $buyPrice;
                        }
                    }
                }
            }

            $bill_buy_discount = Bill::where('type', 0)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('discount');
            $result['bill_discount_buy'] = $bill_buy_discount;

            $result['bill_buy']=$result['bill_buy'] - $result['bill_discount_buy'];

            $bill_discount_sale = Bill::where('type', 1)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('discount');
            $result['bill_discount_sale'] = $bill_discount_sale;

            $result['bill_sale']=$result['bill_sale'] - $result['bill_discount_sale'];

            $totalBuyHasNoQte = 0;
            $totalBuyNoQte = Bill::with('details')->where('type', 0)->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            for ($i = 0; $i < count($totalBuyNoQte); $i++) {
                for ($d = 0; $d < count($totalBuyNoQte[$i]['detail']); $d++) {
                    if ($totalBuyNoQte[$i]['details'][$d]['store_id'] == null) {
                        $price_buy = $totalBuyNoQte[$i]['detail'][$d]['price'];
                        $qte_buy = $totalBuyNoQte[$i]['detail'][$d]['qte'];
                        $totalBuyHasNoQte += $qte_buy * $price_buy;
                    }
                }
            }


            $result['profit_sale_has_qte_without_discount'] = $totalProfitHasQte;
            $result['profit_sale_has_no_qte_without_discount'] = $totalProfitNoQte;
            $result['profit_buy_has_no_qte_without_discount'] = $totalBuyHasNoQte;


            $emp_account = Emp::whereIn('device_id', $device_id)->sum('account');
            $result['emp_account'] = $emp_account;

            $emp_borrow = EmpMove::whereIn('device_id', $device_id)->where('type', 3)->sum('value');
            $emp_paid = EmpMove::whereIn('device_id', $device_id)->where('type', 4)->sum('value');
            $result['emp_paid_and_borrow'] = $emp_borrow + $emp_paid;


            //get total for bill back
            $bill_back=BillBack::with('bill')->whereIn('device_id', $device_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            foreach ($bill_back as $b){
                if ($b->bill->type==0){
                    if ($b->type==0){
                        $result['buy_back_replace'] += $b->total_price;
                    }elseif ($b->type == 1){
                        $result['buy_back_take_money'] += $b->total_price;
                    }else{
                        $result['buy_back_discount'] += $b->total_price;
                    }
                }else{
                    if ($b->type==0){
                        $result['sale_back_replace'] += $b->total_price;
                    }elseif ($b->type == 1){
                        $result['sale_back_take_money'] += $b->total_price;
                    }else{
                        $result['sale_back_discount'] += $b->total_price;
                    }
                }
            }

            //get total for ivsit and mission
            $result['total_visit']=Visit::where('type','<',3)->whereIn('device_id', $device_id)->
            whereBetween('date_finish', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('price');

            $result['total_mission']=Visit::where('type',3)->whereIn('device_id', $device_id)->
            whereBetween('date_finish', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->sum('price');

            return (array)$result;

        }
    }

    public function check_device(Request $r)
    {
        $result='notChange';
        $rSerial=$r->serial;
        $ip=\Illuminate\Support\Facades\Request::ip();
        if ($ip != '::1' && $ip !='127.0.0.1'){
            $checkDevice=Device::where('mac',$rSerial)->first();
            if ($checkDevice != ''){
                //check if ip change
                if(!\Hash::check($ip,$checkDevice->hash_check)) {
                    $old_ip=$checkDevice->mac;
                    $checkDevice->mac=$ip;
                    $checkDevice->hash_check=\Hash::make($ip);
                    $checkDevice->save();

                    $activity = new \App\Activity();
                    $activity->data = 'تغير Ip الجهاز ' . $checkDevice->name.' من ' .$old_ip. ' إلى ' . $ip;
                    $activity->notification = 1;
                    $activity->type = 0;
                    $activity->save();
                    $result='change';
                }
            }
        }
        return $result;
    }

}
