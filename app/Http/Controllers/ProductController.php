<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Barcode;
use App\Device;
use App\Product;
use App\ProductCategory;
use App\ProductMove;
use App\ProductUnit;
use App\RelationProductMake;
use App\RelationProductUnit;
use App\Rules\valid_negative_price;
use App\Rules\valid_qte;
use App\Setting;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
//        return Product::with('productCategory')->with('productUnit')->orderBy('name')->get();
        return view('products.index', [
            'products' => Product::with('productCategory')->with('productUnit')->orderBy('name')->get(),
            'categories' => ProductCategory::orderby('name')->get(),
            'barcode' => Barcode::first(),
            'setting' => Setting::first(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create', [
            'cuts' => ProductCategory::where('state', 1)->orderby('name', 'asc')->get(),
            'setting' => Setting::first(),
            'products' => Product::orderby('name')->get(),
            'units' => ProductUnit::where('state', 1)->orderby('name')->get(),
            'barcode' => Barcode::first(),
            'devise_stokes' => Device::with('allowedStoke')->where('id', Auth::user()->device_id)->first(),

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
            'name' => 'required|max:250|unique:products,name',
            'product_category_id' => 'exists:product_categories,id',
            'note' => 'max:250',
            'allow_buy' => 'boolean',
            'allow_sale' => 'boolean',
            'allow_make' => 'boolean',
            'allow_no_qte' => 'boolean',
            'product_unit_id' => 'exists:product_units,id',
            'min_qte' => 'required|gt:-1',
            'barcode1' => 'nullable|numeric',
            'barcode2' => 'nullable|numeric',
            'barcode3' => 'nullable|numeric',
            'price_buy' => ['gt:-1', new valid_negative_price],
            'price_sale1' => ['gt:-1', new valid_negative_price],
            'price_sale2' => ['gt:-1', new valid_negative_price],
            'price_sale3' => ['gt:-1', new valid_negative_price],
            'price_sale4' => ['gt:-1', new valid_negative_price],

            'relation_product_unit_id.*' => 'exists:product_units,id',
            'relation_qte.*' => ['gt:0', new valid_qte],
            'relation_barcode1.*' => 'nullable|numeric',
            'relation_barcode2.*' => 'nullable|numeric',
            'relation_barcode3.*' => 'nullable|numeric',
            'relation_price_buy.*' => ['gt:-1', new valid_negative_price],
            'relation_price_sale1.*' => ['gt:-1', new valid_negative_price],
            'relation_price_sale2.*' => ['gt:-1', new valid_negative_price],
            'relation_price_sale3.*' => ['gt:-1', new valid_negative_price],
            'relation_price_sale4.*' => ['gt:-1', new valid_negative_price],

            'relation_creator_id.*' => 'exists:products,id',
            'relation_qte_creator.*' => ['gt:-1', 'numeric'],
        ]);
        DB::beginTransaction();
        try {

            //check if selected type of product((buy or sale or make) at least one of this)
            if (!$request->allow_buy && !$request->allow_sale && !$request->allow_make) {
                throw new \Exception('برجاء تحديد نوع للمنتج من شراء أو بيع أو إنتاج');
            }

            //check Repeat Unit
            for ($i = 0; $i < (is_array($request->relation_product_unit_id) ? count($request->relation_product_unit_id) : 0); $i++) {
                if (array_count_values($request->relation_product_unit_id)[$request->relation_product_unit_id[$i]] > 1
                    || $request->relation_product_unit_id[$i] == $request->product_unit_id) {
                    throw new \Exception('الوحدة ' . ProductUnit::find($request->product_unit_id[$i])['name'] . ' مكررة ');
                }
            }

            //check Repeat Component
            for ($i = 0; $i < (is_array($request->relation_creator_id) ? count($request->relation_creator_id) : 0); $i++) {
                if (array_count_values($request->relation_creator_id)[$request->relation_creator_id[$i]] > 1) {
                    throw new \Exception('المكون ' . Product::find($request->relation_creator_id[$i])['name'] . ' مكررة ');
                }
                if ($request->relation_qte_creator[$i] <= 0) {
                    throw new \Exception('كمية المكون ' . Product::find($request->relation_creator_id[$i])['name'] . ' يجب أن تكون أكبر من 0 ');
                }

            }

            //check if product type no qte
            if ($request->allow_no_qte &&
                ($request->relation_product_unit_id != '' || $request->relation_creator_id != '')) {
                throw new \Exception('لا يجوز وجود وحدات أو مكونات لمنتج بدون كمية ');
            }


            //checkUniqueBarcodeInProduct
            $tempCheckUniqueBarcode = Product::whereIn('barcode1', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode1')->get();
            if (count($tempCheckUniqueBarcode) > 0) {
                throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode1 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
            }
            $tempCheckUniqueBarcode = Product::whereIn('barcode2', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode2')->get();
            if (count($tempCheckUniqueBarcode) > 0) {
                throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode2 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
            }
            $tempCheckUniqueBarcode = Product::whereIn('barcode3', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode3')->get();
            if (count($tempCheckUniqueBarcode) > 0) {
                throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode3 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
            }

            //add product
            $p = new Product();
            $p->name = $request->name;
            $p->product_category_id = $request->product_category_id;
            $p->note = $request->note ? $request->note : '';
            $p->allow_buy = $request->allow_buy ? 1 : 0;
            $p->allow_sale = $request->allow_sale ? 1 : 0;
            $p->allow_make = $request->allow_make ? 1 : 0;
            $p->allow_no_qte = $request->allow_no_qte ? 1 : 0;
            $p->product_unit_id = $request->product_unit_id;
            $p->min_qte = $request->min_qte;
            $p->barcode1 = $request->barcode1 ? $request->barcode1 : '';
            $p->barcode2 = $request->barcode2 ? $request->barcode2 : '';
            $p->barcode3 = $request->barcode3 ? $request->barcode3 : '';
            $p->price_buy = $request->price_buy;
            $p->price_sale1 = $request->price_sale1;
            $p->price_sale2 = isset($request->price_sale2)?$request->price_sale2:'0';
            $p->price_sale3 = isset($request->price_sale3)?$request->price_sale3:'0';
            $p->price_sale4 = isset($request->price_sale4)?$request->price_sale4:'0';

            $p->save();

            //update min qte for unit if setting for update automaticly is allowed
            $setting = Setting::first();
            if ($setting->edit_auto_for_default_min_qte_unit) {
                $tempUnit = ProductUnit::find($request->product_unit_id);
                if ($tempUnit->default_value_for_min_qte != $p->min_qte) {
                    $tempUnit->default_value_for_min_qte = $p->min_qte;
                    $tempUnit->save();
                }
            }

            //update automatic barcode
            $tempBarcode = Barcode::first();
            if ($tempBarcode->last_barcode + 1 == $p->barcode1) {
                $tempBarcode->last_barcode++;
                $tempBarcode->save();
            }


            //add units
            for ($i = 0; $i < (is_array($request->relation_product_unit_id) ? count($request->relation_product_unit_id) : 0); $i++) {

                //checkUniqueBarcodeInRelationProductUnit
                $tempCheckUniqueBarcode = RelationProductUnit::whereIn('barcode1', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode1')->get();
                if (count($tempCheckUniqueBarcode) > 0) {
                    throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode1 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
                }
                $tempCheckUniqueBarcode = RelationProductUnit::whereIn('barcode2', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode2')->get();
                if (count($tempCheckUniqueBarcode) > 0) {
                    throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode2 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
                }
                $tempCheckUniqueBarcode = RelationProductUnit::whereIn('barcode3', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode3')->get();
                if (count($tempCheckUniqueBarcode) > 0) {
                    throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode3 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
                }

                $u = new RelationProductUnit();
                $u->product_id = $p->id;
                $u->product_unit_id = $request->relation_product_unit_id[$i];
                $u->relation_qte = $request->relation_qte[$i];
                $u->barcode1 = $request->relation_barcode1[$i];
                if ($tempBarcode->last_barcode + 1 == $u->barcode1) {
                    $tempBarcode->last_barcode++;
                    $tempBarcode->save();
                }
                $u->barcode2 = $request->relation_barcode2[$i];
                $u->barcode3 = $request->relation_barcode3[$i];
                $u->price_buy = $request->relation_price_buy[$i];
                $u->price_sale1 = $request->relation_price_sale1[$i];
                $u->price_sale2 = isset($request->relation_price_sale2[$i]) ? $request->relation_price_sale2[$i] : '0';
                $u->price_sale3 = isset($request->relation_price_sale3[$i]) ? $request->relation_price_sale3[$i] : '0';
                $u->price_sale4 = isset($request->relation_price_sale4[$i]) ? $request->relation_price_sale4[$i] : '0';

                $u->save();
            }
            //add componet
            for ($i = 0; $i < (is_array($request->relation_creator_id) ? count($request->relation_creator_id) : 0); $i++) {
                $c = new RelationProductMake();
                $c->product_id = $p->id;
                $c->creator_id = $request->relation_creator_id[$i];
                $c->qte_creator = $request->relation_qte_creator[$i];
                $c->save();
            }

            //add default value to store
            if(isset($request->device_stoke) && $request->qte_stoke >0){
                $store = new Store();
                $store->stoke_id = $request->device_stoke;
                $store->product_id = $p->id;
                $store->qte = $request->qte_stoke;
                $store->price = $p->price_buy;
                $store->type = 0;
                $store->save();

                //add data to product move (buy and sale)
                $product_move = new ProductMove();
                $product_move->device_id = Auth::user()->device_id;
                $product_move->user_id = Auth::user()->id;
                $product_move->store_id = $store->id;
                $product_move->stoke_id = $store->stoke_id;
                $product_move->product_id = $p->id;
                $product_move->product_unit_id = $p->product_unit_id;
                $product_move->relation_qte = 1;
                $product_move->qte = $store->qte;
                $product_move->price = $store->price;
                $product_move->type = 16;
                $product_move->note = 'تم إضافة هذة الكمية عند إنشاء المنتج';
                $product_move->save();
            }

            Session::flash('success', 'تمت العملية بنجاح');
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'اضافة منتج جديد باسم ' . $p->name . '  ونوعة ' . ($p->allow_buy ? ' منتج شراء ' : '') . ($p->allow_sale ? ' - منتج بيع ' : '')
                . ($p->allow_make ? ' - منتج إنتاج ' : '') . ($p->allow_no_qte ? ' - منتج بدون كمية ' : '');
            $activity->type = 12;
            $activity->save();
        } catch (\Exception $e) {
            DB::rollback();
            //            return $e->getMessage();
            Session::flash('fault', $e->getMessage());
            return back();
            //            throw $e;
        }
        DB::commit();

        return redirect()->route('products.create', [
            'allow_buy' => $request->allow_buy,
            'allow_sale' => $request->allow_sale,
            'allow_make' => $request->allow_make,
            'allow_no_qte' => $request->allow_no_qte,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $p = Product::with('relationProductUnit')->with('relationProductMake')->find($id);
        return view('products.edit', [
            'cuts' => ProductCategory::where('state', 1)->orderby('name', 'asc')->get(),
            'setting' => Setting::first(),
            'products' => Product::orderby('name')->get(),
            'units' => ProductUnit::where('state', 1)->orderby('name')->get(),
            'barcode' => Barcode::first(),
            'product' => $p,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $p = Product::findOrFail($id);
        $validateName = $p->name == $request->name ? '' : '|unique:products,name';
        $request->validate([
            'name' => 'required|max:250' . $validateName,
            'product_category_id' => 'exists:product_categories,id',
            'note' => 'max:250',
            'allow_buy' => 'boolean',
            'allow_sale' => 'boolean',
            'allow_make' => 'boolean',
            'allow_no_qte' => 'boolean',
            'product_unit_id' => 'exists:product_units,id',
            'min_qte' => 'required|gt:-1',
            'barcode1' => 'nullable|numeric',
            'barcode2' => 'nullable|numeric',
            'barcode3' => 'nullable|numeric',
            'price_buy' => ['gt:-1', new valid_negative_price],
            'price_sale1' => ['gt:-1', new valid_negative_price],
            'price_sale2' => ['gt:-1', new valid_negative_price],
            'price_sale3' => ['gt:-1', new valid_negative_price],
            'price_sale4' => ['gt:-1', new valid_negative_price],

            'relation_product_unit_id.*' => 'exists:product_units,id',
            'relation_qte.*' => ['gt:0', new valid_qte],
            'relation_barcode1.*' => 'nullable|numeric',
            'relation_barcode2.*' => 'nullable|numeric',
            'relation_barcode3.*' => 'nullable|numeric',
            'relation_price_buy.*' => ['gt:-1', new valid_negative_price],
            'relation_price_sale1.*' => ['gt:-1', new valid_negative_price],
            'relation_price_sale2.*' => ['gt:-1', new valid_negative_price],
            'relation_price_sale3.*' => ['gt:-1', new valid_negative_price],
            'relation_price_sale4.*' => ['gt:-1', new valid_negative_price],

            'relation_creator_id.*' => 'exists:products,id',
            'relation_qte_creator.*' => ['gt:-1', 'numeric'],
        ]);
        DB::beginTransaction();
        try {

            //check if selected type of product((buy or sale or make) at least one of this)
            if (!$request->allow_buy && !$request->allow_sale && !$request->allow_make) {
                throw new \Exception('برجاء تحديد نوع للمنتج من شراء أو بيع أو إنتاج');
            }

            //check Repeat Unit
            for ($i = 0; $i < (is_array($request->relation_product_unit_id) ? count($request->relation_product_unit_id) : 0); $i++) {
                if (array_count_values($request->relation_product_unit_id)[$request->relation_product_unit_id[$i]] > 1
                    || $request->relation_product_unit_id[$i] == $request->product_unit_id) {
                    throw new \Exception('الوحدة ' . ProductUnit::find($request->product_unit_id[$i])['name'] . ' مكررة ');
                }
            }

            //check Repeat Component
            for ($i = 0; $i < (is_array($request->relation_creator_id) ? count($request->relation_creator_id) : 0); $i++) {
                if (array_count_values($request->relation_creator_id)[$request->relation_creator_id[$i]] > 1) {
                    throw new \Exception('المكون ' . Product::find($request->relation_creator_id[$i])['name'] . ' مكررة ');
                }
                if ($request->relation_qte_creator[$i] <= 0) {
                    throw new \Exception('كمية المكون ' . Product::find($request->relation_creator_id[$i])['name'] . ' يجب أن تكون أكبر من 0 ');
                }

            }

            //check if product type no qte
            if ($request->allow_no_qte &&
                ($request->relation_product_unit_id != '' || $request->relation_creator_id != '')) {
                throw new \Exception('لا يجوز وجود وحدات أو مكونات لمنتج بدون كمية ');
            }


            //checkUniqueBarcodeInProduct
            $tempCheckUniqueBarcode = Product::whereIn('barcode1', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode1')->where('id', '!=', $id)->get();
            if (count($tempCheckUniqueBarcode) > 0) {
                throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode1 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
            }
            $tempCheckUniqueBarcode = Product::whereIn('barcode2', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode2')->where('id', '!=', $id)->get();
            if (count($tempCheckUniqueBarcode) > 0) {
                throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode2 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
            }
            $tempCheckUniqueBarcode = Product::whereIn('barcode3', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode3')->where('id', '!=', $id)->get();
            if (count($tempCheckUniqueBarcode) > 0) {
                throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode3 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
            }
            //update product
            $p->name = $request->name;
            $p->product_category_id = $request->product_category_id;
            $p->note = $request->note ? $request->note : '';
            $p->allow_buy = $request->allow_buy ? 1 : 0;
            $p->allow_sale = $request->allow_sale ? 1 : 0;
            $p->allow_make = $request->allow_make ? 1 : 0;
            $p->allow_no_qte = $request->allow_no_qte ? 1 : 0;
            $p->product_unit_id = $request->product_unit_id;
            $p->min_qte = $request->min_qte;
            $p->barcode1 = $request->barcode1 ? $request->barcode1 : '';
            $p->barcode2 = $request->barcode2 ? $request->barcode2 : '';
            $p->barcode3 = $request->barcode3 ? $request->barcode3 : '';
            $p->price_buy = $request->price_buy;
            $p->price_sale1 = $request->price_sale1;
            $p->price_sale2 = isset($request->price_sale2)?$request->price_sale2:'0';
            $p->price_sale3 = isset($request->price_sale3)?$request->price_sale3:'0';
            $p->price_sale4 = isset($request->price_sale4)?$request->price_sale4:'0';

            $p->save();


            //update automatic barcode
            $tempBarcode = Barcode::first();
            if ($tempBarcode->last_barcode + 1 == $p->barcode1) {
                $tempBarcode->last_barcode++;
                $tempBarcode->save();
            }


            //update old unit and add new units
            $tempUnitId = [];
            for ($i = 0, $updateUnit = (isset($request->counter_product_unit) ? ((is_array($request->counter_product_unit) ? count($request->counter_product_unit) : 1)) : 0);
                 $i < (is_array($request->relation_product_unit_id) ? count($request->relation_product_unit_id) : 0); $i++) {
                //checkUniqueBarcodeInRelationProductUnit
                $tempCheckUniqueBarcode = RelationProductUnit::whereIn('barcode1', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode1')->where('id', '!=', $id)->get();
                if (count($tempCheckUniqueBarcode) > 0) {
                    throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode1 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
                }
                $tempCheckUniqueBarcode = RelationProductUnit::whereIn('barcode2', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode2')->where('id', '!=', $id)->get();
                if (count($tempCheckUniqueBarcode) > 0) {
                    throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode2 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
                }
                $tempCheckUniqueBarcode = RelationProductUnit::whereIn('barcode3', [$request->barcode1, $request->barcode2, $request->barcode3])->whereNotNull('barcode3')->where('id', '!=', $id)->get();
                if (count($tempCheckUniqueBarcode) > 0) {
                    throw new \Exception(' الباركود ' . $tempCheckUniqueBarcode[0]->barcode3 . ' مستخدم فى المنتج ' . $tempCheckUniqueBarcode[0]->name);
                }

                if ($updateUnit > $i) {
                    $u = RelationProductUnit::find($request->counter_product_unit[$i]);
                } else {
                    $u = new RelationProductUnit();
                }
                $u->product_id = $p->id;
                $u->product_unit_id = $request->relation_product_unit_id[$i];
                $u->relation_qte = $request->relation_qte[$i];
                $u->barcode1 = $request->relation_barcode1[$i];
                if ($tempBarcode->last_barcode + 1 == $u->barcode1) {
                    $tempBarcode->last_barcode++;
                    $tempBarcode->save();
                }
                $u->barcode2 = $request->relation_barcode2[$i];
                $u->barcode3 = $request->relation_barcode3[$i];
                $u->price_buy = $request->relation_price_buy[$i];
                $u->price_sale1 = $request->relation_price_sale1[$i];
                $u->price_sale2 = isset($request->relation_price_sale2[$i]) ? $request->relation_price_sale2[$i] : '0';
                $u->price_sale3 = isset($request->relation_price_sale3[$i]) ? $request->relation_price_sale3[$i] : '0';
                $u->price_sale4 = isset($request->relation_price_sale4[$i]) ? $request->relation_price_sale4[$i] : '0';

                $u->save();

                array_push($tempUnitId, $u->id);
            }
            RelationProductUnit::where('product_id', $p->id)->whereNotIn('id', $tempUnitId)->delete();

            //add componet
            $tempComponentId = [];
            for ($i = 0, $updateComponent = (isset($request->counter_product_make) ? ((is_array($request->counter_product_make) ? count($request->counter_product_make) : 1)) : 0);
                 $i < (is_array($request->relation_creator_id) ? count($request->relation_creator_id) : 0); $i++) {
                if ($updateUnit > $i) {
                    $c = RelationProductMake::find($request->counter_product_make[$i]);
                } else {
                    $c = new RelationProductMake();
                }
                $c->product_id = $p->id;
                $c->creator_id = $request->relation_creator_id[$i];
                $c->qte_creator = $request->relation_qte_creator[$i];
                $c->save();
                array_push($tempComponentId, $c->id);
            }
            RelationProductMake::where('product_id', $p->id)->whereNotIn('id', $tempComponentId)->delete();

            Session::flash('success', 'تمت العملية بنجاح');
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->device_id = Auth::user()->device_id;
            $activity->data = 'تعديل منتج باسم ' . $p->name . '  ونوعة ' . ($p->allow_buy ? ' منتج شراء ' : '') . ($p->allow_sale ? ' - منتج بيع ' : '')
                . ($p->allow_make ? ' - منتج إنتاج ' : '') . ($p->allow_no_qte ? ' - منتج بدون كمية ' : '');
            $activity->type = 12;
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


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $s = Product::findOrFail($id);

        $message = ' بإسم ' . $s->name;
        try {
            $s->delete();

            Session::flash('success', 'حذف منتج');
            $activty = new Activity();
            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data = 'حذف منتج ' . $message;
            $activty->type = 12;

            $activty->save();

            return back();
        } catch (\Exception $e) {
            Session::flash('fault', 'لا يجوز حذف هذاالمنتج لوجود تعاملات من خلالة');
            return back();
        }
    }

    public function getData(Request $r)
    {
        //used in products.index.blade.php
        if ($r->type == 'findProductWithUnitWithComponent') {
            return Product::with('productUnit')->with('relationProductUnit')->with('relationProductMake')->find($r->product_id);
        }

        //used in bills.create.blade.php
        if ($r->type == 'findUnitForProduct') {
            return Product::with('productUnit')->with('relationProductUnit')->find($r->product_id);
        }

        //use in bills.create.blade.php
        if ($r->type == 'findBarcode') {
            $barcode = $r->barcode;
            $bill_type = $r->billType;
            $p = Product::with('productUnit')->where(function ($q) use ($barcode) {
                $q->where('barcode1', $barcode)->orwhere('barcode2', $barcode)->
                orwhere('barcode3', $barcode);
            })->where(function ($q) use ($bill_type) {
                if ($bill_type == 0) {
                    $q->where('allow_buy', 1);
                } else {
                    $q->where('allow_sale', 1);
                }
            })->where('state', 1)->first();
            /*$p=Product::with('productUnit')->where('barcode1',$barcode)->orwhere('barcode2',$barcode)->
                orwhere('barcode3',$barcode)->first();*/
            if ($p == '') {
                $relation_unit = RelationProductUnit::where('barcode1', $barcode)->orwhere('barcode2', $barcode)->
                orwhere('barcode3', $barcode)->first();

                $relation_id = $relation_unit != '' ? $relation_unit->id : '';
                if ($relation_id != '') {
                    $p = $p = Product::with(['relationProductUnit' => function ($q) use ($relation_id) {
                        $q->where('id', $relation_id);
                    }])->
                    where(function ($q) use ($bill_type) {
                        if ($bill_type == 0) {
                            $q->where('allow_buy', 1);
                        } else {
                            $q->where('allow_sale', 1);
                        }
                    })->where('state', 1)->find($relation_unit->product_id);
                }
                if ($p == '') {
                    return [];
                }
            }
            return $p;
        }
    }

    public function changeState(Request $r, $id)
    {
        //
        $o = Product::findOrFail($id);
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
            $activty->data = 'تغير حالة منتج ' . $o->name . ' إلى ' . ($o->state == 1 ? 'مفعل' : 'غير مفعل');
            $activty->type = 12;
            $activty->save();

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }

        if ($r->type = 'change_favorit') {
            $activty = new Activity();
            if ($o->special == 0) {
                $o->special = 1;
                $activty->data = 'إضافة المنتج ' . $o->name . ' إلى قائمة المنتجات الخاصة';
            } else {
                $o->special = 0;
                $activty->data = 'حذف المنتج ' . $o->name . ' من قائمة المنتجات الخاصة';
            }
            $o->save();

            $activty->user_id = Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type = 12;
            $activty->save();

            Session::flash('success', 'تمت العملية بنجاح');
            return back();
        }

    }

}
