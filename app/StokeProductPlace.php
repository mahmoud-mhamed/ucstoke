<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StokeProductPlace extends Model
{
    //used in App\Product
    function stoke(){
        return $this->belongsTo('App\Stoke');
    }

    //used in App\Product
    function placeName(){
        return $this->hasOne('App\StokePlaceName','id','stoke_place_name_id');
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
