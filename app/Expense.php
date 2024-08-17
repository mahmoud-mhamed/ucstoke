<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //used in ExpenseController.getdata && ExpenseController.destroy
    function expense_type(){
        return $this->belongsTo('App\ExpensesType');
    }

    //used in ExpenseController.getdata
    function user(){
        return $this->belongsTo('App\User');
    }

    //used in ExpenseController.getdata  && ExpenseController.destroy
    function device(){
        return $this->belongsTo('App\Device');
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
