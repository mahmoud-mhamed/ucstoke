<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Emp extends Model
{
    //
    function user(){
        return $this->belongsTo('App\User');
    }

    function device(){
        return $this->belongsTo('App\Device');
    }

    function empJop(){
        return $this->belongsTo('App\EmpJop');
    }

    function empMove(){
        return $this->hasMany('App\EmpMove');
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
