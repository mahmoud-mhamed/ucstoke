<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Barcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('barcodes.index',[
            'b'=>Barcode::first()
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
     * @param  \App\Barcode  $barcode
     * @return \Illuminate\Http\Response
     */
    public function show(Barcode $barcode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Barcode  $barcode
     * @return \Illuminate\Http\Response
     */
    public function edit(Barcode $barcode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Barcode  $barcode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Barcode $barcode)
    {
        //
        $s = Barcode::findOrFail(1);
        $s->user_id=Auth::user()->id;
        $s->company_name=$request->company_name;
        $s->company_name_font_size=$request->company_name_font_size;
        $s->company_name_color=$request->company_name_color;
        $s->barcode_font_size=$request->barcode_font_size;
        $s->barcode_type=$request->barcode_type;
        $s->barcode_width=$request->barcode_width;
        $s->barcode_height=$request->barcode_height;
        $s->product_font_size=$request->product_font_size;
        $s->product_color=$request->product_color;
        $s->price_font_size=$request->price_font_size;
        $s->price_color=$request->price_color;
        $s->price_color=$request->price_color;
        $s->time_font_size=$request->time_font_size;
        $s->time_color=$request->time_color;
        $s->padding_top=$request->padding_top;
        $s->padding_bottom=$request->padding_bottom;
        $s->padding_right=$request->padding_right;
        $s->padding_left=$request->padding_left;
        $s->barcode_color=$request->barcode_color;
        $s->last_barcode=$request->last_barcode;

        $s->save();
        Session::flash('success', 'ضبط إعدادات الباركود');
        $activity = new Activity();
        $activity->user_id = Auth::user()->id;
        $activity->device_id = Auth::user()->device_id;
        $activity->data = 'ضبط إعدادات الباركود';
        $activity->type = 12;
        $activity->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Barcode  $barcode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barcode $barcode)
    {
        //
    }


}
