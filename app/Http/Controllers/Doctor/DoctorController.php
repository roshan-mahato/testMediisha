<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SuperAdmin\CustomController;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\DoctorSubscription;
use App\Models\Expertise;
use App\Models\Hospital;
use App\Models\Language;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Treatments;
use App\Models\User;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use App;

class DoctorController extends Controller
{
    public function doctorLogin()
    {
        return view('doctor.auth.doctor_login');
    }

    public function doctorSignup()
    {
        $countries = Country::get();
        return view('doctor.auth.doctor_register',compact('countries'));
    }
    
    public function verify_doctor(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6'
        ]);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')]))
        {
            $doctor = Auth::user()->load('roles');
            if($doctor->hasRole('doctor'))
            {
                if($doctor->verify == 1)
                {
                    $doctor = Doctor::where('user_id',auth()->user()->id)->first();
                    if($doctor->status == 1)
                    {
                        if($doctor->based_on == 'subscription')
                        {
                            $subscription = DoctorSubscription::with(['subscription:id,total_appointment'])->where([['doctor_id',$doctor->id],['status',1]])->first();
                            if ($subscription)
                            {
                                if ($subscription['subscription']['total_appointment'] > $subscription['booked_appointment'])
                                {
                                    $cDate = Carbon::parse($doctor['start_time'])->format('Y-m-d');
                                    if($subscription->end_date > $cDate)
                                    {
                                        // subscription active
                                        $doctor->update(['subscription_status' => 1]);
                                    }
                                    else
                                    {
                                        // subscription expire
                                        $doctor->update(['subscription_status' => 0]);
                                    }
                                }
                            }
                            else
                            {
                                // subscription expire
                                $doctor->update(['subscription_status' => 0]);   
                            }
                            return redirect('doctor_home');
                        }
                        else
                        {
                            return redirect('doctor_home');
                        }
                    }
                    else
                    {
                        Auth::logout();
                        return redirect()->back()->withErrors('you are disable by admin please contact admin');
                    }
                }
                else
                {
                    return redirect('doctor/send_otp/'.$doctor->id);
                }
            }
            else
            {
                Auth::logout();
                return redirect()->back()->withErrors('Only doctor can login');
            }
        }
        else
        {
            return redirect()->back()->withErrors('your creditial does not match our record');
        }
    }

    public function doctor_register(Request $request)
    {
        $request->validate([
            'name' => 'bail|required|unique:doctor',
            'email' => 'bail|required|email|unique:users',
            'dob' => 'bail|required',
            'gender' => 'bail|required',
            'phone' => 'bail|required|digits_between:6,12',
            'password' => 'bail|required|min:6'
        ]);
        $data = $request->all();
        $verification = Setting::first()->verification;
        $verify = $verification == 1 ? 0 : 1;
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($request->password),
            'verify' => $verify,
            'phone' => $data['phone'],
            'phone_code' => $data['phone_code'],
            'status' => 1,
        ]);
        $user->assignRole('doctor');
        $data['user_id'] = $user->id;
        $data['image'] = 'defaultUser.png';
        $data['based_on'] = Setting::first()->default_base_on;
        if($data['based_on'] == 'commission'){
            $data['commission_amount'] = Setting::first()->default_commission;
        }
        $data['since'] = Carbon::now(env('timezone'))->format('Y-m-d , h:i A');
        $data['status'] = 1;
        $data['name'] = $user->name;
        $data['dob'] = $request->dob;
        $data['start_time'] = '08:00 am';
        $data['end_time'] = '08:00 pm';
        $data['timeslot'] = 15;
        $data['gender'] = $request->gender;
        $data['subscription_status'] = 1;
        $data['is_filled'] = 0;
        $doctor = Doctor::create($data);
        if($doctor->based_on == 'subscription')
        {
            $subscription = Subscription::where('name','free')->first();
            if($subscription)
            {
                $doctor_subscription['doctor_id'] = $doctor->id;
                $doctor_subscription['subscription_id'] = $subscription->id;
                $doctor_subscription['duration'] = 1;
                $doctor_subscription['start_date'] = Carbon::now(env('timezone'))->format('Y-m-d');
                $doctor_subscription['end_date'] = Carbon::now(env('timezone'))->addMonths(1)->format('Y-m-d');
                $doctor_subscription['status'] = 1;
                $doctor_subscription['payment_status'] = 1;
                DoctorSubscription::create($doctor_subscription);
            }
        }
        $data['status'] = 1;
        $start_time = strtolower('08:00 am');
        $end_time = strtolower('08:00 pm');
        $days = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
        for($i = 0; $i < count($days); $i++)
        {
            $master = array();
            $temp2['start_time'] = $start_time;
            $temp2['end_time'] = $end_time;
            array_push($master,$temp2);
            $work_time['doctor_id'] = $doctor->id;
            $work_time['period_list'] = json_encode($master);
            $work_time['day_index'] = $days[$i];
            $work_time['status'] = 1;
            WorkingHour::create($work_time);
        }
        if($user->verify == 1)
        {
            if (Auth::attempt(['email' => $user['email'], 'password' => $request->password]))
            {
                return redirect('doctor_home');
            }
        }
        else
        {
            return redirect('doctor/send_otp/'.$user->id);
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
        return view('doctor.auth.send_otp',compact('user'))->with('status',$status);
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
                    return redirect('doctor_home');
            }
            else
                return redirect()->back()->with('error',__('otp does not match'));
        }
        else
        {
            return redirect()->back()->with('error',__('Oops.user not found.!'));
        }
    }

    public function doctor_home()
    {
        (new CustomController)->cancel_max_order();
        abort_if(Gate::denies('doctor_home'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        $today_Appointments = Appointment::whereDate('created_at',Carbon::now(env('timezone')))->where('doctor_id',$doctor->id)->orderBy('id','DESC')->get();
        $currency = Setting::first()->currency_symbol;
        $orderCharts = $this->orderChart();
        $allUsers = User::where('doctor_id',$doctor->id)->doesntHave('roles')->orderBy('id','DESC')->get()->take(10);
        $totalUser = User::where('doctor_id',$doctor->id)->doesntHave('roles')->count();
        $totalAppointment = Appointment::where('doctor_id',$doctor->id)->count();
        $totalReview = Review::where('doctor_id',$doctor->id)->count();
        return view('doctor.doctor.home',compact('today_Appointments','allUsers','totalReview','totalAppointment','totalUser','orderCharts','currency'));
    }

    public function orderChart()
    {
        $masterYear = array();
        $labelsYear = array();
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        array_push($masterYear, Appointment::where('doctor_id',$doctor->id)->whereMonth('created_at', Carbon::now(env('timezone')))->count());
        for ($i = 1; $i <= 11; $i++)
        {
            if ($i >= Carbon::now(env('timezone'))->month)
            {
                array_push($masterYear, Appointment::where('doctor_id',$doctor->id)->whereMonth('created_at',Carbon::now(env('timezone'))->subMonths($i))->whereYear('created_at', Carbon::now(env('timezone'))->subYears(1))->count());
            }
            else
            {
                array_push($masterYear, Appointment::where('doctor_id',$doctor->id)->whereMonth('created_at', Carbon::now(env('timezone'))->subMonths($i))->whereYear('created_at', Carbon::now(env('timezone'))->year)->count());
            }
        }

        array_push($labelsYear, Carbon::now(env('timezone'))->format('M-y'));
        for ($i = 1; $i <= 11; $i++)
        {
            array_push($labelsYear, Carbon::now(env('timezone'))->subMonths($i)->format('M-y'));
        }
        return ['data' => json_encode($masterYear), 'label' => json_encode($labelsYear)];
    }

    public function schedule()
    {
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        $doctor->workingHours = WorkingHour::where('doctor_id',$doctor->id)->get();
        $doctor->firstHours = WorkingHour::where('doctor_id',$doctor->id)->first();
        return view('doctor.doctor.doctor_schedule',compact('doctor'));
    }

    public function doctor_profile()
    {
        abort_if(Gate::denies('doctor_profile'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        $doctor->user = User::find($doctor->user_id);
        $countries = Country::get();
        $treatments = Treatments::whereStatus(1)->get();
        $categories = Category::whereStatus(1)->get();
        $expertieses = Expertise::whereStatus(1)->get();
        $hospitals = Hospital::whereStatus(1)->get();
        $doctor['start_time'] = Carbon::parse($doctor['start_time'])->format('H:i');
        $doctor['end_time'] = Carbon::parse($doctor['end_time'])->format('H:i');
        $languages = Language::whereStatus(1)->get();
        return view('doctor.doctor.doctor_profile',compact('doctor','countries','treatments','hospitals','categories','expertieses','languages'));
    }

    public function update_doctor_profile(Request $request)
    {
        $id = Doctor::where('user_id',auth()->user()->id)->first()->id;
        $request->validate([
            'name' => 'bail|required|unique:doctor,name,' . $id . ',id',
            'treatment_id' => 'bail|required',
            'category_id' => 'bail|required',
            'dob' => 'bail|required',
            'gender' => 'bail|required',
            'phone' => 'bail|required|digits_between:6,12',
            'expertise_id' => 'bail|required',
            'timeslot' => 'bail|required',
            'start_time' => 'bail|required',
            'end_time' => 'bail|required|after:start_time',
            'hospital_id' => 'bail|required',
            'desc' => 'required',
            'appointment_fees' => 'required|numeric',
            'experience' => 'bail|required|numeric',
            'image' => 'bail|max:1000',
            'custom_timeslot' => 'bail|required_if:timeslot,other'
        ],
        [
            'image.max' => 'The Image May Not Be Greater Than 1 MegaBytes.',
        ]);
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        $data = $request->all();
        $data['start_time'] = Carbon::parse($data['start_time'])->format('h:i A');
        $data['end_time'] = Carbon::parse($data['end_time'])->format('h:i A');
        if($request->hasFile('image'))
        {
            (new CustomController)->deleteFile($doctor->image);
            $data['image'] = (new CustomController)->imageUpload($request->image);
        }
        $education = array();
        for ($i=0; $i < count($data['degree']); $i++)
        {
            $temp['degree'] = $data['degree'][$i];
            $temp['college'] = $data['college'][$i];
            $temp['year'] = $data['year'][$i];
            array_push($education,$temp);
        }
        $data['education'] = json_encode($education);
        $certificate = array();
        for ($i=0; $i < count($data['certificate']); $i++)
        {
            $temp1['certificate'] = $data['certificate'][$i];
            $temp1['certificate_year'] = $data['certificate_year'][$i];
            array_push($certificate,$temp1);
        }
        $data['certificate'] = json_encode($certificate);
        $data['is_filled'] = 1;
        $data['custom_timeslot'] = $data['custom_timeslot'] == "" ? null : $data['custom_timeslot'];
        if($data['timeslot'] != 'other')
            $data['custom_timeslot'] = null;
        $doctor->update($data);
        $this->changeLanguage();
        return redirect('/doctor_home')->withStatus(__('Doctor updated successfully..!!'));
    }

    public function changeLanguage()
    {
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        App::setLocale($doctor->language);
        session()->put('locale', $doctor->language);
        $direction = Language::where('name',$doctor->language)->first()->direction;
        session()->put('direction', $direction);
        return true;
    }

    public function changePassword()
    {
        return view('doctor.doctor.change_password');
    }

    public function doctor_review()
    {
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        $reviews = Review::with('appointment:id,appointment_id','user:id,name')->where('doctor_id',$doctor->id)->get();
        return view('doctor.doctor.review',compact('reviews'));
    }
}
