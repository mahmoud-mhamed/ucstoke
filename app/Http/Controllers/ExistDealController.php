<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountCalculation;
use App\Activity;
use App\Device;
use App\ExistDeal;
use App\Rules\valid_negative_price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExistDealController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPower:allow_manage_exit_deal,use_exit_deal', ['only' => ['index']]);
        $this->middleware('checkPower:allow_create_exit_deal,use_exit_deal', ['only' => ['create', 'store']]);
        $this->middleware('checkPower:allow_delete_exit_deal,use_exit_deal', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        //
        return view('exist_deals.index',[
            'id'=>isset($r->id)?$r->id:'',
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
        return view('exist_deals.create', [
            'accounts' => Account::orderBy('name', 'asc')->get(),
        ]);
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
        DB::beginTransaction();
        try {
            $ex = new ExistDeal();
            $ex->user_id = Auth::user()->id;
            $ex->device_id = Auth::user()->device_id;
            $ex->type = $request->type;
            $ex->note = $request->note;

            $d = Device::findOrFail(Auth::user()->device_id);
            //check if has account
            if ($request->account_id == 0) {
                if ($request->type == 0) {
                    $ex->value = $request->value;
                    $ex->value_add_to_treasury = $request->value;
                    $ex->save();

                    $d->treasury_value += $ex->value_add_to_treasury;
                    $d->save();

                    $activity = new Activity();
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;
                    $activity->data = 'اضافة أرباح خارجية بملاحظة ' . $request->note . ' وقيمتها ' . $request->value . ' ج ' .
                        'وتم إضافة المبلغ للدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                    $activity->relation_treasury = 1;
                    $activity->treasury_value = $request->value;
                    $activity->type = 18;
                    $activity->save();
                } else {
                    //check if money in device greater than loses
                    if ($d->treasury_value < $request->value) {
                        DB::rollBack();
                        Session::flash('fault', 'حصل خطاء فى العملية المال فى الدرج غير كافى للعملية حيث المال فى الدرج ' . round($d['treasury_value'], 2) . ' ج ' . ' والمبلغ المراد دفعة ' .
                            round($request->value, 2) . ' ج');
                        return back();
                    } else {
                        $ex->value = $request->value;
                        $ex->value_add_to_treasury = $request->value;
                        $ex->save();

                        $d->treasury_value -= $ex->value_add_to_treasury;
                        $d->save();

                        $activity = new Activity();
                        $activity->user_id = Auth::user()->id;
                        $activity->device_id = Auth::user()->device_id;
                        $activity->data = 'اضافة خسائر خارجية بملاحظة ' . $request->note . ' وقيمتها ' . $request->value . ' ج ' .
                            'وتم خصم المبلغ من الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                        $activity->relation_treasury = 2;
                        $activity->treasury_value = $request->value;
                        $activity->type = 18;
                        $activity->save();
                    }
                }
            } else {
                $account = Account::findOrFail($request->account_id);
                $ex->account_id = $request->account_id;
                if ($request->type == 0) {//type exist deal is profit
                    $ex->value = $request->value;
                    $ex->value_add_to_treasury = $request->paid;
                    $ex->save();

                    $activity = new Activity();
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;
                    if ($ex->value_add_to_treasury > 0) {
                        //update tresaury
                        $d->treasury_value += $ex->value_add_to_treasury;
                        $d->save();

                        $activity->relation_treasury = 1;
                        $activity->treasury_value = $ex->value_add_to_treasury;
                    }

                    //update account
                    if ($request->value != $request->paid) {
                        //if type of account is customer only
                        if ($account->is_supplier == false && $account->is_customer == true) {
                            $account->account += ($ex->value - $ex->value_add_to_treasury);
                            $account->save();

                            $nr = new AccountCalculation();
                            $nr->user_id = Auth::user()->id;
                            $nr->device_id = Auth::user()->device_id;
                            $nr->account_id = $account->id;
                            $nr->exist_deal_id = $ex->id;
                            $nr->value = $ex->value;
                            $nr->rent = ($ex->value - $ex->value_add_to_treasury);
                            $nr->type = 9;
                            $nr->account_after_this_action = $account->account;
                            $nr->relation_account = 1;
                            $nr->note = $request->note;
                            $nr->save();

                            $activity->data = 'اضافة أرباح خارجية من ' . $account->name . ' بملاحظة ' . $request->note . ' وقيمتها ' . $request->value . ' ج ' .
                                ' وتم دفع ' . $ex->value_add_to_treasury . 'ج والباقى ' . ($ex->value - $ex->value_add_to_treasury) .
                                'ج وتم إضافة الباقى لحساب الشخص ليصبح إجمالى حسابة ' . $account->account . 'ج ' .
                                'وتم إضافة المبلغ المدفوع للدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                        } else {//if type is supplier or supplier customer
                            $account->account -= ($ex->value - $ex->value_add_to_treasury);
                            $account->save();

                            $nr = new AccountCalculation();
                            $nr->user_id = Auth::user()->id;
                            $nr->device_id = Auth::user()->device_id;
                            $nr->account_id = $account->id;
                            $nr->exist_deal_id = $ex->id;
                            $nr->value = $ex->value;
                            $nr->rent = ($ex->value - $ex->value_add_to_treasury);
                            $nr->type = 9;
                            $nr->account_after_this_action = $account->account;
                            $nr->relation_account = 2;
                            $nr->note = $request->note;
                            $nr->save();

                            $activity->data = 'اضافة أرباح خارجية من ' . $account->name . ' بملاحظة ' . $request->note . ' وقيمتها ' . $request->value . ' ج ' .
                                ' وتم دفع ' . $ex->value_add_to_treasury . 'ج والباقى ' . ($ex->value - $ex->value_add_to_treasury) .
                                'ج وتم خصم الباقى من حساب الشخص ليصبح إجمالى حسابة ' . $account->account . 'ج ' .
                                'وتم إضافة المبلغ المدفوع للدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                        }
                    } else {
                        $nr = new AccountCalculation();
                        $nr->user_id = Auth::user()->id;
                        $nr->device_id = Auth::user()->device_id;
                        $nr->account_id = $account->id;
                        $nr->exist_deal_id = $ex->id;
                        $nr->value = $ex->value;
                        $nr->rent = ($ex->value - $ex->value_add_to_treasury);
                        $nr->type = 9;
                        $nr->relation_account = 0;
                        $nr->account_after_this_action = $account->account;
                        $nr->note = $request->note;
                        $nr->save();

                        $activity->data = 'اضافة أرباح خارجية من ' . $account->name . ' بملاحظة ' . $request->note . ' وقيمتها ' . $request->value . ' ج ' .
                            'وتم إضافة المبلغ للدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                        $activity->relation_treasury = 1;
                        $activity->treasury_value = $request->value;
                    }
                    $activity->type = 18;
                    $activity->save();
                } else {
                    //add exist loses
                    //check if money in device greater than loses
                    if ($d->treasury_value < $request->paid) {
                        DB::rollBack();
                        Session::flash('fault', 'حصل خطاء فى العملية المال فى الدرج غير كافى للعملية حيث المال فى الدرج ' . round($d['treasury_value'], 2) . ' ج ' . ' والمبلغ المراد دفعة ' .
                            round($request->paid, 2) . ' ج');
                        return back();
                    }

                    $ex->value = $request->value;
                    $ex->value_add_to_treasury = $request->paid;
                    $ex->save();

                    $activity = new Activity();
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;
                    if ($ex->value_add_to_treasury > 0) {
                        //update tresaury
                        $d->treasury_value -= $ex->value_add_to_treasury;
                        $d->save();

                        $activity->relation_treasury = 2;
                        $activity->treasury_value = $ex->value_add_to_treasury;
                    }

                    //update account
                    if ($request->value != $request->paid) {
                        //if type of account is customer only
                        if ($account->is_supplier == false && $account->is_customer == true) {
                            $account->account -= ($ex->value - $ex->value_add_to_treasury);
                            $account->save();

                            $nr = new AccountCalculation();
                            $nr->user_id = Auth::user()->id;
                            $nr->device_id = Auth::user()->device_id;
                            $nr->account_id = $account->id;
                            $nr->exist_deal_id = $ex->id;
                            $nr->value = ($ex->value);
                            $nr->rent = ($ex->value - $ex->value_add_to_treasury);
                            $nr->type = 10;
                            $nr->account_after_this_action = $account->account;
                            $nr->relation_account = 2;
                            $nr->note = $request->note;
                            $nr->save();

                            $activity->data = 'اضافة خسائر خارجية من ' . $account->name . ' بملاحظة ' . $request->note . ' وقيمتها ' . $request->value . ' ج ' .
                                ' وتم دفع ' . $ex->value_add_to_treasury . 'ج والباقى ' . ($ex->value - $ex->value_add_to_treasury) .
                                'ج وتم خصم الباقى من حساب الشخص ليصبح إجمالى حسابة ' . $account->account . 'ج ' .
                                'وتم خصم المبلغ المدفوع من الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                        } else {//if type is supplier or supplier customer
                            $account->account += ($ex->value - $ex->value_add_to_treasury);
                            $account->save();

                            $nr = new AccountCalculation();
                            $nr->user_id = Auth::user()->id;
                            $nr->device_id = Auth::user()->device_id;
                            $nr->account_id = $account->id;
                            $nr->exist_deal_id = $ex->id;
                            $nr->value = ($ex->value);
                            $nr->rent = ($ex->value - $ex->value_add_to_treasury);
                            $nr->type = 10;
                            $nr->account_after_this_action = $account->account;
                            $nr->relation_account = 1;
                            $nr->note = $request->note;
                            $nr->save();

                            $activity->data = 'اضافة خسائر خارجية من ' . $account->name . ' بملاحظة ' . $request->note . ' وقيمتها ' . $request->value . ' ج ' .
                                ' وتم دفع ' . $ex->value_add_to_treasury . 'ج والباقى ' . ($ex->value - $ex->value_add_to_treasury) .
                                'ج وتم إضافة الباقى إلى حساب الشخص ليصبح إجمالى حسابة ' . $account->account . 'ج ' .
                                'وتم خصم المبلغ المدفوع من الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                        }
                    } else {
                        $nr = new AccountCalculation();
                        $nr->user_id = Auth::user()->id;
                        $nr->device_id = Auth::user()->device_id;
                        $nr->account_id = $account->id;
                        $nr->exist_deal_id = $ex->id;
                        $nr->value = ($ex->value);
                        $nr->rent = ($ex->value - $ex->value_add_to_treasury);
                        $nr->type = 10;
                        $nr->relation_account = 0;
                        $nr->account_after_this_action = $account->account;
                        $nr->note = $request->note;
                        $nr->save();

                        $activity->data = 'اضافة خسائر خارجية من ' . $account->name . ' بملاحظة ' . $request->note . ' وقيمتها ' . $request->value . ' ج ' .
                            'وتم خصم المبلغ من الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                        $activity->relation_treasury = 2;
                        $activity->treasury_value = $request->value;
                    }
                    $activity->type = 18;
                    $activity->save();
                }
            }

            Session::flash('success', 'تمت العملية بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
//            throw $e;
        }
        DB::commit();
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param \App\ExistDeal $existDeal
     * @return \Illuminate\Http\Response
     */
    public function show(ExistDeal $existDeal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ExistDeal $existDeal
     * @return \Illuminate\Http\Response
     */
    public function edit(ExistDeal $existDeal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ExistDeal $existDeal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExistDeal $existDeal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ExistDeal $existDeal
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $exit = ExistDeal::with('account')->with('accountCalculation')->findOrFail($id);
        $d = Device::findOrFail(Auth::user()->device_id);
        $account = $exit->account_id == null ? null : Account::findOrFail($exit->account_id);
        DB::beginTransaction();
        try {
            //if no rent
            if ($exit->value == $exit->value_add_to_treasury) {
                //type profit
                if ($exit->type == 0) {
                    $d->treasury_value -= $exit->value;
                    $d->save();

                    $activity = new Activity();
                    if (Auth::user()->type!=1 && Auth::user()->notification_when_delete_exit_deal){
                        $activity->notification=1;
                    }
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;

                    $activity->relation_treasury = 2;
                    $activity->treasury_value = $exit->value;
                    $activity->type = 18;
                    //if no account
                    if ($exit->account_id == null) {
                        $activity->data = 'حذف أرباح خارجية كانت بدون شخص بملاحظة ' . $exit->note . ' وقيمتها ' . $exit->value . ' ج ' .
                            'وتم خصم القيمة من الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                    } else {
                        $activity->data = 'حذف أرباح خارجية كانت من ' . $exit->account->name . ' بملاحظة ' . $exit->note . ' وقيمتها ' . $exit->value . ' ج ' .
                            'وتم خصم القيمة من الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                    }
                    $activity->save();
                    $exit->delete();
                } else {//type == loses
                    $d->treasury_value += $exit->value;
                    $d->save();

                    $activity = new Activity();
                    if (Auth::user()->type!=1 && Auth::user()->notification_when_delete_exit_deal){
                        $activity->notification=1;
                    }
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;

                    $activity->relation_treasury = 1;
                    $activity->treasury_value = $exit->value;
                    $activity->type = 18;
                    //if no account
                    if ($exit->account_id == null) {
                        $activity->data = 'حذف خسائر خارجية كانت بدون شخص بملاحظة ' . $exit->note . ' وقيمتها ' . $exit->value . ' ج ' .
                            'وتم إعادة القيمة إلى الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                    } else {
                        $activity->data = 'حذف خسائر خارجية كانت من ' . $exit->account->name . ' بملاحظة ' . $exit->note . ' وقيمتها ' . $exit->value . ' ج ' .
                            'وتم إعادة القيمة إلى الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                    }
                    $activity->save();
                    $exit->delete();
                }
            } else {//if has rent
                //type profit
                if ($exit->type == 0) {
                    $d->treasury_value -= $exit->value_add_to_treasury;
                    $d->save();

                    $activity = new Activity();
                    if (Auth::user()->type!=1 && Auth::user()->notification_when_delete_exit_deal){
                        $activity->notification=1;
                    }
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;

                    $activity->relation_treasury = 2;
                    $activity->treasury_value = $exit->value_add_to_treasury;
                    $activity->type = 18;
                    $activity->data = 'حذف أرباح خارجية كانت من ' . $exit->account->name . ' بملاحظة ' . $exit->note . ' وقيمتها ' . $exit->value . ' ج ' .
                        'والمبلغ المدفوع ' . $exit->value_add_to_treasury . 'ج ' . 'وتم خصم المبلغ المدفوع من الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                    //update account data
                    if ($exit->account->is_supplier) {//it account is supplier or supplier customer
                        $exit->account->account += $exit->value - $exit->value_add_to_treasury;
                        //update account calculations
                        $nextAccountCalclution = AccountCalculation::where('id', '>', $exit->accountCalculation->id)->get();
                        foreach ($nextAccountCalclution as $x) {
                            $x->account_after_this_action += $exit->accountCalculation->rent;
                            $x->save();
                        }
                    } else {//if type is customer
                        $exit->account->account -= $exit->value - $exit->value_add_to_treasury;
                        //update account calculations
                        $nextAccountCalclution = AccountCalculation::where('id', '>', $exit->accountCalculation->id)->get();
                        foreach ($nextAccountCalclution as $x) {
                            $x->account_after_this_action -= $exit->accountCalculation->rent;
                            $x->save();
                        }
                    }
                    $exit->account->save();

                    $activity->save();
                    $exit->delete();
                } else {//type loses
                    $d->treasury_value += $exit->value_add_to_treasury;
                    $d->save();

                    $activity = new Activity();
                    if (Auth::user()->type!=1 && Auth::user()->notification_when_delete_exit_deal){
                        $activity->notification=1;
                    }
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;

                    $activity->relation_treasury = 1;
                    $activity->treasury_value = $exit->value_add_to_treasury;
                    $activity->type = 18;
                    $activity->data = 'حذف خسائر خارجية كانت من ' . $exit->account->name . ' بملاحظة ' . $exit->note . ' وقيمتها ' . $exit->value . ' ج ' .
                        'والمبلغ المدفوع ' . $exit->value_add_to_treasury . 'ج ' . 'وتم إضافة المبلغ المدفوع إلى الدرج ليصبح المبلغ فى الدرج ' . round($d->treasury_value, 2) . 'ج';
                    //update account data
                    if ($exit->account->is_supplier) {//it account is supplier or supplier customer
                        $exit->account->account -= $exit->value - $exit->value_add_to_treasury;
                        //update account calculations
                        $nextAccountCalclution = AccountCalculation::where('id', '>', $exit->accountCalculation->id)->get();
                        foreach ($nextAccountCalclution as $x) {
                            $x->account_after_this_action -= $exit->accountCalculation->rent;
                            $x->save();
                        }
                    } else {//if type is customer
                        $exit->account->account += $exit->value - $exit->value_add_to_treasury;
                        //update account calculations
                        $nextAccountCalclution = AccountCalculation::where('id', '>', $exit->accountCalculation->id)->get();
                        foreach ($nextAccountCalclution as $x) {
                            $x->account_after_this_action += $exit->accountCalculation->rent;
                            $x->save();
                        }
                    }
                    $exit->account->save();

                    $activity->save();
                    $exit->delete();
                }
            }
            Session::flash('success', 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
//            throw $e;
        }
        DB::commit();
        return back();
    }

    public function getData(Request $r)
    {
        if ($r->type == 'getDataByDateCreate') {
            return ExistDeal::with('user')->with('device')->with('account')->
            orderBy('id', 'desc')->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
        }
        if ($r->type == 'getDataById') {
            return ExistDeal::with('user')->with('device')->with('account')->
            orderBy('id', 'desc')->
            where('id',$r->id)->get();
        }
    }
}
