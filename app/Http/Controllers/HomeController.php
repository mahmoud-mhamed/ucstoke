<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Backup;
use App\Device;
use App\DeviceStoke;
use App\Product;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //create auto backup
        $b=Backup::where('dayCreate','<=',date('Y-m-d'))->get();
        $state_create_backup=false;
        if(count($b)>0){
            $state_create_backup=true;
//            return redirect(route('backups.createBackup','createAuto'));
        }
        $state_create_backup=true;

        //download backup automatic
        $state_download_backup=false;
        $device=Device::find( Auth::user()->device_id);
        if ($device->state_download_backup){
            if (date($device->day_download)<=date('Y-m-d')){
                $state_download_backup=true;
            }
        }
        $state_download_backup=false;

        $activities=[];
        if (Auth::user()->type==1 || Auth::user()->allow_manage_activities){
            $activities=Activity::where('notification',1)->orderby('id','desc')->get();
        }

        //check product qte in stoke
        $deviceStoke= DeviceStoke::where('device_id', Auth::user()->device_id)->get();
        $tempStokeAllowedId=[];
        foreach ($deviceStoke as $d){
            array_push($tempStokeAllowedId,$d->stoke_id);
        }
        $littleProduct= Product::
        with('productUnit')->
        with(['store' => function ($q) use ($tempStokeAllowedId) {
            $q->whereIn('stoke_id', $tempStokeAllowedId)->where('qte','>',0);
        }])->where('allow_no_qte', 0)->where('state',1)->get();

        $resultLittleProduct=[];
        foreach ($littleProduct as $p){
            $tempQte=0;
            foreach ($p->store as $s){
                $tempQte +=$s->qte;
            }
            //check if tempQte less than min qte
            if ($p->min_qte > $tempQte){
                array_push($resultLittleProduct,[
                    'product_id'=>$p->id,
                    'product_name'=>$p->name,
                    'product_unit'=>$p->productUnit->name,
                    'min_qte'=>$p->min_qte,
                    'qte_exist'=>$tempQte,
                    'allow_buy'=>$p->allow_buy,
                    'allow_sale'=>$p->allow_sale,
                    'allow_make'=>$p->allow_make,
                ]);
            }

        }

        //check visit
        $visits=[];
        if (Auth::user()->type==1 || Auth::user()->show_notification_visit){
            $visits=DB::select("select * from visits WHERE state_visit = 0 AND SUBDATE(date_alarm, alarm_before) <= CURRENT_DATE()");
        }

        //change serial if ip change
        $serial=Device::findOrFail(Auth::user()->device_id)->hash_check;

        return view('home',[
            'activities'=>$activities,
            'little_product'=>$resultLittleProduct,
            'state_download_backup'=>$state_download_backup,
            'state_create_backup'=>$state_create_backup,
            'visits'=>$visits,
            'serial'=>$serial,
        ]);
    }
}
