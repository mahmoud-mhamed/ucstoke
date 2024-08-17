<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Device;
use App\Expense;
use App\ExpensesType;
use App\Rules\valid_negative_price;
use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPower:allow_mange_expenses,use_expenses', ['only' => ['index']]);
        $this->middleware(['checkPower:allow_add_expenses_and_expenses_type,use_expenses'], ['only' => ['create', 'store']]);
        $this->middleware('checkPower:allow_delete_expenses,use_expenses', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('expenses.index', [
            'devices' => Device::orderby('name')->get(),
            'users' => User::orderby('state')->get(),
            'expensesTypes' => ExpensesType::orderby('state')->orderby('name')->get()
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
        return view('expenses.create', [
            'types' => ExpensesType::where('state', 1)->orderby('name')->get(),
            'setting' => Setting::first(),
            'device' => Auth::user()->device
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
        $validateType = '';
        if ($request->type != '') {
            $validateType = 'exists:expenses_types,id';
        }
        $request->validate([
            'note' => 'max:150',
            'type' => $validateType,
            'typePrice' => 'boolean',
            'price' => ['gt:0', new valid_negative_price],
            'newType' => 'max:50'
        ]);
        DB::beginTransaction();
        try {
            $s = Setting::first();

            $ex = new Expense();
            $ex->user_id = Auth::user()->id;
            $ex->device_id = Auth::user()->device_id;
            $ex->price = $request->price;
            $ex->note = isset($request->note) ? $request->note : '';

            if ($request->newType != '') {
                $expensesType = new ExpensesType();
                $expensesType->name = $request->newType;
                $expensesType->save();

                $ex->expense_type_id = $expensesType->id;
            } else {
                $ex->expense_type_id = $request->type;
            }
            $ex->state = isset($request->typePrice) ? $request->typePrice : 1;
            $ex->save();


            /*update treasuries*/
            $expensesTypeName = ExpensesType::find($ex->expense_type_id)->name;

//            $expensesTypeName=$ex->type->name;
            if ($request->typePrice == 0 && $s->allow_add_expenses_without_subtract_from_treasury &&
                (Auth::user()->type == 1 || Auth::user()->allow_add_expenses_with_out_subtract_form_treasury)) {//not subtract from treasury
                Session::flash('success', 'اضافة مصروف جديد ولم يتم خصم البلغ من الدرج');
                $activty = new Activity();
                $activty->user_id = Auth::user()->id;
                $activty->type = 11;
                $activty->device_id = Auth::user()->device_id;
                if (Auth::user()->type != 1 && Auth::user()->notification_when_add_expenses_with_out_subtract_form_treasury) {
                    $activty->notification = 1;
                }
                $activty->data = 'اضافة مصروف جديد إلى قسم ' . $expensesTypeName . ' بمبلغ ' . $request->price . ' جنية ' .
                    'ولم يتم خصم المبلغ من الدرج '.($ex->note==''?'':' بملاحظة '.$ex->note);
                $activty->save();
            } else {
                $d = Auth::user()->device;
                if (round($d->treasury_value, 2) < $request->price) {
                    throw new \Exception('المال في الدرج غير كافي لتغطيه المبلغ حيث المال في الدرج ' . round($d->treasury_value, 2) . ' ج ' .
                        'والمبلغ المراد صرفة ' . $request->price . ' ج ');
                }
                $d->treasury_value -= $request->price;
                $d->save();
                Session::flash('success', 'اضافة مصروف جديد وتم خصم المبلغ من الدرج');
                $activty = new Activity();
                $activty->user_id = Auth::user()->id;
                $activty->type = 11;
                $activty->relation_treasury = 2;
                $activty->treasury_value = $request->price;
                $activty->device_id = Auth::user()->device_id;
                $activty->data = 'اضافة مصروف جديد إلى قسم ' . $expensesTypeName . ' بمبلغ ' . $request->price . ' جنية ' .
                    ' وتم خصم المبلغ من الدرج ليصبح المال فى الدرج '.round( $d->treasury_value,2).'ج '.($ex->note==''?'':' بملاحظة '.$ex->note);
                $activty->save();
            }
        } catch (\Exception $e) {
            DB::rollback();
//            return  $e->getMessage();
            Session::flash('fault', $e->getMessage());
            return back();
//            throw $e;
        }
        DB::commit();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Expense $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
        $ex = $expense;
        $exCategory = $expense->expense_type;
        $device = $ex->device;

        DB::beginTransaction();
        try {
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 11;
            if (Auth::user()->type != 1 && Auth::user()->notification_when_delete_expenses) {
                $activity->notification = 1;
            }
            $activity->device_id = Auth::user()->device_id;
            if ($ex->state){
                $activity->relation_treasury = 1;
                $activity->relation_treasury =  $ex->price;
            }


            //subtract money from treasury when expenses state is (1) take money from treasury

            if ($ex->state == 1) {
                $device->treasury_value += $ex->price;
                $device->save();
            }
            $activity->data = 'حذف مصروف من قسم ' . $exCategory->name . ' بقيمة ' . $ex->price . ' ج ' .
                ($ex->state == 1 ? 'وتم  إضافة المبلغ إلى الدرج ليصبح المال فى الدرج ' . round($device->treasury_value,2).'ج ' : ' ولم يتم التغيير فى الدرج ').($ex->note==''?'':' وكان بملاحظة '.$ex->note);
            Session::flash('success', $activity->data);

            $activity->save();
            $ex->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            Session::flash('fault', $e->getMessage());
            return back();
        }
        DB::commit();
        return back();
    }

    public function getData(Request $r)
    {
        //used in expenses.index.blade
        if ($r->type == 'getDataByDateCreate') {
            return Expense::with('user')->with('device')->with('expense_type')->orderBy('id', 'desc')->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
        }

        //used in expenses.index.blade
        if ($r->type == 'searchInNote') {
            return Expense::with('user')->with('device')->with('expense_type')->orderBy('id', 'desc')->
            where('note', 'like', '%' . $r->search . '%')->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->
            get();
        }

        //used in expenses.index.blade
        if ($r->type == 'searchByType') {
            return Expense::with('user')->with('device')->with('expense_type')->orderBy('id', 'desc')->
            where('expense_type_id', $r->type_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->
            get();
        }

    }
}
