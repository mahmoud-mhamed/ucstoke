<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Device;
use App\Emp;
use App\EmpJop;
use App\EmpMove;
use App\Rules\valid_negative_price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EmpController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPower:allow_manage_emp', ['only' => ['index']]);
        $this->middleware('checkPower:allow_add_emp', ['only' => ['create', 'store']]);
        $this->middleware('checkPower:allow_manage_emp', ['only' => ['edit', 'update']]);
        $this->middleware('checkPower:allow_manage_emp', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        //
        if (isset($r->show_opertaion)) { //to mange emp operation
            return view('emps.index', [
                'emps' => Emp::with('user')->with('empJop')->orderBy('name')->where('device_id', Auth::user()->device_id)->get(),
                'jops' => EmpJop::where('state', 1)->orderBy('name')->get(),
                'devices' => Device::orderBy('name')->get(),
                'op' => true,
            ]);
        } else { //to mange emp
            return view('emps.index', [
                'emps' => Emp::with('user')->with('empJop')->orderBy('name')->get(),
                'jops' => EmpJop::where('state', 1)->orderBy('name')->get(),
                'devices' => Device::orderBy('name')->get()
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('emps.create', [
            'emps' => Emp::orderBy('name')->get(),
            'jops' => EmpJop::where('state', 1)->orderBy('name')->get(),
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
        $request->validate([
            'name' => 'required|max:250|unique:emps,name',
            'emp_jop_id' => 'required|max:250|exists:emp_jops,id',
            'tel' => 'max:250',
            'address' => 'max:250',
            'note' => 'max:250',
            'account' => [new valid_negative_price, 'gt:-1'],
            'salary_by_day' => [new valid_negative_price, 'gt:0'],
        ]);


        DB::beginTransaction();
        try {
            $n = new Emp();
            $n->user_id = Auth::user()->id;
            $n->emp_jop_id = $request->emp_jop_id;
            $n->device_id = Auth::user()->device_id;
            $n->name = $request->name;
            if ($request->tel)
                $n->tel = $request->tel;
            if ($request->note)
                $n->note = $request->note;
            if ($request->address)
                $n->address = $request->address;
            $n->account = $request->account;
            $n->day_salary = $request->salary_by_day;

            $n->save();

            if ($n->account != 0) {
                $nr = new EmpMove();
                $nr->user_id = Auth::user()->id;
                $nr->device_id = Auth::user()->device_id;
                $nr->emp_id = $n->id;
                $nr->value = $n->account;
                $nr->date = date("Y-m-d");
                $nr->type = 0;
                $nr->account_after_this_action = $n->account;
                $nr->note = $n->note;
                $nr->save();
            }

            Session::flash('success', 'اضافة موظف');
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'اضافة موظف جديد باسم ' . $request->name . ' بحساب ' . $request->account . ' ج ';
            $activity->type = 17;
            $activity->save();
//            return date("Y-m-d",strtotime($n->created_at));
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
     * @param \App\Emp $emp
     * @return \Illuminate\Http\Response
     */
    public function show(Emp $emp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Emp $emp
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return view('emps.edit', [
            'emp' => Emp::findOrFail($id),
            'jops' => EmpJop::where('state', 1)->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Emp $emp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Emp $emp)
    {
        //
        $validate_unique_name = $emp->name == $request->name ? '' : '|unique:emps,name';
        $oldName = $request->name;
        $request->validate([
            'name' => 'required|max:250' . $validate_unique_name,
            'emp_jop_id' => 'required|max:250|exists:emp_jops,id',
            'tel' => 'max:250',
            'address' => 'max:250',
            'note' => 'max:250',
            'salary_by_day' => [new valid_negative_price, 'gt:0'],
        ]);


        $emp->emp_jop_id = $request->emp_jop_id;
        $emp->name = $request->name;
        if ($request->tel)
            $emp->tel = $request->tel;
        if ($request->note)
            $emp->note = $request->note;
        if ($request->address)
            $emp->address = $request->address;
        $emp->day_salary = $request->salary_by_day;

        $emp->save();


        Session::flash('success', 'تعديل موظف موظف');
        $activity = new Activity();
        $activity->user_id = Auth::user()->id;
        $activity->device_id = Auth::user()->device_id;
        $activity->data = 'تعديل موظف باسم ' . $oldName . ' إلى إسم ' . $request->name;
        $activity->type = 17;
        $activity->save();
//            return date("Y-m-d",strtotime($n->created_at));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Emp $emp
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $s = Emp::findOrFail($id);

        try {

            Session::flash('success', 'حذف موظف');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف موظف باسم ' . $s->name . ' بحساب ' . round($s->account, 2) . 'ج';
            $activty->type = 12;

            $activty->save();
            $s->delete();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذا المظف لوجود تعاملات تخصه برجاء إلغاء تفعيلة بدل حذفة');
            return back();
        }
    }

    public function changeState(Request $r, $id)
    {
        //
        $o = Emp::findOrFail($id);
        if (isset($r->type)) {
            if ($r->type == 'changeState') {
                if ($o->state == 0) {
                    $o->state = 1;
                } else {
                    $o->state = 0;
                }
                $o->save();

                $activty = new Activity();
                $activty->user_id = Auth::user()->id;
                $activty->device_id = Auth::user()->device_id;
                $activty->data = 'تغير حالة الموظف ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
                $activty->type = 17;
                $activty->save();

                Session::flash('success', 'تمت العملية بنجاح');
                return back();
            }
        } else {//change default device
            $o->device_id = $r->select_device_id;
            $o->save();

            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'تغير الجهاز المحدد للموظف ' . $o->name . ' إلى ' . $o->device->name;
            $activty->type = 17;
            $activty->save();

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }

    }

    public function create_operation($emp_id, $op)
    {
        $emp = Emp::find($emp_id);
        $op_string = $op == 0 ? 'إضافى' : ($op == 1 ? 'خصم' : ($op == 2 ? 'سلفة' : 'دفع أجر'));
        if ($emp->device_id != Auth::user()->device_id) {
            $activty = new Activity();
            $activty->data = 'قام المستخدم ' . Auth::user()->name . ' بمحاولة تسجيل ' . $op_string . ' للموظف ' . $emp->name;

            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type = 0;
            $activty->notification = 1;
            $activty->save();
            Session::flash('fault', 'غير مصرح لك بفتح هذة الصفحة');
            return back();
        }

        return view('emps.add_operation', [
            'emp' => $emp,
            'type' => $op,
        ]);
    }

    public function post_operation($id, Request $r)
    {
        $emp=Emp::findOrFail($id);
        DB::beginTransaction();
        try {
            //check treasury
            if ($r->op_type == 2 || $r->op_type == 3) {
                $d = Device::findOrFail(Auth::user()->device_id);
                if ($d->treasury_value < $r->price) {
                    Session::flash('fault', 'حصل خطاء فى العملية المال فى الدرج غير كافى للعملية حيث المال فى الدرج '.round($d['treasury_value'],2).' ج '.' والمبلغ المراد دفعة '.
                        round($r->price,2).' ج');
                    return back();
                }else{
                    //update treasury
                    $d->treasury_value -=$r->price;
                    $d->save();

                    //update account
                    $emp->account -=$r->price;
                    $emp->save();



                    $activity = new Activity();
                    $activity->user_id = Auth::user()->id;
                    $activity->device_id = Auth::user()->device_id;
                    $activity->data = 'دفع '.($r->op_type == 2?' سلفة ':' أجر ').' للموظف ' . $emp->name . ' والمبلغ هو ' . $r->price . ' ج '.
                        ' ليصبح إجمالى حساب الموظف '.round($emp->account,2).'ج'.
                        ' و المال فى الدرج '.round($d->treasury_value,2).'ج';
                    $activity->relation_treasury = 2;
                    $activity->treasury_value = $r->price;
                    $activity->type = 17;
                    $activity->save();
                }
            }else{
                $activity = new Activity();
                $activity->user_id = Auth::user()->id;
                $activity->device_id = Auth::user()->device_id;
                if ($r->op_type==0){
                    $emp->account +=$r->price;
                    $emp->save();
                }else{
                    $emp->account -=$r->price;
                    $emp->save();
                }
                $activity->data = 'إضافة '.($r->op_type == 0?' إضافى ':' خصم ').' للموظف ' . $emp->name . ' والمبلغ هو ' . $r->price . ' ج '.
                    ' ليصبح إجمالى حساب الموظف '.round($emp->account,2).'ج';
                $activity->type = 17;
                $activity->save();
            }

            $nr = new EmpMove();
            $nr->user_id = Auth::user()->id;
            $nr->device_id = Auth::user()->device_id;
            $nr->emp_id = $emp->id;
            $nr->value = $r->price;
            $nr->date = date("Y-m-d");
            $nr->type = $r->op_type == 0 ? 1 : ($r->op_type == 1 ? 2 : ($r->op_type == 2 ? 3 : 4));
            $nr->account_after_this_action = $emp->account;
            $nr->note = isset($r->note)?$r->note:'';
            $nr->save();

            Session::flash('success', 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
//            throw $e;
        }
        DB::commit();
        return redirect(route('emps.index').'?show_opertaion=true');
    }

    public function report()
    {
        return view('emps.report', [
            'emps' => Emp::with('user')->with('empJop')->orderBy('name')->get(),
            'jops' => EmpJop::where('state', 1)->orderBy('name')->get(),
            'devices' => Device::orderBy('name')->get()
        ]);
    }
    public function report2()
    {
        return view('emps.report2', [
            'emps' => Emp::with('user')->with('empJop')->orderBy('name')->get(),
            'jops' => EmpJop::where('state', 1)->orderBy('name')->get(),
            'devices' => Device::orderBy('name')->get()
        ]);
    }

    public function getData(Request $r)
    {
        if ($r->type=='getDataByEmpIdAndDate'){
            if ($r->emp_id!=''){
                return EmpMove::with('emp')->with('device')->with('user')->
                orderBy('id','desc')->where('emp_id',$r->emp_id)->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }else{
                return EmpMove::with('emp')->with('device')->with('user')->orderBy('id','desc')->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }

        }
        if ($r->type=='getDataBy_move_day'){
            if ($r->emp_id!=''){
                return EmpMove::with('emp')->with('device')->with('user')->
                orderBy('id','desc')->where('emp_id',$r->emp_id)->
                whereBetween('date', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }else{
                return EmpMove::with('emp')->with('device')->with('user')->orderBy('id','desc')->
                whereBetween('date', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }

        }

        /*if ($r->type=='getDataByEmpIdAndDateWithEmp'){
            if ($r->emp_id!=''){
                return EmpMove::with('emp')->with('emp')->with('user')->
                orderBy('id','desc')->where('emp_id',$r->emp_id)->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }else{
                return EmpMove::with('emp')->with('emp')->with('user')->orderBy('id','desc')->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }
        }*/

        if ($r->type=='getDataByEmpIdAndDateMoveWithEmp'){
            if ($r->emp_id!=''){
                return EmpMove::with('emp')->with('emp')->with('user')->
                orderBy('id','desc')->where('emp_id',$r->emp_id)->
                whereBetween('date', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }else{
                return EmpMove::with('emp')->with('emp')->with('user')->orderBy('id','desc')->
                whereBetween('date', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->get();
            }
        }

        if ($r->type=='getAttendForDay'){
            $date=$r->date;
            $emp= Emp::with('empJop')->orderBy('name')->
            with(['empMove'=>function($q) use($date){
                $q->where('date',$date)->where('type',5);
            }])->
            where('device_id',Auth::user()->device_id)->where('state',1)->get();
            for ($i=0;$i<count($emp);$i++){
                if ($emp[$i]['empMove']==null && $emp[$i]['device_id']!=Auth::user()->device_id){
                    unset($emp[$i]);
                }
            }
            return $emp;
        }

    }

    public function show_emp_attend()
    {
        return view('emps.emp_attend',[
            'jops' => EmpJop::where('state', 1)->orderBy('name')->get(),
        ]);
    }

    public function change_emp_attend(Request $r)
    {
        $emp=Emp::find($r->emp_id);
        $stateAttendString=$r->state_now==0?'حضور':'غياب';
        //check if emp device not login device
        if ($emp->device_id!=Auth::user()->device_id){
            $activty=new Activity();
            $activty->data='قام المستخدم '.Auth::user()->name.' بمحاولة تسجيل الموظف '.
                $emp->name.' '.$stateAttendString.' عن يوم '.$r->date;
            $activty->user_id=Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type=0;
            $activty->notification=1;
            $activty->save();
            return 'error';
        }

        //check if this day is last day has attend
        $tempCheck=EmpMove::where('emp_id',$r->emp_id)->
        where('date','>',$r->date)->where('type',5)->first();
        if ($tempCheck!=null){
            return 'حصل خطاء فى العملية , الموظف مسجل حضور عن يوم '.$tempCheck->date.' ولا يوجوز تعديل حضورة قبل هذا اليوم';
        }
        DB::beginTransaction();
        //set emp attend
        if ($r->state_now==0){
            $emp->account +=$emp->day_salary;
            $emp->save();

            $nr = new EmpMove();
            $nr->user_id = Auth::user()->id;
            $nr->device_id = Auth::user()->device_id;
            $nr->emp_id = $emp->id;
            $nr->value = $emp->day_salary;
            $nr->date = $r->date;
            $nr->type = 5;
            $nr->account_after_this_action = $emp->account;
            $nr->save();

            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'تسجيل الموظف '.$emp->name.' حضور ليوم ' . $r->date . ' وتم إضافة ' . $emp->day_salary . ' ج '.
                'إلى حسابة ليصبح إجمالى حساب الموظف '.round($emp->account,2).'ج';
            $activity->type = 17;
            $activity->save();
        }else{//set emp not attend
            $empMove=EmpMove::where('emp_id',$r->emp_id)->where('date',$r->date)->
            where('type',5)->first();
            $moveValue=$empMove->value;
            $emp->account -=$moveValue;
            $emp->save();

            $empMove->type=7; //to set emp not attend
            $empMove->save();

            $nr = new EmpMove();
            $nr->user_id = Auth::user()->id;
            $nr->device_id = Auth::user()->device_id;
            $nr->emp_id = $emp->id;
            $nr->value = $moveValue;
            $nr->date = $r->date;
            $nr->type = 6;
            $nr->account_after_this_action = $emp->account;
            $nr->save();

            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'تسجيل الموظف '.$emp->name.' غياب ليوم ' . $r->date . ' وتم خصم ' . $moveValue . ' ج '.
                'من حسابة ليصبح إجمالى حساب الموظف '.round($emp->account,2).'ج';
            $activity->type = 17;
            $activity->save();
        }
        DB::commit();
        return 'success';
    }
}
