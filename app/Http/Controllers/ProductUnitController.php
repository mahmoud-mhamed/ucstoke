<?php

namespace App\Http\Controllers;

use App\Activity;
use App\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('products_units.index');
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
        $request->validate([
            'name' => 'required|max:250|unique:product_units,name',
            'default_value'=>'required|gt:-1|numeric'
        ]);
        $nr = new ProductUnit();
        $nr->name = $request->name;
        $nr->default_value_for_min_qte=$request->default_value;
        $nr->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'اضافة وحدة منتجات جديدة باسم ' . $nr->name .' بقيمة إفتراضية لأقل عدد للمنتج عند إضافة منتج جديد '.$nr->default_value_for_min_qte ;
        $activty->type = 12;
        $activty->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function show(ProductUnit $productUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductUnit $productUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $s =ProductUnit::findOrFail($id);

        $validUnique='|unique:product_units,name';
        if ($s->name==$request->name&&$s->default_value_for_min_qte!=$request->default_value){
            $validUnique='';
        }
        $request->validate([
            'name' => 'required|max:50'.$validUnique,
            'default_value'=>'required|gt:-1|numeric'
        ]);
        $oldName = $s->name;
        $s->name = $request->name;
        $s->default_value_for_min_qte=$request->default_value;
        $s->save();


        Session::flash('success', 'تعديل وحدة منتجات');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        if ($validUnique==''){
            $activty->data = 'تعديل وحدة منتجات بإسم ' . $oldName .' لتصبح بقيمة إفتراضية لأقل عدد للمنتج عند إضافة منتج جديد '.$s->default_value_for_min_qte ;
        }else{
            $activty->data = 'تعديل وحدة منتجات من إسم ' . $oldName . ' إلي إسم ' . $request->name .' بقيمة إفتراضية لأقل عدد للمنتج عند إضافة منتج جديد '.$s->default_value_for_min_qte ;
        }
        $activty->type = 12;
        $activty->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductUnit  $productUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $o = ProductUnit::findOrFail($id);
        try {
            $o->delete();

            Session::flash('success', 'حذف وحدة منتجات منتجات');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف وحدة منتجات باسم ' . $o->name;
            $activty->type = 8;
            $activty->save();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذة الوحدة لوجود تعاملات تخصها');
            return back();
        }
    }
    public function getData()
    {
        return ProductUnit::orderby('name')->get();
    }

    public function changeState($id)
    {
        //
        $o = ProductUnit::findOrFail($id);
        if ($o->state == 0) {
            $o->state = 1;
        } else {
            $o->state = 0;
        }
        $o->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تغير حالة وحدة المنتجات ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
        $activty->type = 12;
        $activty->save();

        Session::flash('success', 'تمت العملية بنجاح');
        return back();

    }

}
