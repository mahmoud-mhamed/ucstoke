<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    //used in TreasuryController.getdata
    function user(){
        return $this->belongsTo('App\User');
    }

    //used in TreasuryController.getdata
    function device(){
        return $this->belongsTo('App\Device');
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
