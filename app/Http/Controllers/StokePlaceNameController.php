<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Product;
use App\ProductCategory;
use App\Stoke;
use App\StokePlaceName;
use App\StokeProductPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StokePlaceNameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('stoke_product_places.index');
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
        $request->validate(['name' => 'required|max:250|unique:stoke_place_names,name']);
        $nr = new StokePlaceName();
        $nr->name = $request->name;
        $nr->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'اضافة مكان حفظ جديد باسم ' . $nr->name ;
        $activty->type = 2;
        $activty->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StokePlaceName  $stokePlaceName
     * @return \Illuminate\Http\Response
     */
    public function show(StokePlaceName $stokePlaceName)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StokePlaceName  $stokePlaceName
     * @return \Illuminate\Http\Response
     */
    public function edit(StokePlaceName $stokePlaceName)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StokePlaceName  $stokePlaceName
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $s = StokePlaceName::findOrFail($id);

        $request->validate([
            'name' => 'required|max:50|unique:stoke_place_names,name'
        ]);
        $oldName = $s->name;
        $s->name = $request->name;
        $s->save();


        Session::flash('success', 'تعديل مكان');
        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تعديل مكان حفظ من إسم ' . $oldName . ' إلي إسم ' . $request->name ;
        $activty->type = 2;
        $activty->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StokePlaceName  $stokePlaceName
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $o = StokePlaceName::findOrFail($id);
        try {
            $o->delete();

            Session::flash('success', 'حذف مكان');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف مكان حفظ باسم ' . $o->name;
            $activty->type = 2;
            $activty->save();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذا المكان لوجود حفظ من خلالة');
            return back();
        }
    }

    public function getData()
    {
        return StokePlaceName::orderby('name')->get();
    }

    public function changeState($id)
    {
        //
        $o = StokePlaceName::findOrFail($id);
        if ($o->state == 0) {
            $o->state = 1;
        } else {
            $o->state = 0;
        }
        $o->save();

        $activty = new Activity();
        $activty->user_id = Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data = 'تغير حالة مكان الحفظ ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
        $activty->type = 2;
        $activty->save();

        Session::flash('success', 'تمت العملية بنجاح');
        return back();

    }

    public function showProductPlace()
    {
//        return Product::with('place')->with('productCategory')->orderBy('name')->get();
        return view('stoke_product_places.show_product_place', [
            'products' => Product::with('place')->with('productCategory')->orderBy('name')->get(),
            'categories' => ProductCategory::orderby('name')->get(),
            'stokes'=>Stoke::where('state',1)->orderby('name')->get(),
            'places'=>StokePlaceName::orderby('name')->get(),
        ]);
    }

    public function updateProductPlace(Request $r)
    {
        if ($r->place_id==0){
            StokeProductPlace::where('product_id',$r->product_id)->where('stoke_id',$r->stoke_id)->delete();
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف مكان حفظ المنتج ' . Product::find($r->product_id)->name . ' فى المخزن ' . Stoke::find($r->stoke_id)->name;
            $activty->type = 2;
            $activty->save();

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }else{
            $activty = new Activity();
            $temp=StokeProductPlace::where('product_id',$r->product_id)->where('stoke_id',$r->stoke_id)->first();
            if ($temp==''){
                $temp=new StokeProductPlace();
                $temp->product_id=$r->product_id;
                $temp->stoke_id=$r->stoke_id;
                $activty->data = 'إضافة مكان حفظ للمنتج ' . Product::find($r->product_id)->name . ' فى المخزن ' .
                    Stoke::find($r->stoke_id)->name.' ليصبح '.StokePlaceName::find($r->place_id)->name;
            }else{
                $activty->data = 'تعديل مكان حفظ المنتج ' . Product::find($r->product_id)->name . ' فى المخزن ' .
                    Stoke::find($r->stoke_id)->name.' ليصبح '.StokePlaceName::find($r->place_id)->name;
            }
            $temp->stoke_place_name_id=$r->place_id;
            $temp->save();

            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;

            $activty->type = 2;
            $activty->save();

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }
    }
}
