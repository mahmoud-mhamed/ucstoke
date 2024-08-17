<?php

namespace App\Http\Controllers;

use App\Activity;
use App\EmpJop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmpJopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('emps.emp_jop_category');
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
        $request->validate(['name' => 'required|max:250|unique:emp_jops,name']);
        $nr = new EmpJop();
        $nr->name = $request->name;
        $nr->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'اضافة وظيفة جديدة باسم ' . $nr->name ;
        $activty->type = 17;
        $activty->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmpJop  $empJop
     * @return \Illuminate\Http\Response
     */
    public function show(EmpJop $empJop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmpJop  $empJop
     * @return \Illuminate\Http\Response
     */
    public function edit(EmpJop $empJop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmpJop  $empJop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $s = EmpJop::findOrFail($id);

        $request->validate([
            'name' => 'required|max:50|unique:emp_jops,name'
        ]);
        $oldName = $s->name;
        $s->name = $request->name;
        $s->save();


        Session::flash('success', 'تعديل وظيفة');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تعديل وظيفة من إسم ' . $oldName . ' إلي إسم ' . $request->name ;
        $activty->type = 17;
        $activty->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmpJop  $empJop
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $o = EmpJop::findOrFail($id);
        try {
            $o->delete();

            Session::flash('success', 'حذف وظيفة');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف وظيفة باسم ' . $o->name;
            $activty->type = 17;
            $activty->save();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذه الوظيفة لوجود موظفين بها');
            return back();
        }
    }
    public function getData()
    {
        return EmpJop::orderby('name')->get();
    }

    public function changeState($id)
    {
        //
        $o = EmpJop::findOrFail($id);
        if ($o->state == 0) {
            $o->state = 1;
        } else {
            $o->state = 0;
        }
        $o->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تغير حالة الوظيفة ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
        $activty->type = 17;
        $activty->save();

        Session::flash('success', 'تمت العملية بنجاح');
        return back();
    }
}
