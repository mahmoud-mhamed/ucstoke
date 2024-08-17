<?php

namespace App\Http\Controllers;

use App\Activity;
use App\BillDetail;
use App\Device;
use App\DeviceStoke;
use App\Make;
use App\Product;
use App\ProductCategory;
use App\ProductMove;
use App\ProductUnit;
use App\Setting;
use App\Stoke;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('stokes.store', [
            'stokes' => DeviceStoke::with('Stoke')->where('device_id', Auth::user()->device_id)->get(),
//            'stokes'=>Stoke::where('state',1)->orderby('name')->get(),
            'categories' => ProductCategory::where('state', 1)->orderby('name')->get(),
            'setting'=>Setting::first(),
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
     * @param \App\Store $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Store $store
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $store = Store::with('stoke')->with('product')->findOrFail($id);
        return view('stokes.move_store', [
            'stokes' => DeviceStoke::with('Stoke')->where('device_id', Auth::user()->device_id)->get(),
            'store' => $store,
        ]);
        return $store;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Store $store
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        //
        $store = Store::with('stoke')->with('product')->findOrFail($id);
        if ($store->qte < $request->value_move) {
            Session::flash('fault', 'حصل خطاء فى العملية الكمية المراد نقلها أكبر من الكمية الموجودة!');
            return back();
        }
        DB::beginTransaction();
        try {
            //check if product exist with this price and type in stoke move
            $n_store = Store::where('stoke_id', $request->stoke_id)->where('product_id', $store->product_id)->
            where('price', $store->price)->where('type', $store->type)->first();
            $store->qte -= $request->value_move;
            $store->save();
            if ($n_store != '') {//product exist before in new stoke
                $n_store->qte += $request->value_move;
                $n_store->save();
            } else {//product not exist before in new stoke
                $n_store = new Store();
                $n_store->stoke_id = $request->stoke_id;
                $n_store->product_id = $store->product_id;
                $n_store->qte = $request->value_move;
                $n_store->price = $store->price;
                $n_store->type = $store->type;
                $n_store->save();
            }

            //add product move for first stoke (-)
            $product_move = new ProductMove();
            $product_move->device_id = Auth::user()->device_id;
            $product_move->user_id = Auth::user()->id;
            $product_move->store_id = $store->id;
            $product_move->stoke_id = $store->stoke_id;
            $product_move->product_id = $store->product_id;
            $product_move->product_unit_id = $store->product->product_unit_id;
            $product_move->relation_qte = 1;
            $product_move->qte = $request->value_move;
            $product_move->price = $n_store->price;
            $product_move->type = 10;
            $product_move->note = 'نقل من المخزن ' . $store->stoke->name . ' إلى المخزن ' . $n_store->stoke->name . (isset($request->note) ? ' بملاحظة ' . $request->note : '');
            $product_move->save();


            //add product move for second stoke (+)
            $product_move2 = new ProductMove();
            $product_move2->device_id = Auth::user()->device_id;
            $product_move2->user_id = Auth::user()->id;
            $product_move2->store_id = $n_store->id;
            $product_move2->stoke_id = $n_store->stoke_id;
            $product_move2->product_id = $n_store->product_id;
            $product_move2->product_unit_id = $store->product->product_unit_id;
            $product_move2->relation_qte = 1;
            $product_move2->qte = $request->value_move;
            $product_move2->price = $n_store->price;
            $product_move2->type = 11;
            $product_move2->note = 'نقل من المخزن ' . $store->stoke->name . ' إلى المخزن ' . $n_store->stoke->name . (isset($request->note) ? ' بملاحظة ' . $request->note : '');
            $product_move2->save();


            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'نقل ' . $request->value_move . ' ' . $store->product->productUnit->name . ' من المنتج ' . $store->product->name . ' من المخزن ' . $store->stoke->name . ' إلى المخزن ' . $n_store->stoke->name . ' بملاحظة ' . $n_store->note;
            $activity->type = 1;
            if (Auth::user()->type != 1 && Auth::user()->notification_when_move_product) {
                $activity->notification = 1;
            }
            $activity->save();
            Session::flash('success', 'تمت العملية بنجاح ');
        } catch (\Exception $e) {
            DB::rollback();
//            return $e->getMessage();
//            Session::flash('fault',$e->getMessage());
//            return back();
            throw $e;
        }
        DB::commit();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Store $store
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete damage
        $pm = ProductMove::findOrFail($id);
        $store = Store::findOrFail($pm->store_id);
        $product = Product::findOrFail($pm->product_id);

        DB::beginTransaction();
        try {
            //restore qte
            $store->qte += $pm->qte;
            $store->save();


            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'حذف تالف للمنتج ' . $product->name . ' والكمية التالفه المحذوفة هى ' . $pm->qte .
                (ProductUnit::find($product->product_unit_id)->name) . ' وتم إعادة الكمية إلى المخزن ' .
                (Stoke::find($store->stoke_id)->name) . ' ونوع التالف ' . ($pm->type == 2 ? 'تالف شراء ' : 'تالف إنتاج ') . ' وملاحظة التالف كانت ' . $pm->note;
            $activity->type = 8;
            if (Auth::user()->type != 1 && Auth::user()->notification_when_delete_damage) {
                $activity->notification = 1;
            }
            $activity->save();

            //delete pm
            $pm->delete();

            Session::flash('success', 'حذف تالف ');
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
        //in stokes.store.blade.php
        if ($r->type == 'getStokeData') {
            $stoke_id = $r->stoke_id;
            if ($stoke_id != '0') {
                return Product::with('productCategory')->
                with('productUnit')->with('relationProductUnit')->with('place')->
                with(['store' => function ($q) use ($stoke_id) {
                    $q->where('stoke_id', $stoke_id);
                }])->where('allow_no_qte', 0)->get();
            } else {
                return Product::with('productCategory')->
                with('productUnit')->with('relationProductUnit')->with('place')->
                with('store')->where('allow_no_qte', 0)->get();
            }

        }
        //in stokes.store.blade.php
        if ($r->type == 'getStokeDetailsData') {
            $stoke_id = $r->stoke_id;
            return Product::
            with('productUnit')->with('relationProductUnit')->
            with(['store' => function ($q) use ($stoke_id) {
                $q->where('stoke_id', $stoke_id);
            }])->where('allow_no_qte', 0)->where('id', $r->product_id)->get();
        }

        //in bills.create.blade.php
        if ($r->type == 'getQteForProductInStoke') {
            $relation_unit_id = $r->relation_unit_id;
            return Store::
            with('product')->
            with(['relationProductUnit' => function ($q) use ($relation_unit_id) {
                $q->where('id', $relation_unit_id);
            }])->where('stoke_id', $r->stoke_id)->where('qte', '>', '0')->
            where('product_id', $r->product_id)->get();
        }

        //in stokes.store.blade.php
        if ($r->type == 'getSourceForStore') {
            $bill = BillDetail::with('productUnit')->with('bill')->where('store_id', $r->store_id)->orderBy('id', 'desc')->take(5)->get();
            $product_move = ProductMove::with('productUnit')->where('store_id', $r->store_id)->where('type', 11)->orderBy('id', 'desc')->take(5)->get();
            $product_make = Make::with('productUnit')->where('store_id', $r->store_id)->orderBy('id', 'desc')->take(5)->get();
            return [$bill, $product_move, $product_make];
        }
    }

    public function addDamage(Request $r)
    {
        $store = Store::findOrFail($r->store_id);
        $product = Product::findOrFail($store->product_id);
        //check if qte in store less than qte damage
        if ($store->qte < $r->qte_damage) {
            Session::flash('fault', 'الكمية خاطئة برجاء إعادة المحاولة');
            return back();
        }
        DB::beginTransaction();
        try {
            //add data to product move
            $pm = new ProductMove();
            $pm->device_id = Auth::user()->device_id;
            $pm->user_id = Auth::user()->id;
            $pm->store_id = $store->id;
            $pm->stoke_id = $store->stoke_id;
            $pm->product_id = $store->product_id;
            $pm->product_id = $store->product_id;
            $pm->product_unit_id = $product->product_unit_id;
            $pm->relation_qte = 1;
            $pm->qte = $r->qte_damage;
            $pm->price = $store->price;
            $pm->price = $store->price;
            $pm->type = ($store->type == 0 ? 2 : 3);//for damage product buy or product make
            $pm->note = (isset($r->note) ? $r->note : '');
            $pm->save();

            //update store
            $store->qte -= $r->qte_damage;
            $store->save();

            Session::flash('success', 'إضافة تالف ');
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'إضافة تالف للمنتج ' . $product->name . ' والكمية التالفه هى ' . $r->qte_damage .
                (ProductUnit::find($product->product_unit_id)->name) . ' حيث الكمية كانت فى المخزن ' .
                (Stoke::find($store->stoke_id)->name) . ' وملاحظة التالف هى ' . $r->note;
            $activity->type = 8;
            if (Auth::user()->type != 1 && Auth::user()->notification_when_add_damage) {
                $activity->notification = 1;
            }
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
}
