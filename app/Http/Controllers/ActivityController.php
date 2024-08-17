<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Device;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r)
    {
        //search activity
        if (isset($r->id)){
            $activity=Activity::findOrFail($r->id);
            return view('users.activities',[
                'users'=>User::orderby('name')->get(),
                'devices'=>Device::orderby('name')->get(),
                'activity'=>$activity
            ]);
        }

        return view('users.activities',[
            'users'=>User::orderby('name')->get(),
            'devices'=>Device::orderby('name')->get()
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
        if ($id=='-1'){
            $temActivity=Activity::where('notification',true)->get();
            foreach ($temActivity as $a){
                $a->notification=2;
                $a->save();
            }
            $activty=new Activity();
            $activty->user_id=Auth::user()->id;
            $activty->device_id = Auth::user()->device_id;
            $activty->data='إلغاء كل الإشعارات';
            $activty->type=0;
            $activty->save();
            Session::flash('success','تمت العملية بنجاح');
            return back();
        }
        $activity=Activity::findOrFail($id);
        $activity->notification=2;
        $activity->save();
        Session::flash('success','تمت العملية بنجاح');
        return back();
    }
    public function truncate(){
        Activity::truncate();
        $activty=new Activity();
        $activty->user_id=Auth::user()->id;
        $activty->device_id = Auth::user()->device_id;
        $activty->data='حذف كل النشاطات';
        $activty->type=0;
        $activty->notification=1;
        $activty->save();
        return back();
    }

    public function search(Request $r){
        if ($r->user_id=='all'){
            $data=Activity::
            whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->
            where('data','like','%'.$r->search.'%')->
            orderBy('id','desc')->with('user')->with('device')->get();
        }else{
            $data=Activity::where('user_id', $r->user_id )->
            whereBetween('created_at', [$r->dateFrom.' 0:0:0',$r->dateTo.' 23:59:59'])->
            where('data','like','%'.$r->search.'%')->
            orderBy('id','desc')->with('user')->get();
        }
        echo $data;
    }
}
