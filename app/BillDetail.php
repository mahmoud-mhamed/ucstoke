<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    //used in BillController.getData,App\Bill.php
    function product(){
        return $this->belongsTo('App\Product');
    }

    //used in Bill.php in relation Details
    function relationProductUnit(){
        return $this->hasMany('App\RelationProductUnit','product_id','product_id')->with('ProductUnit');
    }


    //used in BillController.getData,App\Bill.php
    function store(){
        return $this->belongsTo('App\Store');
    }
    //used in BillController.getData,App\Bill.php
    function productUnit(){
        return $this->belongsTo('App\ProductUnit');
    }
    //used in BillController.getData
    function saleMakeQteDetail(){
        return $this->hasMany('App\SaleMakeQteDetails')->with('store');
    }

    //used in BillController.getData
    function bill(){
        return $this->belongsTo('App\Bill')->with('billBack');
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
