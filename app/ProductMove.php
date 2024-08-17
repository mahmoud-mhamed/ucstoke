<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductMove extends Model
{
    //
    //used in ProductMoveController.getData
    function user(){
        return $this->belongsTo('App\User');
    }

    //used in ProductMoveController.getData
    function device(){
        return $this->belongsTo('App\Device');
    }
    //used in ProductMoveController.getData
    function product(){
        return $this->belongsTo('App\Product')->with('productCategory')->with('productUnit');
    }
    //used in ProductMoveController.getData
    function store(){
        return $this->belongsTo('App\Store');
    }
    //used in ProductMoveController.getData
    function stoke(){
        return $this->belongsTo('App\Stoke');
    }
    //used in ProductMoveController.getData
    function productUnit(){
        return $this->belongsTo('App\ProductUnit');
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
