<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmpMove extends Model
{
    //
    function user(){
        return $this->belongsTo('App\User');
    }

    function device(){
        return $this->belongsTo('App\Device');
    }

    function emp(){
        return $this->belongsTo('App\Emp')->with('empJop');
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
