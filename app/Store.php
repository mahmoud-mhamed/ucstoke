<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //store controller.getData
    function relationProductUnit(){
        return $this->hasOne('App\RelationProductUnit','product_id','product_id');
    }

    //store controller.getData
    function product(){
        return $this->belongsTo('App\Product')->with('ProductUnit');
    }

    //store controller.edit
    function stoke(){
        return $this->belongsTo('App\Stoke');
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
