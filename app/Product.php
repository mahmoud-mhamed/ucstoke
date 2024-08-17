<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //used in ProductController.index, StoreController.getData,ProductMoveController.getData
    function productCategory(){
        return $this->belongsTo('App\ProductCategory');
    }

    // used in ProductController.index , StoreController.getData
    function productUnit(){
        return $this->belongsTo('App\ProductUnit');
    }

    //used in ProductController.getData , StoreController.getData, ProductMoveController.getData
    function relationProductUnit(){
        return $this->hasMany('App\RelationProductUnit')->with('ProductUnit');
    }

    //used in ProductController.getData,MakeController.store
    function relationProductMake(){
        return $this->hasMany('App\RelationProductMake')->with('productCreator');
    }

    //used in StokePlaceNameController.showProductPlace , StoreController.getData
    function place(){
        return $this->hasMany('App\StokeProductPlace')->with('stoke')->with('placeName');
    }

    //used in StoreController.getData
    function store(){
        return $this->hasMany('App\Store');
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
