<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BillBackDetail extends Model
{
    //
    //used in BillController.getdata
    function productUnit(){
        return $this->belongsTo('App\ProductUnit');
    }

    /*function billDetail(){
        return $this->belongsTo('App\BillDetail','id')->with('product');
    }*/

    //used in BillController.getdata
    function product(){
        return $this->belongsTo('App\Product');
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
