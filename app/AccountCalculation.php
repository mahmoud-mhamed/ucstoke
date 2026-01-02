<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AccountCalculation extends Model
{
    //used in AccountCalculationController.getData
    function user(){
        return $this->belongsTo('App\User');
    }

    //used in AccountCalculationController.getData
    function device(){
        return $this->belongsTo('App\Device');
    }
    //used in AccountCalculationController.getData
    function account(){
        return $this->belongsTo('App\Account');
    }

    //for update date created_at and updated_at format
    public function getCreatedAtAttribute($date)
    {
        if (!$date) return null;
        return Carbon::parse($date)->format('Y-m-d h:i:sa');
    }

    public function getUpdatedAtAttribute($date)
    {
        if (!$date) return null;
        return Carbon::parse($date)->format('Y-m-d h:i:sa');
    }
}
