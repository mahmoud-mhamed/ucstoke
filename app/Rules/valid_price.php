<?php

namespace App\Rules;

use App\Setting;
use Illuminate\Contracts\Validation\Rule;

class valid_price implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //

        $s=Setting::first();
        if ($s->use_small_price){
            if (preg_match("/^[0-9]{1,6}([.][0-9][0-9]?[0-9]?)?$/",$value)){
                return true;
            }
        }else{
            if (preg_match("/^[0-9]{1,6}([.][0-9][0-9]?)?$/",$value)){
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'price error.';
    }
}
