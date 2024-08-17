<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('settings.index', [
            'setting' => Setting::first()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $request->validate([
            //for public
            'show_treasury_value_in_header' => 'boolean',
            'allow_sound' => 'boolean',
            'use_small_price' => 'boolean',

            'allow_account_without_tel' => 'boolean',
            'allow_repeat_tell_account' => 'boolean',
            'allow_repeat_supplier_name' => 'boolean',
            'allow_repeat_customer_name' => 'boolean',
            'allow_account_with_negative_account' => 'boolean',
            'allow_pay_money_to_account_with_negative_account' => 'boolean',
            'allow_take_money_from_account_with_negative_account' => 'boolean',

            //expenses
            'allow_add_expenses_without_subtract_from_treasury'=>'boolean',

            //products
            'edit_auto_for_default_min_qte_unit'=>'boolean',
            'automatic_barcode'=>'integer',

            //bill
            'auto_update_price_product_bill_buy'=>'boolean',
            'auto_update_price_product_bill_sale'=>'boolean',
            'show_unit_when_print_bill'=>'boolean',

        ]);
        $s = Setting::findOrFail(1);

        //setting for public
        $s->show_treasury_value_in_header = isset($request->show_treasury_value_in_header) ? 1 : 0;
        $s->allow_sound = isset($request->allow_sound) ? 1 : 0;
        $s->use_small_price = isset($request->use_small_price) ? 1 : 0;


        //setting for accounts
        $s->allow_account_without_tel = isset($request->allow_account_without_tel) ? 1 : 0;
        $s->allow_repeat_tell_account = isset($request->allow_repeat_tell_account) ? 1 : 0;
        $s->allow_repeat_supplier_name = isset($request->allow_repeat_supplier_name) ? 1 : 0;
        $s->allow_repeat_customer_name = isset($request->allow_repeat_customer_name) ? 1 : 0;
        $s->allow_account_with_negative_account = isset($request->allow_account_with_negative_account) ? 1 : 0;
        $s->allow_pay_money_to_account_with_negative_account = isset($request->allow_pay_money_to_account_with_negative_account) ? 1 : 0;
        $s->allow_take_money_from_account_with_negative_account = isset($request->allow_take_money_from_account_with_negative_account) ? 1 : 0;

        //setting for expenses
        $s->allow_add_expenses_without_subtract_from_treasury = isset($request->allow_add_expenses_without_subtract_from_treasury) ? 1 : 0;


        //setting for product
        $s->edit_auto_for_default_min_qte_unit = isset($request->edit_auto_for_default_min_qte_unit) ? 1 : 0;
        $s->price1_name = isset($request->price1_name) ? $request->price1_name : $s->price1_name;
        $s->price2_name = isset($request->price2_name) ? $request->price2_name : $s->price2_name;
        $s->price3_name = isset($request->price3_name) ? $request->price3_name : $s->price3_name;
        $s->price4_name = isset($request->price4_name) ? $request->price4_name : $s->price4_name;

        //setting for bill
        $s->auto_update_price_product_bill_buy = isset($request->auto_update_price_product_bill_buy) ? 1 : 0;
        $s->auto_update_price_product_bill_sale = isset($request->auto_update_price_product_bill_sale) ? 1 : 0;
        $s->show_unit_when_print_bill = isset($request->show_unit_when_print_bill) ? 1 : 0;


        $s->save();
        Session::flash('success', 'ضبط إعدادات البرنامج');
        $activity = new Activity();
        $activity->user_id = Auth::user()->id;
        $activity->device_id = Auth::user()->device_id;
        $activity->data = 'ضبط إعدادات البرنامج';
        $activity->type = 4;
        $activity->save();

        return back();
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
    }
}
