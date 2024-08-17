<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    //used in MakeController.getData
    function user(){
        return $this->belongsTo('App\User');
    }

    //used in MakeController.getData
    function device(){
        return $this->belongsTo('App\Device');
    }

    //used in MakeController.getData
    function product(){
        return $this->belongsTo('App\Product')->with('productUnit');
    }

    //used in MakeController.getData
    function productUnit(){
        return $this->belongsTo('App\ProductUnit');
    }

    //used in MakeController.getData,MakeController.index
    function stoke(){
        return $this->belongsTo('App\Stoke');
    }

    //used in MakeController.destroy
    function saleMakeQteDetail(){
        return $this->hasMany('App\SaleMakeQteDetails')->with('store');
    }

    //used in MakeController.destroy
    function store(){
        return $this->belongsTo('App\Store');
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
