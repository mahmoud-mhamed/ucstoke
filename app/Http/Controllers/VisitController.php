<?php

namespace App\Http\Controllers;

use App\Account;
use App\Activity;
use App\Bill;
use App\Device;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class VisitController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPower:allow_manage_visit', ['only' => ['index']]);
        $this->middleware('checkPower:allow_add_visit', ['only' => ['create', 'store']]);
        $this->middleware('checkPower:allow_manage_visit', ['only' => ['edit', 'update']]);
        $this->middleware('checkPower:allow_delete_visit', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        //
        if (isset($r->id)){
            return view('visits.index',[
                'bill_id'=>$r->id,
            ]);
        }else{
            return view('visits.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        if (isset($r->id)){
            $bill=Bill::with('account')->findOrFail($r->id);
            return view('visits.create_edit',[
                'bill'=>$bill,
                'type'=>'create'
            ]);
        }else{
            return view('visits.create_edit',[
                'type'=>'create'
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $bill='';
        if (isset($request->bill_id)){
            $bill=Bill::with('account')->findOrFail($request->bill_id);
        }
        DB::beginTransaction();
        try {
            $n=new Visit();
            $n->user_id=Auth::user()->id;
            $n->device_id=Auth::user()->device_id;
            if (isset($request->bill_id)){
                $n->bill_id=$bill->id;
                $n->account_id=$bill->account_id;
            }
            $n->price=$request->price;
            $n->type=$request->type;
            $n->state_visit=$request->stateFinish==1?1:0;
            $n->alarm_before=$request->alarm_before;
            $n->date_alarm=$request->date_alarm;
            if ($request->stateFinish==1){
                $n->date_finish=$request->date_finish;
                $d=Device::find(Auth::user()->device_id);
                $d->treasury_value +=$request->price;
                $d->save();
            }
            $n->note=isset($request->note)?$request->note:'';
            $n->save();

            Session::flash('success', 'تمت العملية بنجاح');
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            if ($n->type==3){
                $activity->data = 'إضافة مهمة بمبلغ '.$request->price.' ج '.'بتاريخ '.$n->date_alarm.' بملاحظة '.$n->note;
            }else{
                $activity->data = 'إضافة زيارة بمبلغ '.$request->price.' ج '.'بتاريخ '.$n->date_alarm.' لفاتورة رقم  '.$n->bill_id.' حيث الشخص صاحب الفاتورة هو '.$bill->account->name.' بملاحظة '.$n->note;
            }
            if ($request->stateFinish==1 && $request->price!='0') {
                $activity->data .=' وتم إضافة المبلغ إلى الدرج ليصبح المال فى الدرج '.$d->treasury_value.'ج';
                $activity->relation_treasury=$n->price>0?1:2;
                $activity->treasury_value=$request->price;
            }else{
                $activity->data .=' ولم يتم التغير فى الدرج ';
            }

            $activity->type = 19;

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
     * Display the specified resource.
     *
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function show(Visit $visit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $v=Visit::findOrFail($id);
        if ($v->bill_id!=null){
            return view('visits.create_edit',[
                'visit'=>$v,
                'bill'=>Bill::with('account')->find($v->bill_id),
                'type'=>'edit'
            ]);
        }else{
            return view('visits.create_edit',[
                'visit'=>$v,
                'type'=>'edit'
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $n=Visit::findOrFail($id);
        DB::beginTransaction();
        try {
            $activity = new Activity();
            //update treasury
            //if old state and new state finish
            $aData='';
            $d=Device::find(Auth::user()->device_id);
            if ($n->state_visit==0 && $request->stateFinish==1){
                if ($request->price!=0){
                    $d->treasury_value +=$request->price;
                    $d->save();
                    $aData=' وتم إضافة المبلغ إلى الدرج ليصبح المال فى الدرج '.$d->treasury_value.'ج';
                    $activity->relation_treasury=$n->price>0?1:2;
                    $activity->treasury_value=$request->price;
                }
            }else if ($n->state_visit==1 && $request->stateFinish==0){
                if ($n->price!=0){
                    $d->treasury_value -=$request->price;
                    $d->save();
                    $aData=' وتم خصم المبلغ من الدرج ليصبح المال فى الدرج '.$d->treasury_value.'ج';
                    $activity->relation_treasury=2;
                    $activity->treasury_value=$n->price;
                }
            }else if ($n->state_visit==1 && $request->stateFinish==1){
                if ($n->price < $request->price){
                    $d->treasury_value +=($request->price - $n->price);
                    $d->save();
                    $aData=' وتم إضافة المبلغ إلى الدرج ليصبح المال فى الدرج '.$d->treasury_value.'ج';
                    $activity->relation_treasury=1;
                    $activity->treasury_value=($request->price - $n->price);
                }else if ($n->price > $request->price){
                    $d->treasury_value +=($request->price - $n->price);
                    $d->save();
                    $aData=' وتم خصم المبلغ من الدرج ليصبح المال فى الدرج '.$d->treasury_value.'ج';
                    $activity->relation_treasury=2;
                    $activity->treasury_value=($request->price - $n->price);
                }
            }

            $n->price=$request->price;
            $n->type=$request->type;
            $n->state_visit=$request->stateFinish==1?1:0;
            $n->alarm_before=$request->alarm_before;
            $n->date_alarm=$request->date_alarm;
            $n->date_finish=$request->stateFinish==1?$request->date_finish:null;
            $n->note=isset($request->note)?$request->note:'';
            $n->save();

            Session::flash('success', 'تمت العملية بنجاح');
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            if ($n->type==3){
                $activity->data = 'تعديل مهمة بملاحظة '.$n->note;
            }else{
                $activity->data = 'تعديل زيارة لفاتورة رقم  '.$n->bill_id.' حيث الشخص صاحب الفاتورة هو '.$n->account->name.' بملاحظة '.$n->note;
            }
            $activity->data.=$aData;
            $activity->type = 19;

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
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $v=Visit::with('account')->findOrFail($id);
        try {
            Session::flash('success', 'تمت العملية بنجاح ');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف ' . ($v->type==3?'مهمة':'زيارة') . ' بتاريخ إشعار ' . $v->date_alarm . ' بقيمة ' . $v->price . 'ج'.' وتاريخ الإنتهاء  '.
            $v->date_finish.' وملاحظة '.$v->note;
            $device=Device::find(Auth::user()->device_id);
            $aData='';
            if ($v->state_visit==1){
                $device->treasury_value -=$v->price;
                $device->save();
                if ($v->price!=0){
                    $activty->relation_treasury=2;
                    $activty->treasury_value=$v->price;
                    $aData=' وتم خصم المبلغ من الدرج ليصبح المال فى الدرج '.$device->treasury_value.' ج ';
                }
            }
            if ($v->bill_id!=null){
                $activty->data = 'حذف ' . ($v->type==3?'مهمة':'زيارة') .' برقم فاتورة '.$v->bill_id.' والشخص صاحب الفاتورة هو '.$v->account->name. ' بتاريخ إشعار ' . $v->date_alarm . ' بقيمة ' . $v->price . 'ج'.' وتاريخ الإنتهاء  '.
                    $v->date_finish.' وملاحظة '.$v->note;
            }
            $activty->data .=$aData;
            $activty->type = 19;
            if (Auth::user()->type!=1 && Auth::user()->notification_when_delete_visit){
                $activty->notification=1;
            }
            $activty->save();

            $v->delete();
            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'حصل خطاء فى العملية ');
            return back();
        }
        return back();
    }

    public function getData(Request $r)
    {
        if ($r->type=='getDataByDateCreate'){
            return Visit::with('user')->with('device')->with('account')->orderBy('id', 'desc')->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
        }

        if ($r->type=='getDataByBillId'){
            return Visit::with('user')->with('device')->with('account')->orderBy('id', 'desc')->
            where('bill_id',$r->search)->get();
        }

        if ($r->type=='getNotFinish'){
            return Visit::with('user')->with('device')->with('account')->orderBy('id', 'desc')->
            where('state_visit','0')->get();
        }
    }
}
