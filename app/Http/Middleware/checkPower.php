<?php

namespace App\Http\Middleware;

use App\Activity;
use App\Permit;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class checkPower
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard='',$permit='')
    {
        //check if this route is active
        if ($permit !=''){
            if(!Hash::check($permit,Permit::first()[$permit])) {
                Session::flash('fault','هذة الخاصية غير موجودة فى النسخة الخاص بكم برجاء الإتصال بالشركة لتفعيلها!');
                return redirect(route('home'));
            }
        }

        if (Auth::user()->type==1){
            return $next($request);
        }

        if ($guard==1){//for admin only
            $activty=new Activity();
            $activty->data='قام المستخدم '.Auth::user()->name.' بمحاولة فتح صفحة غير مصرح بدخولها باسم '.
                Route::currentRouteName().' وتفاصيل اخري '.$guard.
                (Auth::user()->log_out_security?'وتم عمل تسجيل خروج له':'');

            $activty->user_id=Auth::user()->id;
            $activty->device_id=Auth::user()->device_id;
            $activty->type=0;
            $activty->notification=1;
            $activty->save();

            if (Auth::user()->log_out_security){
                $request->session()->invalidate();
                return redirect(\route('login'));
            }else{
                Session::flash('fault','غير مصرح لك بفتح هذة الصفحة !');
                return redirect(route('home'));
            }
        }

        if ($guard !=''){
            if (Auth::user()[$guard]==true){
                return $next($request);
            }else{
                $activty=new Activity();
                $activty->data='قام المستخدم '.Auth::user()->name.' بمحاولة فتح صفحة غير مصرح بدخولها باسم '.
                    Route::currentRouteName().' وتفاصيل اخري '.$guard.
                    (Auth::user()->log_out_security?'وتم عمل تسجيل خروج له':'');

                $activty->user_id=Auth::user()->id;
                $activty->device_id=Auth::user()->device_id;
                $activty->type=0;
                $activty->notification=1;
                $activty->save();

                if (Auth::user()->log_out_security){
                    $request->session()->invalidate();
                    return redirect(\route('login'));
                }else{
                    Session::flash('fault','غير مصرح لك بفتح هذة الصفحة !');
                    return redirect(route('home'));
                }

            }
        }
        return $next($request);
    }
}
