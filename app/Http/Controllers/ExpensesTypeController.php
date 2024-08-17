<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ExpensesType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ExpensesTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('expenses_types.index');
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
        $request->validate(['name' => 'required|max:250|unique:expenses_types,name']);
        $nr = new ExpensesType();
        $nr->name = $request->name;
        $nr->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'اضافة قسم مصروفات جديد باسم ' . $nr->name ;
        $activty->type =11;
        $activty->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExpensesType  $expensesType
     * @return \Illuminate\Http\Response
     */
    public function show(ExpensesType $expensesType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExpensesType  $expensesType
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpensesType $expensesType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpensesType  $expensesType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpensesType $expensesType)
    {
        //
        $s = $expensesType;

        $request->validate([
            'name' => 'required|max:50|unique:expenses_types,name'
        ]);
        $oldName = $s->name;
        $s->name = $request->name;
        $s->save();


        Session::flash('success', 'تعديل قسم مصروفات');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->data = 'تعديل قسم مصروفات من إسم ' . $oldName . ' إلي إسم ' . $request->name ;
        $activty->type = 11;
        $activty->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpensesType  $expensesType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpensesType $expensesType)
    {
        //
        $o = $expensesType;
        try {
            $o->delete();

            Session::flash('success', 'حذف قسم مصروفات');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف قسم مصروفات باسم ' . $o->name;
            $activty->type = 11;
            $activty->save();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذا القسم لوجود تعاملات تخصة');
            return back();
        }
    }

    public function getData()
    {
        return ExpensesType::orderby('name')->get();
    }

    public function changeState($id)
    {
        //
        $o = ExpensesType::findOrFail($id);
        if ($o->state == 0) {
            $o->state = 1;
        } else {
            $o->state = 0;
        }
        $o->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تغير حالة قسم المصروفات ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
        $activty->type = 11;
        $activty->save();

        Session::flash('success', 'تمت العملية بنجاح');
        return back();

    }
}
