<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Device;
use App\DeviceStoke;
use App\Make;
use App\Product;
use App\ProductMove;
use App\RelationProductUnit;
use App\Rules\valid_qte;
use App\SaleMakeQteDetails;
use App\Stoke;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MakeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPower:allow_manage_make,product_make', ['only' => ['index']]);
        $this->middleware('checkPower:allow_add_make,product_make', ['only' => ['create', 'store']]);
        $this->middleware('checkPower:allow_delete_make,product_make', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        //
        if (isset($r->make_id)) {
            return view('makings.index', [
                'products' => Product::where('allow_make', true)->where('state', true)->orderby('name')->get(),
                'stokes' => DeviceStoke::with('Stoke')->where('device_id', Auth::user()->device_id)->get(),
                'result' => Make::with('user')->with('device')->with('stoke')->with('product')->with('productUnit')->findOrFail($r->make_id),
            ]);
        } else {
            return view('makings.index', [
                'products' => Product::where('allow_make', true)->where('state', true)->orderby('name')->get(),
                'stokes' => DeviceStoke::with('Stoke')->where('device_id', Auth::user()->device_id)->get(),
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
        $devise_stokes = Device::with('allowedStoke')->where('id', Auth::user()->device_id)->first();
        return view('makings.create', [
            'products' => Product::where('allow_make', true)->where('state', true)->orderby('name')->get(),
            'devise_stokes' => $devise_stokes,
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
            'product_id' => 'required|exists:products,id',//product_id
            'stoke_id' => 'required|exists:stokes,id',//stoke_id
            'qteMaking' => ['gt:0', new valid_qte],//qte
            'note' => 'max:150',
        ]);

        $product = Product::with('relationProductMake')->findOrFail($request->product_id);

        //convert qte to main qte
        $qteByMain = $request->qteMaking;
        if ($request->unit_relation_id != 0) {
            $qteByMain = $qteByMain / (RelationProductUnit::findOrFail($request->unit_relation_id)->relation_qte);
        }

        DB::beginTransaction();
        try {
            //add making
            $m = new Make();
            $m->device_id = Auth::user()->device_id;
            $m->user_id = Auth::user()->id;
            $m->stoke_id = $request->stoke_id;
            $m->product_id = $request->product_id;
            $tempUnitRelation = ($request->unit_relation_id != 0) ? (RelationProductUnit::findOrFail($request->unit_relation_id)) : '';
            $productUnitId = ($request->unit_relation_id != 0) ? ($tempUnitRelation->product_unit_id) : $product->product_unit_id;
            $m->product_unit_id = $productUnitId;
            $m->relation_qte = ($request->unit_relation_id == 0) ? 1 : ($tempUnitRelation->relation_qte);

            $m->qte = $request->qteMaking * $m->relation_qte;
            $m->note = $request->note == null ? '' : $request->note;

            $m->save();

            //subtract qte from store and add it to saleMakeQteDetail
            $totalPriceForMake = 0;
            foreach ($product->relationProductMake as $details) {
                $totalPriceForDetails = 0;
                $detailsQte = $details->qte_creator * $m->qte;
                $tempQteResult = $detailsQte;
                $storeData = Store::where('product_id', $details->creator_id)->where('stoke_id', $request->stoke_id)->where('qte', '>', 0)->get();
                foreach ($storeData as $store) {
                    if ($store->qte > 0 && $tempQteResult>0){
                        if ($tempQteResult <= $store->qte ) {
                            $totalPriceForDetails += ($tempQteResult * $store->price);

                            $store->qte -= $tempQteResult;
                            $store->save();
                            $s = new SaleMakeQteDetails();
                            $s->make_id = $m->id;
                            $s->store_id = $store->id;
                            $s->qte = $tempQteResult;
                            $s->save();

                            $productMove = new ProductMove();
                            $productMove->device_id = Auth::user()->device_id;
                            $productMove->user_id = Auth::user()->id;
                            $productMove->store_id = $store->id;
                            $productMove->stoke_id = $request->stoke_id;
                            $productMove->product_id = $store->product_id;
                            $productMove->product_unit_id = $product->product_unit_id;
                            $productMove->relation_qte = 1;
                            $productMove->qte = $tempQteResult;
                            $productMove->price = $store->price;
                            $productMove->type = 5;
                            $productMove->make_id = $m->id;
                            $productMove->note = $m->note;
                            $productMove->save();

                            $tempQteResult = 0;
                        } else{
                            $productMove = new ProductMove();
                            $productMove->device_id = Auth::user()->device_id;
                            $productMove->user_id = Auth::user()->id;
                            $productMove->store_id = $store->id;
                            $productMove->stoke_id = $request->stoke_id;
                            $productMove->product_id = $store->product_id;
                            $productMove->product_unit_id = $product->product_unit_id;
                            $productMove->relation_qte = 1;
                            $productMove->qte = $store->qte;
                            $productMove->price = $store->price;
                            $productMove->type = 5;
                            $productMove->make_id = $m->id;
                            $productMove->note = $m->note;
                            $productMove->save();


                            $totalPriceForDetails += ($store->qte * $store->price);

                            $s = new SaleMakeQteDetails();
                            $s->make_id = $m->id;
                            $s->store_id = $store->id;
                            $s->qte = $store->qte;
                            $s->save();
                            $tempQteResult -= $store->qte;
                            $store->qte = 0;
                            $store->save();
                        }
                    }else{
                        break;
                    }
                }

                if ($tempQteResult != 0) {
                    DB::rollback();
                    Session::flash('fault', 'الكمية ' . $detailsQte . ' ' .
                        ($details->productCreator->productUnit->name) . ' من المنتج ' . $details->productCreator->name .
                        ' اللأزمة للإنتاج ' . ' غير موجوده فى المخزن بعجز ' . $tempQteResult . ($details->productCreator->productUnit->name));
                    return back();
                }

                $totalPriceForMake += $totalPriceForDetails;
            }

            $m->price_make = $totalPriceForMake / $m->qte;

            //add qte to store
            $makeStore = Store::where('product_id', $product->id)->where('stoke_id', $request->stoke_id)->where('type', 1)->where('price', $m->price_make)->first();
            if ($makeStore != '') {
                $makeStore->qte += $m->qte;
                $makeStore->save();
            } else {
                $makeStore = new Store();
                $makeStore->stoke_id = $request->stoke_id;
                $makeStore->product_id = $product->id;
                $makeStore->qte = $m->qte;
                $makeStore->price = $m->price_make;
                $makeStore->type = 1;
                $makeStore->save();
            }
            $m->store_id = $makeStore->id;
            $m->save();

            $productMove = new ProductMove();
            $productMove->device_id = Auth::user()->device_id;
            $productMove->user_id = Auth::user()->id;
            $productMove->store_id = $store->id;
            $productMove->stoke_id = $m->stoke_id;
            $productMove->product_id = $m->product_id;
            $productMove->product_unit_id = $m->product_unit_id;
            $productMove->relation_qte = $m->relation_qte;
            $productMove->qte = $m->qte;
            $productMove->price = $m->price_make;
            $productMove->type = 4;
            $productMove->make_id = $m->id;
            $productMove->note = $m->note;
            $productMove->save();

            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'عمل إنتاج للمنتج ' . $product->name . ' فى المخزن ' . (Stoke::find($m->stoke_id)->name) . ' والكمية المنتجه هى ' . (round($m->qte / $m->relation_qte)) . ' ' . $m->productUnit->name;
            $activity->type = 16;
            $activity->save();

        } catch (\Exception $e) {
            DB::rollback();
//            return $e->getMessage();
            Session::flash('fault', $e->getMessage());
            return back();
//            throw $e;
        }
        DB::commit();
        Session::flash('success', 'تمت العملية بنجاح');
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Make $make
     * @return \Illuminate\Http\Response
     */
    public function show(Make $make)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Make $make
     * @return \Illuminate\Http\Response
     */
    public function edit(Make $make)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Make $make
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Make $make)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Make $make
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $m = Make::with('store')->with('saleMakeQteDetail')->with('product')->with('productUnit')->findOrFail($id);

//        return $m;
        DB::beginTransaction();
        try {
            //check if qte delete exist in store
            if ($m->store->qte < $m->qte) {
                throw new \Exception('الكمية المراد حذفها غير موجودة فى المخزن حيث الكمية الموجودة فى المخزن '.$m->store->qte .$m->product->productUnit->name.' والكمية المراد حذفها '.(round($m->qte )) . ' ' . $m->product->productUnit->name);
            }
            $m->store->qte -= $m->qte;
            $m->store->save();
            foreach ($m->saleMakeQteDetail as $d) {
                $d->store->qte += $d->qte;
                $d->store->save();
            }
            $tempActiviyData = 'حذف عملية إنتاج للمنتج ' . $m->product->name . ' وكانت الكمية المنتجة ' .
                (round($m->qte / $m->relation_qte)) . ' ' . $m->productUnit->name . ' وتم إرجاع منتجات الإنتاج للمخزن ' . ' وملاحظة الإنتاج ' . $m->note;
            $m->delete();

            Session::flash('success', 'حذف عملية إنتاج');
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = $tempActiviyData;
            $activity->type = 16;
            if (Auth::user()->type != 1 &&
                Auth::user()->notification_delete_make) {
                $activity->notification = 1;
            }
            $activity->save();
        } catch (\Exception $e) {
            DB::rollback();
//            return $e->getMessage();
            Session::flash('fault', $e->getMessage());
            return back();
//            throw $e;
        }
        DB::commit();

        return back();
    }

    public function getData(Request $r)
    {
        if ($r->type == 'getMakingData') {//used in makings.index.blade.php
            return Make::with('user')->with('stoke')->with('device')->with('product')->with('productUnit')->
            where('stoke_id', $r->stoke_id)->
            whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->orderBy('created_at','desc')->get();
        }

        if ($r->type == 'getMakeDetailsData') {//used in makings.index.blade.php
            return SaleMakeQteDetails::with('storeWithProduct')->
            where('make_id', $r->make_id)->get();
        }
    }
}
