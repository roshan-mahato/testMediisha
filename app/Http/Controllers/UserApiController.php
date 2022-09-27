<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Config;
use OneSignal;
use Artisan;
use Carbon\Carbon;
use App\Models\Blog;
use App\Models\User;
use App\Models\Offer;
use App\Models\Banner;
use App\Models\Doctor;
use App\Models\Review;
use App\Models\Settle;
use App\Models\Setting;
use App\Models\Hospital;
use App\Models\Medicine;
use Stripe\StripeClient;
use App\Models\Faviroute;
use App\Models\Pharmacy;
use App\Models\Treatments;
use App\Models\Appointment;
use App\Models\UserAddress;
use App\Models\Notification;
use App\Models\Prescription;
use App\Models\MedicineChild;
use App\Models\HospitalGallery;
use App\Models\PharmacySettle;
use App\Models\PurchaseMedicine;
use Spatie\Permission\Models\Role;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\SendMail;
use App\Http\Controllers\SuperAdmin\CustomController;
use App\Models\VideoCallHistory;
use DateTime;
use DateTimeZone;
include 'RtcTokenBuilder.php';

class UserApiController extends Controller
{
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6'
        ]);

        $user = ([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (Auth::attempt($user))
        {
            $user = Auth::user();
            if(!$user->hasAnyRole(Role::all()))
            {
                if ($user->status == 1)
                {
                    if (isset($request->device_token)) {
                        $user->device_token = $request->device_token;
                        $user->save();
                    }
                    if ($user['verify'] == 1)
                    {
                        $user['token'] = $user->createToken('doctro')->accessToken;
                        $user->makeHidden('roles');
                        return response()->json(['success' => true, 'data' => $user , 'msg' => 'successfully login'], 200);
                    }
                    else
                    {
                        (new CustomController)->sendOtp($user);
                        return response(['success' => true, 'data' => $user, 'msg' => 'Otp send in your account']);
                    }
                }
                else
                {
                    return response(['success' => false, 'msg' => 'You are blocked please contact to admin.']);
                }
            }
            else
            {
                return response()->json(['success' => false, 'msg' => 'Only Patient Can login'], 401);
            }
        }
        else
        {
            return response()->json(['success' => false, 'msg' => 'Invalid Email & Password.']);
        }
    }

    // Register
    public function apiRegister(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'dob' => 'bail|required|date_format:Y-m-d',
            'gender' => 'bail|required',
            'phone' => 'bail|required|digits_between:6,12',
            'phone_code' => 'bail|required',
            'password' => 'bail|required|min:6'
        ]);
        $data = $request->all();
        $verification = Setting::first()->verification;
        $verify = $verification == 1 ? 0 : 1;
        $data['password'] = Hash::make($request->password);
        $data['verify'] = $verify;
        $data['image'] = 'defaultUser.png';
        $data['status'] = 1;
        $user = User::create($data);
        if($user->verify == 1)
        {
            if (Auth::attempt(['email' => $user['email'], 'password' => $request->password]))
            {
                $user['token'] = $user->createToken('doctor')->accessToken;
                return response()->json(['success' => true, 'data' => $user,'msg' => 'successfully register'], 200);
            }
        }
        else
        {
            (new CustomController)->sendOtp($user);
            return response()->json(['success' => false, 'data' => $user , 'msg' => 'OTP send into your account'], 200);
        }
    }

    public function apiCheckOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'bail|required',
            'otp' => 'bail|required|min:4',
        ]);
        $user = User::find($request->user_id);
        if ($user) {
            if ($user->otp == $request->otp)
            {
                $user->verify = 1;
                $user->save();
                $user['token'] = $user->createToken('doctro')->accessToken;
                return response(['success' => true, 'data' => $user, 'msg' => 'SuccessFully verify your account...!!']);
            }
            else
            {
                return response(['success' => false, 'msg' => 'Please Enter Valid Otp.']);
            }
        }
        else
        {
            return response(['success' => false, 'msg' => 'Oops...user not found..!!']);
        }
    }

    public function apiResendOtp($user_id)
    {
        $user = User::find($user_id);
        if ($user)
        {
            (new CustomController)->sendOtp($user);
            return response()->json(['success' => true , 'msg' => 'OTP resend']);
        }
        else
        {
            return response()->json(['success' => false , 'msg' => 'User not found']);
        }
    }

    // Treatment Wise Doctor
    public function apiTreatmentDoctor(Request $request,$treatment_id)
    {
        $request->validate([
            'lat' => 'bail|required',
            'lang' => 'bail|required',
        ]);
        $doctors = Doctor::with('treatment:id,name')->whereStatus(1)->where([['is_filled',1],['treatment_id',$treatment_id]])->whereSubscriptionStatus(1);
        $data = $request->all();
        $radius = Setting::first()->radius;
        $hospital = Hospital::whereStatus(1)->GetByDistance($data['lat'],$data['lang'],$radius)->get(['id']);
        $doctors->whereIn('hospital_id',$hospital);
        $doctors = $doctors->get(['id','status','image','name','treatment_id'])->makeHidden(['rate','review']);
        foreach ($doctors as $doctor) {
            $doctor->is_faviroute = $this->checkFavourite($doctor->id);
        }
        return response(['success' => true , 'data' => $doctors]);
    }

    public function apiDoctors(Request $request)
    {
        /* $request->validate([
            'lat' => 'bail|required',
            'lang' => 'bail|required',
        ]); */
        $doctors = Doctor::with('treatment:id,name')->whereStatus(1)->where('is_filled',1)->whereSubscriptionStatus(1);
        $data = $request->all();
        $radius = Setting::first()->radius;
        $hospital = Hospital::whereStatus(1)->get(['id']);
        //$hospital = Hospital::whereStatus(1)->GetByDistance($data['lat'],$data['lang'],$radius)->get(['id']);
        $doctors->whereIn('hospital_id',$hospital);
        $doctors = $doctors->get(['id','status','image','name','treatment_id'])->makeHidden(['rate','review']);
        foreach ($doctors as $doctor) {
            $doctor->is_faviroute = $this->checkFavourite($doctor->id);
        }
        return response(['success' => true , 'data' => $doctors , 'msg' => 'All Doctors']);
    }

    public function apiTreatments()
    {
        $treatments = Treatments::whereStatus(1)->get(['id','name','image']);
        return response(['success' => true , 'data' => $treatments , 'msg' => 'All Treatments']);
    }

    public function apiOffers()
    {
        $offers = Offer::whereStatus(1)->get(['id','name','image','offer_code','discount','is_flat','discount_type','flatDiscount']);
        return response(['success' => true , 'data' => $offers , 'msg' => 'All Offers']);
    }

    public function apiNearByDoctor(Request $request)
    {
        $request->validate([
            'lat' => 'bail|required',
            'lang' => 'bail|required'
        ]);
        $data = $request->all();
        if(isset($data['lat']) && isset($data['lang']))
        {
            $radius = Setting::first()->radius;
            $hospital = Hospital::whereStatus(1)->GetByDistance($data['lat'],$data['lang'],$radius)->get(['id']);
            $doctors = Doctor::with('hospital:id,name,address')->whereStatus(1)->where('is_filled',1)->whereSubscriptionStatus(1)->whereIn('hospital_id',$hospital)->get(['id','name','image','hospital_id'])->makeHidden(['rate','review']);
            foreach ($doctors as $value)
            {
                $hospital = Hospital::where('id',$value->hospital_id)->first(['lat','lng']);
                $value->distance = number_format($this->distance($hospital['lat'],$hospital['lng'],$data['lat'],$data['lang']),2);
                $value->is_faviroute = $this->checkFavourite($value->id);
            }
            return response(['success' => true , 'data' => $doctors , 'msg' => 'show all doctors']);
        }
    }

    public function distance($lat1,$lang1,$lat2,$lang2)
    {
        $lat1 = $lat1;
        $lon1 = $lang1;
        $lat2 = $lat2;
        $lon2 = $lang2;
        $unit = 'K';
        if (($lat1 == $lat2) && ($lon1 == $lon2))
        {
            $distance = 0;
        }
        else
        {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
            if ($unit == "K")
            {
                $distance = $miles * 1.609344;
            }
            else if ($unit == "N")
            {
                $distance = $miles * 0.8684;
            }
            else
            {
                $distance = $miles;
            }
        }
        return $distance;
    }

    public function apiSingleDoctor($id)
    {
        $doctor = Doctor::with(['treatment:id,name','expertise:id,name'])->find($id)->makeHidden(['created_at','updated_at','timeslot','dob','gender','timeslot','since','status','based_on']);
        $doctor['hosiptal'] = Hospital::where('id',$doctor->hospital_id)->get()->makeHidden(['created_at','updated_at']);
        foreach ($doctor['hosiptal'] as $value)
        {
            $value->hospitalGallery = HospitalGallery::where('hospital_id',$value->id)->get(['image']);
        }
        $doctor['reviews'] = Review::with('user:id,name,image')->where('doctor_id',$id)->get();
        return response(['success' => true , 'data' => $doctor , 'msg' => 'single doctor details']);
    }

    public function apiTimeslot(Request $request)
    {
        $request->validate([
            'date' => 'bail|required|date_format:Y-m-d',
            'doctor_id' => 'bail|required|numeric'
        ]);
        $data = $request->all();
        $timeslots = (new CustomController)->timeSlot($data['doctor_id'],$data['date']);
        return response(['success' => true , 'data' => $timeslots , 'msg' => 'Doctor Timeslot']);
    }

    public function apiBooking(Request $request)
    {
        $request->validate([
            'appointment_for' => 'bail|required',
            'illness_information' => 'bail|required',
            'patient_name' => 'bail|required',
            'age' => 'bail|required|numeric',
            'patient_address' => 'bail|required',
            'phone_no' => 'bail|required|numeric|digits_between:6,12',
            'drug_effect' => 'bail|required',
            'note' => 'bail|nullable',
            'date' => 'bail|required',
            'time' => 'bail|required',
            'doctor_id' => 'bail|required',
            'amount' => 'bail|required',
            'payment_type' => 'bail|required',
            'payment_status' => 'bail|required',
        ]);
        $data = $request->all();
        $data['appointment_id'] =  '#' . rand(100000, 999999);
        $data['user_id'] = auth()->user()->id;
        $data['appointment_status'] = 'pending';
        if(isset($data['report_image']))
        {
            $report = [];
            for ($i=0; $i < count($data['report_image']); $i++)
            {
                $img = $request->report_image[$i];
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data1 = base64_decode($img);
                $Iname = uniqid();
                $file = public_path('/images/upload/') . $Iname . ".png";
                $success = file_put_contents($file, $data1);
                array_push($report,$Iname . ".png");
            }
            $data['report_image'] = json_encode($report);
        }
        if($data['payment_type'] == 'STRIPE'){
            $paymentSetting = Setting::find(1);
            $stripe_sk = $paymentSetting->stripe_secret_key;
            $stripe = new StripeClient($stripe_sk);
            $charge = $stripe->charges->create([
                "amount" => $data['amount'] * 100,
                "currency" => Setting::first()->currency_code,
                "source" => $request->payment_token,
            ]);
            $data['payment_token'] = $charge->id;
        }
        $doctor = Doctor::find($data['doctor_id']);
        if($doctor->based_on == 'commission')
        {
            $comm = $data['amount'] * $doctor->commission_amount;
            $data['admin_commission'] = intval($comm / 100);
            $data['doctor_commission'] = intval($data['amount'] - $data['admin_commission']);
        }
        $data['payment_type'] = strtoupper($data['payment_type']);
        $appointment = Appointment::create($data);

        // doctor booked appointment
        $notification_template1 = NotificationTemplate::where('title','doctor book appointment')->first();
        $doc_msg_content = $notification_template1->msg_content;
        $doc_mail_content = $notification_template1->mail_content;
        $detail1['doctor_name'] = $doctor->name;
        $detail1['appointment_id'] = $appointment->appointment_id;
        $detail1['date'] = $appointment->date;
        $detail1['user_name'] = auth()->user()->name;
        $detail1['app_name'] = Setting::first()->business_name;
        $doctor_data = ["{{doctor_name}}","{{appointment_id}}","{{date}}","{{user_name}}","{{app_name}}"];
        $doc_mail = str_replace($doctor_data, $detail1, $doc_mail_content);
        $doc_message = str_replace($doctor_data, $detail1, $doc_msg_content);
        $doctor_user = User::where('id',$doctor->user_id)->first();
        if(Setting::first()->doctor_mail == 1){
            try {
                Mail::to($doctor_user->email)->send(new SendMail($doc_mail,$notification_template1->subject));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        if(Setting::first()->doctor_notification == 1){
            try {
                $content1 = array(
                    "en" => $doc_message
                    );
                
                $fields1 = array(
                    'app_id' => env('doctor_app_id'),
                    'include_player_ids' => array($doctor_user->device_token),
                    'data' => null,
                    'contents' => $content1
                );
                
                $fields1 = json_encode($fields1);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        
                $response = curl_exec($ch);
                curl_close($ch);
            } catch (\Throwable $th) {
            }
        }

        // create Appointment to user
        $notification_template = NotificationTemplate::where('title','create appointment')->first();
        $msg_content = $notification_template->msg_content;
        $mail_content = $notification_template->mail_content;
        $detail['user_name'] = auth()->user()->name;
        $detail['appointment_id'] = $appointment->appointment_id;
        $detail['date'] = $appointment->date;
        $detail['time'] = $appointment->time;
        $detail['app_name'] = Setting::first()->business_name;
        $user_data = ["{{user_name}}","{{appointment_id}}","{{date}}","{{time}}","{{app_name}}"];
        $mail1 = str_replace($user_data, $detail, $mail_content);
        $message1 = str_replace($user_data, $detail, $msg_content);
        if(Setting::first()->patient_mail == 1){
            try {
                Mail::to(auth()->user()->email)->send(new SendMail($mail1,$notification_template->subject));
            } catch (\Throwable $th) {
            }
        }


        if(Setting::first()->patient_notification == 1){
            try {
                Config::set('onesignal.app_id', env('patient_app_id'));
                Config::set('onesignal.rest_api_key', env('patient_api_key'));
                Config::set('onesignal.user_auth_key', env('patient_auth_key'));
                
                \Artisan::call('cache:clear');
                \Artisan::call('config:clear');
                \Artisan::call('route:clear');
                \Artisan::call('view:clear');
                OneSignal::sendNotificationToUser(
                    $message1,
                    auth()->user()->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    Setting::first()->business_name
                );
            } catch (\Throwable $th) {
            }
        }

        $user_notification = array();
        $user_notification['user_id'] = auth()->user()->id;
        $user_notification['doctor_id'] = $appointment->doctor_id;
        $user_notification['user_type'] = 'user';
        $user_notification['title'] = 'create appointment';
        $user_notification['message'] = $message1;
        Notification::create($user_notification);

        $doctor_notification = array();
        $doctor_notification['user_id'] = auth()->user()->id;
        $doctor_notification['doctor_id'] = $appointment->doctor_id;
        $doctor_notification['user_type'] = 'doctor';
        $doctor_notification['title'] = 'create appointment';
        $doctor_notification['message'] = $doc_message;
        Notification::create($doctor_notification);
        return response(['success' => true , 'data' => $appointment->appointment_id ,'msg' => 'Booking is successfully waiting for doctor confirmation']);
    }

    public function apiAppointments()
    {
        (new CustomController)->cancel_max_order();
        $upcoming = array();
        $pending = array();
        $past = array();
        $appoint = array();
        $appointments = Appointment::where('user_id',auth()->user()->id)->orderBy('id','DESC')->get(['id','date','time','appointment_status','patient_name','doctor_id','appointment_id']);
        foreach($appointments as $appointment)
        {
            $appointment->doctor = Doctor::with(['treatment:id,name','hospital:id,address'])->where('id',$appointment->doctor_id)->first(['id','name','image','treatment_id','hospital_id'])->makeHidden(['rate','review']);
            $appointment->prescription = Prescription::where('appointment_id',$appointment->id)->exists();
            $appointment->timming = Carbon::parse($appointment['date'].' '.$appointment['time'])->setTimezone(env('timezone'));
            if ($appointment->timming > Carbon::now(env('timezone')) && $appointment->appointment_status == 'approve') {
                unset($appointment['timming']);
                array_push($upcoming,$appointment);
            }
            else if($appointment->timming > Carbon::now(env('timezone')) && $appointment->appointment_status == 'pending')
            {
                unset($appointment['timming']);
                array_push($pending,$appointment);
            }
            else
            {
                unset($appointment['timming']);
                array_push($past,$appointment);
            }
        }
        $appoint['upcoming_appointment'] = $upcoming;
        $appoint['pending_appointment'] = $pending;
        $appoint['past_appointment'] = $past;
        return response(['success' => true , 'data' => $appoint , 'msg' => 'Appointment details']);
    }

    public function apiAppointmentPrescription($id)
    {
        (new CustomController)->cancel_max_order();
        $appointment = Appointment::find($id,'id','date','time','patient_name','doctor_id');
        $appointment->doctor = Doctor::with('treatment:id,name')->where('id',$appointment->doctor_id)->first(['id','name','image','treatment_id']);
        $appointment->prescription = Prescription::where('appointment_id',$id)->first();
        $appointment->prescription['pdfPath'] = url('prescription/upload/'.$appointment->prescription['pdf']);
        return response(['success' => true , 'data' => $appointment , 'msg' => 'Doctor Prescription']);
    }

    public function apiSetting()
    {
        $setting = Setting::first(['cod','doctor_app_id','paypal','stripe','razor','flutterwave','paystack','stripe_public_key','stripe_secret_key','paypal_sandbox_key','paypal_producation_key','razor_key','flutterwave_encryption_key','patient_app_id','playstore','appstore','privacy_policy','about_us','agora_app_id','agora_app_certificate','cancel_reason','flutterwave_key','paystack_public_key','currency_symbol','currency_code'])->makeHidden(['companyWhite','logo','favicon']);
        if (!auth('api')->check()) 
        {
            $setting = $setting->makeHidden(['stripe_public_key','stripe_secret_key','paypal_sandbox_key','paypal_producation_key','razor_key','flutterwave_encryption_key','agora_app_id','agora_app_certificate','flutterwave_key','paystack_public_key']);
        }
        return response(['success' => true , 'data' => $setting , 'msg' => 'Setting']);
    }

    public function apiBlogs()
    {
        $blogs = Blog::where([['status',1],['release_now',1]])->orderBy('id','DESC')->get()->makeHidden(['created_at','updated_at','status','release_now']);
        return response(['success' => true , 'data' => $blogs , 'msg' => 'Blogs']);
    }

    public function apiSingleBlog($id)
    {
        $blogs = Blog::where('id',$id)->orderBy('id','DESC')->first()->makeHidden(['created_at','updated_at','status','release_now']);
        return response(['success' => true , 'data' => $blogs , 'msg' => 'Single Blog Details']);
    }

    public function apiUpdateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'dob' => 'required',
            'gender' => 'required',
        ]);
        $id = auth()->user();
        $id->update($request->all());
        return response(['success' => true , 'msg' => 'update successfully']);
    }

    public function apiUpdateImage(Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        $id = auth()->user();
        if(isset($request->image))
        {
            $img = $request->image;
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data1 = base64_decode($img);
            $Iname = uniqid();
            $file = public_path('/images/upload/') . $Iname . ".png";
            $success = file_put_contents($file, $data1);
            $data['image'] = $Iname . ".png";
        }
        $id->update($data);
        return response(['success' => true , 'data' => 'image updated succssfully..!!']);
    }

    public function apiPharmacy()
    {
        $pharamacies = Pharmacy::whereStatus(1)->orderBy('id','DESC')->get(['id','name','image','address']);
        return response(['success' => true , 'data' => $pharamacies , 'msg' => 'All phamracy']);
    }

    public function apiSinglePharmacy($id)
    {
        $pharmacy = Pharmacy::find($id);
        $pharmacy->medicine = Medicine::where('pharmacy_id',$id)->get();
        return response(['success' => true , 'data' => $pharmacy , 'msg' => 'Single Phamracy Details']);
    }

    public function apiSingleMedicine($id)
    {
        $medicine = Medicine::find($id);
        return response(['success' => true , 'data' => $medicine , 'msg' => 'Single Medicine Details']);
    }

    public function apiBookMedicine(Request $request)
    {
        $request->validate([
            'pharmacy_id' => 'bail|required',
            'amount' => 'bail|required',
            'payment_type' => 'bail|required',
            'payment_status' => 'bail|required',
            'medicines' => 'bail|required'
        ]);
        $data = $request->all();
        if(isset($data['pdf']))
        {
            $test = explode('.', $data['pdf']);
            $ext = end($test);
            $name = uniqid() . '.' . 'pdf' ;
            $location = public_path() . '/prescription/upload';
            $data['pdf']->move($location,$name);
            $data['pdf'] = $name;
        }
        $data['user_id'] = auth()->user()->id;
        $data['medicine_id'] = '#' . rand(100000, 999999);
        $data['payment_type'] = strtoupper($request->payment_type);
        $pharmacy = Pharmacy::find($data['pharmacy_id']);
        $commission = $pharmacy->commission_amount;
        $com = $data['amount'] * $commission;
        $data['admin_commission'] = $com / 100;
        $data['pharmacy_commission'] = $data['amount'] - $data['admin_commission'];
        $purchase = PurchaseMedicine::create($data);
        foreach (json_decode($data['medicines']) as $value)
        {
            $master = array();
            $master['purchase_medicine_id'] = $purchase->id;
            $master['medicine_id'] = $value->id;
            $master['price'] = $value->price;
            $master['qty'] = $value->qty;
            $medicine = Medicine::find($value->id);
            $use_stock = $medicine->use_stock + $value->qty;
            Medicine::find($value->id)->update(['use_stock' => $use_stock]);
            MedicineChild::create($master);
        }

        $settle = array();
        $settle['purchase_medicine_id'] = $purchase->id;
        $settle['pharmacy_id'] = $purchase->pharmacy_id;
        $settle['pharmacy_amount'] = $purchase->pharmacy_commission;
        $settle['admin_amount'] = $purchase->admin_commission;
        $settle['payment'] = $purchase->payment_type == 'COD' ? 0 : 1;
        $settle['pharmacy_status'] = 0;
        PharmacySettle::create($settle);
        return response(['success' => true,'msg' => 'Medicine booked']);
    }

    public function app_medicine_flutter_payment($medicine_id)
    {
        $medicine = PurchaseMedicine::find($medicine_id);
        $medicine->customer = auth()->user();
        return view('app_flutter.medicine_flutter',compact('medicine'));
    }

    public function app_medicine_transction_confirm(Request $request,$appointment_id)
    {
        $appointment = PurchaseMedicine::find($appointment_id);
        $id = $request->input('transaction_id');
        if ($request->input('status') == 'successful')
        {
            $appointment->payment_token = $id;
            $appointment->payment_status = 1;
            $appointment->save();
            return view('app_flutter.success');
        }
        else
        {
            return view('app_flutter.cancel');
        }
    }

    public function apiMedicines()
    {
        $medicines = PurchaseMedicine::with('address')->where('user_id',auth()->user()->id)->get()->makeHidden(['updated_at']);
        foreach ($medicines as $medicine)
        {
            $medicine['pharmacy_details'] = Pharmacy::find($medicine->pharmacy_id,['id','name','image','address']);
        }
        return response(['success' => true,'msg' => 'purchased medicine details' , 'data' => $medicines]);
    }

    public function apiForgotPassword(Request $request)
    {
        // $request->validate([
        //     'email' => 'bail|required|email'
        // ]);

        $validator = Validator::make($request->all(),[
            'email'=> 'email'
        ]);

        if($validator->passes()){
            $user = User::where('email',$request->email)->first();
            $mobile = 0;
        }
        else
        {
            $request->email = substr($request->email, strlen($request->email)-10);
            if(preg_match("/^[0-9]{10}$/", $request->email) == 1)
            {
                $user = User::where('phone', $request->email)->first();
                $mobile = 1;
            };
        }

        $notification_template = NotificationTemplate::where('title','forgot password')->first();
        if($user)
        {
            $otp = rand(1000, 9999);
            $user->update(['otp' => $otp, 'expires_at'=>Carbon::now()->addSeconds(3600)]);
            $detail['otp'] = $otp;
            $detail['user_name'] =$user->name;
            $detail['app_name'] = Setting::first()->business_name;
            $data = ["{{user_name}}", "{{otp}}", "{{app_name}}"];
            $message1 = str_replace($data, $detail, $notification_template->mail_contact);

            try{
                if($mobile){
                    $message1 = str_replace($data, $detail, $notification_template->msg_content);
                    $token = Setting::first()->easy_sms_token;
                    try {
                        $api_url = "http://app.easy.com.np/easyApi?".
                        http_build_query(array(
                            'key' => 'EASY5f2285cb848a05.39824458',
                            'source'  => 'none',
                            'destination'    => $user->phone,
                            'type'    => '1',
                            'message'  => $message1));
                        file_get_contents($api_url);

                    } catch (\Throwable $th) {
                        
                    }                  
                }
                else{
                    $message1 = str_replace($data, $detail, $notification_template->mail_content);
                    Mail::to($user->email)->send(new SendMail($message1,$notification_template->subject));
                }
            }  catch (\Throwable $th) {

            }
            return response(['success' => true, 'msg' => 'Otp sent Successfully']);
        }
        else{
            return response(['success' => false, 'msg' => 'User not found']);
        }

        


        // if($user)
        // {

        //     $password = rand(100000, 999999);
        //     $detail['user_name'] = $user->name;
        //     $detail['password'] = $password;
        //     $detail['app_name'] = Setting::first()->business_name;
        //     $data = ["{{user_name}}","{{password}}","{{app_name}}"];
        //     $user->password = Hash::make($password);
        //     $user->save();
        //     $message1 = str_replace($data, $detail, $notification_template->mail_content);
        //     try {
        //         Mail::to($user->email)->send(new SendMail($message1,$notification_template->subject));
        //     } catch (\Throwable $th) {
        //     }
        //     return response(['success' => true , 'msg' => 'Password sent into your mail']);
        // }
        // else
        // {
        //     return response(['success' => false , 'msg' => 'User not found..!!']);
        // }
    }

    public function apiOtpVerification(Request $request)
    {
        $user = User::find($request->id);
        $otp_expired = Carbon::parse($user->expires_at)->isPast();
        if($otp_expired)
        {   
            return response(['success'=>false, 'msg'=> 'The OTP has expired.']);
        }
        if(!($request->otp == $user->otp))
        {
            return response(['success'=>false, 'msg'=> 'The OTP is invalid.']);
        }
        
        return response()->json(['success'=>true]);
    }

    public function apiPasswordSaved(Request $request)
    {
    //     $request->validate([
    //         'password' => 'bail|required|min:6',
    //         'confirm_password' => 'bail|required|min:6',
    // ]);

    $validator = Validator::make($request->all(),[
        'password'=> 'bail|required|min:6',
        'confirm_password' => 'bail|required|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
        'status' => false,
        'msg'    => $validator->messages(),
        ], 500);
    if(!($request->password == $request->confirm_password))
    {
        return response([ 'success'=> false,'msg'=>'Password doesnot match']);
    }
    else
    {
        $password = Hash::make($request->password);
        $user = User::find($request->user_id);
        $user->update([
            'password'=> $password,
        ]);
        return reponse(['success'=>true,'msg'=>'Password Updated Successfully']);
    }

    }
}

    public function apiCancelAppointment(Request $request)
    {
        $data = $request->all();
        $appointment = Appointment::find($data['appointment_id']);
        if($appointment){
            $appointment->appointment_status = 'cancel';
            $appointment->cancel_by = 'user';
            $appointment->cancel_reason = $data['cancel_reason'];
            $appointment->save();
            $user = auth()->user();
            $status = 'cancel';
            $this->statusChangeNotification($user,$appointment,$status);
            return response(['success' => true , 'msg' => 'appointment cancel']);
        }
        else
        {
            return response(['success' => false , 'msg' => 'appointment not found']);
        }
    }

    public function statusChangeNotification($user,$appointment,$status)
    {
        $notification_template = NotificationTemplate::where('title','status change')->first();
        $setting = Setting::first();
        $detail['user_name'] = $user->name;
        $detail['appointment_id'] = $appointment->appointment_id;
        $detail['status'] = $status;
        $detail['date'] = Carbon::now(env('timezone'))->format('Y-m-d');
        $detail['app_name'] = Setting::first()->business_name;
        $data = ["{{user_name}}","{{appointment_id}}","{{status}}","{{date}}","{{app_name}}"];
        $msg1 = str_replace($data, $detail, $notification_template->msg_content);
        $mail1 = str_replace($data, $detail, $notification_template->mail_content);
        if($setting->patient_notification == 1)
        {
            try {
                Config::set('onesignal.app_id', env('patient_app_id'));
                Config::set('onesignal.rest_api_key', env('patient_api_key'));
                Config::set('onesignal.user_auth_key', env('patient_auth_key'));
                OneSignal::sendNotificationToUser(
                    $msg1,
                    $user->device_token,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    Setting::first()->business_name
                );
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        if($setting->patient_mail == 1)
        {
            try {
                Mail::to($user->email)->send(new SendMail($mail1,$notification_template->subject));
            } catch (\Throwable $th) {
            }
        }

        $user_notification = array();
        $user_notification['user_id'] = auth()->user()->id;
        $user_notification['doctor_id'] = $appointment->doctor_id;
        $user_notification['user_type'] = 'user';
        $user_notification['title'] = 'status change';
        $user_notification['message'] = $msg1;
        Notification::create($user_notification);
        return true;
    }

    public function apiAddAddress(Request $request)
    {
        $request->validate([
            'address' => 'bail|required',
            'lat' => 'bail|required',
            'lang' => 'bail|required',
        ]);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        UserAddress::create($data);
        return response(['success' => true , 'msg' => 'Address Added..!!']);
    }

    public function apiShowAddress()
    {
        $addresses = UserAddress::where('user_id',auth()->user()->id)->get(['id','address','lat','lang','label']);
        return response(['success' => true, 'data' => $addresses , 'msg' => 'User Address !!']);
    }

    public function apiDeleteAddress($id)
    {
        $address = UserAddress::find($id);
        $address->delete();
        return response(['success' => true, 'msg' => 'Address deleted... !!']);
    }

    public function apiAddReview(Request $request)
    {
        $request->validate([
            'review' => 'bail|required',
            'rate' => 'bail|required',
            'appointment_id' => 'bail|required'
        ]);
        $data = $request->all();
        if (Review::where([['appointment_id', $data['appointment_id'], ['user_id', auth()->user()->id]]])->exists() != true) {
            if (Appointment::find($data['appointment_id'])) {
                $data['doctor_id'] = Appointment::find($data['appointment_id'])->doctor_id;
                $data['user_id'] = auth()->user()->id;
                Review::create($data);
                return response(['success' => true,'data' => __('Thank You For This Review.!!')]);
            }
            else
            {
                return response(['success' => false , 'data' => __('Appointment Not Found.!!')]);
            }
        }
        else
        {
            return response(['success' => false , 'data' => __('Review Already Added.!!')]);
        }
    }

    public function apiSingleMedicineDetails($purchase_medicine_id)
    {
        $purchase_medicine = PurchaseMedicine::find($purchase_medicine_id);
        return response(['success' => true , 'data' => $purchase_medicine]);
    }

    public function apiCheckCoupen(Request $request)
    {
        $request->validate([
            'offer_code' => 'bail|required',
            'date' => 'bail|required',
            'from' => 'bail|required|in:appointment,medicine',
            'doctor_id' => 'bail|required_if:from,appointment'
        ]);
        $data = $request->all();
        $coupen = Offer::where([['offer_code',$request->offer_code],['status',1]])->whereColumn('max_use', '>' ,'use_count')->first();
        if($coupen)
        {
            $users = explode(',', $coupen->user_id);
            $doctors = explode(',',$coupen->doctor_id);
            if (($key = array_search(auth()->user()->id, $users)) !== false)
            {
                if ($request->from == 'appointment') 
                {
                    if (($key = array_search($request->doctor_id, $doctors)) !== false) {
                        $exploded_date = explode(' - ', $coupen->start_end_date);
                        $currentDate = date('Y-m-d', strtotime($data['date']));
                        if (($currentDate >= $exploded_date[0]) && ($currentDate <= $exploded_date[1]))
                        {
                            $promo = Offer::where('offer_code',$request->offer_code)->first(['id','name','offer_code','discount','discount_type','is_flat','flatDiscount','min_discount'])->makeHidden(['fullImage']);
                            return response(['success' => true , 'data' => $promo]);
                        }
                        else
                        {
                            return response(['success' => false, 'msg' => 'Coupen Is Expire.']);
                        }
                    }
                    else{
                        return response(['success' => false, 'msg' => __('Coupen is not valid for this doctor.!')]);
                    }
                }
                else
                {
                    $exploded_date = explode(' - ', $coupen->start_end_date);
                    $currentDate = date('Y-m-d', strtotime($data['date']));
                    if (($currentDate >= $exploded_date[0]) && ($currentDate <= $exploded_date[1]))
                    {
                        $promo = Offer::where('offer_code',$request->offer_code)->first(['id','name','offer_code','discount','discount_type','is_flat','flatDiscount','min_discount'])->makeHidden(['fullImage']);
                        return response(['success' => true , 'data' => $promo]);
                    }
                    else
                    {
                        return response(['success' => false, 'msg' => 'Coupen Is Expire.']);
                    }
                }
            }
            else {
                return response(['success' => false, 'msg' => __('Coupen is not valid for this user.!')]);
            }
        }
        else
        {
            return response(['success' => false , 'msg' => __('Coupen code is invalid...!!')]);
        }
    }

    public function apiUserNotification()
    {
        $data = Notification::with('doctor:id,name,image')->where([['user_id',auth()->user()->id],['user_type','user']])->get();
        return response(['success' => false , 'data' => $data]);
    }

    public function apiAddBookmark($doctor_id)
    {
        $user_id = auth()->user()->id;
        $faviroute = Faviroute::where([['user_id',$user_id],['doctor_id',$doctor_id]])->first();
        if(!$faviroute)
        {
            $data = [];
            $data['user_id'] = $user_id;
            $data['doctor_id'] = $doctor_id;
            Faviroute::create($data);
            return response(['success' => true , 'msg' => __('Added into faviroute..!!')]);
        }
        else
        {
            $faviroute->delete();
            return response(['success' => true , 'msg' => __('Remove from faviroute..!!')]);
        }
    }

    public function apiBanner()
    {
        $data = Banner::get();
        return response(['success' => true , 'data' => $data]);
    }

    public function apiFaviroute()
    {
        $favourites = Faviroute::where('user_id',auth()->user()->id)->get(['doctor_id']);
        $doctors = Doctor::with(['treatment:id,name'])->whereIn('id',$favourites)->get(['id','image','name','treatment_id'])->makeHidden(['created_at','updated_at','rate','review']);
        return response(['success' => true , 'data' => $doctors]);
    }

    public function checkFavourite($doctor_id)
    {
        if (auth('api')->user() != null)
        {
            if(Faviroute::where([['user_id',auth('api')->user()->id],['doctor_id',$doctor_id]])->first())
            {
                return true;
            }
            return false;
        }
        return false;
    }

    public function apiGenerateToken(Request $request)
    {
        $request->validate([
            'to_id' => 'bail|required'
        ]);
        $cn = uniqid();
        $appID = Setting::first()->agora_app_id;
        $appCertificate = Setting::first()->agora_app_certificate;
        if ($appID != null && $appCertificate != null) 
        {
            $channelName = $cn;
            $uid =0;
            $role = \RtcTokenBuilder::RolePublisher;
            $expireTimeInSeconds = 9000;
            $currentTimestamp = (new DateTime("now", new DateTimeZone(env('timezone'))))->getTimestamp();
            $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;
    
            $doctor = Doctor::find($request->to_id);
            $doctor_user = User::find($doctor->user_id);
            $token = \RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);
            User::whereIn('id', [auth()->id(), $doctor_user->id])->update(['channel_name' => $cn, 'agora_token' => $token]);
            $this->oneSignalNoti($doctor_user->device_token, auth()->user()->name);
            return response()->json(['msg' => null, 'data' => ['token' => $token, 'cn' => $cn], 'success' => true], 200);
        }
        return response()->json(['success' => false , 'msg' => 'Token And ID Not Available'], 200);
    }

    public function oneSignalNoti($userid, $sub)
    {
        try {
            Config::set('onesignal.app_id', env('doctor_app_id'));
            Config::set('onesignal.rest_api_key', env('doctor_api_key'));
            Config::set('onesignal.user_auth_key', env('doctor_auth_key'));
            OneSignal::sendNotificationToUser(
                $sub,
                $userid,
                $url = null,
                $data = [
                    'name' => $sub,
                    'id' => auth()->user()->id,
                ],
                $buttons = [
                    [
                        "id" => "accept", 
                        "text" => "Accept"
                    ], 
                    [
                        "id" => "decline", 
                        "text" => "Decline"
                    ]
                ],
                $schedule = null,
                $headings = null
            );
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function apiVideoCallHistory()
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(Role::all()))
            $history = VideoCallHistory::with(['doctor:id,name,image','user:id,name,image'])->where('user_id',auth()->user()->id)->get()->makeHidden(['created_at','updated_at']);
        else
        {
            $doctor = Doctor::where('user_id',$user->id)->first();
            $history = VideoCallHistory::with(['doctor:id,name,image','user:id,name,image'])->where('doctor_id',$doctor->id)->get()->makeHidden(['created_at','updated_at']);
        }
        return response(['success' => true , 'data' => $history]);
    }

    public function apiAddHistory(Request $request)
    {
        $request->validate([
            'doctor_id' => 'bail|required',
            'date' => 'bail|required',
            'start_time' => 'bail|required',
            'duration' => 'bail|required',
        ]);
        $data = $request->all();
        $doctor = Doctor::where('user_id',auth()->user()->id)->first();
        $data['doctor_id'] = $doctor->id;
        VideoCallHistory::create($data);
        return response(['success' => true]);
    }
}

