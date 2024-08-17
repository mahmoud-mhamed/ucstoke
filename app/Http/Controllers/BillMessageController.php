<?php

namespace App\Http\Controllers;

use App\Activity;
use App\BillMessage;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BillMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('bills.message',['setting'=>Setting::first()]);
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
        $request->validate(['name' => 'required|max:250|unique:bill_messages,name']);
        $nr = new BillMessage();
        $nr->name = $request->name;
        $nr->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'اضافة رسالة فواتير جديدة وهى ' . $nr->name ;
        $activty->type = 15;
        $activty->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BillMessage  $billMessage
     * @return \Illuminate\Http\Response
     */
    public function show(BillMessage $billMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BillMessage  $billMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(BillMessage $billMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BillMessage  $billMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BillMessage $billMessage)
    {
        //
        $s = BillMessage::findOrFail($billMessage->id);

        $request->validate([
            'name' => 'required|max:50|unique:bill_messages,name'
        ]);
        $oldName = $s->name;
        $s->name = $request->name;
        $s->save();


        Session::flash('success', 'تعديل رسالة فواتير');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تعديل رسالة فواتير من ' . $oldName . ' إلي  ' . $request->name ;
        $activty->type = 15;
        $activty->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BillMessage  $billMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillMessage $billMessage)
    {
        //
        $o = BillMessage::findOrFail($billMessage->id);
        try {
            $o->delete();

            Session::flash('success', 'حذف رسالة فواتير');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف رسالة فواتير وهى ' . $o->name;
            $activty->type = 15;
            $activty->save();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذه الرسالة');
            return back();
        }
    }

    public function getData()
    {
        return BillMessage::orderby('name')->get();
    }

    public function changeState($id)
    {
        //
        $o = BillMessage::findOrFail($id);
        if ($o->state == 0) {
            $o->state = 1;
        } else {
            $o->state = 0;
        }
        $o->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تغير حالة رسالة الفواتير ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
        $activty->type = 15;
        $activty->save();

        Session::flash('success', 'تمت العملية بنجاح');
        return back();

    }

    public function setDefault($id,Request $r)
    {
        //
        $o = BillMessage::findOrFail($id);
        $setting=Setting::first();
        if($r->type==0){//default bill buy
            $setting->bill_message_buy_id=$id;
            $setting->save();
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'تعين رسالة أفتراضية لفواتير الشراء وهى ' . $o->name ;
            $activty->type = 15;
            $activty->save();

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }else{//default bill sale
            $setting->bill_message_sale_id=$id;
            $setting->save();
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'تعين رسالة أفتراضية لفواتير البيع وهى ' . $o->name ;
            $activty->type = 15;
            $activty->save();

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }



    }
}
