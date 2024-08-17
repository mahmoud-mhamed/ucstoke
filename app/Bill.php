<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{

    //used in BillController.getData
    function user(){
        return $this->belongsTo('App\User');
    }

    //used in BillController.getData,BillController.destroy
    function device(){
        return $this->belongsTo('App\Device');
    }
    //used in BillController.getData,BillController.destroy
    function account(){
        return $this->belongsTo('App\Account');
    }
    //used in BillController.getData,BillController.edit
    function Stoke(){
        return $this->belongsTo('App\Stoke');
    }

    //used in BillController.destroy,ProductMoveController.getData
    function detail(){
        return $this->hasMany('App\BillDetail')->with('product')->with('productUnit')->with('store')->with('saleMakeQteDetail');
    }

    //used in BillController.getData
    function visit(){
        return $this->hasMany('App\Visit');
    }

    //used in BillController.destroy
    function accountCalculation(){
        return $this->hasOne('App\AccountCalculation');
    }

    //used in BillController.print, used in BillController.edit
    function details(){
        return $this->hasMany('App\BillDetail')->with('product')->with('relationProductUnit')->with('productUnit');
    }

    //used in BillController.edit
    /*function detailsProduct(){
        return $this->hasMany('App\BillDetail')->with('product')->with('productUnit');
    }*/


    //used in BillController.getData
    function billBack(){
        return $this->hasMany('App\BillBack');
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
