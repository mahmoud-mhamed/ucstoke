<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountCalculation;
use App\Activity;
use App\Device;
use App\Emp;
use App\EmpJop;
use App\Product;
use App\Rules\valid_negative_price;
use App\Rules\valid_price;
use App\Setting;
use App\Stoke;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPower:allow_access_index_account', ['only' => ['index']]);
        $this->middleware('checkPower:allow_add_account', ['only' => ['create', 'store']]);
        $this->middleware('checkPower:allow_edit_account', ['only' => ['edit', 'update']]);
        $this->middleware('checkPower:allow_delete_account', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('accounts.index', [
            'emps' => Emp::orderby('state')->orderby('name')->get(),
            'jops' => EmpJop::orderby('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        return view('accounts.create', [
            'accounts' => Account::orderBy('name')->get(),
            'setting' => Setting::first()
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
        $s = Setting::first();
        $allow_account_without_tel = '';
        $allow_repeat_tell_account = '';
        $allow_account_with_negative_account = '';
        $allow_repeat_name = '';
        if (!$s->allow_account_without_tel) {
            $allow_account_without_tel = '|required';
        }
        if (!$s->allow_repeat_tell_account) {
            $allow_repeat_tell_account = '|unique:accounts,tel';
        }
        if (!$s->allow_account_with_negative_account && $request->account != 0) {
            $allow_account_with_negative_account = 'gt:0';
        }

        if (!$s->allow_repeat_customer_name && $request->is_customer == 1) {
            $temp = Account::where('name', $request->name)->where('is_customer', 1)->first();
            if ($temp) {
                $allow_repeat_name = '|unique:accounts,name';
            }
        }

        if (!$s->allow_repeat_supplier_name && $request->is_supplier == 1) {
            $temp = Account::where('name', $request->name)->where('is_supplier', 1)->first();
            if ($temp) {
                $allow_repeat_name = '|unique:accounts,name';
            }
        }
        $request->validate([
            'name' => 'required|max:250' . $allow_repeat_name,
            'tel' => 'max:250' . $allow_account_without_tel . $allow_repeat_tell_account,
            'address' => 'max:250',
            'note' => 'max:250',
            'is_supplier' => 'boolean',
            'is_customer' => 'boolean',
            'account' => [new valid_negative_price, $allow_account_with_negative_account],
        ]);


        DB::beginTransaction();
        try {
            $n = new Account();
            $n->user_id = Auth::user()->id;
            $n->device_id = Auth::user()->device_id;
            $n->name = $request->name;
            if ($request->tel)
                $n->tel = $request->tel;
            if ($request->note)
                $n->note = $request->note;
            if ($request->address)
                $n->address = $request->address;
            $n->account = $request->account;
            $n->is_supplier = $request->is_supplier == 1 ? 1 : 0;
            $n->is_customer = $request->is_customer == 1 ? 1 : 0;
            $n->save();

            $activity = new Activity();

            if ($n->account != 0) {
                $nr = new AccountCalculation();
                $nr->user_id = Auth::user()->id;
                $nr->account_id = $n->id;
                $nr->value = $n->account;
                $nr->rent = abs($n->account);
                $nr->type = 0;
                $nr->device_id = Auth::user()->device_id;
                $nr->account_after_this_action = $n->account;
                $nr->relation_account = $nr->value > 0 ? 1 : ($nr->value < 0 ? 2 : 0);
                $nr->save();
            }

            $type = $request->is_supplier == 1 ? 'مورد' : '';
            $type = $type . ($request->is_customer == 1 ? ' عميل ' : '');

            Session::flash('success', 'اضافة ' . $type);
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'اضافة ' . $type . ' جديد باسم ' . $request->name . ' بحساب ' . $request->account . ' ج ';
            $activtyType = $request->is_supplier == 1 ? '5' : '';
            $activtyType = $request->is_customer == 1 ? '6' : $activtyType;
            $activtyType = $request->is_supplier && $request->is_customer ? '7' : $activtyType;
            $activity->type = $activtyType;
            if (Auth::user()->type != 1 &&
                Auth::user()->create_notification_when_add_account_with_old_account &&
                $n->account != 0) {
                $activity->notification = 1;
            }
            $activity->save();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
//            throw $e;
        }
        DB::commit();
        $is_supplier = $request->is_supplier;
        $is_customer = $request->is_customer;
        return redirect()->route('accounts.create', compact('is_customer', 'is_supplier'));
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
        $a = Account::findOrFail($id);
//         return  $a;
        return view('accounts.edit', [
            'r' => Account::findOrFail($id),
            'accounts' => Account::orderby('name')->get(),
            'setting' => Setting::first(),
        ]);

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
        $s = Setting::first();
        $n = Account::findorfail($id);
        $n->device_id = Auth::user()->device_id;
        $oldName = $n->name;
        $allow_account_without_tel = '';
        $allow_repeat_tell_account = '';
        $allow_repeat_name = '';
        if (!$s->allow_account_without_tel) {
            $allow_account_without_tel = '|required';
        }
        if (!$s->allow_repeat_tell_account && $n->tel != $request->tel) {
            $allow_repeat_tell_account = '|unique:accounts,tel';
        }

        if (!$s->allow_repeat_customer_name && $request->is_customer == 1 && $n->name != $request->name) {
            $temp = Account::where('name', $request->name)->where('is_customer', 1)->first();
            if ($temp) {
                $allow_repeat_name = '|unique:accounts,name';
            }
        }

        if (!$s->allow_repeat_supplier_name && $request->is_supplier == 1 && $n->name != $request->name) {
            $temp = Account::where('name', $request->name)->where('is_supplier', 1)->first();
            if ($temp) {
                $allow_repeat_name = '|unique:accounts,name';
            }
        }
        $request->validate([
            'name' => 'required|max:250' . $allow_repeat_name,
            'tel' => 'max:250' . $allow_account_without_tel . $allow_repeat_tell_account,
            'address' => 'max:250',
            'note' => 'max:250',
            'is_supplier' => 'boolean',
            'is_customer' => 'boolean'
        ]);


        DB::beginTransaction();
        try {
            $n->name = $request->name;
            if ($request->tel)
                $n->tel = $request->tel;
            if ($request->note)
                $n->note = $request->note;
            if ($request->address)
                $n->address = $request->address;
            $n->is_supplier = $request->is_supplier == 1 ? 1 : 0;
            $n->is_customer = $request->is_customer == 1 ? 1 : 0;

            $n->save();


            $type = $n->is_supplier == 1 ? 'مورد' : '';
            $type = $type . ($n->is_customer == 1 ? ' عميل ' : '');

            Session::flash('success', 'تعديل ' . $type);
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'تعديل بيانات ' . $type . '  حيث الإسم قبل التعديل ' . $oldName . ' وبعد التعديل  ' . $request->name;
            $activtyType = $n->is_supplier == 1 ? '5' : '';
            $activtyType = $n->is_customer == 1 ? '6' : $activtyType;
            $activtyType = $n->is_supplier && $n->is_customer ? '7' : $activtyType;
            $activity->type = $activtyType;
            $activity->save();
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $s = Account::findOrFail($id);

        $type = $s->is_supplier == 1 ? 'مورد' : '';
        $type = $type . ($s->is_customer == 1 ? ' عميل ' : '');

        $account = $s->account;
        $activtyType = $s->is_supplier == 1 ? '5' : '';
        $activtyType = $s->is_customer == 1 ? '6' : $activtyType;
        $activtyType = $s->is_supplier && $s->is_customer ? '7' : $activtyType;
        try {
            $s->delete();

            Session::flash('success', 'حذف ' . $type);
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف ' . $type . ' باسم ' . $s->name . ' بحساب ' . $account . 'ج';
            $activty->type = $activtyType;

            $activty->save();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذا ' . $type . ' لوجود تعاملات تخصه');
            return back();
        }
    }

    public function getData(Request $r)
    {
        //used in view accounts.index
        if ($r->type == 'getDataByDateCreate') {
            return Account::with('user')->with('device')->orderBy('id', 'desc')->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
        }

        //used in view accounts.index
        if ($r->type == 'getByHasAccount') {
            return Account::with('user')->with('device')->orderBy('id', 'desc')->
            where('account', '>', 0.3)->orWhere('account','<',0)->get();
        }

        //used in view accounts.index
        if ($r->type == 'searchInAllCell') {
            return Account::with('user')->with('device')->orderBy('id', 'desc')->
            where('name', 'like', '%' . $r->search . '%')->
            orWhere('tel', 'like', '%' . $r->search . '%')->
            orWhere('address', 'like', '%' . $r->search . '%')->
            orWhere('note', 'like', '%' . $r->search . '%')->
            get();
        }
    }

    public function get_adjust_account($id)
    {
        return view('accounts.adjust_account', ['r' => Account::findOrFail($id), 'setting' => Setting::findOrFail(1)]);
    }

    public function post_adjust_account($id, Request $r)
    {
        $n = Account::findOrfail($id);
        DB::beginTransaction();
        try {
            $oldOccount = $n->account;
            $n->account = $r->account;
            $n->device_id = Auth::user()->device_id;
            $n->save();

            $f = new AccountCalculation();
            $f->user_id = Auth::user()->id;
            $f->account_id = $n->id;
            $f->value = $n->account - $oldOccount;
            $f->rent = $f->value;
            $f->device_id = Auth::user()->device_id;
            $f->account_after_this_action = $n->account;
            $f->relation_account = $f->rent > 0 ? 1 : ($f->rent < 0 ? 2 : 0);
            $f->type = 3;

            $f->note = $r->note ? $r->note : '';

            $f->save();

            $type = $n->is_supplier == 1 ? 'مورد' : '';
            $type = $type . ($n->is_customer == 1 ? ' عميل ' : '');

            Session::flash('success', 'ضبط حساب ' . $type);
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'ضبط حساب ' . $type . $n->name . '  حيث الحساب قبل الضبط ' . round($oldOccount, 2) . ' وبعد الضبط  ' . round($n->account, 2);
            $activtyType = $n->is_supplier == 1 ? '5' : '';
            $activtyType = $n->is_customer == 1 ? '6' : $activtyType;
            $activtyType = $n->is_supplier && $n->is_customer ? '7' : $activtyType;
            $activity->type = $activtyType;
            if (Auth::user()->type != 1 &&
                Auth::user()->create_notification_when_adjust_account) {
                $activity->notification = 1;
            }
            $activity->save();
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

    public function get_add_or_subtract_debt($id, $type /*type 1 for add number to account ,2 for subtract number from account*/)
    {
        return view('accounts.add_or_subtract_debt', ['r' => Account::findOrFail($id), 'setting' => Setting::findOrFail(1), 'type' => $type]);
    }

    public function post_add_or_subtract_debt($id, $type, Request $r)
    {
        //type 1 for take money from customer or take money from supplier customer,2 fro pay money to suppleir or supplier customers

        $n = Account::findOrfail($id);
        $n->device_id = Auth::user()->device_id;

        $treasury = Device::findOrFAil(Auth::user()->device_id);

        //check if allow price negative number
        $s = Setting::first();
        if ($type == 1 && $r->price <= 0 && !$s->allow_take_money_from_account_with_negative_account) {
            Session::flash('fault', 'المبلغ المراد أخذه غير صحيح');
            return back();
        }
        if ($type == 2 && $r->price <= 0 && !$s->allow_pay_money_to_account_with_negative_account) {
            Session::flash('fault', 'المبلغ المراد دفعة غير صحيح');
            return back();
        }

        DB::beginTransaction();
        try {
            if ($type == 1 && $n->is_supplier) {//take money from supplier customer
                $n->account = $n->account + $r->price;
            } else {
                $n->account = $n->account - $r->price;
            }
            $n->save();

            $activity = new Activity();

            /*update treasury*/
            if ($type == 2) {//pay money
                if (round($treasury->treasury_value, 2) - round($r->price, 2) < 0) {
                    throw new \Exception('المال في الدرج غير كافي لتغطيه المبلغ حيث المال في الدرج ' . round($treasury->treasury_value, 2) . ' ج ' .
                        'والمبلغ المراد صرفة ' . $r->price . ' ج ');
                } else {
                    $treasury->treasury_value = $treasury->treasury_value - $r->price;
                    $treasury->save();

                    $activity->relation_treasury = 2;
                    $activity->treasury_value = $r->price;

                    $acc = new AccountCalculation();
                    $acc->user_id = Auth::user()->id;
                    $acc->account_id = $n->id;
                    $acc->value = $r->price;
                    $acc->rent = abs($r->price);
                    $acc->account_after_this_action = $n->account;
                    $acc->device_id = Auth::user()->device_id;
                    $acc->type = 2;
                    $acc->relation_account = $acc->value > 0 ? 2 : ($acc->value < 0 ? 1 : 0);
                    $acc->note = $r->note ? $r->note : '';
                    $acc->save();
                }
            } else { // take money
                $treasury->treasury_value = $treasury->treasury_value + $r->price;
                $treasury->save();

                $activity->relation_treasury = 1;
                $activity->treasury_value = $r->price;

                $acc = new AccountCalculation();
                $acc->user_id = Auth::user()->id;
                $acc->account_id = $n->id;
                $acc->value = $r->price;
                $acc->rent = abs($r->price);
                $acc->account_after_this_action = $n->account;
                $acc->device_id = Auth::user()->device_id;
                if ($n->is_supplier) {//take money from supplier customer
                    $acc->relation_account = $acc->value > 0 ? 1 : ($acc->value < 0 ? 2 : 0);
                    $acc->type = 11;
                } else {
                    $acc->relation_account = $acc->value > 0 ? 2 : ($acc->value < 0 ? 1 : 0);
                    $acc->type = 1;
                }
                $acc->note = $r->note ? $r->note : '';
                $acc->save();

            }

            $type_account = $n->is_supplier == 1 ? 'مورد' : '';
            $type_account = $type_account . ($n->is_customer == 1 ? ' عميل ' : '');

            $op_type_name = $type == 1 ? 'أخذ مال من ' : 'دفع مال إلى ';

            Session::flash('success', $op_type_name . $type_account . ' بإسم ' . $n->name);
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = $op_type_name . $type_account . ' بإسم ' . $n->name . '  والمبلغ هو ' . $r->price . ' ليصبح الحساب  ' . round($n->account, 2) . ' ج ' . ' والمال فى الدرج ' .
                round($treasury->treasury_value, 2) . 'ج';
            $activtyType = $n->is_supplier == 1 ? '5' : '';
            $activtyType = $n->is_customer == 1 ? '6' : $activtyType;
            $activtyType = $n->is_supplier && $n->is_customer ? '7' : $activtyType;
            $activity->type = $activtyType;
            $activity->device_id = Auth::user()->device_id;

            $activity->save();
        } catch (\Exception $e) {
            DB::rollback();
//            return $e->getMessage();
            Session::flash('fault', $e->getMessage());
            return back();
//            throw $e;
        }
        DB::commit();

        return redirect()->route('accounts.index');
    }


    public function account_bill_with_details()
    {
        return view('accounts.account_bill_with_details', [
            'accounts' => Account::orderBy('name')->get(),
        ]);
    }
}
