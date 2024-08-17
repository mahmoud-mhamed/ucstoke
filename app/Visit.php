<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    //
    function user(){
        return $this->belongsTo('App\User');
    }

    public function device(){
        return $this->belongsTo('App\Device');
    }

    public function account(){
        return $this->belongsTo('App\Account');
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
