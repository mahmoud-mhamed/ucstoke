<?php

namespace App\Http\Controllers\Auth;

use App\Activity;
use App\Device;
use App\Http\Controllers\Controller;
use App\Permit;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $checkDb='success';
        $checkDevice='';
        $expire_date='no';
        try {
            //check expire date
            $permit=Permit::first();


//            if ($permit->expire_date!=null){
//                if($permit->expire_date < date('Y-m-d') ||  $permit->login_counter >100){
//                    $expire_date='yes';
//                }
//            }


            $ip=\Illuminate\Support\Facades\Request::ip();
           /* if ($ip=='::1' ||$ip =='127.0.0.1'){
                $checkDevice=Device::where('mac',substr(exec('getmac'), 0, 17))->first();
                if(!isset($checkDevice->hash_check) ||!\Hash::check((substr(exec('getmac'), 0, 17)),$checkDevice->hash_check)) {
                    $checkDevice='';
                }
            }else{
                $checkDevice=Device::where('mac',$ip)->first();
                if(!isset($checkDevice->hash_check) ||!\Hash::check($ip,$checkDevice->hash_check)) {
                    $checkDevice='';
                }
            }*/
			$checkDevice=Device::first();

//            if ($checkDevice==''){
//                $activity = new \App\Activity();
//                $activity->data = 'محاولة فتح البرنامج على جهاز غير مصرح له بالتعامل مع البرنامج حيث عنوان الماك لهذا الجهاز هو ' . substr(exec('getmac'), 0, 17) . ' والـ IP هو ' . $ip;
//                $activity->notification = 1;
//                $activity->type = 0;
//                $activity->save();
//            }
        }catch (\Exception $e){
            $checkDb='error';
        }

        return view('auth.login',[
            'check_db'=>$checkDb,
            'checkDevice'=>$checkDevice,
            'expire_date'=>$expire_date
        ]);
    }

    public function login(Request $request)
    {
        $state = isset(User::where('email', $request->email)->where('state', 1)->first()->name);
        $ip=\Illuminate\Support\Facades\Request::ip();
        /*if ($ip=='::1' ||$ip =='127.0.0.1'){
            $checkDevice=Device::where('mac',substr(exec('getmac'), 0, 17))->first();
            if(!\Hash::check((substr(exec('getmac'), 0, 17)),$checkDevice->hash_check)) {
                $checkDevice='';
            }
        }else{
            $checkDevice=Device::where('mac',$ip)->first();
            if(!\Hash::check($ip,$checkDevice->hash_check)) {
                $checkDevice='';
            }
        }*/
		$checkDevice=Device::first();

        $this->validateLogin($request);


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        //my code
        if ($state && $checkDevice !='') {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                $permit=Permit::first();
                $permit->login_counter+=1;
                $permit->save();

                /*my code*/
                $activity = new Activity();
                $activity->user_id = Auth::user()->id;
                $activity->data = 'تسجيل الدخول بحساب باسم ' . Auth::user()->name.' فى جهاز بإسم '.$checkDevice->name;
                $activity->device_id=$checkDevice->id;
                $activity->type = 0;
                $activity->save();

                $user=Auth::user();
                $user->device_id=$checkDevice->id;
                $user->save();

                return $this->sendLoginResponse($request);
            }
        }

//        end my code

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        /*my code*/
        $activity = new Activity();
        if ($checkDevice == ''){
            $activity->data = 'محاولة فتح البرنامج على جهاز غير مصرح له بالتعامل مع البرنامج حيث عنوان الماك لهذا الجهاز هو ' . substr(exec('getmac'), 0, 17) . ' والـ IP هو ' . $ip;
            $activity->notification = 1;
            $activity->type = 0;
            $activity->save();
        }else{
            if (isset(User::where('email', $request->email)->where('state', 0)->first()->name)) {
                $activity->data = ' محاولة تسجيل دخول خاطئة بحساب غير مفعل باسم ' . $request->email . ' وباسورد ' . $request->password;
            } else {
                $activity->data = ' محاولة تسجيل دخول خاطئة باسم ' . $request->email . ' وباسورد ' . $request->password;
            }
            $activity->notification = 1;
            $activity->device_id=$checkDevice->id;
            $activity->type = 0;
            $activity->save();
        }


        /*end my code*/

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        /*my code*/
        $activity=new Activity();
        $activity->user_id=Auth::user()->id;
        $activity->data='تسجيل الخروج بحساب باسم '.Auth::user()->name;
        $activity->type = 0;
        $activity->device_id = Auth::user()->device_id;

        $activity->save();
        /*end my code*/
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

}
