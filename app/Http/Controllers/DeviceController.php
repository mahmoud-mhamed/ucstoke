<?php

namespace App\Http\Controllers;

use App\Activity;
use App\BillPrint;
use App\Device;
use App\DeviceStoke;
use App\Stoke;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('devices.index', [
            'devices' => Device::With('mainStoke')->with('allowedStoke')->orderby('name','asc')->orderby('name')->get(),
            'stokes'=>Stoke::orderby('name')->get(),
            'prints'=>BillPrint::orderby('name')->get(),
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function show(Device $device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Device $device)
    {
        //
        $s = $device;

        $request->validate([
            'name' => 'required|max:50|unique:devices,name'
        ]);
        $oldName = $s->name;
        $s->name = $request->name;
        $s->save();


        Session::flash('success', 'تعديل جهاز');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = ' تعديل إسم جهاز من إسم ' . $oldName . ' إلي إسم ' . $request->name ;
        $activty->type = 9;
        $activty->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        //
    }

    public function changeState(Request $r,$id)
    {
        $o = Device::findOrFail($id);
        //type ==0 for change treasury state,1 for change default stoke

        if($r->type==1){
            $o->default_stoke=$r->stoke_id;
            $o->save();

            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'تغير المخزن الإفتراضى فى الجهاز ' . $o->name . ' إلى ' . $o->mainStoke->name;
            $activty->type = 9;
            $activty->save();

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }

        if($r->type==2){
            if ($r->state_access=='1'){//for allow access to this stoke
                $d=new DeviceStoke();
                $d->device_id=$id;
                $d->stoke_id=$r->stoke_id;
                $d->save();
                $activty = new Activity();
                $activty->user_id = Auth::user()->id;
                $activty->device_id = Auth::user()->device_id;
                $activty->data = 'السماح للجهاز ' . $o->name . 'بالوصول إلى المخزن ' . Stoke::find($r->stoke_id)->name;
                $activty->type = 9;
                $activty->save();
            }else{//for don't allow access to this stoke
                DeviceStoke::where('device_id',$id)->where('stoke_id',$r->stoke_id)->delete();
                if ($o->default_stoke==$r->stoke_id){
                    $o->default_stoke=null;
                    $o->save();
                }
                $activty = new Activity();
                $activty->user_id = Auth::user()->id;
                $activty->device_id = Auth::user()->device_id;
                $activty->data = 'منع الجهاز ' . $o->name . 'من الوصول إلى المخزن ' . Stoke::find($r->stoke_id)->name;
                $activty->type = 9;
                $activty->save();
            }

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }
    }

    public function changeDefaultBillPrint($id,Request $r)
    {
        $d=Device::findOrFail($id);
        $d->design_bill_print=$r->design_print_id;
        $d->save();
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تغير ديزاين الطباعة للفواتير فى الجهاز ' . $d->name . ' إلى ' . BillPrint::find($r->design_print_id)->name;
        $activty->type = 9;
        $activty->save();
        Session::flash('success', 'تمت العملية بنجاح');
        return back();
    }
}
