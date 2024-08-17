<?php

namespace App\Http\Controllers;

use App\Account;
use App\Bill;
use App\Device;
use App\Product;
use App\ProductCategory;
use App\ProductMove;
use App\Sale;
use App\Setting;
use App\Stoke;
use Illuminate\Http\Request;

class ProductMoveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('products.product_move', [
            'products' => Product::orderBy('name')->get(),
            'stokes'=>Stoke::orderBy('name')->get(),
            'devices'=>Device::orderBy('name')->get(),
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
    }

    public function getData(Request $r)
    {
        //used in product.report
        if ($r->type=='getProductMove'){
            if($r->product_id==''){
                return ProductMove::with('user')->with('device')->
                with('product')->with('store')->with('stoke')->with('productUnit')->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])
                    ->orderby('id','desc')->get();
            }else{
                return ProductMove::with('user')->with('device')->
                with('product')->with('store')->with('stoke')->with('productUnit')->
                whereBetween('created_at', [$r->dateFrom . ' 0:0:0', $r->dateTo . ' 23:59:59'])->
                      where('product_id',$r->product_id)
                    ->orderby('id','desc')->get();
            }
        }


        //used in products.product_profit.blade.php
        if ($r->type=='getProductProfit'){
            if ($r->device_id==''){
                $bill= Bill::with('detail')->where('type',1)->
                whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->get();

                $total_discount=Bill::where('type',1)->
                whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->sum('discount');

            }else{
                $bill= Bill::with('detail')->where('type',1)->where('device_id',$r->device_id)->
                whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->get();

                $total_discount=Bill::where('type',1)->where('device_id',$r->device_id)->
                whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->sum('discount');
            }

//            return $bill;
            $profit=[];
            /*
             * $profit=[['product_id'=>'','qte'=>'','profit'=>'','has_qte'=>'','product'=>'']];
             *رقم المنتج
             * المباع
             * الربح
             النوع (بدون كمية أو بكمية)
             المنتج
             * */
            for ($i=0;$i<count($bill);$i++){
                for ($d=0;$d<count($bill[$i]['detail']);$d++){
                    $product_id=$bill[$i]['detail'][$d]['product']['id'];
                    $price_sale=$bill[$i]['detail'][$d]['price'];
                    $qte_sale=$bill[$i]['detail'][$d]['qte'];

                    if (count($bill[$i]['detail'][$d]['saleMakeQteDetail'])==0){//product type is no qte
                        $search='notExist';
                        for ($r=0;$r<count($profit);$r++){
                            if ($profit[$r]['product_id']==$product_id && $profit[$r]['has_qte']==false){
                                $search =$r;
                                break;
                            }
                        }
                        if ($search !='notExist' || $search === 0){
                            $profit[$search]['qte']+=$qte_sale;
                            $profit[$search]['profit']+=$qte_sale * $price_sale;
                        }else{
                            array_push($profit,[
                                'product_id'=>$product_id,
                                'qte'=>$qte_sale,
                                'profit'=>$qte_sale*$price_sale ,
                                'has_qte'=>false,
                                'product'=>Product::with('relationProductUnit')->with('productUnit')->find($product_id)
                            ]);
                        }
                    }else{//product type not is no qte
                        //get qte profit
                        for ($q=0;$q< count($bill[$i]['detail'][$d]['saleMakeQteDetail']);$q++){
                            $qte=$bill[$i]['detail'][$d]['saleMakeQteDetail'][$q]['qte'];
                            $buyPrice=$bill[$i]['detail'][$d]['saleMakeQteDetail'][$q]['store']['price'];
                            $search='notExist';
                            for ($r=0;$r<count($profit);$r++){
                                if ($profit[$r]['product_id']==$product_id && $profit[$r]['has_qte']==true){
                                    $search =$r;
                                    break;
                                }
                            }

                            if ($search !='notExist' || $search === 0){
                                $profit[$search]['qte']+=$qte;
                                $profit[$search]['profit']+=($qte * $price_sale - $qte*$buyPrice);
                            }else{
                                array_push($profit,[
                                    'product_id'=>$product_id,
                                    'qte'=>$qte,
                                    'profit'=>$qte*$price_sale - $qte*$buyPrice,
                                    'has_qte'=>true,
                                    'product'=>Product::with('relationProductUnit')->with('productUnit')->find($product_id)
                                ]);
                            }
                        }
                    }

                }
            }
            $endResult=[];
            array_push($endResult,$profit,['total_discount'=>$total_discount]);
            return $endResult;
        }


        //used in products.product_profit.blade.php
        if ($r->type=='getBillProductProfit'){
            $product_id=$r->product_id;
            if ($r->device_id==''){
                $bill= Bill::with('account')->with(['detail'=>function($q) use ($product_id) {
                    $q->where('product_id',$product_id);
                }])->where('type',1)->
                whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->get();
            }else{
                $bill= Bill::with(['detail'=>function($q) use ($product_id) {
                    $q->where('product_id',$product_id);
                }])->
                where('device_id',$r->device_id)->where('type',1)->
                whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->get();
            }
            return $bill;
        }

        //used in products.account_product_move.blade.php
        if ($r->type=='getAccountProductMove'){
            $bill= Bill::with('user')->with('stoke')->with('device')->with('details')->where('account_id',$r->account_id)->
            whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->get();

            return $bill;
        }
    }

    public function show_profit()
    {
        return view('products.product_profit',[
            'devices'=>Device::orderBy('name')->get(),
            'categories'=>ProductCategory::orderBy('name')->get(),
        ]);

    }

    public function account_product_move()
    {
        return view('products.account_product_move',[
            'accounts' => Account::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'stokes'=>Stoke::orderBy('name')->get(),
            'devices'=>Device::orderBy('name')->get(),
        ]);

    }
}
