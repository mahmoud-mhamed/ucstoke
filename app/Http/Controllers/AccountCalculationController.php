<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountCalculation;
use App\Activity;
use App\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AccountCalculationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('accounts.report', [
            'accounts' => Account::orderBy('name')->get(),
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
        $ac = AccountCalculation::findOrFail($id);
        $account = Account::find($ac->account_id);
        $account->device_id = Auth::user()->device_id;
        $tempVal = $ac->value;
        DB::beginTransaction();
        try {
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;

            //if type accountCalculation is 1 or 11(1=> for takeMoneyFromCustomer,11=> for take money from supplier)
            if ($ac->type == 1 ||$ac->type==11) {
                $treasury = Device::findOrFAil($ac->device_id);
                if (round($treasury->treasury_value, 2) - round($tempVal, 2) < 0) {
                    throw new \Exception('المال في درج الجهاز ' . $treasury->name . ' غير كافي لتغطيه المبلغ حيث المال في الدرج ' . round($treasury->treasury_value, 2) . ' ج ' .
                        'والمبلغ المراد حذفة ' . $tempVal . ' ج ');
                }
                $treasury->treasury_value -= $tempVal;
                $treasury->save();

                //update account
                if ($ac->type==1){
                    $account->account += $tempVal;
                    $account->save();

                    //update account after this action
                    $nextAccountCalclution = AccountCalculation::where('id', '>', $ac->id)->where('account_id', $account->id)->get();
                    foreach ($nextAccountCalclution as $x) {
                        $x->account_after_this_action += $tempVal;
                        $x->save();
                    }
                }elseif($ac->type==11){
                    $account->account -= $tempVal;
                    $account->save();

                    //update account after this action
                    $nextAccountCalclution = AccountCalculation::where('id', '>', $ac->id)->where('account_id', $account->id)->get();
                    foreach ($nextAccountCalclution as $x) {
                        $x->account_after_this_action -= $tempVal;
                        $x->save();
                    }
                }


                $type = $account->is_supplier == 1 ? 'مورد' : '';
                $type = $type . ($account->is_customer == 1 ? ' عميل ' : '');

                Session::flash('success', 'حذف أخذ مال من ' . $type . ' بإسم ' . $account->names);
                $activity->data = 'حذف أخذ مال من ' . $type . ' بإسم ' . $account->names . '  وكان المبلغ ' . round($tempVal, 2) . 'ج وتم حذف المبلغ من الدرج ليصبح المبلغ فى الدرج '.round($treasury->treasury_value,2).'ج '.' وإضافته للحساب وكان بملاحظة '.$ac->note;
                $activity->relation_treasury = 2;
                $activity->treasury_value = $tempVal;


                $ac->delete();

                $activtyType = $account->is_supplier == 1 ? '5' : '';
                $activtyType = $account->is_customer == 1 ? '6' : $activtyType;
                $activtyType = $account->is_supplier && $account->is_customer ? '7' : $activtyType;
                $activity->type = $activtyType;
                if (Auth::user()->type != 1 &&
                    Auth::user()->notification_when_delete_account_buy_take_money) {
                    $activity->notification = 1;
                }
                $activity->save();
            } elseif ($ac->type == 2) {
                $treasury = Device::findOrFAil($ac->device_id);

                $treasury->treasury_value += $tempVal;
                $treasury->save();

                $account->account += $tempVal;
                $account->save();

                //update account after this action
                $nextAccountCalclution = AccountCalculation::where('id', '>', $ac->id)->where('account_id', $account->id)->get();
                foreach ($nextAccountCalclution as $x) {
                    $x->account_after_this_action += $tempVal;
                    $x->save();
                }

                $type = $account->is_supplier == 1 ? 'مورد' : '';
                $type = $type . ($account->is_customer == 1 ? ' عميل ' : '');

                Session::flash('success', 'حذف دفع مال ل' . $type . ' بإسم ' . $account->names);
                $activity->data = 'حذف دفع مال ل' . $type . ' بإسم ' . $account->names . '  وكان المبلغ ' . round($tempVal, 2) . 'ج وتم إعادة المبلغ إلى الحساب وإضافته للدرج ليصبح المال فى الدرج '.round($treasury->treasury_value,2).'ج '.' وكان بملاحظة '.$ac->note;
                $ac->delete();

                $activtyType = $account->is_supplier == 1 ? '5' : '';
                $activtyType = $account->is_customer == 1 ? '6' : $activtyType;
                $activtyType = $account->is_supplier && $account->is_customer ? '7' : $activtyType;
                $activity->type = $activtyType;
                if (Auth::user()->type != 1 &&
                    Auth::user()->notification_when_delete_account_buy_take_money) {
                    $activity->notification = 1;
                }
                $activity->relation_treasury = 1;
                $activity->treasury_value = $tempVal;
                $activity->save();
            }
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
        //used in view accounts.report
        if ($r->type == 'getDataByAccountIdAndDate') {
            if ($r->account_id == '') {
                return AccountCalculation::with('user')->with('device')->with('account')->orderBy('id', 'desc')->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            } else {
                return AccountCalculation::with('user')->with('device')->with('account')->
                orderBy('id', 'desc')->
                where('account_id', $r->account_id)->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }

        }

    }
}
