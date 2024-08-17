<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Device;
use App\Rules\valid_price;
use App\Treasury;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TreasuryController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPower:allow_mange_treasury', ['only' => ['index']]);
        $this->middleware('checkPower:allow_delete_treasury', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('treasuries.index',[
            'devices'=>Device::orderby('name')->get(),
            'users'=>User::orderby('state')->get()
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
     * @param \App\Treasury $treasury
     * @return \Illuminate\Http\Response
     */
    public function show(Treasury $treasury)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Treasury $treasury
     * @return \Illuminate\Http\Response
     */
    public function edit(Treasury $treasury)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Treasury $treasury
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Treasury $treasury)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Treasury $treasury
     * @return \Illuminate\Http\Response
     */
    public function destroy(Treasury $treasury)
    {
        //
        $t=$treasury;
        $d=Device::findOrFail($t->device_id);
        $typeName=$t->type==0?'وضع مال':'أخذ مال';
        DB::beginTransaction();
        try{
            $activity=new Activity();
            $activity->user_id=Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->type = 10;
            if (Auth::user()->type!=1 && Auth::user()->create_notification_when_delete_treasury){
                $activity->notification=1;
            }
            $activity->data='حذف '.$typeName.' بقيمة '.$t->val.' ج '.($t->type==0?'وتم خصم المبلغ من الدرج':'وتم إضافة المبلغ إلى الدرج').' فى الخزنة '.$t->device->name;
            if ($t->type=='1'){
                $d->treasury_value+=$t->val;
                $d->save();


                $activity->relation_treasury = 1;
                $activity->treasury_value = $t->val;

                Session::flash('success','حذف اخذ مال');
            }else{
                if ($t->val <= $d->treasury_value){
                    $d->treasury_value-=$t->val;
                    $d->save();

                    $activity->relation_treasury = 2;
                    $activity->treasury_value = $t->val;

                    Session::flash('success','حذف وضع مال');
                }else{
                    throw new \Exception('المبلغ الموجود في الدرج غير كافي لحذف وضع المال المراد حذفة حيث المبلغ الموجود في الدرج '.
                        round($d->treasury_value,2) .' ج والمبلغ المراد حذفة '.$t->val .' ج ');
                }
            }
            $activity->save();
            $t->delete();

        }catch(\Exception $e)
        {
            DB::rollBack();
            Session::flash('fault',$e->getMessage());
            return back();
        }
        DB::commit();
        return back();
    }

    public function get_add_or_take_money()
    {
        $device = Device::findOrFail(Auth::user()->device_id);
        return view('treasuries.add_or_take_money', ['treasury' => $device->treasury_value, 'device' => $device]);
    }

    public function post_add_or_take_money($type, Request $r)//type 0 for add money ,1 for take money
    {
        $r->validate(
            ['money' => ['gt:0', new valid_price]]
        );
        DB::beginTransaction();
        try {
            $money = $r->money;
            $d = Device::findOrFail(Auth::user()->device_id);

            $treasury = new Treasury();
            $treasury->user_id = Auth::user()->id;
            $treasury->val = $money;
            $treasury->note = isset($r->note) ? $r->note : '';
            $treasury->type = $type;
            $treasury->device_id = Auth::user()->device_id;
            $treasury->save();
            if ($type == 0) {
                $d->treasury_value += $money;

                $activity = new Activity();
                $activity->user_id = Auth::user()->id;
                $activity->device_id = Auth::user()->device_id;
                $activity->data = ' وضع مال فى الدرج الجهاز ' . $d->name . ' والمبلغ هو ' . $money .
                    ' ج ' . ' ليصبح المال فى الدرج ' . round($d->treasury_value,2) . 'ج'.($treasury->note==''?'':' بملاحظة '.$treasury->note);;
                $activity->type = 10;

                $activity->relation_treasury = 1;
                $activity->treasury_value =$money;

                $activity->save();
            } else {
                if ($d->treasury_value >= $money) {
                    $d->treasury_value -= $money;

                    $activity = new Activity();
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;
                    $activity->data = ' أخذ مال من درج الجهاز ' . $d->name . ' والمبلغ هو ' . $money .
                        ' ج ' . ' ليصبح المال فى الدرج ' . round($d->treasury_value,2) . 'ج'.($treasury->note==''?'':' بملاحظة '.$treasury->note);
                    $activity->type = 10;
                    $activity->relation_treasury = 2;
                    $activity->treasury_value =$money;

                    $activity->save();
                } else {
                    throw new \Exception('هذا المبلغ غير متاح في الدرج!');
                }
            }
            $d->save();
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
        //used in view treasuries.index
        if ($r->type == 'getDataByDateCreate') {
            if ($r->device_id==''){
                return Treasury::with('user')->with('device')->orderBy('id', 'desc')->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }else{
                return Treasury::with('user')->with('device')->orderBy('id', 'desc')->
                    where('device_id',$r->device_id)->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }

        }

        //used in view treasuries.index
        if ($r->type == 'searchInNote') {
            if ($r->device_id=='') {
                return Treasury::with('user')->with('device')->orderBy('id', 'desc')->
                where('note', 'like', '%' . $r->search . '%')->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->
                get();
            }else{
                return Treasury::with('user')->with('device')->orderBy('id', 'desc')->
                where('note', 'like', '%' . $r->search . '%')->
                where('device_id',$r->device_id)->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->
                get();
            }

        }
    }
}
