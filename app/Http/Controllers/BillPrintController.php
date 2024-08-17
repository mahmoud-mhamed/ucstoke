<?php

namespace App\Http\Controllers;

use App\Activity;
use App\BillPrint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BillPrintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $date = (new \DateTime())->format('h:i:sa Y-m-d');
//        $date = (new \DateTime('2020-01-17 20:16:22'))->format('Y-m-d h:i:s a');
        return view('bills.print_design',[
            'date'=>$date,
            'bills'=>BillPrint::orderby('name')->get(),
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
     * @param  \App\BillPrint  $billPrint
     * @return \Illuminate\Http\Response
     */
    public function show(BillPrint $billPrint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BillPrint  $billPrint
     * @return \Illuminate\Http\Response
     */
    public function edit(BillPrint $billPrint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BillPrint  $billPrint
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        //
        try {
            $image=null;
            if ($request->hasFile('img')){
                if ($request->file('img')->getSize()>2070804){//=1M
                    Session::flash('fault','برجاء إختيار صورة أصغر من 2 ميجا حتى لا تقلل من سرعة البرنامج هناك مواقع كثيرة لتصغير الصور ومن أفضلها '.'<a href="https://tinypng.com/" target="_blank">tinypng</a>');
                    return back();
                }
                $imgType=$request->file('img')->getClientOriginalExtension();
                if ($imgType!='jpg' && $imgType!='png' &&$imgType!='ico' &&$imgType!='JPG' && $imgType!='PNG' &&$imgType!='ICO'){
                    Session::flash('fault','برجاء إختيار صورة نوعها '.'<br/>'.'ico أو jpg أو png');
                    return back();
                }
                $image_base64 = base64_encode(file_get_contents($request->file('img')->getRealPath()));
                $image = 'data:image/'.$imgType.';base64,'.$image_base64;
            }


            $billPrint=BillPrint::find($id);
            $billPrint->name=isset($request->name)?$request->name:'';
            $billPrint->company_name=isset($request->company_name)?$request->company_name:'';
            $billPrint->row_under_company_name=isset($request->row_under_company_name)?$request->row_under_company_name:'';
            $billPrint->row_contact1=isset($request->row_contact1)?$request->row_contact1:'';
            $billPrint->row_contact2=isset($request->row_contact2)?$request->row_contact2:'';
            $billPrint->icon=$image;

            $billPrint->use_small_size=isset($request->use_small_size)?$request->use_small_size:'0';
            $billPrint->small_size=isset($request->small_size)?$request->small_size:'6';

            $billPrint->header_size=$request->header_size;
            $billPrint->bill_number_date_size=$request->bill_number_date_size;
            $billPrint->contact_size=$request->contact_size;
            $billPrint->account_size=$request->account_size;
            $billPrint->table_header_size=$request->table_header_size;
            $billPrint->table_body_size=$request->table_body_size;
            $billPrint->table_footer_size=$request->table_footer_size;
            $billPrint->message_uc_size=$request->message_uc_size;
            $billPrint->opacity_background=$request->opacity_background;


            $billPrint->save();

            $activty=new Activity();
            $activty->user_id=Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->type=15;
            $activty->data='تغير ديزاين فاتورة الطباعة '.$billPrint->name;
            $activty->save();
            Session::flash('success','تمت العملية بنجاح');
            return back();
        } catch (\Exception $e) {
//            return  $e->getMessage();
//            Session::flash('fault','حصل خطاء في العملية الصورة غير صالحة');
//            return back();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BillPrint  $billPrint
     * @return \Illuminate\Http\Response
     */
    public function destroy(BillPrint $billPrint)
    {
        //
    }

    public function getData(Request $r)
    {
        if (isset($r->id)){ //used in Bills.print_design.blade.php
            return BillPrint::find($r->id);
        }
    }
}
