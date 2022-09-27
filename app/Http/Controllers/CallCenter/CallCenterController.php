<?php

namespace App\Http\Controllers\CallCenter;
use App;
use Auth;
use Gate;
use Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Review;
use App\Models\Country;
use App\Models\Setting;
use App\Models\CallCenter;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CallCenterWorkingHour;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\SuperAdmin\CustomController;

class CallCenterController extends Controller
{

    public function callCenterLogin()
    {
        return view('callCenter.auth.callCenter_login');
  
    }

    public function callCenter_signUp()
    {
        $countries = Country::get();
        return view('callCenter.auth.callcenter_register',compact('countries'));
    }

    public function callCenterRegister(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'phone' => 'bail|required|digits_between:6,12',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6'
        ]);
        $data = $request->all();
        $password = $request->password;
        $verify = Setting::first()->verification == 1 ? 0 : 1;
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($password),
            'verify' => $verify,
            'phone' => $data['phone'],
            'phone_code' => $data['phone_code'],
        ]);
        $user->assignRole('callcenter');
        $data['user_id'] = $user->id;
        $data['start_time'] = strtolower('08:00 am');
        $data['end_time'] = strtolower('08:00 pm');
        $data['image'] = 'defaultUser.png';
        $data['status'] = 1;
        $caller = CallCenter::create($data);
        $start_time = strtolower($caller->start_time);
        $end_time = strtolower($caller->end_time);
        $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $temp2['start_time'] = $start_time;
            $temp2['end_time'] = $end_time;
            array_push($master,$temp2);
            $work_time['callCenter_id'] = $caller->id;
            $work_time['period_list'] = json_encode($master);
            $work_time['day_index'] = $days[$i];
            $work_time['status'] = 1;
            CallCenterWorkingHour::create($work_time);
        }
        if($user->verify == 1)
        {
            if (Auth::attempt(['email' => $user['email'], 'password' => $request->password]))
            {
                return redirect('caller_home');
            }
        }
        else
        {
            return redirect('send_otp/'.$user->id.'/'.Str::slug($user->name));
        }
    }

    public function verify_caller(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6'
        ]);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $caller = Auth::user()->load('roles');
            // dd($caller);
            if($caller->hasRole('callcenter'))
            {
                if($caller->verify == 1)
                {
                    $caller = CallCenter::where('user_id',auth()->user()->id)->first();
                    if($caller->status == 1)
                    {
                        return redirect('caller_home');
                        // return redirect('caller_home');
                    }
                    else
                    {
                        Auth::logout();
                        return redirect()->back()->withErrors('you are disable by admin please contact admin');
                    }
                }
                else
                {
                    return redirect('CallCenter/send_otp/'.$caller->id);
                }
            }
            else
            {
                Auth::logout();
                return redirect()->back()->withErrors('Only callcenter can login');
            }
        }
        else
        {
            Auth::logout();
            return redirect()->back()->withErrors('your creditial does not match our record');
        }
    }

    public function send_otp($user_id)
    {
        $user = User::find($user_id);
        (new CustomController)->sendOtp($user);
        $status = '';
        if(Setting::first()->using_msg == 1 && Setting::first()->using_mail == 1)
            $status = 'verification code sent in email and phone';

        if ($status == '')
        {
            if (Setting::first()->using_msg == 1 || Setting::first()->using_mail == 1) {
                if (Setting::first()->using_msg == 1)
                    $status = 'verification code sent into phone';
                if (Setting::first()->using_mail == 1)
                    $status = 'verification code sent into email';
            }
        }
        return view('callCenter.auth.send_otp',compact('user'))->with('status',$status);
    }

    public function verify_otp(Request $request)
    {
        $data = $request->all();
        $otp = $data['digit_1'] . $data['digit_2'] . $data['digit_3'] . $data['digit_4'];
        $user = User::find($request->user_id);
        if ($user) {
            if ($user->otp == $otp)
            {
                $user->verify = 1;
                $user->save();
                if(Auth::loginUsingId($user->id))
                    return redirect('caller_home');
            }
            else
                return redirect()->back()->with('error',__('otp does not match'));
        }
        else
        {
            return redirect()->back()->with('error',__('Oops.user not found.!'));
        }
    }

    public function caller_home()
    {
            // return 'callcenter home';

        // (new CustomController)->cancel_max_order();
        // // abort_if(Gate::denies('doctor_home'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        // $today_Appointments = Appointment::whereDate('created_at',Carbon::now(env('timezone')))->where('doctor_id',$doctor->id)->orderBy('id','DESC')->get();
        // $currency = Setting::first()->currency_symbol;
        // $orderCharts = $this->orderChart();
        // $allUsers = User::where('doctor_id',$doctor->id)->doesntHave('roles')->orderBy('id','DESC')->get()->take(10);
        // $totalUser = User::where('doctor_id',$doctor->id)->doesntHave('roles')->count();
        // // $totalAppointment = Appointment::where('doctor_id',$doctor->id)->count();
        // $totalAppointment = Appointment::count();
        // // $totalReview = Review::where('doctor_id',$doctor->id)->count();
        // return view('doctor.doctor.home',compact('today_Appointments','allUsers','totalAppointment','totalUser','orderCharts','currency'));
        return view('callCenter.caller.home');
    }






}
