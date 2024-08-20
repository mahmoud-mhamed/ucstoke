<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    //
    function mainStoke(){
        return $this->hasOne('App\Stoke','id','default_stoke');
    }

    function allowedStoke(){
        return $this->hasMany('App\DeviceStoke')->with('stoke');
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
