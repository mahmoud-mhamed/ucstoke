<?php

namespace App;

use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Eloquent\Model;

class SaleMakeQteDetails extends Model
{
    //used in BillDetail.php function saleMakeQteDetail,Make.php
    function store(){
        return $this->belongsTo('App\Store');
    }

    //used in MakeController.getData
    public function storeWithProduct(){
        return $this->belongsTo('App\Store','store_id')->with('product');
    }

    /*function make(){
        return $this->belongsTo('App\Make')->with('product')->with('productUnit');
    }*/

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
