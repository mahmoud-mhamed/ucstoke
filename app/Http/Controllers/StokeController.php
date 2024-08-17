<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Stoke;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StokeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('stokes.index');
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
        $request->validate(['name' => 'required|max:250|unique:stokes,name']);
        $nr = new Stoke();
        $nr->name = $request->name;
        $nr->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'اضافة مخزن جديد باسم ' . $nr->name ;
        $activty->type = 1;
        $activty->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $s = Stoke::findOrFail($id);

        $request->validate([
            'name' => 'required|max:50|unique:stokes,name'
        ]);
        $oldName = $s->name;
        $s->name = $request->name;
        $s->save();


        Session::flash('success', 'تعديل مخزن');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تعديل إسم مخزن من إسم ' . $oldName . ' إلي إسم ' . $request->name ;
        $activty->type = 1;
        $activty->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $o = Stoke::findOrFail($id);
        try {
            $o->delete();

            Session::flash('success', 'حذف مخزن');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف مخزن  باسم ' . $o->name;
            $activty->type = 1;
            $activty->save();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذا المخزن لوجود تعاملات تخصة');
            return back();
        }
    }

    public function getData()
    {
        return Stoke::orderby('name')->get();
    }

    public function changeState($id)
    {
        //
        $o = Stoke::findOrFail($id);
        if ($o->state == 0) {
            $o->state = 1;
        } else {
            $o->state = 0;
        }
        $o->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تغير حالة المخزن ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
        $activty->type = 1;
        $activty->save();

        Session::flash('success', 'تمت العملية بنجاح');
        return back();

    }
}
