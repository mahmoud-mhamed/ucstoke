<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BillBack extends Model
{
    //
    function user(){
        return $this->belongsTo('App\User');
    }

    function device(){
        return $this->belongsTo('App\Device');
    }

    //used in userController.getReport
    function bill(){
        return $this->belongsTo('App\Bill');
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
