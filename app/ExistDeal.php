<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExistDeal extends Model
{
    //
    //used in ExistDealController.getData
    function user(){
        return $this->belongsTo('App\User');
    }

    //used in ExistDealController.getData
    function device(){
        return $this->belongsTo('App\Device');
    }
    //used in ExistDealController.getData
    function account(){
        return $this->belongsTo('App\Account');
    }

    //used in BillController.destroy
    function accountCalculation(){
        return $this->hasOne('App\AccountCalculation');
    }

    //for update date created_at and updated_at format
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d h:i:sa');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d h:i:sa');
    }
}
